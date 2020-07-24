<?php


namespace Com\BJ58\ZHUANZHUAN\ZZscf\Api\Contract;


abstract class AbstractContract implements Contract
{
    function getRemoteMethodName(string $methodName): string
    {
        return $methodName;
    }

    function getTypeMap(): array
    {
        return array();
    }


}