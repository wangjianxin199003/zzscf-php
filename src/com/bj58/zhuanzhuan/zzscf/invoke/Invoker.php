<?php


namespace com\bj58\zhuanzhuan\zzscf\invoke;


use com\bj58\zhuanzhuan\zzscf\application\Application;
use com\bj58\zhuanzhuan\zzscf\config\RpcArgs;
use com\bj58\zhuanzhuan\zzscf\config\ServerNode;
use com\bj58\zhuanzhuan\zzscf\protocol\KeyValuePair;
use com\bj58\zhuanzhuan\zzscf\protocol\Message;
use com\bj58\zhuanzhuan\zzscf\protocol\Request;
use com\bj58\zhuanzhuan\zzscf\protocol\ScfCodec;

class Invoker
{
    static array $protocolTypeMap = array('com\bj58\zhuanzhuan\zzscf\protocol\Request' => 'com.bj58.spat.scf.protocol.sdp.RequestProtocol',
        'com\bj58\zhuanzhuan\zzscf\protocol\Response' => 'com.bj58.spat.scf.protocol.sdp.ResponseProtocol',
        'com\bj58\zhuanzhuan\zzscf\protocol\KeyValuePair' => 'com.bj58.spat.scf.protocol.utility.KeyValuePair');
    private string $interfaceName;

    private string $lookup;

    private array $typeMap;

    private ServerNode $serverNode;

    private RpcArgs $rpcArgs;


    /**
     * Invoker constructor.
     * @param string $interfaceName
     * @param string $lookup
     * @param array $typeMap
     */
    public function __construct(string $interfaceName, string $lookup, array $typeMap, ServerNode $serverNode, RpcArgs $rpcArgs)
    {
        $this->interfaceName = $interfaceName;
        $this->lookup = $lookup;
        if ($typeMap) {
            $this->typeMap = array_merge($typeMap, self::$protocolTypeMap);
        } else {
            $this->typeMap = self::$protocolTypeMap;
        }
        $this->serverNode = $serverNode;
        $this->rpcArgs = $rpcArgs;
    }


    function invoke(Invocation $invocation)
    {
        $request = $this->buildRequest($invocation);

        list($codec, $data) = $this->encode($request);
        static $socket;
        if (!$socket){
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_connect($socket, $this->serverNode->getHost(), $this->serverNode->getPort());;
        }
        $this->write($data, $socket);
        $responseData = $this->read($socket);
        $response = $codec->decode($responseData)->getBody();
        if ($response->getException) {
            throw $response->getException;
        } else {
            return $response->getResult();
        }
    }

    /**
     * @return string
     */
    public function getInterfaceName(): string
    {
        return $this->interfaceName;
    }

    /**
     * @return string
     */
    public function getLookup(): string
    {
        return $this->lookup;
    }

    /**
     * @param Invocation $invocation
     * @return Request
     */
    public function buildRequest(Invocation $invocation): Request
    {
        $request = new Request();
        $request->setLookup($this->lookup);
        $request->setInterfaceName($this->interfaceName);
        $request->setMethodName($invocation->getMethodName());
        $kvList = array();
        $parameterTypes = $invocation->getParameterTypes();
        $args = $invocation->getArgs();
        for ($i = 0; $i < count($args); $i++) {
            $kv = new KeyValuePair();
            $value = $args[$i];
            $kv->setKey($parameterTypes[$i]);
            $kv->setValue($value);
            $kvList[] = $kv;
        }
        $request->setParaKVList($kvList);
        return $request;
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function encode(Request $request): array
    {
        $codec = new ScfCodec($this->typeMap);
        $data = $codec->encode(Message::newRequest($request, Application::getInstance()->getCallerKey()));
        return array($codec, $data);
    }

    /**
     * @param $data
     * @param $socket
     * @throws \Exception
     */
    public function write($data, $socket): void
    {
        $wroteBytes = 0;
        $triedTimes = 0;
        while ($triedTimes < 3 && $wroteBytes < strlen($data)) {
            $writeResult = socket_write($socket, $data, strlen($data) - $wroteBytes);
            if ($wroteBytes === false) {
                throw new \Exception("send data error");
            } else {
                $wroteBytes += $writeResult;
            }
            $triedTimes++;
        }
        if ($triedTimes === 3) {
            throw new \Exception("send data error, tried " . $triedTimes . ' times');
        }
    }

    /**
     * @param $socket
     * @return string
     */
    public function read($socket): string
    {
        $responseData = socket_read($socket, 10);
        $responseDataLength = unpack('V', substr($responseData, 6));
        $responseData .= socket_read($socket, $responseDataLength[1] + 10 + 14);
        return $responseData;
    }


}