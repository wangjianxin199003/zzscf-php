<?php


namespace com\bj58\zhuanzhuan\zzscf\api\contract;


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