<?php


use com\bj58\zhuanzhuan\zzscf\application\Application;
use com\bj58\zhuanzhuan\zzscf\config\ApplicationConfig;
use com\bj58\zhuanzhuan\zzscf\config\ReferenceConfig;
use com\bj58\zhuanzhuan\zzscf\config\ServerNode;
use com\bj58\zhuanzhuan\zzscf\config\ServiceReferenceConfig;
use test\SleepService;
use test\StudentService;

require '..\..\vendor\autoload.php';
$applicationConfig = new ApplicationConfig();
Application::buildInstance($applicationConfig);
$refConfig = new ReferenceConfig();
$refConfig->setServiceName("serverTest1");
$refConfig->setLookup("SleepService1");
$refConfig->setContract(new SleepService());
$serviceReferenceConfig = new ServiceReferenceConfig();
$serverNode = new ServerNode;
$serverNode->setHost("127.0.0.1");
$serverNode->setPort(16546);
$serviceReferenceConfig->setServerNodes(array($serverNode));
$refConfig->setLocalConfig($serviceReferenceConfig);
$proxy = $refConfig->ref();
echo $proxy->sleep(1000);

$refConfig2 = new ReferenceConfig();
$refConfig2->setServiceName("serverTest1");
$refConfig2->setLookup("StudentService");
$refConfig2->setContract(new StudentService());
$refConfig2->setLocalConfig($serviceReferenceConfig);
$proxy2 = $refConfig2->ref();
$student = ($proxy2->getStudent(1, null, 14));
echo $student->getName()."\n";
echo $student->getId()."\n";
echo $student->getAge();


