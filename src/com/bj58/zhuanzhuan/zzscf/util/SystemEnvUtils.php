<?php


namespace com\bj58\zhuanzhuan\zzscf\util;


class SystemEnvUtils
{
    public static function getIPV4(): ?string
    {
        if (file_exists('/opt/systemEnv')) {
            $systemEnv = parse_ini_file('/opt/systemEnv');
            if ($systemEnv && array_key_exists('ip', $systemEnv)) {
                return $systemEnv['ip'];
            }
        }
        return false;
    }

}