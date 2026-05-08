<?php
require_once __DIR__ . '/config.php';

function slug_to_url(string $type, string $slug): string {
    return SITE_URL . '/' . $type . '/' . rawurlencode($slug) . '/';
}

function format_date(string $datetime): string {
    $months = [
        1=>'stycznia',2=>'lutego',3=>'marca',4=>'kwietnia',5=>'maja',6=>'czerwca',
        7=>'lipca',8=>'sierpnia',9=>'września',10=>'października',11=>'listopada',12=>'grudnia',
    ];
    $ts = strtotime($datetime);
    return (int)date('j',$ts) . ' ' . $months[(int)date('n',$ts)] . ' ' . date('Y',$ts);
}

function format_date_short(string $datetime): string {
    $months = [
        1=>'sty',2=>'lut',3=>'mar',4=>'kwi',5=>'maj',6=>'cze',
        7=>'lip',8=>'sie',9=>'wrz',10=>'paź',11=>'lis',12=>'gru',
    ];
    $ts = strtotime($datetime);
    return (int)date('j',$ts) . ' ' . $months[(int)date('n',$ts)] . ' ' . date('Y',$ts);
}

function format_date_iso(string $datetime): string {
    return date('Y-m-d', strtotime($datetime));
}

function truncate(string $text, int $length = 160): string {
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length - 1) . '…';
}

function reading_time(string $html_content): int {
    $text  = strip_tags($html_content);
    $words = str_word_count($text, 0, 'ąćęłńóśźżĄĆĘŁŃÓŚŹŻ');
    return max(1, (int) ceil($words / 200));
}

function _cat_by_name(string $name): ?array {
    static $cache = null;
    if ($cache === null) $cache = fetch_categories();
    $lower = mb_strtolower($name);
    foreach ($cache as $c) {
        if (mb_strtolower($c['name']) === $lower) return $c;
    }
    return null;
}

function category_color(string $category_name): string {
    $c = _cat_by_name($category_name);
    return $c['color'] ?? '#64748b';
}

function category_icon(string $category_name): string {
    $c   = _cat_by_name($category_name);
    $raw = $c['icon'] ?? 'fas fa-tag';
    // CMS zwraca "fas fa-newspaper" — wyciągnij sam klasę ikony (fa-*)
    if (preg_match('/(fa-[\w-]+)\s*$/', $raw, $m)) return $m[1];
    return 'fa-tag';
}

function category_slug(string $category_name): string {
    $c = _cat_by_name($category_name);
    if ($c && !empty($c['slug'])) return $c['slug'];
    $slug = mb_strtolower($category_name);
    $slug = str_replace(['ą','ć','ę','ł','ń','ó','ś','ź','ż'],['a','c','e','l','n','o','s','z','z'],$slug);
    $slug = preg_replace('/[^a-z0-9]+/','-',$slug);
    return trim($slug,'-');
}

function placeholder_svg(string $title, string $color = '#10b981'): string {
    $initials = '';
    foreach (array_slice(explode(' ', $title), 0, 2) as $w) {
        $initials .= mb_strtoupper(mb_substr($w, 0, 1));
    }
    $hex = str_replace('#','%23',$color);
    $init = htmlspecialchars($initials, ENT_QUOTES);
    return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1200' height='630'%3E%3CdefinS%3E%3ClinearGradient id='g' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' stop-color='{$hex}'/%3E%3Cstop offset='100%25' stop-color='%230f172a'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect fill='url(%23g)' width='1200' height='630'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='Georgia,serif' font-size='120' fill='rgba(255,255,255,0.15)'%3E{$init}%3C/text%3E%3C/svg%3E";
}

function post_image(array $post, string $class = '', bool $eager = false): string {
    $color   = category_color($post['category_name'] ?? '');
    $loading = $eager ? 'eager' : 'lazy';
    $fp      = $eager ? ' fetchpriority="high"' : '';
    $alt     = htmlspecialchars($post['title'], ENT_QUOTES);
    $cls     = $class ? ' class="' . htmlspecialchars($class, ENT_QUOTES) . '"' : '';

    if (!empty($post['featured_image'])) {
        $src = htmlspecialchars($post['featured_image'], ENT_QUOTES);
        return "<img{$cls} src=\"{$src}\" alt=\"{$alt}\" width=\"1200\" height=\"630\" loading=\"{$loading}\"{$fp}>";
    }
    $svg = placeholder_svg($post['title'], $color);
    return "<img{$cls} src=\"{$svg}\" alt=\"{$alt}\" width=\"1200\" height=\"630\" loading=\"{$loading}\">";
}

function schema_article(array $post, string $url): string {
    $image = fix_image_url($post['featured_image'] ?? '') ?: SITE_URL . '/img/og-default.png';
    // Author — use real author from post if available
    if (!empty($post['author_name'])) {
        $author = [
            '@type' => 'Person',
            'name'  => $post['author_name'],
            'url'   => SITE_URL . '/autor/' . rawurlencode($post['author_slug'] ?? '') . '/',
        ];
    } else {
        $author = ['@type' => 'Organization', 'name' => 'Redakcja blogcasha.pl', 'url' => SITE_URL . '/'];
    }
    $data = [
        '@context'         => 'https://schema.org',
        '@type'            => 'BlogPosting',
        'headline'         => $post['seo_title'] ?: $post['title'],
        'description'      => $post['seo_description'] ?: truncate($post['excerpt'] ?? '', 160),
        'image'            => $image,
        'datePublished'    => date('c', strtotime($post['published_at'])),
        'dateModified'     => date('c', strtotime($post['updated_at'] ?? $post['published_at'])),
        'url'              => $url,
        'inLanguage'       => 'pl',
        'author'           => $author,
        'publisher'        => [
            '@type' => 'Organization',
            'name'  => 'blogcasha.pl',
            'logo'  => ['@type' => 'ImageObject', 'url' => SITE_URL . '/img/logo.png'],
        ],
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $url],
    ];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}

function render_ad_slot(string $slot_id, string $css_class = '', ?array $ad_slots = null): string {
    static $slots_cache = null;
    if ($ad_slots !== null) $slots_cache = $ad_slots;
    if ($slots_cache === null) $slots_cache = fetch_ad_slots();

    $slot = null;
    foreach ($slots_cache as $s) {
        if (($s['slot_id'] ?? '') === $slot_id) { $slot = $s; break; }
    }

    $cls = 'ad-slot' . ($css_class ? ' ' . $css_class : '') . ' ad-slot--' . preg_replace('/[^a-z0-9-]/', '-', $slot_id);
    $label = '<span class="ad-slot__label">Reklama</span>';

    if (!$slot || empty($slot['content'])) {
        return '<div class="' . $cls . '" data-ad-slot="' . htmlspecialchars($slot_id) . '" aria-hidden="true">' . $label . '</div>';
    }

    $content = $slot['content'];
    $type    = $slot['type'] ?? 'script'; // 'script' or 'image'

    if ($type === 'image' && filter_var($content, FILTER_VALIDATE_URL)) {
        $link  = !empty($slot['link']) ? htmlspecialchars($slot['link']) : '#';
        $inner = '<a href="' . $link . '" target="_blank" rel="noopener sponsored">'
               . '<img src="' . htmlspecialchars($content) . '" alt="Reklama" loading="lazy" style="max-width:100%;height:auto;display:block;">'
               . '</a>';
        return '<div class="' . $cls . ' ad-slot--image" data-ad-slot="' . htmlspecialchars($slot_id) . '">' . $inner . '</div>';
    }

    // script — output raw (AdSense / custom)
    return '<div class="' . $cls . '" data-ad-slot="' . htmlspecialchars($slot_id) . '" aria-hidden="true">'
         . $label . $content
         . '</div>';
}

function schema_breadcrumb(array $items): string {
    $list = [];
    foreach ($items as $i => $item) {
        $list[] = ['@type'=>'ListItem','position'=>$i+1,'name'=>$item['name'],'item'=>$item['url']];
    }
    $data = ['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>$list];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}

function schema_website(): string {
    $data = [
        '@context'   => 'https://schema.org',
        '@graph'     => [
            [
                '@type'           => 'WebSite',
                '@id'             => SITE_URL . '/#website',
                'name'            => SITE_NAME,
                'url'             => SITE_URL,
                'description'     => SITE_DESC,
                'inLanguage'      => 'pl',
                'potentialAction' => [
                    '@type'       => 'SearchAction',
                    'target'      => ['@type'=>'EntryPoint','urlTemplate'=> SITE_URL . '/?q={search_term_string}'],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            [
                '@type'   => 'Organization',
                '@id'     => SITE_URL . '/#organization',
                'name'    => SITE_NAME,
                'url'     => SITE_URL,
                'logo'    => ['@type'=>'ImageObject','url'=> SITE_URL . '/img/logo.png'],
                'sameAs'  => [],
                'email'   => CONTACT_EMAIL,
            ],
        ],
    ];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}

function current_url(): string {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    return SITE_URL . $path;
}
