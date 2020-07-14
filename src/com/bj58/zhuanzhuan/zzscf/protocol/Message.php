<?php


namespace com\bj58\zhuanzhuan\zzscf\protocol;



use com\bj58\zhuanzhuan\zzscf\protocol\Request;

class Message
{
    const RESPONSE = 1;
    const REQUEST = 2;
    const EXCEPTION = 3;
    const CONFIG = 4;
    const HANDCLASP = 5;
    const RESET = 6;
    const STRING_KEY = 7;

    private  $version;
    private  $id;
    private  $type;
    private  $callerKey;
    private  $body;
    private  $tiresiasData = '';
    private  $platform;


    public static function newRequest(Request $request, string $callerKey)
    {
        $message = new Message();
        $message->id = self::createSessionId();
        $message->type = self::REQUEST;
        $message->callerKey = $callerKey;
        $message->body = $request;
        $message->version = 2;
        return $message;

    }

    public static function newResponse(){

    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }


    /**
     * @return string
     */
    public function getCallerKey(): string
    {
        return $this->callerKey;
    }

    public function getVersion()
    {
        return $this->version;
    }

    private static function createSessionId()
    {
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
    public function getTiresiasData(): string
    {
        return $this->tiresiasData;
    }

    /**
     * @param int $version
     */
    public function setVersion(int $version): void
    {
        $this->version = $version;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @param string $callerKey
     */
    public function setCallerKey(string $callerKey): void
    {
        $this->callerKey = $callerKey;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }



    /**
     * @return int
     */
    public function getPlatform(): int
    {
        return $this->platform;
    }

    /**
     * @param int $platform
     */
    public function setPlatform(int $platform): void
    {
        $this->platform = $platform;
    }







}