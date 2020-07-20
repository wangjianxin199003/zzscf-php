<?php

use com\bj58\zhuanzhuan\zzscf\util\StdClassUtil;

require '..\..\vendor\autoload.php';


$student = new stdClass();
$student->name = "wang";
$student->__type = 'com.bj58.Student';

$teacher = new stdClass();
$teacher->age = 13;
//$teacher->__type = 'com.bj58.Teacher';

$student->teacher = $teacher;
//var_dump($student);
var_dump(StdClassUtil::stdClassToArray($student));
