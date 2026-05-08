<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/api.php';
require_once __DIR__ . '/../includes/helpers.php';

$domain_meta   = get_domain_meta();
$page_title    = 'Współpraca i reklama | ' . SITE_NAME;
$page_desc     = 'Dotrzyj do świadomych finansowo Polaków. Oferta artykułów sponsorowanych i linków na blogcasha.pl.';
$canonical_url = SITE_URL . '/wspolpraca/';
$extra_head    = schema_breadcrumb([
    ['name'=>'Strona główna','url'=> SITE_URL.'/'],
    ['name'=>'Współpraca',   'url'=> $canonical_url],
]);
include __DIR__ . '/header.php';
?>

<section class="collab-hero" aria-labelledby="collab-h">
    <div class="container collab-hero__inner">
        <div class="collab-hero__text">
            <span class="collab-hero__eyebrow">Oferta reklamowa</span>
            <h1 class="collab-hero__title" id="collab-h">
                Dotarcie do <span class="highlight">świadomych finansowo</span> Polaków
            </h1>
            <p class="collab-hero__sub">
                blogcasha.pl to niszowy serwis finansowy skupiający czytelników aktywnie zainteresowanych
                inwestycjami, kredytami, kryptowalutami i planowaniem finansowym. Najwyższe CPC w branży.
            </p>
            <a href="#kontakt" class="btn btn--primary btn--lg">
                <i class="fas fa-envelope"></i> Napisz do nas
            </a>
        </div>
        <div class="collab-hero__stats">
            <div class="stat-card">
                <span class="stat-card__num">Tysiące</span>
                <span class="stat-card__label">unikalnych użytkowników/mies.</span>
            </div>
            <div class="stat-card">
                <span class="stat-card__num">8</span>
                <span class="stat-card__label">nisz finansowych</span>
            </div>
            <div class="stat-card">
                <span class="stat-card__num">Wysokie</span>
                <span class="stat-card__label">CPC w niszy finansowej</span>
            </div>
            <div class="stat-card">
                <span class="stat-card__num">Dofollow</span>
                <span class="stat-card__label">linki bez nofollow</span>
            </div>
        </div>
    </div>
</section>

<section class="usp-section" aria-labelledby="usp-h">
    <div class="container">
        <h2 class="section__title section__title--center" id="usp-h">
            Dlaczego <span class="section__title-accent">blogcasha.pl</span>?
        </h2>
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

<section class="offer-section" aria-labelledby="offer-h">
    <div class="container">
        <h2 class="section__title section__title--center" id="offer-h">
            Nasza <span class="section__title-accent">oferta</span>
        </h2>
        <div class="offer-cards">
            <div class="offer-card">
                <div class="offer-card__header" style="--offer-color:#f59e0b">
                    <i class="fas fa-file-pen"></i><h3>Artykuł sponsorowany</h3>
                </div>
                <ul class="offer-card__features">
                    <li><i class="fas fa-check"></i> 1 artykuł dofollow</li>
                    <li><i class="fas fa-check"></i> Oznaczony jako sponsorowany</li>
                    <li><i class="fas fa-check"></i> Publikacja w 48h</li>
                    <li><i class="fas fa-check"></i> Wieczysta publikacja</li>
                </ul>
                <div class="offer-card__price">od <span>500 zł</span></div>
                <a href="#kontakt" class="btn btn--primary offer-card__btn">Zamów</a>
            </div>
            <div class="offer-card offer-card--featured">
                <div class="offer-card__badge">Premium</div>
                <div class="offer-card__header" style="--offer-color:#10b981">
                    <i class="fas fa-crown"></i><h3>Artykuł Premium</h3>
                </div>
                <ul class="offer-card__features">
                    <li><i class="fas fa-check"></i> 1 artykuł bez oznaczenia</li>
                    <li><i class="fas fa-check"></i> 2 linki dofollow</li>
                    <li><i class="fas fa-check"></i> Priorytetowa obsługa</li>
                    <li><i class="fas fa-check"></i> Wieczysta publikacja</li>
                </ul>
                <div class="offer-card__price">od <span>900 zł</span></div>
                <a href="#kontakt" class="btn btn--primary offer-card__btn">Zamów</a>
            </div>
            <div class="offer-card">
                <div class="offer-card__header" style="--offer-color:#3b82f6">
                    <i class="fas fa-link"></i><h3>Link w artykule</h3>
                </div>
                <ul class="offer-card__features">
                    <li><i class="fas fa-check"></i> Osadzenie w istniejącym tekście</li>
                    <li><i class="fas fa-check"></i> Dofollow</li>
                    <li><i class="fas fa-check"></i> Realizacja 24h</li>
                    <li><i class="fas fa-check"></i> Wieczyste</li>
                </ul>
                <div class="offer-card__price">od <span>250 zł</span></div>
                <a href="#kontakt" class="btn btn--primary offer-card__btn">Zamów</a>
            </div>
            <div class="offer-card">
                <div class="offer-card__header" style="--offer-color:#8b5cf6">
                    <i class="fas fa-layer-group"></i><h3>Pakiet 5 artykułów</h3>
                </div>
                <ul class="offer-card__features">
                    <li><i class="fas fa-check"></i> 5 artykułów/miesiąc</li>
                    <li><i class="fas fa-check"></i> Raport miesięczny</li>
                    <li><i class="fas fa-check"></i> Dedykowany opiekun</li>
                    <li><i class="fas fa-check"></i> Rabat pakietowy</li>
                </ul>
                <div class="offer-card__price">od <span>2000 zł</span></div>
                <a href="#kontakt" class="btn btn--primary offer-card__btn">Zamów</a>
            </div>
        </div>
        <p class="offer-note">
            <i class="fas fa-gavel"></i>
            Artykuły sponsorowane oznaczamy zgodnie z wymogami UOKiK. Ceny netto.
        </p>
    </div>
</section>

<section class="contact-section" id="kontakt" aria-labelledby="contact-h">
    <div class="container">
        <div class="contact-simple">
            <h2 class="section__title section__title--center" id="contact-h">
                Skontaktuj <span class="section__title-accent">się z nami</span>
            </h2>
            <p class="contact-simple__sub">Odpowiadamy w ciągu 24 godzin w dni robocze.</p>
            <a href="mailto:bok@mediaplanet.pl" class="contact-simple__email">
                <span class="contact-simple__email-icon"><i class="fas fa-envelope"></i></span>
                <span>bok@mediaplanet.pl</span>
            </a>
            <div class="contact-simple__features">
                <div><i class="fas fa-check-circle"></i> Szybka odpowiedź</div>
                <div><i class="fas fa-check-circle"></i> Indywidualna wycena</div>
                <div><i class="fas fa-check-circle"></i> Zgodność z UOKiK</div>
            </div>
        </div>
    </div>
</section>

<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
