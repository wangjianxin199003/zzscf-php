<?php


use com\bj58\zhuanzhuan\zzscf\util\StdClassUtil;
use com\bj58\zhuanzhuan\zzscf\util\TraceUtils;
use test\SleepService;
use test\Student;
use test\StudentService;
use test\Teacher;

require '..\..\vendor\autoload.php';


$sleepService = new SleepService();
print($sleepService->sleep(10)."\n");
echo TraceUtils::getTraceId()."\n";
print($sleepService->sleep(500)."\n");
echo TraceUtils::getTraceId()."\n";
$studentService = new StudentService();
$returnedStudent = $studentService->getStudent(1, "wang", 12);
if ($returnedStudent instanceof stdClass){
    var_dump(StdClassUtil::stdClassToArray($returnedStudent));
}
echo TraceUtils::getTraceId()."\n";
$student = new stdClass();
$student->__type = 'com.bj58.zhuanzhuan.wjx.scf.server.test1.entity.Student';
$student->id=2;
$student->name = "wang";
$student->age = 13;
$studentService->saveStudent($student);
//$student->setId(2);
//$student->setName("zhang");
//$student->setAge(13);
//$student->setMap(array(0 => 'abc', null=>null));
//$teacher1 = new Teacher();
//$teacher1->setName("li");
//$teacher2 = new Teacher();
//$teacher2->setName("zhao");
//$student->setTeachers(array($teacher1, $teacher2));
//print $studentService->saveStudent($student);
echo TraceUtils::getTraceId()."\n";


