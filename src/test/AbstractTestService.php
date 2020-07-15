<?php


namespace test;


use com\bj58\zhuanzhuan\zzscf\config\ApplicationConfig;
use com\bj58\zhuanzhuan\zzscf\contract\AbstractService;
use com\bj58\zhuanzhuan\zzscf\contract\Contract;
use com\bj58\zhuanzhuan\zzscf\util\ReferenceConfigUtil;

class AbstractTestService extends AbstractService
{

    /**
     * AbstractTestService constructor.
     */
    public function __construct(string $serviceName, Contract $contract)
    {
        $applicationConfig = new ApplicationConfig();
        $document = new \DOMDocument();
        $document->load('scf.config.xml');
        $applicationConfig->setLocalServiceRefConfigs(ReferenceConfigUtil::parserMultiFromXmlDOMDocument($document));
        parent::__construct($serviceName, $contract, $applicationConfig);
    }
}