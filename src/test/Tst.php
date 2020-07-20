<?php

use com\bj58\zhuanzhuan\zzscf\util\SystemEnvUtils;
require '..\..\vendor\autoload.php';

$arr1 = array();
$arr2 = array('abc'=>143, 'def'=>2343);
$arr1[] = $arr2;
foreach ($arr1 as &$item){
    unset($item['abc']);
}
var_dump($arr1);