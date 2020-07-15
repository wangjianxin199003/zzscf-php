<?php


namespace com\bj58\zhuanzhuan\zzscf\config;


use com\bj58\zhuanzhuan\zzscf\log\Logger;

class ApplicationConfig
{

    private $callerKey = '';

    private $localServiceRefConfigs = array();

    private $logger;

    private $appName;

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

    /**
     * @return mixed
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param mixed $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return mixed
     */
    public function getAppName()
    {
        return $this->appName;
    }

    /**
     * @param mixed $appName
     */
    public function setAppName(string $appName): void
    {
        $this->appName = $appName;
    }




}

