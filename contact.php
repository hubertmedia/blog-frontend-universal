<?php
/**
 * blogcasha.pl – Obsługa formularza kontaktowego
 * Odbiera POST z wspolpraca.php i wysyła email.
 */

require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . SITE_URL . '/wspolpraca/');
    exit;
}

// Sanitizacja
$name    = htmlspecialchars(trim($_POST['name']    ?? ''), ENT_QUOTES, 'UTF-8');
$email   = filter_var(trim($_POST['email']   ?? ''), FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars(trim($_POST['subject'] ?? 'Inne zapytanie'), ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');
$privacy = !empty($_POST['privacy']);

// Walidacja
$errors = [];
if (empty($name))    $errors[] = 'Imię jest wymagane.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Niepoprawny adres email.';
if (empty($message)) $errors[] = 'Wiadomość jest wymagana.';
if (!$privacy)       $errors[] = 'Wymagana zgoda na przetwarzanie danych.';

if (!empty($errors)) {
    // W produkcji przekieruj z błędem; tu prosty output
    http_response_code(400);
    echo '<p>Błędy: ' . implode(', ', $errors) . '</p>';
    echo '<a href="javascript:history.back()">Wróć</a>';
    exit;
}

// Email
$to      = CONTACT_EMAIL;
$subject_mail = '[blogcasha.pl] Zapytanie o współpracę – ' . $subject;
$body    = "Imię: $name\nEmail: $email\nTemat: $subject\n\nWiadomość:\n$message";
$headers = "From: noreply@blogcasha.pl\r\n"
         . "Reply-To: $email\r\n"
         . "X-Mailer: PHP/" . phpversion();

$sent = mail($to, $subject_mail, $body, $headers);

// Przekierowanie z parametrem statusu
if ($sent) {
    header('Location: ' . SITE_URL . '/wspolpraca/?sent=1#kontakt');
} else {
    header('Location: ' . SITE_URL . '/wspolpraca/?sent=error#kontakt');
}
exit;
