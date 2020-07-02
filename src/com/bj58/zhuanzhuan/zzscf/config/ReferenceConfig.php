<?php


namespace com\bj58\zhuanzhuan\zzscf\config;


use com\bj58\zhuanzhuan\zzscf\contract\Contract;
use com\bj58\zhuanzhuan\zzscf\invoke\Invoker;
use com\bj58\zhuanzhuan\zzscf\proxy\Proxy;

class ReferenceConfig
{
    private ?string $lookup;
    private string $serviceName;
    private Contract $contract;
    private ?ServiceReferenceConfig $localConfig;

    public function ref(): Proxy
    {
        $serverNodes = $this->localConfig->getServerNodes();
        $invokers = array();
        foreach ($serverNodes as $serverNode){
            $invoker = new Invoker($this->contract->getRemoteInterfaceName(), $this->lookup, $this->contract->getTypeMap(), $serverNode, $this->localConfig->getRpcArgs());
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

    /**
     * @return ServiceReferenceConfig
     */
    public function getLocalConfig(): ServiceReferenceConfig
    {
        return $this->localConfig;
    }

    /**
     * @param ServiceReferenceConfig $localConfig
     */
    public function setLocalConfig(ServiceReferenceConfig $localConfig): void
    {
        $this->localConfig = $localConfig;
    }




}