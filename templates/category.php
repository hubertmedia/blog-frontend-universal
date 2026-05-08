<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/api.php';
require_once __DIR__ . '/../includes/helpers.php';

$nav_cats   = fetch_categories();
$cat_info   = null;
$cms_cat_id = null;
foreach ($nav_cats as $c) {
    if (($c['slug'] ?? '') === $cat_slug) {
        $cat_info   = $c;
        $cms_cat_id = (int)$c['id'];
        break;
    }
}

if (!$cat_info) {
    http_response_code(404);
    $domain_meta = get_domain_meta();
    $page_title  = '404 | ' . SITE_NAME;
    include __DIR__ . '/header.php';
    echo '<div class="container error-page"><div class="error-page__code">404</div><h1>Nie znaleziono kategorii</h1><p><a href="' . SITE_URL . '/" class="btn btn--primary">Strona główna</a></p></div>';
    include __DIR__ . '/footer.php';
    exit;
}

$page        = max(1, (int)($_GET['page'] ?? 1));
$data        = fetch_posts_paginated($page, 12, $cms_cat_id);
$domain_meta = $data['domain'] ?? [];
$cat_posts   = $data['posts']  ?? [];
$total_pages = (int)($data['pages'] ?? 1);
$total_count = (int)($data['total'] ?? count($cat_posts));

$cat_name  = $cat_info['name'];
$cat_color = $cat_info['color'];
$_raw_icon = $cat_info['icon'] ?? 'fas fa-tag';
$cat_icon  = preg_match('/(fa-[\w-]+)\s*$/', $_raw_icon, $_m) ? $_m[1] : 'fa-tag';
$cat_url   = SITE_URL . '/kategoria/' . $cat_slug . '/';

$page_title    = $cat_name . ' – analizy i porady finansowe | ' . SITE_NAME;
$page_desc     = $cat_info['description'] ?? $cat_info['desc'] ?? 'Artykuły z kategorii ' . $cat_name . ' na blogcasha.pl.';
$canonical_url = $cat_url;
$extra_head    = schema_breadcrumb([
    ['name' => 'Strona główna', 'url' => SITE_URL . '/'],
    ['name' => $cat_name,       'url' => $cat_url],
]);

include __DIR__ . '/header.php';
?>

<section class="cat-hero" style="--cat-color:<?= $cat_color ?>">
    <div class="container cat-hero__inner">
        <div class="cat-hero__icon"><i class="fas <?= $cat_icon ?>"></i></div>
        <div>
            <nav class="breadcrumbs breadcrumbs--light" aria-label="Nawigacja okruszkowa">
                <ol class="breadcrumbs__list">
                    <li class="breadcrumbs__item"><a href="<?= SITE_URL ?>/">Strona główna</a></li>
                    <li class="breadcrumbs__item" aria-hidden="true"><i class="fas fa-chevron-right"></i></li>
                    <li class="breadcrumbs__item" aria-current="page"><?= htmlspecialchars($cat_name) ?></li>
                </ol>
            </nav>
            <h1 class="cat-hero__title">Artykuły: <span><?= htmlspecialchars($cat_name) ?></span></h1>
            <p class="cat-hero__desc"><?= htmlspecialchars($cat_info['description'] ?? $cat_info['desc'] ?? '') ?></p>
            <p class="cat-hero__count"><?= $total_count ?> artykułów</p>
        </div>
    </div>
</section>

<div class="container posts-layout" style="margin-top:2.5rem;margin-bottom:3rem">
    <div>
        <?php if (empty($cat_posts)): ?>
        <div class="empty-state">
            <i class="fas <?= $cat_icon ?>" style="color:<?= $cat_color ?>"></i>
            <h2>Brak artykułów w tej kategorii</h2>
            <p>Wkrótce pojawią się nowe treści. Zajrzyj ponownie!</p>
            <a href="<?= SITE_URL ?>/" class="btn btn--primary">Strona główna</a>
        </div>
        <?php else: ?>
        <div class="posts-grid">
            <?php foreach ($cat_posts as $post): ?>
            <?php include __DIR__ . '/partials/post-card.php'; ?>
            <?php endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <nav class="pagination-section" aria-label="Strony wyników">
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="<?= $cat_url ?>?page=<?= $page - 1 ?>" class="pagination__btn pagination__btn--prev">
                        <i class="fas fa-arrow-left"></i> Poprzednia
                    </a>
                <?php endif; ?>
                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                    <a href="<?= $cat_url ?>?page=<?= $i ?>"
                       class="pagination__btn<?= $i === $page ? ' pagination__btn--active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="<?= $cat_url ?>?page=<?= $page + 1 ?>" class="pagination__btn pagination__btn--next">
                        Następna <i class="fas fa-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
        <?php endif; ?>
        <?php endif; ?>
    </div>
    <aside class="sidebar">
        <?php
        $sidebar_posts   = $cat_posts;
        $active_cat_slug = $cat_slug;
        include __DIR__ . '/partials/sidebar.php';
        ?>
    </aside>
</div>

<?php include __DIR__ . '/footer.php'; ?>
