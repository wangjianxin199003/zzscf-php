<?php


namespace test;

use com\bj58\zhuanzhuan\zzscf\api\contract\AbstractContract;

class SleepServiceContract extends AbstractContract
{
    private  $parameterTypeMap = array('sleep' => array('long'
    ), 'sleep1' => array('long'));


    function getRemoteParameterTypes(string $methodName): array
    {
        return $this->parameterTypeMap[$methodName];
    }


}