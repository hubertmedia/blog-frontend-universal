<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/api.php';
require_once __DIR__ . '/../includes/helpers.php';

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$is_search    = strlen($search_query) >= 2;

if ($is_search) {
    $search_posts = fetch_search($search_query);
    $domain_meta  = get_domain_meta();
    $page_title   = 'Wyniki wyszukiwania: ' . htmlspecialchars($search_query) . ' | ' . SITE_NAME;
    $page_desc    = 'Wyniki wyszukiwania dla zapytania: ' . $search_query;
    $canonical_url = SITE_URL . '/?q=' . urlencode($search_query);
    $extra_head    = '';
    include __DIR__ . '/header.php';
    ?>
    <section class="section" aria-labelledby="search-h">
        <div class="container">
            <div class="section__header">
                <h1 class="section__title" id="search-h">
                    Wyniki dla: <span class="section__title-accent"><?= htmlspecialchars($search_query) ?></span>
                </h1>
            </div>
            <?php if (empty($search_posts)): ?>
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h2>Brak wyników</h2>
                <p>Nie znaleziono artykułów pasujących do frazy <strong><?= htmlspecialchars($search_query) ?></strong>.</p>
                <a href="<?= SITE_URL ?>/" class="btn btn--primary">Strona główna</a>
            </div>
            <?php else: ?>
            <p style="margin-bottom:1.5rem;color:var(--color-text-muted)">
                Znaleziono <?= count($search_posts) ?> <?= count($search_posts) === 1 ? 'artykuł' : (count($search_posts) < 5 ? 'artykuły' : 'artykułów') ?>
            </p>
            <div class="posts-grid">
                <?php foreach ($search_posts as $post): ?>
                <?php include __DIR__ . '/partials/post-card.php'; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    include __DIR__ . '/footer.php';
    exit;
}

$page        = max(1, (int)($_GET['page'] ?? 1));
$data        = fetch_posts_paginated($page, 12);
$domain_meta = $data['domain'] ?? [];
$posts       = $data['posts']  ?? [];
$total_pages = (int)($data['pages'] ?? 1);

$featured   = $posts[0] ?? null;
$ticker     = array_slice($posts, 0, 3);
// Gdy jest mało artykułów, grid pokazuje wszystkie (łącznie z wyróżnionym)
$grid_posts = count($posts) > 1 ? array_slice($posts, 1, 6) : $posts;
$more_posts = array_slice($posts, 7);

$page_title    = SITE_NAME . ' – ' . SITE_DESC;
$page_desc     = SITE_DESC;
$page_image    = fix_image_url($featured['featured_image'] ?? '') ?: SITE_URL . '/img/og-default.png';
$canonical_url = SITE_URL . '/';
$extra_head    = schema_website();

include __DIR__ . '/header.php';
?>

<!-- Breaking bar / Ticker -->
<?php if (!empty($ticker)): ?>
<div class="breaking-bar">
    <div class="container breaking-bar__inner">
        <span class="breaking-bar__label">
            <i class="fas fa-bolt" aria-hidden="true"></i> Najnowsze
        </span>
        <div class="breaking-bar__items">
            <?php foreach ($ticker as $t): ?>
            <a href="<?= SITE_URL ?>/artykul/<?= rawurlencode($t['slug']) ?>/" class="breaking-bar__item">
                <span class="breaking-bar__cat" style="--cat-color:<?= category_color($t['category_name']??'') ?>">
                    <?= htmlspecialchars($t['category_name'] ?? '') ?>
                </span>
                <?= htmlspecialchars(truncate($t['title'], 70)) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Main Featured -->
<?php if ($featured): ?>
<section class="featured-section" aria-label="Wyróżniony artykuł">
    <div class="container">
        <div class="featured-article">
            <div class="featured-article__img-wrap">
                <?php if (!empty($featured['featured_image'])): ?>
                <img src="<?= htmlspecialchars(fix_image_url($featured['featured_image'])) ?>"
                     alt="<?= htmlspecialchars($featured['title']) ?>"
                     width="1200" height="630" loading="eager" fetchpriority="high"
                     class="featured-article__img">
                <?php else: ?>
                <div class="featured-article__img featured-article__img--placeholder"
                     style="background:linear-gradient(135deg,<?= category_color($featured['category_name']??'') ?> 0%,#0f172a 100%)">
                    <i class="fas <?= category_icon($featured['category_name']??'') ?>" aria-hidden="true"></i>
                </div>
                <?php endif; ?>
            </div>
            <div class="featured-article__body">
                <div class="featured-article__gold-line" aria-hidden="true"></div>
                <a href="<?= SITE_URL ?>/kategoria/<?= category_slug($featured['category_name']??'') ?>/"
                   class="badge" style="--badge-color:<?= category_color($featured['category_name']??'') ?>">
                    <i class="fas <?= category_icon($featured['category_name']??'') ?>" aria-hidden="true"></i>
                    <?= htmlspecialchars($featured['category_name'] ?? '') ?>
                </a>
                <h1 class="featured-article__title">
                    <a href="<?= SITE_URL ?>/artykul/<?= rawurlencode($featured['slug']) ?>/">
                        <?= htmlspecialchars($featured['title']) ?>
                    </a>
                </h1>
                <p class="featured-article__excerpt">
                    <?= htmlspecialchars(truncate($featured['excerpt'] ?? '', 220)) ?>
                </p>
                <div class="featured-article__meta">
                    <time datetime="<?= format_date_iso($featured['published_at']) ?>">
                        <i class="fas fa-calendar" aria-hidden="true"></i>
                        <?= format_date($featured['published_at']) ?>
                    </time>
                    <span><i class="fas fa-clock" aria-hidden="true"></i> <?= reading_time($featured['content'] ?? '') ?> min czytania</span>
                </div>
                <a href="<?= SITE_URL ?>/artykul/<?= rawurlencode($featured['slug']) ?>/"
                   class="btn btn--primary">
                    Czytaj analizę <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Polecane Artykuły (Z CMS: ?featured=1) -->
<?php $featured_api = fetch_featured_posts(4); ?>
<?php if (!empty($featured_api)): ?>
<section class="section section--featured-alt" aria-labelledby="featured-alt-h" style="background:#f8fafc; border-bottom:1px solid #e2e8f0;">
    <div class="container">
        <div class="section__header">
            <h2 class="section__title" id="featured-alt-h">
                Polecane <span class="section__title-accent">artykuły</span>
            </h2>
            <p style="color:var(--color-text-muted); font-size:0.875rem;">Wyselekcjonowane treści przez naszą redakcję</p>
        </div>
        <div class="posts-grid posts-grid--4">
            <?php foreach ($featured_api as $post): ?>
            <?php include __DIR__ . '/partials/post-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Najnowsze analizy -->
<section class="section" aria-labelledby="latest-h">
    <div class="container">
        <div class="section__header">
            <h2 class="section__title" id="latest-h">
                Najnowsze <span class="section__title-accent">analizy</span>
            </h2>
        </div>
        <div class="posts-layout">
            <div class="posts-grid">
                <?php foreach ($grid_posts as $post): ?>
                <?php include __DIR__ . '/partials/post-card.php'; ?>
                <?php endforeach; ?>
                <?php if (empty($grid_posts)): ?>
                <div class="empty-state" style="grid-column:1/-1">
                    <i class="fas fa-chart-line"></i>
                    <h3>Artykuły pojawią się wkrótce</h3>
                    <p>Trwa ładowanie treści z CMS. Sprawdź ponownie za chwilę.</p>
                </div>
                <?php endif; ?>
            </div>
            <aside class="sidebar">
                <?php
                $sidebar_posts = array_slice($posts, 0, 5);
                include __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>
        </div>
    </div>
</section>

<!-- Ad slot -->
<div class="ad-section">
    <div class="container">
        <?= render_ad_slot('homepage-leaderboard') ?>
    </div>
</div>

<!-- Popularne kategorie -->
<?php $home_cats = $nav_cats ?? get_nav_categories(); ?>
<?php if (!empty($home_cats)): ?>
<section class="section section--cats-dark" aria-labelledby="cats-h">
    <div class="container">
        <h2 class="section__title section__title--light" id="cats-h">
            Kategorie <span class="section__title-accent">finansowe</span>
        </h2>
        <div class="categories-tiles">
            <?php foreach ($home_cats as $cat): ?>
            <a href="<?= SITE_URL ?>/kategoria/<?= htmlspecialchars($cat['slug']) ?>/"
               class="cat-tile" style="--tile-color:<?= htmlspecialchars($cat['color']) ?>">
                <span class="cat-tile__icon"><i class="<?= htmlspecialchars($cat['icon']) ?>"></i></span>
                <span class="cat-tile__name"><?= htmlspecialchars($cat['name']) ?></span>
                <i class="fas fa-arrow-right cat-tile__arrow"></i>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Więcej artykułów -->
<?php if (!empty($more_posts)): ?>
<section class="section" aria-labelledby="more-h">
    <div class="container">
        <h2 class="section__title" id="more-h">Więcej <span class="section__title-accent">do czytania</span></h2>
        <div class="posts-grid">
            <?php foreach ($more_posts as $post): ?>
            <?php include __DIR__ . '/partials/post-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Paginacja -->
<?php if ($total_pages > 1): ?>
<nav class="pagination-section" aria-label="Strony wyników">
    <div class="container">
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="<?= SITE_URL ?>/?page=<?= $page - 1 ?>" class="pagination__btn pagination__btn--prev">
                    <i class="fas fa-arrow-left"></i> Poprzednia
                </a>
            <?php endif; ?>
            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                <a href="<?= SITE_URL ?>/?page=<?= $i ?>"
                   class="pagination__btn<?= $i === $page ? ' pagination__btn--active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <a href="<?= SITE_URL ?>/?page=<?= $page + 1 ?>" class="pagination__btn pagination__btn--next">
                    Następna <i class="fas fa-arrow-right"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<?php endif; ?>

<!-- Disclaimer finansowy -->
<section class="home-disclaimer">
    <div class="container">
        <i class="fas fa-info-circle" aria-hidden="true"></i>
        <p>Treści na <strong>blogcasha.pl</strong> mają charakter informacyjny i nie stanowią porady finansowej. Przed podjęciem decyzji inwestycyjnych lub kredytowych skonsultuj się z licencjonowanym doradcą finansowym. Inwestowanie wiąże się z ryzykiem utraty części lub całości środków.</p>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>
