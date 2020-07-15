<?php


use test\SleepService;
use test\Student;
use test\StudentService;

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
print $studentService->saveStudent($student);


