<?php


namespace com\bj58\zhuanzhuan\config;


class Invocation
{
    private string  $methodName;
    private array $args;
    private string  $lookup;
    private string $interfaceName;

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
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @param array $args
     */
    public function setArgs(array $args): void
    {
        $this->args = $args;
    }

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