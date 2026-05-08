<?php
// Expects $sidebar_posts, optionally $active_cat_slug
$nav_cats = $nav_cats ?? get_nav_categories();
?>
<div class="widget">
    <h3 class="widget__title"><i class="fas fa-fire-flame-curved"></i> Popularne artykuły</h3>
    <ol class="widget__list">
        <?php foreach (($sidebar_posts ?? []) as $i => $sp): ?>
        <li class="widget__item">
            <span class="widget__num"><?= $i+1 ?></span>
            <a href="<?= SITE_URL ?>/artykul/<?= rawurlencode($sp['slug']) ?>/" class="widget__link">
                <?= htmlspecialchars($sp['title']) ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ol>
</div>

<?php if (!empty($nav_cats)): ?>
<div class="widget">
    <h3 class="widget__title"><i class="fas fa-layer-group"></i> Kategorie</h3>
    <ul class="widget__cats">
        <?php foreach ($nav_cats as $cat): ?>
        <li>
            <a href="<?= SITE_URL ?>/kategoria/<?= htmlspecialchars($cat['slug']) ?>/"
               class="widget__cat-link <?= (($active_cat_slug??'') === $cat['slug']) ? 'active' : '' ?>"
               style="--cat-color:<?= htmlspecialchars($cat['color']) ?>">
                <i class="<?= htmlspecialchars($cat['icon']) ?>"></i>
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="widget widget--calculator">
    <h3 class="widget__title"><i class="fas fa-calculator"></i> Kalkulatory</h3>
    <p class="widget__calc-text">Interaktywne kalkulatory finansowe – wkrótce dostępne.</p>
    <ul class="widget__calc-list">
        <li><i class="fas fa-percent"></i> Kalkulator odsetek</li>
        <li><i class="fas fa-home"></i> Kalkulator kredytu hipotecznego</li>
        <li><i class="fas fa-piggy-bank"></i> Kalkulator oszczędności</li>
        <li><i class="fas fa-chart-pie"></i> Kalkulator inwestycji</li>
    </ul>
    <span class="widget__badge-soon">Wkrótce</span>
</div>

<div class="widget widget--disclaimer">
    <h3 class="widget__title"><i class="fas fa-triangle-exclamation"></i> Ważna informacja</h3>
    <p>Artykuły na blogcasha.pl mają charakter informacyjny i <strong>nie stanowią porady finansowej</strong>. Przed podjęciem decyzji skonsultuj się z doradcą.</p>
</div>

<div class="widget widget--ad">
    <?= render_ad_slot('sidebar-rectangle') ?>
</div>
