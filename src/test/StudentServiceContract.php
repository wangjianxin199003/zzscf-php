<?php


namespace test;


use com\bj58\zhuanzhuan\zzscf\api\contract\AbstractContract;

class StudentServiceContract extends AbstractContract
{
    private $parameterTypes = array("getStudent" => array('long', 'String', 'int',
    ), "saveStudent" => array('Student'));
    private $typeMap = array('test\Student' => 'com.bj58.zhuanzhuan.wjx.scf.server.test1.entity.Student',
        'test\Teacher'=>'com.bj58.zhuanzhuan.wjx.scf.server.test1.entity.Teacher');

    function getTypeMap(): array
    {
        return $this->typeMap;
    }

    function getRemoteParameterTypes(string $methodName): array
    {
        return $this->parameterTypes[$methodName];
    }


}