<?php
$domain_meta = $domain_meta ?? [];
$nav_cats    = $nav_cats ?? get_nav_categories();
$site_tagline = $domain_meta['description'] ?? SITE_DESC;
?>
    </main>

    <div class="disclaimer-bar">
        <div class="container">
            <i class="fas fa-triangle-exclamation" aria-hidden="true"></i>
            <p>Treści na <strong><?= SITE_NAME ?></strong> mają charakter informacyjny i nie stanowią porady finansowej, inwestycyjnej ani prawnej. Przed podjęciem decyzji finansowych skonsultuj się z licencjonowanym doradcą. Inwestowanie wiąże się z ryzykiem utraty środków.</p>
        </div>
    </div>

    <footer class="site-footer">
        
        <?php
        $cms_footer = (function_exists('fetch_settings') ? fetch_settings() : [])['pages']['stopka'] ?? null;
        if ($cms_footer):
        ?>
            <?= $cms_footer['content'] ?>
        <?php else: ?>
        <div class="container footer__inner">
            <div class="footer__brand">
                <a class="footer__logo" href="<?= SITE_URL ?>/">
                    <span>
                        <?php 
                        $domain_parts = explode('.', SITE_NAME);
                        echo htmlspecialchars($domain_parts[0] ?? SITE_NAME);
                        if (isset($domain_parts[1])) echo '<strong>.' . htmlspecialchars($domain_parts[1]) . '</strong>';
                        ?>
                    </span>
                </a>
                <p class="footer__tagline"><?= htmlspecialchars($site_tagline) ?></p>
                <div class="footer__social">
                    <a href="#" aria-label="Facebook" class="social__link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter" class="social__link"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" aria-label="LinkedIn" class="social__link"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="footer__nav">
                <h3 class="footer__heading">Kategorie</h3>
                <ul class="footer__links">
                    <?php foreach ($nav_cats as $cat): ?>
                    <li><a href="<?= SITE_URL ?>/kategoria/<?= htmlspecialchars($cat['slug']) ?>/">
                        <i class="<?= htmlspecialchars($cat['icon']) ?>" style="color:<?= htmlspecialchars($cat['color']) ?>"></i>
                        <?= htmlspecialchars($cat['name']) ?>
                    </a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="footer__nav">
                <h3 class="footer__heading">Serwis</h3>
                <ul class="footer__links">
                    <li><a href="<?= SITE_URL ?>/">Strona główna</a></li>
                    <li><a href="<?= SITE_URL ?>/wspolpraca/">Współpraca i reklama</a></li>
                    <li><a href="<?= SITE_URL ?>/polityka-prywatnosci/">Polityka prywatności</a></li>
                    <li><a href="mailto:<?= CONTACT_EMAIL ?>">Kontakt</a></li>
                    <li><a href="<?= SITE_URL ?>/sitemap.xml">Sitemap XML</a></li>
                </ul>
            </div>

            <div class="footer__disclaimer">
                <h3 class="footer__heading">Disclaimer</h3>
                <p>Materiały publikowane na <?= SITE_NAME ?> mają wyłącznie charakter informacyjno-edukacyjny i nie stanowią rekomendacji inwestycyjnych w rozumieniu Ustawy z dnia 29 lipca 2005 r. o obrocie instrumentami finansowymi.</p>
                <p style="margin-top:.75rem">Administrator: <a href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a></p>
            </div>
        </div>

        <div class="footer__bottom">
            <div class="container">
                <p>
                    &copy; <?= date('Y') ?> <a href="<?= SITE_URL ?>/"><?= SITE_NAME ?></a>
                    &nbsp;|&nbsp; <a href="<?= SITE_URL ?>/polityka-prywatnosci/">Polityka prywatności</a>
                    &nbsp;|&nbsp; <a href="<?= SITE_URL ?>/wspolpraca/">Współpraca</a>
                    &nbsp;|&nbsp; <a href="mailto:<?= CONTACT_EMAIL ?>">Kontakt</a>
                </p>
            </div>
        </div>
    <?php endif; ?>
    </footer>

    <script src="<?= SITE_URL ?>/js/main.js" defer></script>
    <?= $domain_meta['footer_scripts'] ?? '' ?>
</body>
</html>
