<?php
require_once 'config.php';
require_once 'oauth.php';

if (isset($_POST['daterange']) || isset($_POST['status'])) {
    /*
shop_id	Y	 	shop_id_or_name
min_created	N	 	epoch
max_created	N	 	epoch
min_last_modified	N	 	epoch
max_last_modified	N	 	epoch
limit	N	25	int
offset	N	0	int
page	N	 	int
was_paid	N	 	boolean
was_shipped	N	 	boolean
     */

    $params['limit'] = 100;

    $by_date_api = ETSY_API_URL . 'shops/TiritaCase/receipts?includes=Buyer,GuestBuyer,Transactions/Listing,Country';
    $by_status_api = ETSY_API_URL . 'shops/TiritaCase/receipts/%s?includes=Buyer,GuestBuyer,Transactions/Listing,Country';

    if(strlen($_POST['start']) > 0 && strlen($_POST['end']) > 0) {
        $dt1 = new DateTime($_POST['start']);
        $dt2 = new DateTime($_POST['end']);

        $params['min_created'] = $dt1->format('U');
        $params['max_created'] = $dt2->format('U');

        $api_to_call = $by_date_api;
        $success = $client->CallAPI($api_to_call, 'GET', $params, array('FailOnAccessError' => true), $receipts);

    } else if (isset($_POST['status'])) {
        $api_to_call = sprintf($by_status_api, strtolower($_POST['status']));
        $success = $client->CallAPI($api_to_call, 'GET', $params, array('FailOnAccessError' => true), $receipts);
    }

    $results = [];
    $results = array_merge($results, $receipts->results);
    $fetched = count($receipts->results);

    while($fetched < $receipts->count) {
        @$params['offset'] += 100;
        $success = $client->CallAPI($api_to_call, 'GET', $params, array('FailOnAccessError' => true), $receipts);
        $results = array_merge($results, $receipts->results);
        $fetched += count($receipts->results);
    }

    $arr_csv = [
        ['Date','Order No','Buyer Name','Address Line 1','Address Line 2','City','State','Zip','Country','Buyer E-mail','Product Title','Product SKU','Product Quantity','Product price','Buyer Note','Buyer Custom Message','Paid', 'Shipped']
    ];
    foreach ($results as $receipt) {
        foreach ($receipt->Transactions as $transaction) {
            $custom_msg = array_values(array_filter($transaction->variations, function($item) {
                return $item->formatted_name == 'Personalization';
            }));

            $arr_csv[] = [
                date('Y-m-d', $receipt->creation_tsz),
                $receipt->receipt_id,
                $receipt->name,
                $receipt->first_line,
                $receipt->second_line,
                $receipt->city,
                $receipt->state,
                $receipt->zip,
                $receipt->Country->name,
                $receipt->buyer_email,
                $transaction->title,
                $transaction->product_data->sku,
                $transaction->quantity,
                $transaction->price . $transaction->currency_code,
                $receipt->message_from_buyer,
                count($custom_msg) > 0 ? $custom_msg[0]->formatted_value : '',
                $receipt->was_paid,
                $receipt->was_shipped
            ];
        }
    }

    array_to_csv_download($arr_csv, 'etsy_'.date('d-m-Y').'.csv');
}