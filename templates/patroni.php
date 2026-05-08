<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/api.php';
require_once __DIR__ . '/../includes/helpers.php';

$domain_meta   = get_domain_meta();
$page_title    = 'Patroni blogcasha.pl – Wesprzyj niezależny blog finansowy';
$page_desc     = 'Zostań patronem blogcasha.pl lub wykup artykuł sponsorowany z linkiem dofollow. Dotrzyj do tysięcy czytelników zainteresowanych finansami osobistymi, inwestycjami i kredytami.';
$canonical_url = SITE_URL . '/patroni/';
$extra_head    = schema_breadcrumb([
    ['name' => 'Strona główna', 'url' => SITE_URL . '/'],
    ['name' => 'Patroni',       'url' => $canonical_url],
]);

include __DIR__ . '/header.php';
?>

<style>
/* ── Patroni page – scoped styles ───────────────────── */
.patroni-hero {
    background: linear-gradient(135deg, var(--color-primary) 0%, #1e293b 60%, #0c1a2e 100%);
    padding: 5rem 0 4rem;
    position: relative;
    overflow: hidden;
}
.patroni-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f59e0b' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
}
.patroni-hero__inner {
    position: relative;
    z-index: 1;
    max-width: 720px;
    margin: 0 auto;
    text-align: center;
    padding: 0 1.5rem;
}
.patroni-hero__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: rgba(245,158,11,.15);
    border: 1px solid rgba(245,158,11,.35);
    color: var(--color-gold);
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    padding: .35rem .9rem;
    border-radius: 2rem;
    margin-bottom: 1.5rem;
}
.patroni-hero__title {
    font-family: var(--font-heading);
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700;
    color: #fff;
    line-height: 1.15;
    margin-bottom: 1.25rem;
}
.patroni-hero__title .gold { color: var(--color-gold); }
.patroni-hero__sub {
    color: rgba(255,255,255,.72);
    font-size: 1.1rem;
    line-height: 1.7;
    margin-bottom: 2rem;
}

/* ── Empty state ──────────────────────────────────── */
.patroni-empty {
    background: #fff;
    border-radius: 1.25rem;
    border: 2px dashed rgba(245,158,11,.4);
    padding: 3.5rem 2rem;
    text-align: center;
    max-width: 560px;
    margin: -2.5rem auto 0;
    position: relative;
    z-index: 2;
    box-shadow: 0 8px 40px rgba(15,23,42,.08);
}
.patroni-empty__icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    font-size: 1.75rem;
    color: #b45309;
}
.patroni-empty__title {
    font-family: var(--font-heading);
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--color-primary);
    margin-bottom: .75rem;
}
.patroni-empty__text {
    color: var(--color-text-muted);
    font-size: .97rem;
    line-height: 1.7;
}

/* ── Why section ──────────────────────────────────── */
.patroni-why {
    padding: 5rem 0 4rem;
    background: var(--color-bg-alt);
}
.patroni-why .section__title {
    text-align: center;
    margin-bottom: 2.5rem;
}
.why-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 1.5rem;
}
.why-card {
    background: #fff;
    border-radius: 1rem;
    padding: 2rem 1.5rem;
    border-top: 3px solid var(--color-gold);
    box-shadow: 0 2px 16px rgba(15,23,42,.06);
    transition: transform .2s, box-shadow .2s;
}
.why-card:hover { transform: translateY(-4px); box-shadow: 0 8px 32px rgba(15,23,42,.1); }
.why-card__icon {
    width: 48px; height: 48px;
    border-radius: .75rem;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    color: #b45309;
    margin-bottom: 1rem;
}
.why-card__title {
    font-family: var(--font-heading);
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--color-primary);
    margin-bottom: .5rem;
}
.why-card__text { color: var(--color-text-muted); font-size: .93rem; line-height: 1.65; }

/* ── Offer section ────────────────────────────────── */
.patroni-offer {
    padding: 5rem 0;
}
.patroni-offer .section__title { text-align: center; margin-bottom: .5rem; }
.patroni-offer .section__sub {
    text-align: center;
    color: var(--color-text-muted);
    margin-bottom: 3rem;
    font-size: .97rem;
}
.patroni-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.75rem;
    max-width: 900px;
    margin: 0 auto;
}
.patroni-card {
    background: var(--color-primary);
    border-radius: 1.25rem;
    padding: 2.25rem 2rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.patroni-card::after {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 120px; height: 120px;
    border-radius: 50%;
    background: rgba(245,158,11,.1);
}
.patroni-card:hover { transform: translateY(-5px); box-shadow: 0 16px 48px rgba(15,23,42,.25); }
.patroni-card--featured {
    background: linear-gradient(135deg, #1e293b, var(--color-primary));
    border: 2px solid var(--color-gold);
}
.patroni-card__badge {
    position: absolute;
    top: 1.25rem; right: 1.25rem;
    background: var(--color-gold);
    color: #000;
    font-size: .72rem;
    font-weight: 800;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: .25rem .65rem;
    border-radius: .5rem;
}
.patroni-card__icon {
    font-size: 1.75rem;
    color: var(--color-gold);
    margin-bottom: 1rem;
}
.patroni-card__title {
    font-family: var(--font-heading);
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: .75rem;
}
.patroni-card__desc {
    color: rgba(255,255,255,.72);
    font-size: .93rem;
    line-height: 1.65;
    margin-bottom: 1.25rem;
}
.patroni-card__features {
    list-style: none; padding: 0; margin: 0 0 1.5rem;
    display: flex; flex-direction: column; gap: .5rem;
}
.patroni-card__features li {
    display: flex; align-items: center; gap: .5rem;
    font-size: .9rem; color: rgba(255,255,255,.85);
}
.patroni-card__features li i { color: var(--color-gold); font-size: .8rem; }
.patroni-card__price {
    font-size: 1.5rem; font-weight: 800;
    color: var(--color-gold);
    font-family: var(--font-heading);
}
.patroni-card__price span { font-size: .85rem; font-weight: 400; color: rgba(255,255,255,.5); }

/* ── CTA section ──────────────────────────────────── */
.patroni-cta {
    background: linear-gradient(135deg, #fef3c7 0%, #fff7ed 100%);
    border-top: 1px solid #fde68a;
    border-bottom: 1px solid #fde68a;
    padding: 4.5rem 0;
    text-align: center;
}
.patroni-cta__title {
    font-family: var(--font-heading);
    font-size: clamp(1.5rem, 3.5vw, 2.25rem);
    font-weight: 700;
    color: var(--color-primary);
    margin-bottom: 1rem;
}
.patroni-cta__sub {
    color: #78350f;
    max-width: 560px;
    margin: 0 auto 2rem;
    font-size: 1rem;
    line-height: 1.7;
}
.patroni-cta__btns {
    display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;
}

/* ── Categories nav ───────────────────────────────── */
.patroni-cats {
    padding: 3.5rem 0 4rem;
}
.patroni-cats .section__title { text-align: center; margin-bottom: 2rem; }
.cat-pills {
    display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center;
}
.cat-pill {
    display: inline-flex; align-items: center; gap: .5rem;
    background: #fff;
    border: 1px solid var(--color-border);
    border-radius: 2rem;
    padding: .5rem 1.1rem;
    font-size: .9rem; font-weight: 600;
    color: var(--color-text);
    text-decoration: none;
    transition: all .2s;
}
.cat-pill:hover {
    border-color: var(--color-gold);
    color: var(--color-primary);
    box-shadow: 0 2px 12px rgba(245,158,11,.2);
    transform: translateY(-2px);
}
.cat-pill i { font-size: .85rem; }
</style>

<!-- Hero -->
<section class="patroni-hero">
    <div class="patroni-hero__inner">
        <span class="patroni-hero__eyebrow">
            <i class="fas fa-heart" aria-hidden="true"></i> Patroni &amp; Sponsorzy
        </span>
        <h1 class="patroni-hero__title">
            Wspierasz nas –<br>
            <span class="gold">my wspieramy Twoje SEO</span>
        </h1>
        <p class="patroni-hero__sub">
            blogcasha.pl to niszowy serwis finansowy czytany przez osoby aktywnie
            zainteresowane inwestycjami, kredytami i finansami osobistymi.
            Treści tworzone tu trafiają wysoko w Google – Twój link też może.
        </p>
        <a href="#zostan-patronem" class="btn btn--gold btn--lg">
            <i class="fas fa-star" aria-hidden="true"></i> Zostań patronem
        </a>
    </div>
</section>

<!-- Empty state card -->
<section class="section" style="padding: 0 0 3rem;">
    <div class="container">
        <div class="patroni-empty">
            <div class="patroni-empty__icon">
                <i class="fas fa-users" aria-hidden="true"></i>
            </div>
            <h2 class="patroni-empty__title">Aktualnie brak patronów</h2>
            <p class="patroni-empty__text">
                Nasz blog jest niezależny i rozwijamy go z pasji do finansów osobistych.
                Jeszcze żaden patron nie dołączył do grona wspierających –
                <strong>możesz być pierwszy</strong>. Napisz do nas i ustal warunki współpracy
                dostosowane do Twoich potrzeb SEO.
            </p>
        </div>
    </div>
</section>

<!-- Why -->
<section class="patroni-why">
    <div class="container">
        <h2 class="section__title">
            Dlaczego <span class="section__title-accent">warto</span> tu zainwestować?
        </h2>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-card__icon"><i class="fas fa-magnifying-glass-chart" aria-hidden="true"></i></div>
                <h3 class="why-card__title">Silne pozycje w Google</h3>
                <p class="why-card__text">
                    Artykuły na blogcasha.pl rankują na frazy z branży fintech, kredytów
                    i inwestycji. Link z trafnego kontekstu finansowego to sygnał dla Googlebota.
                </p>
            </div>
            <div class="why-card">
                <div class="why-card__icon"><i class="fas fa-link" aria-hidden="true"></i></div>
                <h3 class="why-card__title">Wyłącznie Dofollow</h3>
                <p class="why-card__text">
                    Nie stosujemy atrybutu <code>nofollow</code> na linkach sponsorowanych.
                    Każdy link przekazuje pełny link equity – realną wartość SEO.
                </p>
            </div>
            <div class="why-card">
                <div class="why-card__icon"><i class="fas fa-users" aria-hidden="true"></i></div>
                <h3 class="why-card__title">Targetowana publiczność</h3>
                <p class="why-card__text">
                    Czytelnicy blogcasha.pl to osoby w trakcie decyzji finansowych:
                    porównują oferty kredytów, szukają kont oszczędnościowych, pytają o ETF-y.
                </p>
            </div>
            <div class="why-card">
                <div class="why-card__icon"><i class="fas fa-infinity" aria-hidden="true"></i></div>
                <h3 class="why-card__title">Wieczysta publikacja</h3>
                <p class="why-card__text">
                    Artykuł zostaje na stałe. Płacisz raz, a link pracuje na Twoją
                    pozycję przez miesiące i lata – bez opłat abonamentowych.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Offer cards -->
<section class="patroni-offer" id="zostan-patronem" aria-labelledby="patroni-offer-h">
    <div class="container">
        <h2 class="section__title" id="patroni-offer-h">
            Wybierz <span class="section__title-accent">formę wsparcia</span>
        </h2>
        <p class="section__sub">
            Wszystkie ceny netto. Możliwa negocjacja przy dłuższej współpracy.
        </p>
        <div class="patroni-cards">

            <div class="patroni-card">
                <div class="patroni-card__icon"><i class="fas fa-link" aria-hidden="true"></i></div>
                <div class="patroni-card__title">Link w artykule</div>
                <p class="patroni-card__desc">
                    Osadzamy Twój link dofollow w istniejącym, indeksowanym artykule
                    z pasującą tematyką finansową.
                </p>
                <ul class="patroni-card__features">
                    <li><i class="fas fa-check" aria-hidden="true"></i> 1 link dofollow</li>
                    <li><i class="fas fa-check" aria-hidden="true"></i> Istniejący artykuł z ruchem</li>
                    <li><i class="fas fa-check" aria-hidden="true"></i> Realizacja w 24h</li>
                    <li><i class="fas fa-check" aria-hidden="true"></i> Wieczysta publikacja</li>
                </ul>
                <div class="patroni-card__price">250 zł <span>netto / szt.</span></div>
            </div>

            <div class="patroni-card patroni-card--featured">
                <div class="patroni-card__badge">Polecane</div>
                <div class="patroni-card__icon"><i class="fas fa-file-pen" aria-hidden="true"></i></div>
                <div class="patroni-card__title">Artykuł sponsorowany</div>
                <p class="patroni-card__desc">
                    Piszemy lub publikujemy Twój artykuł z naturalnie wplecionym
                    linkiem dofollow do Twojej strony.
                </p>
                <ul class="patroni-card__features">
                    <li><i class="fas fa-check" aria-hidden="true"></i> Artykuł min. 800 słów</li>
                    <li><i class="fas fa-check" aria-hidden="true"></i> Do 2 linków dofollow</li>
                    <li><i class="fas fa-check" aria-hidden="true"></i> Optymalizacja pod frazę</li>
                    <li><i class="fas fa-check" aria-hidden="true"></i> Publikacja w 48h</li>
                    <li><i class="fas fa-check" aria-hidden="true"></i> Wieczysta publikacja</li>
                </ul>
                <div class="patroni-card__price">500 zł <span>netto / szt.</span></div>
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="patroni-cta">
    <div class="container">
        <h2 class="patroni-cta__title">
            Zainteresowany? Napisz do nas
        </h2>
        <p class="patroni-cta__sub">
            Skontaktuj się mailowo, opisz swoją stronę i cel współpracy.
            Odpowiadamy w ciągu jednego dnia roboczego i przygotowujemy
            indywidualną ofertę bez zbędnych formalności.
        </p>
        <div class="patroni-cta__btns">
            <a href="mailto:<?= CONTACT_EMAIL ?>" class="btn btn--primary btn--lg">
                <i class="fas fa-envelope" aria-hidden="true"></i>
                <?= CONTACT_EMAIL ?>
            </a>
            <a href="<?= SITE_URL ?>/wspolpraca/" class="btn btn--outline btn--lg">
                <i class="fas fa-handshake" aria-hidden="true"></i>
                Pełna oferta współpracy
            </a>
        </div>
    </div>
</section>

<!-- Category nav -->
<section class="patroni-cats">
    <div class="container">
        <h2 class="section__title">
            Nasze <span class="section__title-accent">kategorie tematyczne</span>
        </h2>
        <div class="cat-pills">
            <?php foreach (($nav_cats ?? get_nav_categories()) as $cat): ?>
            <a href="<?= SITE_URL ?>/kategoria/<?= htmlspecialchars($cat['slug']) ?>/" class="cat-pill">
                <i class="<?= htmlspecialchars($cat['icon']) ?>" style="color:<?= htmlspecialchars($cat['color']) ?>" aria-hidden="true"></i>
                <?= htmlspecialchars($cat['name']) ?>
            </a>
            <?php endforeach; ?>
            <a href="<?= SITE_URL ?>/" class="cat-pill">
                <i class="fas fa-home" style="color:var(--color-gold)" aria-hidden="true"></i>
                Strona główna
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>
