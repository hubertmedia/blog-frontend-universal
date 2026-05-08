<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/api.php';
require_once __DIR__ . '/../includes/helpers.php';

$data = fetch_settings();
$page_data = $data['pages']['wspolpraca'] ?? null;

$page_title    = ($page_data['title'] ?? 'Współpraca i reklama') . ' | ' . SITE_NAME;
$page_desc     = 'Dotrzyj do świadomych finansowo Polaków. Oferta artykułów sponsorowanych i linków na ' . SITE_NAME . '.';
$canonical_url = SITE_URL . '/wspolpraca/';

$extra_head    = schema_breadcrumb([
    ['name'=>'Strona główna','url'=> SITE_URL.'/'],
    ['name'=>'Współpraca',   'url'=> $canonical_url],
]);

include __DIR__ . '/header.php';
?>

<main id="main-content">
    <?php if (!empty($page_data['content'])): ?>
        <?= str_replace('{{SITE_NAME}}', SITE_NAME, str_replace('{{CONTACT_EMAIL}}', CONTACT_EMAIL, $page_data['content'])) ?>
    <?php else: ?>
        <div class="container py-20 text-center">
            <h1>Współpraca</h1>
            <p>Treść strony jest właśnie konfigurowana w CMS. Zapraszamy za chwilę.</p>
        </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/footer.php'; ?>
