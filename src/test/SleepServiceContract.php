<?php


namespace test;

use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Contract\AbstractContract;

class SleepServiceContract extends AbstractContract
{
    private  $parameterTypeMap = array('sleep' => array('long'
    ));

    function getRemoteParameterTypes(string $methodName): array
    {
        return $this->parameterTypeMap[$methodName];
    }
}