<?php


use test\SleepService;
use test\Student;
use test\StudentService;
use test\Teacher;

require '..\..\vendor\autoload.php';


$sleepService = new SleepService();
print($sleepService->sleep(10)."\n");
print($sleepService->sleep(500)."\n");
$studentService = new StudentService();
print_r($studentService->getStudent(1, "wang", 12));
$student = new Student();
$student->setId(2);
$student->setName("zhang");
$student->setAge(13);
$student->setMap(array(0 => 'abc', null=>null));
$teacher1 = new Teacher();
$teacher1->setName("li");
$teacher2 = new Teacher();
$teacher2->setName("zhao");
$student->setTeachers(array($teacher1, $teacher2));
print $studentService->saveStudent($student);


