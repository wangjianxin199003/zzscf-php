<?php


namespace Com\BJ58\ZHUANZHUAN\ZZscf\Api\Log;


interface Logger
{

    function debug(string $msg);

    function info(string $msg);

    function warn(string $msg);

    function error(string $msg);

    function debugException(string $msg, \Throwable $e = null);

    function infoException(string $msg, \Throwable $e = null);

    function warnException(string $msg, \Throwable $e = null);

    function errorException(string $msg, \Throwable $e = null);

}