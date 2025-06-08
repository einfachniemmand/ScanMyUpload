<?php
$uri = $_SERVER['REQUEST_URI'];
$parts = explode('/', trim($uri, '/'));
$code = $parts[0] ?? null;
$action = $parts[1] ?? null;

$dataFile = __DIR__ . '/../data.json';
$data = [];
if (file_exists($dataFile)) {
    $data = json_decode(file_get_contents($dataFile), true);
}
if ($action === 'dl') {
    $format = isset($data[$code]['format']) ? $data[$code]['format'] : 'bin';
    header("Location: /uploads/" . $code . "." . $format);
    exit;
}
if (!$code || !preg_match('/^\d+$/', $code)) {
    http_response_code(404);
    header('Content-Type: text/html');
    readfile(__DIR__ . '/../404/index.html');
    exit;
}
$dataFile = __DIR__ . '/../data.json';
if (file_exists($dataFile)) {
    $data = json_decode(file_get_contents($dataFile), true);
    if (isset($data[$code])) {
        $serveHtml = file_get_contents(__DIR__ . '/serve.html');
        $serveHtml = str_replace( 
            ['{{FORMAT}}', '{{TYPE}}', '{{EXPIRATION}}','{{NAME}}'],
            [
                htmlspecialchars($data[$code]['format'] ?? ''),
                htmlspecialchars($data[$code]['type'] ?? ''),
                htmlspecialchars($data[$code]['expires'] ?? ''),
                htmlspecialchars($data[$code]['old_name'] ?? '')
            ],
            $serveHtml
        );
        header('Content-Type: text/html');
        echo $serveHtml;
        exit;
    }
}
http_response_code(404);
header('Content-Type: text/html');
readfile(__DIR__ . '/../404/index.html');
exit;