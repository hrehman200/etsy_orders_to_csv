<?php
require_once 'config.php';
require_once 'oauth.php';

switch($_GET['call']) {
    case 'orders':
        $success = $client->CallAPI(ETSY_API_URL.'shops/TiritaCase/receipts', 'GET', array(), array('FailOnAccessError' => true), $receipts);
        echo json_encode([
            'success' => 1,
            'data' => $receipts
        ]);
        break;
}