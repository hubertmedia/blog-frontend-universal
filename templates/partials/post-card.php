<?php
$cat_color = category_color($post['category_name'] ?? '');
$cat_icon  = category_icon($post['category_name'] ?? '');
$cat_slug  = category_slug($post['category_name'] ?? '');
$post_url  = SITE_URL . '/artykul/' . rawurlencode($post['slug']) . '/';
$cat_url   = SITE_URL . '/kategoria/' . $cat_slug . '/';
$rtime     = reading_time($post['content'] ?? $post['excerpt'] ?? '');
?>
<article class="post-card">
    <a href="<?= $post_url ?>" class="post-card__img-wrap" tabindex="-1" aria-hidden="true">
        <?php if (!empty($post['featured_image'])): ?>
        <img src="<?= htmlspecialchars(fix_image_url($post['featured_image'])) ?>"
             alt="<?= htmlspecialchars($post['title']) ?>"
             width="600" height="315" loading="lazy" class="post-card__img">
        <?php else: ?>
        <div class="post-card__img post-card__img--placeholder"
             style="background:linear-gradient(135deg,<?= $cat_color ?> 0%,#0f172a 100%)">
            <i class="fas <?= $cat_icon ?>" aria-hidden="true"></i>
        </div>
        <?php endif; ?>
        <span class="post-card__cat-badge" style="--cat-color:<?= $cat_color ?>">
            <i class="fas <?= $cat_icon ?>" aria-hidden="true"></i>
            <?= htmlspecialchars($post['category_name'] ?? '') ?>
        </span>
    </a>
    <div class="post-card__body">
        <h2 class="post-card__title">
            <a href="<?= $post_url ?>"><?= htmlspecialchars($post['title']) ?></a>
        </h2>
        <p class="post-card__excerpt"><?= htmlspecialchars(truncate($post['excerpt'] ?? '', 120)) ?></p>
        <div class="post-card__footer">
            <time class="post-card__date" datetime="<?= format_date_iso($post['published_at']) ?>">
                <?= format_date_short($post['published_at']) ?>
            </time>
            <span class="post-card__read">
                <i class="fas fa-clock" aria-hidden="true"></i> <?= $rtime ?> min
            </span>
        </div>
    </div>
</article>
