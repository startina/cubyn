<?php
require_once '../../vendor/autoload.php';
$key = '';
//$inbounds = new \startina\cubyn\test\Inbounds($key);
//$res = $inbounds->get();

$inbounds = new \startina\cubyn\test\Inbounds($key);
$res = $inbounds->get();
print_r($res);