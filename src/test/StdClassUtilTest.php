<?php

use Com\BJ58\ZHUANZHUAN\ZZscf\Util\StdClassUtil;

require '..\..\vendor\autoload.php';


$student = new stdClass();
$student->name = "wang";
$student->__type = 'Com.BJ58.Student';

$teacher = new stdClass();
$teacher->age = 13;
//$teacher->__type = 'Com.BJ58.Teacher';

$student->teacher = $teacher;
//var_dump($student);
var_dump(StdClassUtil::stdClassToArray($student));
