<?php


namespace com\bj58\zhuanzhuan\config;


class Proxy
{
    private string  $lookup;
    private string $interfaceClass;

    /**
     * Proxy constructor.
     * @param $target
     */
    public function __construct($target)
    {
        $this->target = $target;
    }

    function _call($name, $args)
    {
        $request = new Request();
        $request->setLookup($this->lookup);
        $request->setInterfaceName($this->interfaceClass);
        $request->setMethodName($name);
        $kvList = array();
        foreach ($args as $arg) {
            $kv = new \stdClass();
            $kv->__type = "com.bj58.spat.scf.protocol.utility.KeyValuePair";
            $kv->key = $arg->__type;
            $kv->value = $arg;
            $kvList[] = $kv;
        }
        $request->setParaKVList($kvList);
        Message::newRequest($request, "");

    }


}