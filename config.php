<?php

define('ETSY_API_KEY', '');
define('ETSY_SECRET', '');
define('ETSY_API_URL', 'https://openapi.etsy.com/v2/');

function array_to_csv_download($array, $filename = "export.csv", $delimiter=",") {
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'";');

    // open the "output" stream
    // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
    $f = fopen('php://output', 'w');

    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }

    fclose($f);
}