<?php
require_once '../../vendor/autoload.php';
$key = '';
//$product = new \startina\cubyn\test\Product($key);
//$res = $product->get();

$inbounds = new \startina\cubyn\test\Inbounds($key);
$res = $inbounds->get();
print_r($res);