<?php


namespace com\bj58\zhuanzhuan\zzscf\application;


use com\bj58\zhuanzhuan\zzscf\config\ApplicationConfig;

class Application
{
    private static ?Application $instance = null;

    private ?string $callerKey = '';

    /**
     * Application constructor.
     */
    private function __construct()
    {
    }


    /**
     * @return string
     */
    public function getCallerKey(): string
    {
        return $this->callerKey;
    }

    /**
     * @param string $callerKey
     */
    private function setCallerKey(string $callerKey): void
    {
        $this->callerKey = $callerKey;
    }

    public static function buildInstance(ApplicationConfig $config){
        if (!self::$instance instanceof self){
            self::$instance = new self();
            self::$instance->setCallerKey($config->getCallerKey());
        }
    }



    public static function getInstance(){
        if (!self::$instance instanceof Application){
             throw new \Exception("application have not been build");
        }
        return self::$instance;
    }


}