<?php


namespace com\bj58\zhuanzhuan\zzscf\config;


class ReferenceConfig
{
    private ?string  $serviceName;

    private ?RpcArgs $rpcArgs;

    private array $serverNodes;

    /**
     * ServiceReferenceConfig constructor.
     */
    public function __construct()
    {
        $this->rpcArgs = new RpcArgs();
        $this->serverNodes = array();
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
     * @return RpcArgs
     */
    public function getRpcArgs(): RpcArgs
    {
        return $this->rpcArgs;
    }

    /**
     * @param RpcArgs $rpcArgs
     */
    public function setRpcArgs(RpcArgs $rpcArgs): void
    {
        $this->rpcArgs = $rpcArgs;
    }

    /**
     * @return array
     */
    public function getServerNodes(): array
    {
        return $this->serverNodes;
    }

    /**
     * @param array $serverNodes
     */
    public function setServerNodes(array $serverNodes): void
    {
        $this->serverNodes = $serverNodes;
    }


}