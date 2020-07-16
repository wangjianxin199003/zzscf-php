<?php


namespace com\bj58\zhuanzhuan\zzscf\application;


use com\bj58\zhuanzhuan\zzscf\config\ApplicationConfig;
use com\bj58\zhuanzhuan\zzscf\config\ReferenceConfig;
use com\bj58\zhuanzhuan\zzscf\log\DoNothingLogger;
use com\bj58\zhuanzhuan\zzscf\log\Logger;
use com\bj58\zhuanzhuan\zzscf\util\ReferenceConfigUtil;

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

    public static function getReferenceConfig(string $serviceName): ?ReferenceConfig
    {
        self::verify();
        // 从本地缓存读取
        $cached = null;
        if (Application::getCallerKey()) {
            self::readConfigFromFile($serviceName);
        }
        $config = null;
        if ($cached){
            $config = $cached['config'];
        }
        // 如果已经过期从管理平台拉取并写入
        if ((!$cached || (time() - $cached['modifyTime'] > 60)) && Application::getCallerKey()) {
            $result = null;
            try {
                $curl = curl_init();
                $url = 'http://api.srvmgr.zhuaninc.com/sdk/getNewConfig?callerKey=' . urlencode(Application::getCallerKey()) . '&serviceName=' . $serviceName . '&clientConfigTime=0';
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, $url);
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
        }
        // 解析
        if ($config) {
            return ReferenceConfigUtil::parseSingleFromSimpleXmlString($config);
        }else{
            // 本地配置
            if (array_key_exists($serviceName, Application::$instance->localReferenceConfigs)) {
                return Application::$instance->localReferenceConfigs[$serviceName];
            }else{
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
        $fd = fopen($filePath, 'w');
        if ($fd) {
            if (flock($fd, LOCK_EX | LOCK_NB)) {
                try {
                    fwrite($fd, $fileContent);
                    Application::logger()->info("[ARCH_SCF_writeConfigToCache] serviceName=" . $serviceName . ' content=' . $fileContent);
                } finally {
                    flock($fd, LOCK_UN);
                }
            }
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
        $configDir = "$root/tmp/zhuanzhuan/zzscf/refrenceConfig/" . urlencode(Application::getCallerKey());
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
            // 每分钟检测一次
            $fd = fopen($filePath, 'r');
            if ($fd) {
                if (flock($fd, LOCK_SH)) {
                    try {
                        $contents = file_get_contents($filePath);
                        $jsonResult = json_decode($contents);
                        if ($jsonResult->status->code === 0) {
                            return array("config" => $jsonResult->result->scfXml, "modifyTime" => $modifyTime);
                        }
                    } finally {
                        flock($fd, LOCK_UN);
                    }
                }
            }
        } else {
            Application::logger()->info('ss');
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

    public static function logger(): ?Logger
    {
        self::verify();
        return Application::$instance->logger;
    }

    public static function getAppName(): ?string{
        self::verify();
        return Application::$instance->appName;
    }
}