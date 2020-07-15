<?php


namespace com\bj58\zhuanzhuan\zzscf\contract;


use com\bj58\zhuanzhuan\zzscf\application\Application;
use com\bj58\zhuanzhuan\zzscf\config\ApplicationConfig;
use com\bj58\zhuanzhuan\zzscf\config\Reference;
use com\bj58\zhuanzhuan\zzscf\proxy\Proxy;

abstract class AbstractService
{
    private $ref;

    public function __construct(string $serviceName, Contract $contract, ApplicationConfig $applicationConfig = null)

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