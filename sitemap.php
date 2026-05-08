<?php
/**
 * multumofert.pl – Dynamiczny XML Sitemap
 * Pobiera posty z CMS i generuje sitemap.
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/api.php';
require_once __DIR__ . '/includes/helpers.php';

header('Content-Type: application/xml; charset=UTF-8');
header('X-Robots-Tag: noindex');

$posts = fetch_sitemap();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
echo '        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"' . "\n";
echo '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

// Strona główna
echo "<url>\n";
echo "  <loc>" . SITE_URL . "/</loc>\n";
echo "  <changefreq>daily</changefreq>\n";
echo "  <priority>1.0</priority>\n";
echo "</url>\n";

// Współpraca
echo "<url>\n";
echo "  <loc>" . SITE_URL . "/wspolpraca/</loc>\n";
echo "  <changefreq>monthly</changefreq>\n";
echo "  <priority>0.6</priority>\n";
echo "</url>\n";

// Kategorie
foreach (fetch_categories() as $cat) {
    if (empty($cat['slug'])) continue;
    echo "<url>\n";
    echo "  <loc>" . SITE_URL . "/kategoria/" . htmlspecialchars($cat['slug']) . "/</loc>\n";
    echo "  <changefreq>daily</changefreq>\n";
    echo "  <priority>0.8</priority>\n";
    echo "</url>\n";
}

// Artykuły
foreach ($posts as $post) {
    $url      = SITE_URL . '/artykul/' . rawurlencode($post['slug']) . '/';
    $lastmod  = date('Y-m-d', strtotime($post['published_at']));
    $image    = $post['featured_image'] ?? '';
    $title    = htmlspecialchars($post['title'], ENT_XML1 | ENT_QUOTES);

    echo "<url>\n";
    echo "  <loc>" . htmlspecialchars($url, ENT_XML1) . "</loc>\n";
    echo "  <lastmod>{$lastmod}</lastmod>\n";
    echo "  <changefreq>monthly</changefreq>\n";
    echo "  <priority>0.7</priority>\n";
    if ($image) {
        echo "  <image:image>\n";
        echo "    <image:loc>" . htmlspecialchars($image, ENT_XML1) . "</image:loc>\n";
        echo "    <image:title>{$title}</image:title>\n";
        echo "  </image:image>\n";
    }
    echo "</url>\n";
}

echo '</urlset>';
