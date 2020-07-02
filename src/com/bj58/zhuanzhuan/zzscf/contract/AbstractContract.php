<?php


namespace com\bj58\zhuanzhuan\zzscf\contract;


abstract class AbstractContract implements Contract
{
    function getRemoteMethodName(string $methodName): string
    {
        return $methodName;
    }

    function getRemoteInterfaceName(): string
    {
        return get_class($this);
    }

    function getTypeMap(): array
    {
        return array();
    }


}