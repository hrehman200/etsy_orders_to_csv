<?php
require_once 'config.php';
set_time_limit(0);
error_reporting(E_ERROR);

function recursiveFind(array $haystack, $needle) {
    $iterator = new RecursiveArrayIterator($haystack);
    $recursive = new RecursiveIteratorIterator(
        $iterator,
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($recursive as $key => $value) {
        if ($key === $needle) {
            return $value;
        }
    }
}

if (isset($_FILES['csvFile'])) {

    $arr_csv = array_map(function ($item) {
        return str_getcsv($item, "\t");
    }, file($_FILES['csvFile']['tmp_name']));

    $i = 0;
    foreach ($arr_csv as &$row) {

        if($i == 0) {
            $i++;
            continue;
        }

        $zip_url = $row[26];

        $zip_localfile = 'uploads/' . time() . '.zip';

        if(!strlen($zip_url)) {
            continue;
        }

        if (!copy($zip_url, $zip_localfile)) {
            die("failed to copy $zip_url");
        }

        $zip = new ZipArchive();
        if ($zip->open($zip_localfile)) {
            for ($j = 0; $j < $zip->numFiles; $j++) {
                if ($zip->extractTo('uploads', array($zip->getNameIndex($j)))) {
                    $json = file_get_contents('uploads/' . $zip->getNameIndex($j));
                    $arr_personalization = json_decode($json, true);

                    if($arr_personalization['customizationInfo'] == null)
                        continue;

                    $text_to_print = recursiveFind($arr_personalization['customizationInfo'], "text");
                    $row[26] = $text_to_print;

                    unlink('uploads/' . $zip->getNameIndex($j));
                }
            }
            $zip->close();
            unlink($zip_localfile);
        }


    }

    array_to_csv_download($arr_csv, 'amazon_' . date('d-m-Y') . '.csv');
}