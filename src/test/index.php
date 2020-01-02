<?php
require_once '../../vendor/autoload.php';
$key = '75b2127652042f320a69e7f4';
$client = new \startina\cubyn\Client($key);
//$res = $client->inbounds()->get();
$res = $client->inventory()->get();
$result = [];
foreach ($res as $item) {
    $tem = [];
    $tem['sku_id'] = $item->id;
    $tem['sku_name'] = $item->sku;
    $result[] = $tem;
}

print_r(json_encode($result));exit;


// 3925656
$res = $client->orders()->getSearch(5000, 1000);
//print_r($res);
print_r($client->orders()->getTotalCount());