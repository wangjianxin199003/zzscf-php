<?php


namespace Com\BJ58\ZHUANZHUAN\ZZscf\Util;


use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Config\RpcArgs;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Config\ServerNode;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Config\ReferenceConfig;

class ReferenceConfigUtil
{
    public static function parserMultiFromXmlDOMDocument(\DOMDocument $xml): array {
        $refConfigs = array();
        $configs = $xml->getElementsByTagName("referenceConfig");
        foreach ($configs as $config) {
            $refConfig = self::parseConfigFromElement($config);
            $refConfigs[] = $refConfig;

        }
        return $refConfigs;
    }

    /**
     * @param $config
     * @return ReferenceConfig
     */
    private static function parseConfigFromElement($config): ReferenceConfig
    {
        $refConfig = new ReferenceConfig();
        $serviceName = $config->getAttribute("serviceName");
        $refConfig->setServiceName($serviceName);
        $children = $config->childNodes;
        $rpcArgs = new RpcArgs();
        $serverNodes = array();
        foreach ($children as $child) {
            if ($child->nodeName === 'ZZscf:rpcArgs') {
                if ($child->getAttribute('timeout')) {
                    $rpcArgs->setTimeout(intval($child->getAttribute('timeout')));
                }
                if ($child->getAttribute('connectTimeout')) {
                    $rpcArgs->setConnectTimeout(intval($child->getAttribute('connectTimeout')));
                }
            }
            if ($child->nodeName === 'ZZscf:nodes') {
                foreach ($child->childNodes as $node) {
                    if ($node->nodeName === 'ZZscf:node') {
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
        return $refConfig;
    }

    public static function parseSingleFromSimpleXmlString(string $xmlString):ReferenceConfig{
        $element = @simplexml_load_string($xmlString);
        $config = new ReferenceConfig();
        if ($element){
            $config->setServiceName( $element->attributes()['serviceName']);
            $children = $element->children();
            foreach ($children as $child){
                if ($child->getName() === 'ZZscf:rpcArgs'){
                    $timeout = $child->attributes()['timeout'];
                    $connectTimeout = $child->attributes()['connectTimeout'];
                    $rpcArgs = new RpcArgs();
                    if ($timeout){
                        $rpcArgs->setTimeout(intval($timeout));
                    }
                    if ($connectTimeout){
                        $rpcArgs->setConnectTimeout(intval($connectTimeout));
                    }
                    $config->setRpcArgs($rpcArgs);
                }
                $nodes = array();
                if ($child->getName() === 'ZZscf:nodes') {
                    $nodesChildren = $child->children();
                    foreach ($nodesChildren as $nodeChild){
                        if ($nodeChild->getName() === 'ZZscf:node'){
                            $host = $nodeChild->attributes()['host'];
                            $port = $nodeChild->attributes()['port'];
                            $systemEnv = $nodeChild->attributes()['systemEnv'];
                            $version = $nodeChild->attributes()['version'];
                            $idc = $nodeChild->attributes()['idc'];
                            $node = new ServerNode();
                            $node->setHost($host);
                            $node->setPort(intval($port));
                            if ($systemEnv){
                                $node->setSystemEnv($systemEnv);
                            }
                            if ($version){
                                $node->setVersion($version);
                            }
                            if ($idc){
                                $node->setIdc($idc);
                            }
                            $nodes[] = $node;

                        }
                    }
                }
                $config->setServerNodes($nodes);
            }
        }
        return $config;
    }
}