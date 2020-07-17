<?php

use com\bj58\zhuanzhuan\zzscf\api\application\Application;
use com\bj58\zhuanzhuan\zzscf\api\config\ApplicationConfig;
use com\bj58\zhuanzhuan\zzscf\api\config\Reference;
use com\bj58\zhuanzhuan\zzscf\util\ReferenceConfigUtil;
use test\SleepServiceContract;
require '..\..\vendor\autoload.php';

$applicationConfig = new ApplicationConfig();
//设置本应用的集群名/appName
$applicationConfig->setAppName("demophp");
$document = new \DOMDocument();
$document->load('scf.config.xml');
//设置引用的本地配置
$applicationConfig->setLocalServiceRefConfigs(ReferenceConfigUtil::parserMultiFromXmlDOMDocument($document));
//设置callerKey, callerKey和本地配置二者不能都为空
$applicationConfig->setCallerKey("abjglasjfljfa");
Application::buildSingletonInstance($applicationConfig);
$reference = new Reference();
$reference->setInterfaceName('com.bj58.zhuanzhuan.wjx.scf.server.test1.contract.SleepService');
$reference->setServiceName("servertest1");
$reference->setContract(new SleepServiceContract());
$proxy = $reference->ref();
echo $proxy->sleep(500);

