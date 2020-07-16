<?php


namespace com\bj58\zhuanzhuan\zzscf\api\log;


class PrintLogger extends AbstractLogger
{
    function debug(string $msg)
    {
        print $msg . "\n";
    }

    function info(string $msg)
    {
        print $msg . "\n";
    }

    function warn(string $msg)
    {
        print $msg . "\n";
    }

    function error(string $msg)
    {
        print $msg . "\n";
    }


}