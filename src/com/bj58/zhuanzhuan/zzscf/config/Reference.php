<?php


namespace com\bj58\zhuanzhuan\zzscf\config;


use com\bj58\zhuanzhuan\zzscf\application\Application;
use com\bj58\zhuanzhuan\zzscf\contract\Contract;
use com\bj58\zhuanzhuan\zzscf\invoke\Invoker;
use com\bj58\zhuanzhuan\zzscf\proxy\Proxy;

class Reference
{
    private  $lookup = '';
    private  $serviceName;
    private  $contract;

    public function ref(): Proxy
    {
        $refConfig = Application::getReferenceConfig($this->serviceName);
        if (!$refConfig) {
            throw new \Exception("can not get reference config of service [" . $this->serviceName . ']');
        }
        $serverNodes = $refConfig->getServerNodes();
        $invokers = array();
        foreach ($serverNodes as $serverNode) {
            $invoker = new Invoker($this->serviceName, $this->contract->getRemoteInterfaceName(), $this->lookup, $this->contract->getTypeMap(), $serverNode, $refConfig->getRpcArgs());
            $invokers[] = $invoker;
        }
        return new Proxy($invokers, $this->contract);
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
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @param string $serviceName
     */
    public function setServiceName(string $serviceName): void
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @return Contract
     */
    public function getContract(): Contract
    {
        return $this->contract;
    }

    /**
     * @param Contract $contract
     */
    public function setContract(Contract $contract): void
    {
        $this->contract = $contract;
    }


}