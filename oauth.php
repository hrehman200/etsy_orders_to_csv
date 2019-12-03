<?php
require(__DIR__ . '/httpclient/http.php');
require(__DIR__ . '/oauth-api/oauth_client.php');

$client = new oauth_client_class;
$client->debug = false;
$client->debug_http = true;
$client->server = 'Etsy';
$client->redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] .
    strtok($_SERVER['REQUEST_URI'], '?') . '/index.php';

$client->client_id = ETSY_API_KEY;
$application_line = __LINE__;
$client->client_secret = ETSY_SECRET;
$client->scope = 'email_r transactions_r listings_w listings_r';

if (strlen($client->client_id) == 0
    || strlen($client->client_secret) == 0
)
    die('Please go to Etsy Developers page https://www.etsy.com/developers/register , ' .
        'create an application, and in the line ' . $application_line .
        ' set the client_id to key string and client_secret with shared secret. ' .
        'The Callback URL must be ' . $client->redirect_uri);

if (($success = $client->Initialize())) {
    if (($success = $client->Process())) {
        if (strlen($client->access_token)) {

        }
    }
    $success = $client->Finalize($success);
}