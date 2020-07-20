<?php


namespace com\bj58\zhuanzhuan\zzscf\util;


class StdClassUtil
{
    /**
     * stdClass转array,递归
     * @param \stdClass $object
     * @return mixed
     */
    public static function stdClassToArray(\stdClass $object)
    {
        $stdClassArray = array();
        $stdClassArray[] = $object;
        $cur = 0;
        $count = count($stdClassArray);
        while ($cur != $count) {
            foreach ($stdClassArray as $item) {
                unset($item->__type);
                foreach (get_object_vars($item) as $var) {
                    if ($var instanceof \stdClass) {
                        $stdClassArray[] = &$var;
                        $count++;
                    }
                };
                $cur++;
            }
        }
        return json_decode(json_encode($object), true);
    }

    /**
     * array转stdClass, 不能递归
     * @param array $arr
     * @param string $type
     * @return \stdClass
     */
    public static function arrayToStdClass(array $arr, string $type)
    {
        $object = new \stdClass();
        $object->__type = $type;
        foreach (array_keys($arr) as $key) {
            $object->$key = $arr[$key];
        }
        return $object;
    }
}