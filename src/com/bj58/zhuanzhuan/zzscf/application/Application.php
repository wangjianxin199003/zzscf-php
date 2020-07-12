<?php


namespace com\bj58\zhuanzhuan\zzscf\application;


use com\bj58\zhuanzhuan\zzscf\config\ApplicationConfig;
use com\bj58\zhuanzhuan\zzscf\config\ReferenceConfig;
use com\bj58\zhuanzhuan\zzscf\util\ServiceReferenceConfigUtil;

class Application
{
    private static ?Application $instance = null;

    private ?string $callerKey = '';

    protected ?array $localReferenceConfigs = array();

    /**
     * Application constructor.
     */
    private function __construct()
    {
    }


    /**
     * @return string
     */
    public static function getCallerKey(): ?string
    {
        if (!Application::$instance) {
            throw new \Exception("application instance has not bean created");
        }
        return Application::$instance->callerKey;
    }

    public static function getReferenceConfigs(): ?array
    {
        if (!Application::$instance) {
            throw new \Exception("application instance has not bean created");
        }
        return Application::$instance->localReferenceConfigs;
    }

    public static function getReferenceConfig(string $serviceName): ?ReferenceConfig
    {
        if (!Application::$instance) {
            throw new \Exception("application instance has not bean created");
        }
        if (!Application::$instance) {
            throw new \Exception("application instance has not bean created");
        }
        $configDir = self::getRefConfigDir();
        $filePath = $configDir . '/' . $serviceName;
        // 从本地缓存读取
        $configXmlString = self::readConfigFromFile($filePath);
        // 从管理平台拉取并写入
        if (!$configXmlString && Application::getCallerKey()) {
            $curl = curl_init();
            $url = 'http://api.srvmgr.zhuaninc.com/sdk/getNewConfig?callerKey=' . urlencode(Application::getCallerKey()) . '&serviceName=' . $serviceName . '&clientConfigTime=0';
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, $url);
            $result = curl_exec($curl);
            if ($result) {
                $jsonResult = json_decode($result);
                if ($jsonResult->status->code === 0) {
                    $configXmlString = $jsonResult->result->scfXml;
                    self::writeToFile($configDir, $filePath, $result);
                }
            }
        }
        // 解析
        if ($configXmlString) {
            $xml = new \DOMDocument($configXmlString);
            $refConfig = ServiceReferenceConfigUtil::parserServiceReferenceConfig($xml);
            if ($refConfig && count($refConfig) > 0) {
                return $refConfig[0];
            }
        }
        // 本地配置
        return Application::$instance->localReferenceConfigs[$serviceName];
    }

    /**
     * @param $fileContent
     * @throws \Exception
     */
    private static function writeToFile($configDir, $filePath, $fileContent): void
    {
        if (!is_dir($configDir)) {
            @mkdir($configDir, null, true);
        };
        $fd = fopen($filePath, 'w');
        if ($fd) {
            if (flock($fd, LOCK_EX | LOCK_NB)) {
                try {
                    fwrite($fd, $fileContent);
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
    private static function readConfigFromFile(string $filePath): mixed
    {
        if (file_exists($filePath)) {
            $modifyTime = filemtime($filePath);
            // 每分钟检测一次
            if (time() - $modifyTime < 60) {
                $fd = fopen($filePath, 'r');
                if ($fd) {
                    if (flock($fd, LOCK_SH)) {
                        try {
                            $contents = file_get_contents($filePath);
                            $jsonResult = json_decode($contents);
                            if ($jsonResult->status->code === 0) {
                                return $jsonResult->result->scfXml;
                            }
                        } finally {
                            flock($fd, LOCK_UN);
                        }
                    }
                }
            }

        }
        return false;
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
            self::$instance->localReferenceConfigs = $config->getLocalServiceRefConfigs();
        } else {
            throw new \Exception("application instance has already bean created");
        }
    }
}