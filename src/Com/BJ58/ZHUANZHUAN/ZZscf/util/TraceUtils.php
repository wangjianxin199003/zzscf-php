<?php


namespace Com\BJ58\ZHUANZHUAN\ZZscf\Util;


class TraceUtils
{
    static $context;

    public static function getTraceId(): string
    {
        if (TraceUtils::$context) {
            return TraceUtils::$context->getTraceId();
        } else {
            return '';
        }
    }

}