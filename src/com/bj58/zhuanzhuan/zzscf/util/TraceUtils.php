<?php


namespace com\bj58\zhuanzhuan\zzscf\util;


class TraceUtils
{
    static $context;

    public static function getTraceId(){
        return TraceUtils::$context->getTraceId();
    }

}