<?php


namespace Com\BJ58\ZHUANZHUAN\ZZscf\Api\Config;


class ReferenceConfig
{
    private  $serviceName;

    private  $rpcArgs;

    private $serverNodes;

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