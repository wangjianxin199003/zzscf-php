<?php


namespace com\bj58\zhuanzhuan\zzscf\api\contract;


use com\bj58\zhuanzhuan\zzscf\api\application\Application;
use com\bj58\zhuanzhuan\zzscf\api\config\ApplicationConfig;
use com\bj58\zhuanzhuan\zzscf\api\config\Reference;
use com\bj58\zhuanzhuan\zzscf\proxy\Proxy;

abstract class AbstractService
{
    private $ref;

    public function __construct(string $serviceName, string $interfaceName, Contract $contract, string $lookup = null, ApplicationConfig $applicationConfig = null)

    {
        if (!Application::getInstance()) {
            if ($applicationConfig) {
                Application::buildSingletonInstance($applicationConfig);
            } else {
                Application::buildSingletonInstance(new ApplicationConfig());
            }
        }
        $refConfig = new Reference();
        $refConfig->setServiceName($serviceName);
        $refConfig->setContract($contract);
        $refConfig->setInterfaceName($interfaceName);
        $refConfig->setLookup($lookup);
        $this->ref = $refConfig->ref();
    }

    /**
     * @return \com\bj58\zhuanzhuan\zzscf\proxy\Proxy
     */
    public function getRef(): Proxy
    {
        return $this->ref;
    }


}