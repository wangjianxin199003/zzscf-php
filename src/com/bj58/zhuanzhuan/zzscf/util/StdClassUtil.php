<?php


namespace com\bj58\zhuanzhuan\zzscf\util;


class StdClassUtil
{
    public static function stdClassToArray(\stdClass $object){
        $result = json_decode(json_encode($object), true);
        if (array_key_exists("__type", $result)) {
            unset($result['__type']);
        }
        return $result;
    }

    public static function arrayToStdClass(array $arr, string $type){
        $object = new \stdClass();
        $object->__type = $type;
        foreach (array_keys($arr) as $key) {
            $object->$key = $arr[$key];
        }
        return $object;
    }
}