<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/api.php';
require_once __DIR__ . '/../includes/helpers.php';

$data = fetch_settings();
$s = $data['pages']['wspolpraca_structured'] ?? [];

// Fallback values
$hero_title = !empty($s['hero_title']) ? $s['hero_title'] : 'Dotarcie do świadomych finansowo Polaków';
$hero_sub   = !empty($s['hero_sub']) ? $s['hero_sub'] : SITE_NAME . ' to niszowy serwis finansowy skupiający czytelników aktywnie zainteresowanych inwestycjami, kredytami, kryptowalutami i planowaniem finansowym. Najwyższe CPC w branży.';

$stats = [
    1 => ['val' => $s['stats'][1]['val'] ?? 'Tysiące', 'lbl' => $s['stats'][1]['lbl'] ?? 'unikalnych użytkowników/mies.'],
    2 => ['val' => $s['stats'][2]['val'] ?? '8',       'lbl' => $s['stats'][2]['lbl'] ?? 'nisz finansowych'],
    3 => ['val' => $s['stats'][3]['val'] ?? 'Wysokie', 'lbl' => $s['stats'][3]['lbl'] ?? 'CPC w niszy finansowej'],
    4 => ['val' => $s['stats'][4]['val'] ?? 'Dofollow','lbl' => $s['stats'][4]['lbl'] ?? 'linki bez nofollow']
];

$prices = [
    'sponsored' => $s['prices']['sponsored'] ?? 'od 500 zł',
    'premium'   => $s['prices']['premium']   ?? 'od 900 zł',
    'link'      => $s['prices']['link']      ?? 'od 250 zł',
    'package'   => $s['prices']['package']   ?? 'od 2000 zł'
];

$features = [
    'sponsored' => !empty($s['features']['sponsored']) ? explode("\n", $s['features']['sponsored']) : ['1 artykuł dofollow', 'Oznaczony jako sponsorowany', 'Publikacja w 48h', 'Wieczysta publikacja'],
    'premium'   => !empty($s['features']['premium'])   ? explode("\n", $s['features']['premium'])   : ['1 artykuł partnerski', '2 linki dofollow', 'Ekspercka redakcja i korekta', 'Wieczysta publikacja'],
    'link'      => !empty($s['features']['link'])      ? explode("\n", $s['features']['link'])      : ['Osadzenie w istniejącym tekście', 'Dofollow', 'Realizacja 24h', 'Wieczyste'],
    'package'   => !empty($s['features']['package'])   ? explode("\n", $s['features']['package'])   : ['5 artykułów/miesiąc', 'Raport miesięczny', 'Dedykowany opiekun', 'Rabat pakietowy']
];

$page_title = 'Współpraca i reklama | ' . SITE_NAME;
include __DIR__ . '/header.php';
?>

<main id="main-content">
    <section class="collab-hero">
        <div class="container collab-hero__inner">
            <div class="collab-hero__text">
                <span class="collab-hero__eyebrow">Oferta reklamowa</span>
                <h1 class="collab-hero__title"><?= htmlspecialchars($hero_title) ?></h1>
                <p class="collab-hero__sub"><?= htmlspecialchars($hero_sub) ?></p>
                <a href="#kontakt" class="btn btn--primary btn--lg"><i class="fas fa-envelope"></i> Napisz do nas</a>
            </div>
            <div class="collab-hero__stats">
                <?php foreach($stats as $st): ?>
                <div class="stat-card">
                    <span class="stat-card__num"><?= htmlspecialchars($st['val']) ?></span>
                    <span class="stat-card__label"><?= htmlspecialchars($st['lbl']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="usp-section">
        <div class="container">
            <h2 class="section__title section__title--center">Dlaczego <span class="section__title-accent"><?= SITE_NAME ?></span>?</h2>
            <div class="usp-grid">
                <div class="usp-card">
                    <div class="usp-card__icon" style="--usp-color:#f59e0b"><i class="fas fa-coins"></i></div>
                    <h3 class="usp-card__title">Nisza finansowa = wysokie CPC</h3>
                    <p class="usp-card__text">Finanse osobiste to jedna z najdroższych nisz reklamowych. Twoje linki trafiają do czytelników o wysokiej sile nabywczej.</p>
                </div>
                <div class="usp-card">
                    <div class="usp-card__icon" style="--usp-color:#3b82f6"><i class="fas fa-shield-halved"></i></div>
                    <h3 class="usp-card__title">Zaufana marka</h3>
                    <p class="usp-card__text">Merytoryczne treści finansowe budują zaufanie do serwisu i reklamodawców. Czytelnicy wracają po sprawdzone informacje.</p>
                </div>
                <div class="usp-card">
                    <div class="usp-card__icon" style="--usp-color:#10b981"><i class="fas fa-magnifying-glass-chart"></i></div>
                    <h3 class="usp-card__title">SEO i widoczność w Google</h3>
                    <p class="usp-card__text">Artykuły zoptymalizowane pod frazy finansowe z wysokim wolumenem. Dofollow linki budują autorytet domeny klienta.</p>
                </div>
                <div class="usp-card">
                    <div class="usp-card__icon" style="--usp-color:#8b5cf6"><i class="fas fa-users"></i></div>
                    <h3 class="usp-card__title">Zaangażowani czytelnicy</h3>
                    <p class="usp-card__text">Średni czas na stronie powyżej 3 min. Czytelnicy szukający konkretnych produktów finansowych – kredytów, lokat, kart.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="offer-section">
        <div class="container">
            <h2 class="section__title section__title--center">Nasza <span class="section__title-accent">oferta</span></h2>
            <div class="offer-cards">
                <?php 
                $card_icons = ['sponsored' => 'fa-file-pen', 'premium' => 'fa-crown', 'link' => 'fa-link', 'package' => 'fa-layer-group'];
                $card_colors = ['sponsored' => '#f59e0b', 'premium' => '#10b981', 'link' => '#3b82f6', 'package' => '#8b5cf6'];
                foreach($prices as $key => $price): 
                    $name = ($key == 'sponsored') ? 'Artykuł sponsorowany' : (($key == 'premium') ? 'Artykuł Premium' : (($key == 'link') ? 'Link w artykule' : 'Pakiet 5 artykułów'));
                ?>
                <div class="offer-card <?= ($key == 'premium') ? 'offer-card--featured' : '' ?>">
                    <?php if($key == 'premium'): ?><div class="offer-card__badge">Premium</div><?php endif; ?>
                    <div class="offer-card__header" style="--offer-color:<?= $card_colors[$key] ?>">
                        <i class="fas <?= $card_icons[$key] ?>"></i><h3><?= $name ?></h3>
                    </div>
                    <ul class="offer-card__features">
                        <?php foreach($features[$key] as $feat): if(empty(trim($feat))) continue; ?>
                        <li><i class="fas fa-check"></i> <?= htmlspecialchars(trim($feat)) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="offer-card__price">od <span><?= htmlspecialchars($price) ?></span></div>
                    <a href="#kontakt" class="btn btn--primary offer-card__btn">Zamów</a>
                </div>
                <?php endforeach; ?>
            </div>
            <p class="offer-note"><i class="fas fa-gavel"></i> Artykuły sponsorowane oznaczamy zgodnie z wymogami UOKiK. Ceny netto.</p>
        </div>
    </section>

    <section class="contact-section" id="kontakt">
        <div class="container">
            <div class="contact-simple">
                <h2 class="section__title section__title--center">Skontaktuj <span class="section__title-accent">się z nami</span></h2>
                <p class="contact-simple__sub">Odpowiadamy w ciągu 24 godzin w dni robocze.</p>
                <a href="mailto:<?= CONTACT_EMAIL ?>" class="contact-simple__email">
                    <span class="contact-simple__email-icon"><i class="fas fa-envelope"></i></span>
                    <span><?= CONTACT_EMAIL ?></span>
                </a>
                <div class="contact-simple__features">
                    <div><i class="fas fa-check-circle"></i> Szybka odpowiedź</div>
                    <div><i class="fas fa-check-circle"></i> Indywidualna wycena</div>
                    <div><i class="fas fa-check-circle"></i> Zgodność z UOKiK</div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/footer.php'; ?>
