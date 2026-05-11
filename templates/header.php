<?php
$page_title    = $page_title    ?? SITE_NAME . ' – Serwis Finansowy';
$page_desc     = $page_desc     ?? ($domain_meta['description'] ?? SITE_DESC);
$page_image    = $page_image    ?? SITE_URL . '/img/og-default.png';
$page_type     = $page_type     ?? 'website';
$canonical_url = $canonical_url ?? current_url();
$domain_meta   = $domain_meta   ?? [];
$nav_cats      = get_nav_categories();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES) ?>">
    <?php if (!empty($seo_keywords)): ?><meta name="keywords" content="<?= htmlspecialchars($seo_keywords, ENT_QUOTES) ?>"><?php endif; ?>
    <link rel="canonical" href="<?= htmlspecialchars($canonical_url, ENT_QUOTES) ?>">

    <meta property="og:type"        content="<?= htmlspecialchars($page_type, ENT_QUOTES) ?>">
    <meta property="og:title"       content="<?= htmlspecialchars($page_title, ENT_QUOTES) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES) ?>">
    <meta property="og:image"       content="<?= htmlspecialchars($page_image, ENT_QUOTES) ?>">
    <meta property="og:url"         content="<?= htmlspecialchars($canonical_url, ENT_QUOTES) ?>">
    <meta property="og:site_name"   content="<?= htmlspecialchars(SITE_NAME, ENT_QUOTES) ?>">
    <meta property="og:locale"      content="pl_PL">
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= htmlspecialchars($page_title, ENT_QUOTES) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES) ?>">
    <meta name="twitter:image"       content="<?= htmlspecialchars($page_image, ENT_QUOTES) ?>">

    <link rel="preconnect" href="https://cms.hubertmedia.pl">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Lora:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?= SITE_URL ?>/css/style.css?v=<?= filemtime(__DIR__ . '/../css/style.css') ?>">
    
    <?php 
    $site_settings = fetch_settings(); 
    $site_colors = $site_settings['colors'] ?? [];
    if (!empty($site_colors)): ?>
    <style>
        :root {
            <?php if(!empty($site_colors['primary'])): ?>--color-primary: <?= $site_colors['primary'] ?>; --color-primary-rgb: <?= hexToRgb($site_colors['primary']) ?>;<?php endif; ?>
            <?php if(!empty($site_colors['background'])): ?>--color-bg: <?= $site_colors['background'] ?>;<?php endif; ?>
            <?php if(!empty($site_colors['text'])): ?>--color-text: <?= $site_colors['text'] ?>;<?php endif; ?>
        }
        <?php if(!empty($site_colors['header'])): ?>
        .header__main { background-color: <?= $site_colors['header'] ?>; }
        <?php endif; ?>
        <?php if(!empty($site_colors['footer'])): ?>
        .site-footer { background-color: <?= $site_colors['footer'] ?>; }
        <?php endif; ?>
    </style>
    <?php endif; ?>

    <link rel="icon" href="<?= !empty($site_settings['favicon']) ? (strpos($site_settings['favicon'], 'http') === 0 ? $site_settings['favicon'] : 'https://cms.hubertmedia.pl' . $site_settings['favicon']) : SITE_URL . '/img/favicon.ico' ?>" type="image/x-icon">

    <?= $extra_head ?? '' ?>
    <?= $domain_meta['header_scripts'] ?? '' ?>
</head>
<body>
    <div class="reading-progress" id="readingProgress" aria-hidden="true"></div>

    <header class="site-header" id="siteHeader">
        <!-- Top bar -->
        <div class="header__topbar">
            <div class="container header__topbar-inner">
                <div class="header__topbar-links">
                    <a href="<?= SITE_URL ?>/wspolpraca/">Reklama</a>
                    <a href="<?= SITE_URL ?>/polityka-prywatnosci/">Prywatność</a>
                    <a href="mailto:<?= CONTACT_EMAIL ?>">Kontakt</a>
                </div>
            </div>
        </div>
        <!-- Main nav -->
        <div class="header__main">
            <div class="container header__inner">
                <a class="header__logo" href="<?= SITE_URL ?>/" aria-label="<?= SITE_NAME ?> – strona główna">
                    <?php if (!empty($site_settings['logo'])): ?>
                        <img src="<?= strpos($site_settings['logo'], 'http') === 0 ? $site_settings['logo'] : 'https://cms.hubertmedia.pl' . $site_settings['logo'] ?>" 
                             alt="<?= SITE_NAME ?>" style="max-height: 45px; width: auto; display: block;">
                    <?php else: ?>
                        <span class="logo__text">
                            <?php 
                            $domain_parts = explode('.', SITE_NAME);
                            echo htmlspecialchars($domain_parts[0] ?? SITE_NAME);
                            if (isset($domain_parts[1])) echo '<span class="logo__tld">.' . htmlspecialchars($domain_parts[1]) . '</span>';
                            ?>
                        </span>
                    <?php endif; ?>
                </a>

                <nav class="header__nav" id="mainNav" aria-label="Nawigacja główna">
                    <ul class="nav__list">
                        <li><a href="<?= SITE_URL ?>/" class="nav__link">Strona główna</a></li>
                        <li class="nav__item--dropdown">
                            <button class="nav__link nav__dropdown-toggle" aria-expanded="false" aria-controls="catsDropdown">
                                <i class="fas fa-layer-group" aria-hidden="true"></i>
                                Kategorie
                                <i class="fas fa-chevron-down nav__chevron" aria-hidden="true"></i>
                            </button>
                            <ul class="nav__dropdown" id="catsDropdown" role="menu">
                                <?php foreach ($nav_cats as $cat): ?>
                                <li role="none">
                                    <a href="<?= SITE_URL ?>/kategoria/<?= htmlspecialchars($cat['slug']) ?>/"
                                       class="nav__dropdown-item" role="menuitem"
                                       style="--cat-color:<?= htmlspecialchars($cat['color']) ?>">
                                        <span class="nav__dropdown-icon" style="background:<?= htmlspecialchars($cat['color']) ?>18;color:<?= htmlspecialchars($cat['color']) ?>" aria-hidden="true">
                                            <i class="<?= htmlspecialchars($cat['icon']) ?>"></i>
                                        </span>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li><a href="<?= SITE_URL ?>/wspolpraca/" class="nav__link nav__link--cta">
                            <i class="fas fa-handshake" aria-hidden="true"></i> Reklama
                        </a></li>
                    </ul>
                </nav>

                <button class="hamburger" id="hamburger" aria-label="Otwórz menu" aria-expanded="false">
                    <span class="hamburger__bar"></span>
                    <span class="hamburger__bar"></span>
                    <span class="hamburger__bar"></span>
                </button>
            </div>
        </div>
    </header>
    <div class="nav-overlay" id="navOverlay" aria-hidden="true"></div>

    <main id="main-content">
