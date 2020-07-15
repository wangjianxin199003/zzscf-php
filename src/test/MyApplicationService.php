<?php


namespace test;


use com\bj58\zhuanzhuan\zzscf\config\ApplicationConfig;
use com\bj58\zhuanzhuan\zzscf\contract\AbstractService;
use com\bj58\zhuanzhuan\zzscf\contract\Contract;
use com\bj58\zhuanzhuan\zzscf\util\ReferenceConfigUtil;

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
            $document->load('scf.config.xml');
            $applicationConfig->setLocalServiceRefConfigs(ReferenceConfigUtil::parserMultiFromXmlDOMDocument($document));
            parent::__construct($serviceName, $contract, $applicationConfig);
        }
    }
}