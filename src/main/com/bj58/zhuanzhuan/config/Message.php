<?php


namespace com\bj58\zhuanzhuan\config;


use com\bj58\spat\scf\client\SCFConst;

class Message
{
    const RESPONSE = 1;
    const REQUEST = 2;
    const EXCEPTION = 3;
    const CONFIG = 4;
    const HANDCLASP = 5;
    const RESET = 6;
    const STRING_KEY = 7;


    private int $id;
    private int $type;
    private string $callerKey;
    private string  $body;




    public static function newRequest(Request $request, string $callerKey)
    {
       $message = new Message();
        $message->id = self::createSessionId();
        $message->type = self::REQUEST;
        $message->callerKey = $callerKey;
        $entity = new \stdClass();
        $entity->lookup = $request->getLookup();
        $entity->methodName = $request->getMethodName();
        $entity->paraKVList = $request->getParaKVList();
        $entity->interfaceName = $request->getInterfaceName();
        $message->body = $entity;
        return $message;

    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getCallerKey(): string
    {
        return $this->callerKey;
    }

    /**
     * @param string $callerKey
     */
    public function setCallerKey(string $callerKey): void
    {
        $this->callerKey = $callerKey;
    }

    public function getVersion(){
        return 2;
    }

    private static function createSessionId() {
        if (isset($GLOBALS['scf_sessionId'])) {
            $sid = $GLOBALS['scf_sessionId'];
            if ($sid == 0x7fffffff) {
                $GLOBALS['scf_sessionId'] = 1;
            } else {
                $GLOBALS['scf_sessionId'] = $sid + 1;
            }
        } else {
            $GLOBALS['scf_sessionId'] = 1;
        }
        return $GLOBALS['scf_sessionId'];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }




}