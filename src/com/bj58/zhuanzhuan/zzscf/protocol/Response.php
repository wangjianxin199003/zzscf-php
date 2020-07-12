<?php


namespace com\bj58\zhuanzhuan\zzscf\protocol;


class Response
{
    private $result;
    private ?\Throwable $exception;

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }

    /**
     * @return \Throwable|null
     */
    public function getException(): ?\Throwable
    {
        return $this->exception;
    }

    /**
     * @param \Throwable|null $exception
     */
    public function setException(?\Throwable $exception): void
    {
        $this->exception = $exception;
    }



}