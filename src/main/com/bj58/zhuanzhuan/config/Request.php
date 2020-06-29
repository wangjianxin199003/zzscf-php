<?php


namespace com\bj58\zhuanzhuan\config;


class Request
{
    private string  $lookup;
    private string $methodName;
    private array $paraKVList;
    private string $interfaceName;

    /**
     * @return string
     */
    public function getLookup(): string
    {
        return $this->lookup;
    }

    /**
     * @param string $lookup
     */
    public function setLookup(string $lookup): void
    {
        $this->lookup = $lookup;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @param string $methodName
     */
    public function setMethodName(string $methodName): void
    {
        $this->methodName = $methodName;
    }

    /**
     * @return array
     */
    public function getParaKVList(): array
    {
        return $this->paraKVList;
    }

    /**
     * @param array $paraKVList
     */
    public function setParaKVList(array $paraKVList): void
    {
        $this->paraKVList = $paraKVList;
    }

    /**
     * @return string
     */
    public function getInterfaceName(): string
    {
        return $this->interfaceName;
    }

    /**
     * @param string $interfaceName
     */
    public function setInterfaceName(string $interfaceName): void
    {
        $this->interfaceName = $interfaceName;
    }


}