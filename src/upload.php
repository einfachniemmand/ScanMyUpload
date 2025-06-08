<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST allowed']);
    exit;
}

if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded',"error_description"=>"Please upload a file before trying to upload anything."]);
    exit;
}

$supportedFormatsPath = __DIR__ . '/supported-formats.json';
if (!file_exists($supportedFormatsPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Supported formats file missing']);
    exit;
}
$supportedFormats = json_decode(file_get_contents($supportedFormatsPath), true);
$allowed = [];
foreach ($supportedFormats as $formats) {
    $allowed = array_merge($allowed, $formats);
}
$allowed = array_map('strtolower', $allowed);

$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$extLower = strtolower($ext);

$fileType = null;
foreach ($supportedFormats as $type => $formats) {
    if (in_array($extLower, array_map('strtolower', $formats))) {
        $fileType = $type;
        break;
    }
}

if (!in_array($extLower, $allowed)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid file type',"error_description"=>"We don't support this file type yet. This issue might also be related to safety and security concerns."]);
    exit;
}

$data = [];
if (file_exists('data.json')) {
    $data = json_decode(file_get_contents('data.json'), true);
}
do {
    $code = rand(100000, 999999);
} while (array_key_exists($code, $data));

$expires = date('Y-m-d', strtotime('+14 days'));
$filename = $code . '.' . $ext;

if (!move_uploaded_file($_FILES['file']['tmp_name'], __DIR__ . '/uploads/' . $filename)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save file',"error_description"=>"You are experiencing an unknown error. Try again in a few minutes before contacting support"]);
    exit;
}

$data[$code] = [
    'code' => $code,
    'expires' => $expires,
    'format' => $ext,
    'type' => $fileType,
    'old_name' => $_FILES['file']['name']
];

file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));
echo json_encode(['success' => true, 'code' => $code, 'type' => $fileType]);
