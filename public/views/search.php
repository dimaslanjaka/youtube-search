<?php
require __DIR__ . '/../../vendor/autoload.php';

use Curl\YTSearch;

$keyword = isset($_REQUEST['q']) && !empty($_REQUEST['q']) ? trim(urldecode($_REQUEST['q'])) : 'remix slow';
$max = isset($_REQUEST['max']) && is_numeric($_REQUEST['max']) && $_REQUEST['max'] > 0 ? trim(urldecode($_REQUEST['max'])) : 10;
$yt = new YTSearch();
$yt->setKeyword($keyword);
$yt->setMax($max);
$yt->allow_cors()->header_json();
echo json_encode($yt->api_search(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    /*for ($i = 0; $i < count($value); $i++) {
            $videoId = $value['items'][$i]['id']['videoId'];
            $title = $value['items'][$i]['snippet']['title'];
            $description = $value['items'][$i]['snippet']['description'];
            }*/
