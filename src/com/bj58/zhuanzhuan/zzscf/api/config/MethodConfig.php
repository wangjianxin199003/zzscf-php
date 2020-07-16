<?php


namespace com\bj58\zhuanzhuan\zzscf\api\config;


class MethodConfig
{
    private  $methodKey;
    private  $timeout;

    /**
     * @return string
     */
    public function getMethodKey(): string
    {
        return $this->methodKey;
    }

    /**
     * @param string $methodKey
     */
    public function setMethodKey(string $methodKey): void
    {
        $this->methodKey = $methodKey;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }



}