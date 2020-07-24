<?php


namespace test;


class StudentService extends MyApplicationService
{
    public function __construct()
    {
        parent::__construct("servertest1",'Com.BJ58.ZHUANZHUAN.wjx.scf.server.test1.contract.StudentService', new StudentServiceContract());
    }

    public function getStudent(int $id, string $name, int $age)
    {
        return parent::getRef()->getStudent($id, $name, $age);
    }

    public function saveStudent($student)
    {
        return parent::getRef()->saveStudent($student);
    }

}