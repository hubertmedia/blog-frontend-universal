<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/api.php';
require_once __DIR__ . '/../includes/helpers.php';

// fetch_post_by_slug teraz zwraca {post, related, comment_count} lub null
$result = isset($slug) ? fetch_post_by_slug($slug) : null;
$post   = $result['post'] ?? null;

if (!$post) {
    http_response_code(404);
    $domain_meta = get_domain_meta();
    $page_title  = '404 | ' . SITE_NAME;
    include __DIR__ . '/header.php';
    echo '<div class="container error-page"><div class="error-page__code">404</div><h1>Nie znaleziono artykułu</h1><p><a href="' . SITE_URL . '/" class="btn btn--primary">Strona główna</a></p></div>';
    include __DIR__ . '/footer.php';
    exit;
}

// Obsługa formularza komentarza — przed jakimkolwiek outputem
$comment_success = false;
$comment_error   = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content'])) {
    $c_author  = trim($_POST['comment_author'] ?? '');
    $c_content = trim($_POST['comment_content'] ?? '');
    if ($c_author && $c_content) {
        $ok = add_comment((int)$post['id'], $c_author, $c_content);
        if ($ok) {
            header('Location: ' . SITE_URL . '/artykul/' . rawurlencode($post['slug']) . '/?commented=1#komentarze');
            exit;
        }
        $comment_error = true;
    } else {
        $comment_error = true;
    }
}

$related  = $result['related'] ?? [];
$comments = fetch_comments((int)$post['id']);

$data        = fetch_from_cms(1);
$domain_meta = $data['domain'] ?? [];

$cat_slug  = category_slug($post['category_name'] ?? '');
$cat_color = category_color($post['category_name'] ?? '');
$cat_icon  = category_icon($post['category_name'] ?? '');
$post_url  = SITE_URL . '/artykul/' . rawurlencode($post['slug']) . '/';
$cat_url   = SITE_URL . '/kategoria/' . $cat_slug . '/';
$read_time = reading_time($post['content'] ?? '');

$page_title    = ($post['seo_title'] ?: $post['title']) . ' | ' . SITE_NAME;
$page_desc     = $post['seo_description'] ?: truncate($post['excerpt'] ?? '', 160);
$page_image    = fix_image_url($post['featured_image'] ?? '') ?: SITE_URL . '/img/og-default.png';
$page_type     = 'article';
$canonical_url = $post_url;
$seo_keywords  = $post['seo_keywords'] ?? '';

$extra_head  = schema_article($post, $post_url);
$extra_head .= schema_breadcrumb([
    ['name' => 'Strona główna',                        'url' => SITE_URL . '/'],
    ['name' => $post['category_name'] ?? 'Kategoria',  'url' => $cat_url],
    ['name' => $post['title'],                         'url' => $post_url],
]);

include __DIR__ . '/header.php';
?>

<div class="container article-layout">
    <div class="article-main">

        <nav class="breadcrumbs" aria-label="Nawigacja okruszkowa">
            <ol class="breadcrumbs__list">
                <li class="breadcrumbs__item"><a href="<?= SITE_URL ?>/">Strona główna</a></li>
                <li class="breadcrumbs__item" aria-hidden="true"><i class="fas fa-chevron-right"></i></li>
                <li class="breadcrumbs__item"><a href="<?= $cat_url ?>"><?= htmlspecialchars($post['category_name'] ?? '') ?></a></li>
                <li class="breadcrumbs__item" aria-hidden="true"><i class="fas fa-chevron-right"></i></li>
                <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page">
                    <?= htmlspecialchars(truncate($post['title'], 60)) ?>
                </li>
            </ol>
        </nav>

        <article class="article" itemscope itemtype="https://schema.org/BlogPosting">
            <header class="article__header">
                <a href="<?= $cat_url ?>" class="badge badge--lg" style="--badge-color:<?= $cat_color ?>">
                    <i class="fas <?= $cat_icon ?>" aria-hidden="true"></i>
                    <?= htmlspecialchars($post['category_name'] ?? '') ?>
                </a>
                <?php if (!empty($post['is_sponsored'])): ?>
                <span class="badge badge--lg" style="--badge-color:#eab308; margin-left:8px;">
                    <i class="fas fa-star" aria-hidden="true"></i> ARTYKUŁ SPONSOROWANY
                </span>
                <?php endif; ?>
                <h1 class="article__title" itemprop="headline">
                    <?= htmlspecialchars($post['title']) ?>
                </h1>
                <div class="article__meta">
                    <time class="article__date" datetime="<?= format_date_iso($post['published_at']) ?>" itemprop="datePublished">
                        <i class="fas fa-calendar-alt"></i> <?= format_date($post['published_at']) ?>
                    </time>
                    <span><i class="fas fa-clock"></i> <?= $read_time ?> min czytania</span>
                    <span itemprop="author" itemscope itemtype="https://schema.org/Organization">
                        <i class="fas fa-pen-nib"></i>
                        <span itemprop="name">Redakcja <?= SITE_NAME ?></span>
                    </span>
                </div>
                <div class="article__gold-rule" aria-hidden="true"></div>
            </header>

            <div class="article__featured-img">
                <?php if (!empty($post['featured_image'])): ?>
                <img src="<?= htmlspecialchars(fix_image_url($post['featured_image'])) ?>"
                     alt="<?= htmlspecialchars($post['title']) ?>"
                     width="1200" height="630" loading="eager" fetchpriority="high"
                     class="article__img" itemprop="image">
                <?php else: ?>
                <div class="article__img article__img--placeholder"
                     style="background:linear-gradient(135deg,<?= $cat_color ?> 0%,#0f172a 100%)">
                    <i class="fas <?= $cat_icon ?>" aria-hidden="true"></i>
                </div>
                <?php endif; ?>
            </div>

            <?= render_ad_slot('article-top') ?>

            <div class="article-content" id="articleContent" itemprop="articleBody">
                <?= $post['content'] ?>
                <?php if (!empty($post['ai_note_enabled'])): ?>
                <div class="ai-note" style="margin-top:2rem; padding:1.25rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; display:flex; gap:12px; font-size:0.875rem; color:#475569;">
                    <div style="color:var(--color-primary); font-size:1.25rem;"><i class="fas fa-robot"></i></div>
                    <div>
                        <strong>Wsparcie AI.</strong> <?= $post['ai_note_text'] ?: 'Ten materiał został opracowany przy wsparciu narzędzi sztucznej inteligencji, a następnie zweryfikowany i zatwierdzony przez redakcję '.SITE_NAME.'.' ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($post['tracking_code'])): ?>
                <?= $post['tracking_code'] ?>
            <?php endif; ?>

            <?= render_ad_slot('article-bottom') ?>

            <div class="article-disclaimer">
                <i class="fas fa-triangle-exclamation" aria-hidden="true"></i>
                <p>Artykuł ma charakter wyłącznie informacyjny i edukacyjny. <strong>Nie stanowi porady finansowej, inwestycyjnej ani prawnej</strong> w rozumieniu obowiązujących przepisów. Przed podjęciem decyzji finansowych skonsultuj się z licencjonowanym doradcą.</p>
            </div>

            <!-- Author box -->
            <?php if (!empty($post['author_name'])): ?>
            <div class="author-box">
                <?php if (!empty($post['author_photo'])): ?>
                <img src="<?= htmlspecialchars(fix_image_url($post['author_photo'])) ?>"
                     alt="<?= htmlspecialchars($post['author_name']) ?>"
                     class="author-box__photo" width="80" height="80" loading="lazy">
                <?php else: ?>
                <div class="author-box__avatar" aria-hidden="true">
                    <?= mb_strtoupper(mb_substr($post['author_name'], 0, 1)) ?>
                </div>
                <?php endif; ?>
                <div class="author-box__body">
                    <p class="author-box__label">Autor</p>
                    <p class="author-box__name"><?= htmlspecialchars($post['author_name']) ?></p>
                    <?php if (!empty($post['author_bio'])): ?>
                    <p class="author-box__bio"><?= htmlspecialchars($post['author_bio']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($post['author_credentials'])): ?>
                    <p class="author-box__credentials"><i class="fas fa-certificate" aria-hidden="true"></i> <?= htmlspecialchars($post['author_credentials']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="article__share">
                <span class="article__share-label">Udostępnij:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($post_url) ?>"
                   class="btn btn--share btn--facebook" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode($post_url) ?>&text=<?= urlencode($post['title']) ?>"
                   class="btn btn--share btn--twitter" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-x-twitter"></i> Twitter
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($post_url) ?>"
                   class="btn btn--share btn--linkedin" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-linkedin-in"></i> LinkedIn
                </a>
                <button class="btn btn--share btn--copy" data-copy-url="<?= htmlspecialchars($post_url) ?>">
                    <i class="fas fa-link"></i> Kopiuj link
                </button>
            </div>
        </article>

        <?php if (!empty($related)): ?>
        <section class="related-posts" aria-labelledby="related-h">
            <h2 class="section__title" id="related-h">
                Powiązane <span class="section__title-accent">artykuły</span>
            </h2>
            <div class="posts-grid">
                <?php foreach ($related as $post): ?>
                <?php include __DIR__ . '/partials/post-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Komentarze -->
        <section class="comments-section" id="komentarze" aria-labelledby="comments-h">
            <h2 class="section__title" id="comments-h">
                Komentarze <span class="section__title-accent">(<?= count($comments) ?>)</span>
            </h2>

            <?php if (isset($_GET['commented'])): ?>
            <div class="alert alert--success" role="alert">
                <i class="fas fa-check-circle"></i>
                Komentarz dodany — pojawi się po moderacji. Dziękujemy!
            </div>
            <?php endif; ?>

            <?php if ($comment_error): ?>
            <div class="alert alert--error" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                Wystąpił błąd. Sprawdź czy wypełniłeś wszystkie pola i spróbuj ponownie.
            </div>
            <?php endif; ?>

            <?php if (!empty($comments)): ?>
            <div class="comments-list">
                <?php foreach ($comments as $c): ?>
                <div class="comment">
                    <div class="comment__avatar" aria-hidden="true">
                        <?= mb_strtoupper(mb_substr($c['author'], 0, 1)) ?>
                    </div>
                    <div class="comment__body">
                        <div class="comment__header">
                            <span class="comment__author"><?= htmlspecialchars($c['author']) ?></span>
                            <time class="comment__date"><?= format_date($c['created_at']) ?></time>
                        </div>
                        <p class="comment__text"><?= nl2br(htmlspecialchars($c['content'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="comments-empty">Bądź pierwszy — zostaw komentarz!</p>
            <?php endif; ?>

            <div class="comment-form-wrap">
                <h3 class="comment-form__title">Dodaj komentarz</h3>
                <p class="comment-form__info">Komentarze pojawiają się po moderacji.</p>
                <form class="comment-form" method="post"
                      action="<?= SITE_URL ?>/artykul/<?= rawurlencode($result['post']['slug']) ?>/#komentarze">
                    <div class="comment-form__fields">
                        <div class="form-group">
                            <label for="comment_author">Imię lub pseudonim <span aria-hidden="true">*</span></label>
                            <input type="text" id="comment_author" name="comment_author"
                                   required maxlength="100"
                                   value="<?= htmlspecialchars($_POST['comment_author'] ?? '') ?>"
                                   placeholder="Twoje imię">
                        </div>
                        <div class="form-group form-group--full">
                            <label for="comment_content">Treść komentarza <span aria-hidden="true">*</span></label>
                            <textarea id="comment_content" name="comment_content"
                                      required maxlength="2000" rows="4"
                                      placeholder="Napisz komentarz..."><?= htmlspecialchars($_POST['comment_content'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn--primary">
                        <i class="fas fa-paper-plane"></i> Wyślij komentarz
                    </button>
                </form>
            </div>
        </section>

    </div><!-- /.article-main -->

    <aside class="sidebar">
        <?php
        $all_posts       = fetch_from_cms(5)['posts'] ?? [];
        $sidebar_posts   = $all_posts;
        $active_cat_slug = $cat_slug;
        include __DIR__ . '/partials/sidebar.php';
        ?>
    </aside>
</div>

<script>
(function(){
    var c = document.getElementById('articleContent');
    if(!c) return;
    var ps = c.querySelectorAll('p');
    if(ps.length >= 3){
        var ad = document.createElement('div');
        ad.className = 'ad-slot ad-slot--article-mid';
        ad.setAttribute('data-ad-slot','article-mid');
        ad.setAttribute('aria-hidden','true');
        ps[2].insertAdjacentElement('afterend', ad);
    }
})();
</script>

<?php include __DIR__ . '/footer.php'; ?>
