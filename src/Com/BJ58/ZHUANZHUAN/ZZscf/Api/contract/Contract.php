<?php


namespace Com\BJ58\ZHUANZHUAN\ZZscf\Api\Contract;


interface Contract
{
    function getRemoteMethodName(string $methodName): string;

    function getRemoteParameterTypes(string $methodName): array;


    function getTypeMap(): array;

}