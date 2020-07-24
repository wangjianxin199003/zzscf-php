<?php


namespace Com\BJ58\ZHUANZHUAN\ZZscf\Api\Contract;


use com\bj58\zhuanzhuan\zzscf\api\Application\Application;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Config\ApplicationConfig;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Config\Reference;
use Com\BJ58\ZHUANZHUAN\ZZscf\Proxy\Proxy;

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
     * @return \Com\BJ58\ZHUANZHUAN\ZZscf\Proxy\Proxy
     */
    public function getRef(): Proxy
    {
        return $this->ref;
    }


}