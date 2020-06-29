<?php


namespace com\bj58\zhuanzhuan\config;


class Invoker
{
    private string $appName;

    function invoke(Invocation $invocation){
        $request = new Request();
        $request->setLookup($invocation->getLookup());
        $request->setInterfaceName($invocation->getInterfaceName());
        $request->setMethodName($invocation->getMethodName());
        $kvList = array();
        foreach ($invocation->getArgs() as $arg) {
            $kv = new \stdClass();
            $kv->__type = "com.bj58.spat.scf.protocol.utility.KeyValuePair";
            $kv->key = $arg->__type;
            $kv->value = $arg;
            $kvList[] = $kv;
        }
        $request->setParaKVList($kvList);
        $data = ScfV2Encoder::encode(Message::newRequest($request, ""));

    }
}