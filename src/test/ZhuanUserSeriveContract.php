<?php


namespace test;


use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Contract\AbstractContract;

class ZhuanUserSeriveContract extends AbstractContract
{
    private $parameterTypes = array("getByUid" => array('Long', 'String'), "saveStudent" => array('Student'));
    private $typeMap = array(
//        'test\Student' => 'Com.BJ58.ZHUANZHUAN.wjx.scf.server.test1.entity.Student',
//        'test\Teacher'=>'Com.BJ58.ZHUANZHUAN.wjx.scf.server.test1.entity.Teacher'
        );
    function getTypeMap(): array
    {
        return $this->typeMap;
    }

    function getRemoteParameterTypes(string $methodName): array
    {
        return $this->parameterTypes[$methodName];
    }


}