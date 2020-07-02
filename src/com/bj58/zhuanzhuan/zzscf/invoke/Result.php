<?php


namespace com\bj58\zhuanzhuan\zzscf\invoke;


class Result
{
    private $value;
    private ?\Throwable $exception;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
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