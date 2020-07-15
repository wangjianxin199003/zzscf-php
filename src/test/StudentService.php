<?php


namespace test;


use com\bj58\zhuanzhuan\zzscf\contract\AbstractService;

class StudentService extends AbstractService
{
    private $ref;

    public function __construct()
    {
        parent::__construct("servertest1", new StudentServiceContract());
    }

    public function getStudent(int $id, string $name, int $age)
    {
        return parent::getRef()->getStudent($id, $name, $age);
    }

    public function saveStudent(Student $student)
    {
        return parent::getRef()->saveStudent($student);
    }


}