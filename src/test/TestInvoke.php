<?php


use com\bj58\zhuanzhuan\zzscf\application\Application;
use com\bj58\zhuanzhuan\zzscf\config\ApplicationConfig;
use com\bj58\zhuanzhuan\zzscf\config\Reference;
use com\bj58\zhuanzhuan\zzscf\config\ServerNode;
use com\bj58\zhuanzhuan\zzscf\config\ReferenceConfig;
use com\bj58\zhuanzhuan\zzscf\util\ServiceReferenceConfigUtil;
use test\SleepService;
use test\Student;
use test\StudentService;

require '..\..\vendor\autoload.php';
$applicationConfig = new ApplicationConfig();
//$applicationConfig->setCallerKey('Tm8dDO1dPUY4QqRU8r/kAw==');
$document = new DOMDocument();
$document->load('scf.config.xml');
$applicationConfig->setLocalServiceRefConfigs(ServiceReferenceConfigUtil::parserServiceReferenceConfig($document));
Application::buildSingletonInstance($applicationConfig);
$refConfig = new Reference();
$refConfig->setServiceName("servertest1");
$refConfig->setLookup("SleepService1");
$refConfig->setContract(new SleepService());
$proxy = $refConfig->ref();
echo $proxy->sleep(10);
echo $proxy->sleep(500);

//$refConfig2 = new Reference();
//$refConfig2->setServiceName("serverTest1");
//$refConfig2->setLookup("StudentService");
//$refConfig2->setContract(new StudentService());
//$refConfig2->setLocalConfig($serviceReferenceConfig);
//$proxy2 = $refConfig2->ref();
//$student = ($proxy2->getStudent(1, null, 14));
//echo $student->getName()."\n";
//echo $student->getId()."\n";
//echo $student->getAge()."\n";
//$newStudent = new Student();
//$newStudent->setName("wang");
//$proxy2->saveStudent($newStudent);


