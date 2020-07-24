<?php


namespace Com\BJ58\ZHUANZHUAN\ZZscf\Api\Log;


abstract class AbstractLogger implements Logger
{
    function debug(string $msg)
    {
        // TODO: Implement debug() method.
    }

    function info(string $msg)
    {
        // TODO: Implement info() method.
    }

    function warn(string $msg)
    {
        // TODO: Implement warn() method.
    }

    function error(string $msg)
    {
        // TODO: Implement error() method.
    }

    function debugException(string $msg, \Throwable $e = null)
    {
        // TODO: Implement debugException() method.
    }

    function infoException(string $msg, \Throwable $e = null)
    {
        // TODO: Implement infoException() method.
    }

    function warnException(string $msg, \Throwable $e = null)
    {
        // TODO: Implement warnException() method.
    }

    function errorException(string $msg, \Throwable $e = null)
    {
        // TODO: Implement errorException() method.
    }


}