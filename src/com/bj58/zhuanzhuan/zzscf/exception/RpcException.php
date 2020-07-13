<?php


namespace com\bj58\zhuanzhuan\zzscf\exception;


class RpcException extends \Exception
{


    /**
     * RpcException constructor.
     */
    public function __construct(string $errorCode, string $message='', \Throwable $cause = null)
    {
        parent::__construct($message, $errorCode, $cause);
    }
}