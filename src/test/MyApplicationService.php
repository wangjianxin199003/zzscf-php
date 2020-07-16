<?php


namespace test;


use com\bj58\zhuanzhuan\zzscf\api\config\ApplicationConfig;
use com\bj58\zhuanzhuan\zzscf\api\contract\AbstractService;
use com\bj58\zhuanzhuan\zzscf\api\contract\Contract;

class MyApplicationService extends AbstractService
{

    /**
     * AbstractTestService constructor.
     */
    public function __construct(string $serviceName, Contract $contract)
    {
        static $applicationConfig;
        if (!$applicationConfig){
            $applicationConfig = new ApplicationConfig();
            $applicationConfig->setAppName("demophp");
            $document = new \DOMDocument();
//            $document->load('scf.config.xml');
//            $applicationConfig->setLocalServiceRefConfigs(ReferenceConfigUtil::parserMultiFromXmlDOMDocument($document));
            $applicationConfig->setCallerKey("Tm8dDO1dPUY4QqRU8r/kAw==");
            parent::__construct($serviceName, $contract, $applicationConfig);
        }
    }
}