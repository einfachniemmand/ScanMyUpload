<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
if (!preg_match('/^[a-zA-Z0-9]+$/', $path)) {
    http_response_code(400);
    exit("Invalid URI");
}
header("Location: https://scanmyupload.alwaysdata.net/$path");
exit;
?>
