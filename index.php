<?php

require 'vendor/autoload.php';

use Sabre\DAV\Client;

// Check for the correct API key
$apiKey = getenv('API_KEY') ?: throw new InvalidArgumentException('Missing API_KEY environment variable');
if ($_SERVER['HTTP_X_API_KEY'] ?? null !== $apiKey) {
    http_response_code(403);
    echo "Invalid API key.";
    exit;
}

// Get the file data from the POST request
$fileData = file_get_contents($_FILES['file']['tmp_name']);
$destinationPath = $_POST['path'];

// Set up the WebDAV client
$client = new Client([
    'baseUri' => getenv('WEBDAV_SERVER') ?: throw new InvalidArgumentException('Missing WEBDAV_SERVER environment variable'),
    'userName' => getenv('WEBDAV_USERNAME') ?: throw new InvalidArgumentException('Missing WEBDAV_USERNAME environment variable'),
    'password' => getenv('WEBDAV_PASSWORD') ?: throw new InvalidArgumentException('Missing WEBDAV_PASSWORD environment variable'),
]);

// Upload the file
$response = $client->request('PUT', $destinationPath, $fileData);

// Check the response
if ($response['statusCode'] == 201) {
    echo "File uploaded successfully.";
} else {
    echo "Failed to upload file. Server response: " . $response['body'];
}
