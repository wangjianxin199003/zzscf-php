<?php


namespace test;


use com\bj58\zhuanzhuan\zzscf\api\config\ApplicationConfig;
use com\bj58\zhuanzhuan\zzscf\api\contract\AbstractService;
use com\bj58\zhuanzhuan\zzscf\api\contract\Contract;
use com\bj58\zhuanzhuan\zzscf\util\ReferenceConfigUtil;

class MyApplicationService extends AbstractService
{

    /**
     * AbstractTestService constructor.
     */
    public function __construct(string $serviceName, string $interfaceName, Contract $contract, string $lookup = null)
    {
        static $applicationConfig;
        if (!$applicationConfig) {
            $applicationConfig = new ApplicationConfig();
            $applicationConfig->setAppName("demophp");
            $document = new \DOMDocument();
            $document->load('scf.config.xml');
            $applicationConfig->setLocalServiceRefConfigs(ReferenceConfigUtil::parserMultiFromXmlDOMDocument($document));
            $applicationConfig->setCallerKey("Tm8dDO1dPUY4QqRU8r/kAw==");
            parent::__construct($serviceName, $interfaceName, $contract, $lookup, $applicationConfig);
        }
    }
}