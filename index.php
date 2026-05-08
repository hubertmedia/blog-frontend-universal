<?php
define('ROOT', __DIR__);
require_once ROOT . '/includes/config.php';
require_once ROOT . '/includes/api.php';
require_once ROOT . '/includes/helpers.php';

$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$path        = parse_url($request_uri, PHP_URL_PATH);
$path        = '/' . trim($path, '/');
if ($path !== '/') $path .= '/';

if ($path === '/') { require ROOT . '/templates/home.php'; exit; }
if ($path === '/sitemap.xml/') { require ROOT . '/sitemap.php'; exit; }

// Wyszukiwarka — obsługiwana przez home.php z flagą $is_search
if (isset($_GET['q']) && strlen(trim($_GET['q'])) >= 2) {
    require ROOT . '/templates/home.php';
    exit;
}

if (preg_match('#^/kategoria/([a-z0-9\-]+)/$#', $path, $m)) {
    $cat_slug = $m[1];
    require ROOT . '/templates/category.php';
    exit;
}
if (preg_match('#^/artykul/([a-z0-9\-]+)/$#', $path, $m)) {
    $slug = $m[1];
    require ROOT . '/templates/article.php';
    exit;
}
if ($path === '/wspolpraca/') { require ROOT . '/templates/wspolpraca.php'; exit; }
if ($path === '/polityka-prywatnosci/') { require ROOT . '/templates/polityka-prywatnosci.php'; exit; }
if ($path === '/patroni/') { require ROOT . '/templates/patroni.php'; exit; }

http_response_code(404);
$domain_meta   = get_domain_meta();
$page_title    = '404 – Strona nie istnieje | ' . SITE_NAME;
$page_desc     = 'Strona, której szukasz, nie istnieje lub została przeniesiona.';
$canonical_url = SITE_URL . $path;
require ROOT . '/templates/header.php';
?>
<div class="container error-page">
    <div class="error-page__code">404</div>
    <h1>Nie znaleziono strony</h1>
    <p>Sprawdź adres URL lub wróć na stronę główną.</p>
    <div style="display:flex;gap:1rem;flex-wrap:wrap;justify-content:center;margin-top:1.5rem;">
        <a href="<?= SITE_URL ?>/" class="btn btn--primary"><i class="fas fa-home"></i> Strona główna</a>
        <a href="javascript:history.back()" class="btn btn--outline"><i class="fas fa-arrow-left"></i> Cofnij</a>
    </div>
</div>
<?php require ROOT . '/templates/footer.php';
