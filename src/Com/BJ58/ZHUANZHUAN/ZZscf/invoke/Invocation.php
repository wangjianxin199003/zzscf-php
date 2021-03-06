<?php


namespace Com\BJ58\ZHUANZHUAN\ZZscf\Api\Invoke;


class Invocation
{
    private $methodName;
    private $args;
    private $parameterTypes;

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
     * @param string $lookup
     */
    public function setLookup(string $lookup): void
    {
        $this->lookup = $lookup;
    }


    /**
     * @param string $interfaceName
     */
    public function setInterfaceName(string $interfaceName): void
    {
        $this->interfaceName = $interfaceName;
    }

    /**
     * @return array
     */
    public function getParameterTypes(): array
    {
        return $this->parameterTypes;
    }

    /**
     * @param array $parameterTypes
     */
    public function setParameterTypes(array $parameterTypes): void
    {
        $this->parameterTypes = $parameterTypes;
    }





}