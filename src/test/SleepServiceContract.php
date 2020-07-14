<?php


namespace test;

use com\bj58\zhuanzhuan\zzscf\contract\AbstractContract;

class SleepServiceContract extends AbstractContract
{
    private  $parameterTypeMap = array('sleep' => array('long'
    ), 'sleep1' => array('long'));


    function getRemoteParameterTypes(string $methodName): array
    {
        return $this->parameterTypeMap[$methodName];
    }

    function getRemoteInterfaceName(): string
    {
        return "com.bj58.zhuanzhuan.wjx.scf.server.test1.contract.SleepService";
    }

}