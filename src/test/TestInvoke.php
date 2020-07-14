<?php


use com\bj58\zhuanzhuan\zzscf\application\Application;
use com\bj58\zhuanzhuan\zzscf\config\ApplicationConfig;
use com\bj58\zhuanzhuan\zzscf\config\Reference;
use com\bj58\zhuanzhuan\zzscf\config\ServerNode;
use com\bj58\zhuanzhuan\zzscf\config\ReferenceConfig;
use com\bj58\zhuanzhuan\zzscf\util\ReferenceConfigUtil;
use test\SleepServiceContract;
use test\Student;
use test\StudentServiceContract;

require '..\..\vendor\autoload.php';
$applicationConfig = new ApplicationConfig();
$applicationConfig->setCallerKey('Tm8dDO1dPUY4QqRU8r/kAw==');
$document = new DOMDocument();
$document->load('scf.config.xml');
$applicationConfig->setLocalServiceRefConfigs(ReferenceConfigUtil::parserMultiFromXmlDOMDocument($document));
Application::buildSingletonInstance($applicationConfig);
$refConfig = new Reference();
$refConfig->setServiceName("servertest1");
$refConfig->setContract(new SleepServiceContract());
//$refConfig->setLookup('SleepServiceContract');
$proxy = $refConfig->ref();
print($proxy->sleep(10)."\n");
print($proxy->sleep(500)."\n");

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


