<?php


namespace test;

use com\bj58\zhuanzhuan\zzscf\contract\AbstractContract;

class SleepService extends AbstractContract
{
    private array $parameterTypeMap = array('sleep' => array('long'
    ));


    function getRemoteParameterTypes(string $methodName): array
    {
        return $this->parameterTypeMap[$methodName];
    }

    function getRemoteInterfaceName(): string
    {
        return "com.bj58.zhuanzhuan.wjx.scf.server.test1.contract.SleepService";
    }

}