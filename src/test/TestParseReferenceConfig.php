<?php


namespace test;

use com\bj58\zhuanzhuan\zzscf\util\ServiceReferenceConfigUtil;

require '..\..\vendor\autoload.php';

$xml = new \DOMDocument();
$xml->load('scf.config.xml');
$configs = ServiceReferenceConfigUtil::parserServiceReferenceConfig($xml);
foreach ($configs as $config) {
    var_dump($config);
}
echo @mkdir('D:/tmp');