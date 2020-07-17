<?php


namespace test;


class StudentService extends MyApplicationService
{
    public function __construct()
    {
        parent::__construct("servertest1",'com.bj58.zhuanzhuan.wjx.scf.server.test1.contract.StudentService', new StudentServiceContract());
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