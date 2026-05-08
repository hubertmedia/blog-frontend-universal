<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/api.php';
require_once __DIR__ . '/../includes/helpers.php';

$domain_meta   = get_domain_meta();
$page_title    = 'Polityka Prywatności | ' . SITE_NAME;
$page_desc     = 'Polityka prywatności i cookies serwisu blogcasha.pl – przetwarzanie danych osobowych (RODO).';
$canonical_url = SITE_URL . '/polityka-prywatnosci/';
$extra_head    = schema_breadcrumb([
    ['name'=>'Strona główna',        'url'=> SITE_URL.'/'],
    ['name'=>'Polityka prywatności', 'url'=> $canonical_url],
]);
include __DIR__ . '/header.php';
?>

<?php
$cms_page = (function_exists('fetch_settings') ? fetch_settings() : [])['pages']['polityka_prywatnosci'] ?? null;
if ($cms_page):
?>
<div class="container privacy-page">
    <nav class="breadcrumbs" aria-label="Nawigacja okruszkowa">
        <ol class="breadcrumbs__list">
            <li class="breadcrumbs__item"><a href="<?= SITE_URL ?>/">Strona główna</a></li>
            <li class="breadcrumbs__item" aria-hidden="true"><i class="fas fa-chevron-right"></i></li>
            <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page"><?= htmlspecialchars($cms_page['title'] ?? 'Polityka prywatności') ?></li>
        </ol>
    </nav>
    <article class="privacy-content">
        <h1 class="privacy-content__title"><?= htmlspecialchars($cms_page['title'] ?? 'Polityka prywatności') ?></h1>
        <div class="post-content">
            <?= $cms_page['content'] ?>
        </div>
    </article>
</div>
<?php else: ?>
<div class="container privacy-page">
    <nav class="breadcrumbs"><ol class="breadcrumbs__list">
        <li class="breadcrumbs__item"><a href="<?= SITE_URL ?>/">Strona główna</a></li>
        <li class="breadcrumbs__item" aria-hidden="true"><i class="fas fa-chevron-right"></i></li>
        <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page">Polityka prywatności</li>
    </ol></nav>

    <article class="privacy-content">
        <h1 class="privacy-content__title">Polityka Prywatności i Plików Cookies</h1>
        <p class="privacy-content__subtitle">Serwis <strong>blogcasha.pl</strong> &mdash; obowiązuje od 22 kwietnia 2026</p>

        <section class="privacy-section">
            <h2>§ 1 Postanowienia Ogólne</h2>
            <ol>
                <li>Administratorem danych osobowych zbieranych za pośrednictwem serwisu <strong>blogcasha.pl</strong> jest <strong>MEDIA PLANET Spółka z ograniczoną odpowiedzialnością</strong> z siedzibą w Sochaczewie (96-500) przy ul. Gawłowskiej 148, KRS: 0000765337, NIP: 8371865477, REGON: 382228046, e-mail: <a href="mailto:bok@mediaplanet.pl">bok@mediaplanet.pl</a>, tel. +48 570 888 999, zwana dalej „Administratorem".</li>
                <li>Dane są przetwarzane zgodnie z rozporządzeniem RODO (UE 2016/679) oraz polskimi przepisami o ochronie danych osobowych.</li>
                <li>Administrator dokłada szczególnej staranności w celu ochrony interesów osób, których dane dotyczą.</li>
            </ol>
        </section>

        <section class="privacy-section">
            <h2>§ 2 Cele i Podstawy Prawne Przetwarzania</h2>
            <ol>
                <li><strong>Obsługa zapytań kontaktowych</strong> (art. 6 ust. 1 lit. b RODO): Imię, e-mail, treść wiadomości.</li>
                <li><strong>Marketing bezpośredni i Newsletter</strong> (art. 6 ust. 1 lit. a RODO): E-mail, adres IP – po wyrażeniu zgody.</li>
                <li><strong>Obowiązki prawne i księgowe</strong> (art. 6 ust. 1 lit. c RODO): Faktury, księgi rachunkowe.</li>
                <li><strong>Prawnie uzasadniony interes Administratora</strong> (art. 6 ust. 1 lit. f RODO): Analiza statystyczna ruchu, bezpieczeństwo IT, obrona przed roszczeniami.</li>
            </ol>
        </section>

        <section class="privacy-section">
            <h2>§ 3 Okres Przechowywania Danych</h2>
            <ul>
                <li><strong>Dane kontaktowe:</strong> Przez czas niezbędny do obsługi zapytania, a następnie do upływu okresu przedawnienia roszczeń.</li>
                <li><strong>Dane księgowe:</strong> 5 lat od końca roku podatkowego.</li>
                <li><strong>Marketing/Newsletter:</strong> Do czasu wycofania zgody.</li>
            </ul>
        </section>

        <section class="privacy-section">
            <h2>§ 4 Odbiorcy Danych</h2>
            <ul>
                <li>Dostawcy hostingu i systemów IT.</li>
                <li>Biuro rachunkowe Administratora.</li>
                <li>Podmioty świadczące usługi prawne i windykacyjne.</li>
                <li>Uprawnione organy państwowe (na żądanie wynikające z przepisów prawa).</li>
            </ul>
        </section>

        <section class="privacy-section">
            <h2>§ 5 Przekazywanie Danych do Państw Trzecich</h2>
            <p>Dane co do zasady nie są przekazywane poza EOG. Wyjątki:</p>
            <ul>
                <li><strong>Google (USA)</strong> – Analytics, Search Console, AdSense – na podstawie standardowych klauzul umownych.</li>
                <li><strong>Meta (USA)</strong> – Meta Pixel, wtyczki społecznościowe – na tych samych zasadach.</li>
            </ul>
        </section>

        <section class="privacy-section">
            <h2>§ 6 Prawa Użytkownika</h2>
            <ul>
                <li>Dostęp do danych i otrzymanie kopii.</li>
                <li>Sprostowanie danych.</li>
                <li>Usunięcie danych („prawo do bycia zapomnianym").</li>
                <li>Ograniczenie przetwarzania.</li>
                <li>Sprzeciw wobec przetwarzania (szczególnie marketingowego).</li>
                <li>Przenoszenie danych.</li>
                <li>Skarga do Prezesa UODO (ul. Stawki 2, 00-193 Warszawa).</li>
            </ul>
            <p>Kontakt: <a href="mailto:bok@mediaplanet.pl">bok@mediaplanet.pl</a></p>
        </section>

        <section class="privacy-section">
            <h2>§ 7 Pliki Cookies i Narzędzia Analityczne</h2>
            <ol>
                <li>Serwis blogcasha.pl używa plików cookies w celach statystycznych, funkcjonalnych i reklamowych.</li>
                <li><strong>Google Analytics</strong> – analiza ruchu i optymalizacja serwisu.</li>
                <li><strong>Google AdSense</strong> – reklamy dopasowane do zainteresowań. Zarządzanie ustawieniami: <a href="https://adssettings.google.com" target="_blank" rel="noopener noreferrer">adssettings.google.com</a>.</li>
                <li>Ustawienia cookies można zmienić w przeglądarce – zablokowanie może wpłynąć na funkcjonalność serwisu.</li>
            </ol>
        </section>

        <section class="privacy-section">
            <h2>§ 8 Disclaimer Finansowy</h2>
            <p>Treści publikowane na blogcasha.pl mają charakter wyłącznie informacyjny i edukacyjny. <strong>Nie stanowią porady finansowej, inwestycyjnej ani prawnej</strong> w rozumieniu Ustawy z dnia 29 lipca 2005 r. o obrocie instrumentami finansowymi. Administrator nie ponosi odpowiedzialności za decyzje finansowe podjęte na podstawie materiałów opublikowanych w serwisie.</p>
        </section>

        <section class="privacy-section">
            <h2>§ 9 Postanowienia Końcowe</h2>
            <ol>
                <li>Administrator stosuje środki techniczne (certyfikat SSL, szyfrowanie) zabezpieczające dane.</li>
                <li>Serwis może zawierać linki zewnętrzne – zalecamy zapoznanie się z ich politykami prywatności.</li>
                <li>Administrator zastrzega prawo aktualizacji Polityki przy zmianach prawnych lub technicznych.</li>
            </ol>
            <p class="privacy-content__contact">
                <i class="fas fa-envelope"></i>
                Kontakt ds. ochrony danych: <a href="mailto:bok@mediaplanet.pl">bok@mediaplanet.pl</a>
            </p>
        </section>
    </article>
</div>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
