<?php
error_reporting(0);
require __DIR__ . '/vendor/autoload.php';
$client = new Google_Client();
$client->setAuthConfig('credentials.json');
$client->setAccessType('offline');
$service = new Google_Service_Sheets($client);
$tokenPath = 'token.json';
if (file_exists($tokenPath)) {
    $accessToken = json_decode(file_get_contents($tokenPath), true);
    $client->setAccessToken($accessToken);
}