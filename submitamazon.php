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

        if($i > 300) {
            break;
        }

        $zip_url = $row[26];

        $zip_localfile = 'uploads/' . time() . '.zip';

        if(!strlen($zip_url)) {
            continue;
        }

        if (filter_var($zip_url, FILTER_VALIDATE_URL) === FALSE) {
            continue;
        }

        if (!copy($zip_url, $zip_localfile)) {
            echo "failed to copy $zip_url";
        }

        $zip = new ZipArchive();
        if ($zip->open($zip_localfile)) {
            for ($j = 0; $j < $zip->numFiles; $j++) {
                if ($zip->extractTo('uploads', array($zip->getNameIndex($j)))) {
                    $json = file_get_contents('uploads/' . $zip->getNameIndex($j));
                    $arr_personalization = json_decode($json, true);

                    if($arr_personalization['customizationInfo'] == null)
                        continue;

                    $arr_areas = recursiveFind($arr_personalization['customizationInfo'], "areas");
                    $row[26] = $arr_areas[0]['text'];
                    if(strlen($arr_areas[1]['text'])) {
                        $row[26] .= "-" . $arr_areas[1]['text'];
                    }

                    unlink('uploads/' . $zip->getNameIndex($j));
                } else {
                    echo 'Failed in extracting zip<br>';
                }
            }
            $zip->close();
            unlink($zip_localfile);
        } else {
            echo 'Failed in opening zip';
        }

        $i++;
    }

    array_to_csv_download($arr_csv, 'amazon_' . date('d-m-Y') . '.txt', "\t");
}