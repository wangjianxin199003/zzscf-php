<?php


namespace test;


use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Contract\AbstractContract;

class StudentServiceContract extends AbstractContract
{
    private $parameterTypes = array("getStudent" => array('long', 'String', 'int',
    ), "saveStudent" => array('Student'));
    private $typeMap = array(
        'test\Teacher'=>'Com.BJ58.ZHUANZHUAN.wjx.scf.server.test1.entity.Teacher');

    function getTypeMap(): array
    {
        return $this->typeMap;
    }

    function getRemoteParameterTypes(string $methodName): array
    {
        return $this->parameterTypes[$methodName];
    }


}