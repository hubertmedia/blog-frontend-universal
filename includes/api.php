<?php
require_once __DIR__ . '/config.php';

// Dynamiczny folder cache na podstawie nazwy domeny, aby uniknąć mieszania danych
define('CACHE_DIR', '/tmp/cms_cache_' . preg_replace('/[^a-z0-9]/', '_', SITE_NAME) . '/');
define('CACHE_TTL', 60);

function _cms_cache_get(string $key): ?array {
    $file = CACHE_DIR . $key . '.json';
    if (file_exists($file) && (time() - filemtime($file)) < CACHE_TTL) {
        $d = json_decode(file_get_contents($file), true);
        if ($d) return $d;
    }
    return null;
}

function _cms_cache_put(string $key, string $raw): void {
    if (!is_dir(CACHE_DIR)) mkdir(CACHE_DIR, 0755, true);
    file_put_contents(CACHE_DIR . $key . '.json', $raw, LOCK_EX);
}

function _cms_request(string $url, string $method = 'GET', string $body = ''): ?string {
    $ch = curl_init($url);
    $opts = [
        CURLOPT_HTTPHEADER     => ['X-API-KEY: ' . API_KEY],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT      => 'blogcasha.pl/1.0',
    ];
    if ($method === 'POST') {
        $opts[CURLOPT_POST]       = true;
        $opts[CURLOPT_POSTFIELDS] = $body;
        $opts[CURLOPT_HTTPHEADER][] = 'Content-Type: application/x-www-form-urlencoded';
    }
    curl_setopt_array($ch, $opts);
    $resp = curl_exec($ch);
    $err  = curl_error($ch);
    curl_close($ch);
    return ($resp && !$err) ? $resp : null;
}

function fetch_from_cms(int $limit = 10, ?int $category_id = null): ?array {
    $key = md5("posts_{$limit}_{$category_id}");
    if ($cached = _cms_cache_get($key)) return $cached;

    $url = API_URL . '?limit=' . $limit . '&api_key=' . API_KEY;
    if ($category_id !== null) $url .= '&category_id=' . $category_id;

    $resp = _cms_request($url);
    if ($resp) {
        $data = json_decode($resp, true);
        if (json_last_error() === JSON_ERROR_NONE && !empty($data)) {
            _cms_cache_put($key, $resp);
            return $data;
        }
    }
    // stale cache fallback
    $file = CACHE_DIR . $key . '.json';
    return file_exists($file) ? json_decode(file_get_contents($file), true) : null;
}

function fetch_featured_posts(int $limit = 4): array {
    $key = md5("featured_{$limit}");
    if ($cached = _cms_cache_get($key)) return $cached['posts'] ?? [];

    $url  = API_URL . '?featured=1&limit=' . $limit . '&api_key=' . API_KEY;
    $resp = _cms_request($url);
    if ($resp) {
        $data = json_decode($resp, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            _cms_cache_put($key, $resp);
            return $data['posts'] ?? [];
        }
    }
    return [];
}

function fetch_posts_paginated(int $page = 1, int $limit = 12, ?int $category_id = null): array {
    $key = md5("paginated_{$page}_{$limit}_{$category_id}");
    if ($cached = _cms_cache_get($key)) return $cached;

    $url = API_URL . '?page=' . $page . '&limit=' . $limit . '&api_key=' . API_KEY;
    if ($category_id !== null) $url .= '&category_id=' . $category_id;

    $resp = _cms_request($url);
    if ($resp) {
        $data = json_decode($resp, true);
        if (json_last_error() === JSON_ERROR_NONE && !empty($data)) {
            _cms_cache_put($key, $resp);
            return $data;
        }
    }
    $file = CACHE_DIR . $key . '.json';
    return file_exists($file) ? (json_decode(file_get_contents($file), true) ?? []) : [];
}

function fetch_post_by_slug(string $slug): ?array {
    $key = md5("post_slug_{$slug}");
    if ($cached = _cms_cache_get($key)) return $cached;

    $url  = API_URL . '?post_slug=' . urlencode($slug) . '&api_key=' . API_KEY;
    $resp = _cms_request($url);
    if ($resp) {
        $data = json_decode($resp, true);
        if (json_last_error() === JSON_ERROR_NONE && !empty($data['post'])) {
            _cms_cache_put($key, $resp);
            return $data;
        }
    }
    $file = CACHE_DIR . $key . '.json';
    return file_exists($file) ? json_decode(file_get_contents($file), true) : null;
}

function fetch_categories(): array {
    $key  = 'categories';
    $file = CACHE_DIR . $key . '.json';
    if (file_exists($file) && (time() - filemtime($file)) < 3600) {
        $d = json_decode(file_get_contents($file), true);
        if ($d) return $d['categories'] ?? [];
    }
    if (!is_dir(CACHE_DIR)) mkdir(CACHE_DIR, 0755, true);

    $url  = API_URL . '?categories=1&api_key=' . API_KEY;
    $resp = _cms_request($url);
    if ($resp) {
        $data = json_decode($resp, true);
        if (json_last_error() === JSON_ERROR_NONE && !empty($data['categories'])) {
            file_put_contents($file, $resp, LOCK_EX);
            return $data['categories'];
        }
    }
    return file_exists($file) ? (json_decode(file_get_contents($file), true)['categories'] ?? []) : [];
}

function fetch_comments(int $post_id): array {
    $key = md5("comments_{$post_id}");
    // komentarze cache 30s — moderacja powinna być szybko widoczna
    $file = CACHE_DIR . $key . '.json';
    if (file_exists($file) && (time() - filemtime($file)) < 30) {
        $d = json_decode(file_get_contents($file), true);
        if ($d) return $d['comments'] ?? [];
    }
    if (!is_dir(CACHE_DIR)) mkdir(CACHE_DIR, 0755, true);

    $url  = API_URL . '?comments=' . $post_id . '&api_key=' . API_KEY;
    $resp = _cms_request($url);
    if ($resp) {
        $data = json_decode($resp, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            file_put_contents($file, $resp, LOCK_EX);
            return $data['comments'] ?? [];
        }
    }
    return [];
}

function add_comment(int $post_id, string $author, string $content): bool {
    $url  = API_URL . '?action=comment&api_key=' . urlencode(API_KEY);
    $body = http_build_query([
        'post_id' => $post_id,
        'author'  => mb_substr(strip_tags($author), 0, 100),
        'content' => mb_substr(strip_tags($content), 0, 2000),
    ]);
    $resp = _cms_request($url, 'POST', $body);
    $data = $resp ? json_decode($resp, true) : [];
    return !empty($data['success']);
}

function fetch_search(string $query, int $limit = 20): array {
    $key = md5("search_{$query}_{$limit}");
    if ($cached = _cms_cache_get($key)) return $cached['posts'] ?? [];

    $url  = API_URL . '?search=' . urlencode($query) . '&limit=' . $limit . '&api_key=' . API_KEY;
    $resp = _cms_request($url);
    if ($resp) {
        $data = json_decode($resp, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            _cms_cache_put($key, $resp);
            return $data['posts'] ?? [];
        }
    }
    return [];
}

function fetch_sitemap(): array {
    $file = CACHE_DIR . 'sitemap.json';
    if (file_exists($file) && (time() - filemtime($file)) < 3600) {
        $d = json_decode(file_get_contents($file), true);
        if ($d) return $d['posts'] ?? [];
    }
    if (!is_dir(CACHE_DIR)) mkdir(CACHE_DIR, 0755, true);

    $url  = API_URL . '?sitemap=1&api_key=' . API_KEY;
    $resp = _cms_request($url);
    if ($resp) {
        $data = json_decode($resp, true);
        if (json_last_error() === JSON_ERROR_NONE && !empty($data['posts'])) {
            file_put_contents($file, $resp, LOCK_EX);
            return $data['posts'];
        }
    }
    return file_exists($file) ? (json_decode(file_get_contents($file), true)['posts'] ?? []) : [];
}

function get_domain_meta(): array {
    $data = fetch_from_cms(1);
    return $data['domain'] ?? [];
}

function fix_image_url(string $url): string {
    if (!$url) return '';
    return str_replace('/admin/admin/', '/admin/', $url);
}

function get_nav_categories(): array {
    return fetch_categories();
}

function fetch_authors(): array {
    $key  = 'authors';
    $file = CACHE_DIR . $key . '.json';
    if (file_exists($file) && (time() - filemtime($file)) < 3600) {
        $d = json_decode(file_get_contents($file), true);
        if ($d) return $d['authors'] ?? [];
    }
    if (!is_dir(CACHE_DIR)) mkdir(CACHE_DIR, 0755, true);
    $url  = API_URL . '?authors=1&api_key=' . API_KEY;
    $resp = _cms_request($url);
    if ($resp) {
        $data = json_decode($resp, true);
        if (json_last_error() === JSON_ERROR_NONE && !empty($data['authors'])) {
            file_put_contents($file, $resp, LOCK_EX);
            return $data['authors'];
        }
    }
    return file_exists($file) ? (json_decode(file_get_contents($file), true)['authors'] ?? []) : [];
}

function fetch_ad_slots(): array {
    $key  = 'ad_slots';
    $file = CACHE_DIR . $key . '.json';
    if (file_exists($file) && (time() - filemtime($file)) < 300) {
        $d = json_decode(file_get_contents($file), true);
        if ($d) return $d['ad_slots'] ?? [];
    }
    if (!is_dir(CACHE_DIR)) mkdir(CACHE_DIR, 0755, true);
    $url  = API_URL . '?ad_slots=1&api_key=' . API_KEY;
    $resp = _cms_request($url);
    if ($resp) {
        $data = json_decode($resp, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($data['ad_slots'])) {
            file_put_contents($file, $resp, LOCK_EX);
            return $data['ad_slots'];
        }
    }
    // fallback: check domain meta
    $domain = get_domain_meta();
    return $domain['ad_slots'] ?? [];
}

function fetch_settings(): array {
    $key = 'settings';
    $file = CACHE_DIR . $key . '.json';
    if (file_exists($file) && (time() - filemtime($file)) < 60) {
        $d = json_decode(file_get_contents($file), true);
        if ($d) return $d['settings'] ?? [];
    }
    if (!is_dir(CACHE_DIR)) mkdir(CACHE_DIR, 0755, true);

    $url  = API_URL . '?settings=1&api_key=' . API_KEY;
    $resp = _cms_request($url);
    if ($resp) {
        $data = json_decode($resp, true);
        if (json_last_error() === JSON_ERROR_NONE && !empty($data['settings'])) {
            file_put_contents($file, $resp, LOCK_EX);
            return $data['settings'];
        }
    }
    return file_exists($file) ? (json_decode(file_get_contents($file), true)['settings'] ?? []) : [];
}
