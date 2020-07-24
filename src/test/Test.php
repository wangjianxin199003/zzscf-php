<?php

use com\bj58\zhuanzhuan\zzscf\api\Application\Application;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Config\ApplicationConfig;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Config\Reference;
use Com\BJ58\ZHUANZHUAN\ZZscf\Util\ReferenceConfigUtil;
use test\SleepServiceContract;
use test\ZhuanUserSeriveContract;

require '..\..\vendor\autoload.php';

$applicationConfig = new ApplicationConfig();
//设置本应用的集群名/appName
$applicationConfig->setAppName("demophp");
$document = new \DOMDocument();
$document->load('scf.config.xml');
//设置引用的本地配置
$applicationConfig->setLocalServiceRefConfigs(ReferenceConfigUtil::parserMultiFromXmlDOMDocument($document));
//设置callerKey, callerKey和本地配置二者不能都为空
//$applicationConfig->setCallerKey("abjglasjfljfa");
Application::buildSingletonInstance($applicationConfig);
$reference = new Reference();
$reference->setInterfaceName('Com.BJ58.ZHUANZHUAN.zzuser.contract.IZLJUserService');
$reference->setServiceName("zzuser");
//$reference->setLookup('ZLJUserService');
$reference->setContract(new ZhuanUserSeriveContract());
$proxy = $reference->ref();
$re = $proxy->getByUid(12, 'add');
var_dump($re);

