<?php


namespace com\bj58\zhuanzhuan\zzscf\api\contract;


interface Contract
{
    function getRemoteMethodName(string $methodName): string;

    function getRemoteParameterTypes(string $methodName): array;

    function getRemoteInterfaceName(): string;

    function getTypeMap(): array;

}