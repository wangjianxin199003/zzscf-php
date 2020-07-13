<?php


namespace com\bj58\zhuanzhuan\zzscf\config;


class ApplicationConfig
{
    static protected $instance;

    private ?string $callerKey = '';

    private ?array $localServiceRefConfigs = array();

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

    /**
     * @return array|null
     */
    public function getLocalServiceRefConfigs(): ?array
    {
        return $this->localServiceRefConfigs;
    }

    /**
     * @param array|null $localServiceRefConfig
     */
    public function setLocalServiceRefConfigs(?array $localServiceRefConfigs): void
    {
        $this->localServiceRefConfigs = $localServiceRefConfigs;
    }
}

