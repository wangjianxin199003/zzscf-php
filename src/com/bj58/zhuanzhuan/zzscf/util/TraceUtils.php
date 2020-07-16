<?php


namespace com\bj58\zhuanzhuan\zzscf\util;


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