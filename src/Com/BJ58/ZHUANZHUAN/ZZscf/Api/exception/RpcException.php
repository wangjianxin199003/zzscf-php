<?php


namespace Com\BJ58\ZHUANZHUAN\ZZscf\Api\Exception;


class RpcException extends \Exception
{
    private  $errorCode = '';

    /**
     * RpcException constructor.
     */
    public function __construct( $errorCode, string $message='', \Throwable $cause = null)
    {
        $this->errorCode = $errorCode;
        parent::__construct($message, 0, $cause);
    }

    /**
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }


}