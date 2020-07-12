<?php


namespace com\bj58\zhuanzhuan\zzscf\config;


class ServerNode
{
    private string  $host;
    private int  $port;
    private ?string $version;
    private ?string $idc;
    private ?string $systemEnv;

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @param string|null $version
     */
    public function setVersion(?string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return string|null
     */
    public function getIdc(): ?string
    {
        return $this->idc;
    }

    /**
     * @param string|null $idc
     */
    public function setIdc(?string $idc): void
    {
        $this->idc = $idc;
    }

    /**
     * @return string|null
     */
    public function getSystemEnv(): ?string
    {
        return $this->systemEnv;
    }

    /**
     * @param string|null $systemEnv
     */
    public function setSystemEnv(?string $systemEnv): void
    {
        $this->systemEnv = $systemEnv;
    }




}