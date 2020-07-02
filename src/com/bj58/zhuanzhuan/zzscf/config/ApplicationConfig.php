<?php


namespace com\bj58\zhuanzhuan\zzscf\config;


class ApplicationConfig
{
    private ?string $callerKey = '';

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

}