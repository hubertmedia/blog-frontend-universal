<?php
require_once __DIR__ . '/includes/config.php';
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: https://cms.hubertmedia.pl');
$provided = $_SERVER['HTTP_X_API_KEY'] ?? $_GET['api_key'] ?? '';
if (!$provided || !hash_equals(API_KEY, $provided)) {
    http_response_code(403);
    echo json_encode(['status' => 'error']);
    exit;
}
echo json_encode(['status' => 'ok', 'domain' => SITE_NAME, 'ts' => time()]);
