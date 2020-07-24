<?php


namespace test;


use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Config\ApplicationConfig;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Contract\AbstractService;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Contract\Contract;
use Com\BJ58\ZHUANZHUAN\ZZscf\Util\ReferenceConfigUtil;

class MyApplicationService extends AbstractService
{
    /**
     * AbstractTestService constructor.
     */
    public function __construct(string $serviceName, string $interfaceName, Contract $contract, string $lookup = null)
    {
        static $applicationConfig;
        // 不要重复构建
        if (!$applicationConfig) {
            $applicationConfig = new ApplicationConfig();
            $applicationConfig->setAppName("demophp");
            $document = new \DOMDocument();
            $document->load('scf.config.xml');
            $applicationConfig->setLocalServiceRefConfigs(ReferenceConfigUtil::parserMultiFromXmlDOMDocument($document));
            $applicationConfig->setCallerKey("Tm8dDO1dPUY4QqRU8r/kAw==");
        }
        parent::__construct($serviceName, $interfaceName, $contract, $lookup, $applicationConfig);
    }
}