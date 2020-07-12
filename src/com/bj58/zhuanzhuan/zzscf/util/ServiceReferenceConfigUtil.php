<?php


namespace com\bj58\zhuanzhuan\zzscf\util;


use com\bj58\zhuanzhuan\zzscf\config\RpcArgs;
use com\bj58\zhuanzhuan\zzscf\config\ServerNode;
use com\bj58\zhuanzhuan\zzscf\config\ReferenceConfig;

class ServiceReferenceConfigUtil
{
    public static function parserServiceReferenceConfig(\DOMDocument $xml): array {
        $refConfigs = array();
        $configs = $xml->getElementsByTagName("referenceConfig");
        foreach ($configs as $config) {
            $refConfig = new ReferenceConfig();
            $serviceName = $config->getAttribute("serviceName");
            $refConfig->setServiceName($serviceName);
            $children = $config->childNodes;
            $rpcArgs = new RpcArgs();
            $serverNodes = array();
            foreach ($children as $child) {
                if ($child->nodeName === 'zzscf:rpcArgs') {
                    if($child->getAttribute('timeout')){
                        $rpcArgs->setTimeout(intval($child->getAttribute('timeout')));
                    }
                    if ($child->getAttribute('connectTimeout')){
                        $rpcArgs->setConnectTimeout(intval($child->getAttribute('connectTimeout')));
                    }
                }
                if ($child->nodeName === 'zzscf:nodes'){
                    foreach ($child->childNodes as $node){
                        if ($node->nodeName === 'zzscf:node'){
                            $host = $node->getAttribute('host');
                            $port = $node->getAttribute('port');
                            $version = $node->getAttribute('version');
                            $systemEnv = $node->getAttribute('systemEnv');
                            $idc = $node->getAttribute('idc');
                            $serverNode = new ServerNode();
                            $serverNode->setHost($host);
                            $serverNode->setPort($port);
                            $serverNode->setVersion($version);
                            $serverNode->setSystemEnv($systemEnv);
                            $serverNode->setIdc($idc);
                            $serverNodes[] = $serverNode;
                        }
                    }
                }
            }
            $refConfig->setRpcArgs($rpcArgs);
            $refConfig->setServerNodes($serverNodes);
            $refConfigs[] = $refConfig;

        }
        return $refConfigs;
    }
}