<?php


namespace Com\BJ58\ZHUANZHUAN\ZZscf\Api\Application;


use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Config\ApplicationConfig;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Config\ReferenceConfig;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Log\DoNothingLogger;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Log\Logger;
use Com\BJ58\ZHUANZHUAN\ZZscf\Util\ReferenceConfigUtil;

class Application
{
    private static $instance = null;

    private $callerKey = '';

    protected $localReferenceConfigs = array();

    private $logger;

    private $appName;

    /**
     * Application constructor.
     */
    private function __construct()
    {
    }

    public static function getInstance()
    {
        return self::$instance;
    }


    /**
     * @return string
     */
    public static function getCallerKey(): ?string
    {
        self::verify();
        return Application::$instance->callerKey;
    }

    public static function getReferenceConfigs(): ?array
    {
        self::verify();
        return Application::$instance->localReferenceConfigs;
    }


    public static function logger(): ?Logger
    {
        self::verify();
        return Application::$instance->logger;
    }

    public static function getAppName(): ?string
    {
        self::verify();
        return Application::$instance->appName;
    }

    public static function getReferenceConfig(string $serviceName): ?ReferenceConfig
    {
        self::verify();
        // 从本地缓存读取
        $cached = null;
        if (Application::getCallerKey()) {
            self::readConfigFromFile($serviceName);
        }
        $config = null;
        if ($cached) {
            $config = $cached['config'];
        }
        // 如果已经过期从管理平台拉取并写入
        if ((!$cached || (time() - $cached['modifyTime'] > 60)) && Application::getCallerKey()) {
            $config = self::getRefConfigFromRegistry($serviceName);
        }
        // 解析
        if ($config) {
            return ReferenceConfigUtil::parseSingleFromSimpleXmlString($config);
        } else {
            // 本地配置
            if (array_key_exists($serviceName, Application::$instance->localReferenceConfigs)) {
                return Application::$instance->localReferenceConfigs[$serviceName];
            } else {
                throw new \Exception('reference configuration can neither be found from the registry nor locally, service name [' . $serviceName . ']');
            }
        }
    }

    /**
     * @param $fileContent
     * @throws \Exception
     */
    private static function writeToCache($serviceName, $fileContent): void
    {
        $configDir = self::getRefConfigDir();
        $filePath = $filePath = $configDir . '/' . $serviceName;
        if (!is_dir($configDir)) {
            @mkdir($configDir, null, true);
        };
        if (!file_put_contents($filePath, $fileContent)) {
            Application::logger()->info("[ARCH_SCF_writeRefConfigToCacheFailed] serviceName [" . $serviceName . '] content [' . $fileContent . ']');
        } else {
            Application::logger()->info("[ARCH_SCF_writeRefConfigToCacheSuccess] serviceName [" . $serviceName . '] content [' . $fileContent . ']');
        }

    }

    /**
     * @return string
     * @throws \Exception
     */
    private static function getRefConfigDir(): string
    {
        $dir = __DIR__;
        $root = '/';
        $index = strpos($dir, '/');
        if ($index !== 0) {
            $index = strpos($dir, '\\');
            $root = substr($dir, 0, $index);
        }
        $configDir = "$root/tmp/ZHUANZHUAN/ZZscf/refrenceConfig/" . urlencode(Application::getCallerKey());
        if (!is_dir($configDir)) {
            @mkdir($configDir, null, true);
        };
        return $configDir;
    }

    /**
     * @param string $filePath
     * @return mixed
     */
    private static function readConfigFromFile(string $serviceName)
    {
        $configDir = self::getRefConfigDir();
        $filePath = $configDir . '/' . $serviceName;
        if (file_exists($filePath)) {
            $modifyTime = filemtime($filePath);
            $contents = file_get_contents($filePath);
            $jsonResult = json_decode($contents);
            if ($jsonResult->status->code === 0) {
                return array("config" => $jsonResult->result->scfXml, "modifyTime" => $modifyTime);
            }
        }
        return false;
    }

    private static function verify(): void
    {
        if (!Application::$instance) {
            throw new \Exception("application instance has not bean created");
        }
    }

    /**
     * @param string $serviceName
     * @return mixed
     * @throws \Exception
     */
    private static function getRefConfigFromRegistry(string $serviceName)
    {
        $result = null;
        try {
            $curl = curl_init();
            $url = 'http://Api.srvmgr.zhuaninc.Com/sdk/getNewConfig?callerKey=' . urlencode(Application::getCallerKey()) . '&serviceName=' . $serviceName . '&clientConfigTime=0';
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, $url);
            curl_setopt($curl, CURLOPT_NOSIGNAL, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);
            $result = curl_exec($curl);
        } catch (\Throwable $e) {
            self::logger()->warnException('failed to get reference configuration from the registry, service name [' . $serviceName . ']', $e);
        }
        if ($result) {
            $jsonResult = json_decode($result);
            if ($jsonResult->status->code === 0) {
                $config = $jsonResult->result->scfXml;
                self::writeToCache($serviceName, $result);
            }
        }
        return $config;
    }

    /**
     * @param string $callerKey
     */
    private function setCallerKey(string $callerKey): void
    {
        $this->callerKey = $callerKey;
    }

    public static function buildSingletonInstance(ApplicationConfig $config)
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
            self::$instance->callerKey = $config->getCallerKey();
            if ($config->getLogger()) {
                self::$instance->logger = $config->getLogger();
            } else {
                self::$instance->logger = new DoNothingLogger();
            }
            if ($config->getAppName()) {
                self::$instance->appName = $config->getAppName();
            }
            foreach ($config->getLocalServiceRefConfigs() as $config) {
                self::$instance->localReferenceConfigs[$config->getServiceName()] = $config;
            }
        } else {
            throw new \Exception("application instance has already bean created");
        }
    }

}