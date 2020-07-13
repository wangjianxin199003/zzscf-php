<?php


namespace com\bj58\zhuanzhuan\zzscf\invoke;


use com\bj58\zhuanzhuan\zzscf\application\Application;
use com\bj58\zhuanzhuan\zzscf\config\RpcArgs;
use com\bj58\zhuanzhuan\zzscf\config\ServerNode;
use com\bj58\zhuanzhuan\zzscf\exception\RpcException;
use com\bj58\zhuanzhuan\zzscf\exception\RpcExceptionCode;
use com\bj58\zhuanzhuan\zzscf\protocol\KeyValuePair;
use com\bj58\zhuanzhuan\zzscf\protocol\Message;
use com\bj58\zhuanzhuan\zzscf\protocol\Request;
use com\bj58\zhuanzhuan\zzscf\protocol\Response;
use com\bj58\zhuanzhuan\zzscf\protocol\ScfCodec;

class Invoker
{
    static array $protocolTypeMap = array('com\bj58\zhuanzhuan\zzscf\protocol\Request' => 'com.bj58.spat.scf.protocol.sdp.RequestProtocol',
        'com\bj58\zhuanzhuan\zzscf\protocol\Response' => 'com.bj58.spat.scf.protocol.sdp.ResponseProtocol',
        'com\bj58\zhuanzhuan\zzscf\protocol\KeyValuePair' => 'com.bj58.spat.scf.protocol.utility.KeyValuePair',
    'com\bj58\zhuanzhuan\zzscf\protocol\Exception' => 'com.bj58.spat.scf.protocol.sdp.ExceptionProtocol',
    'com\bj58\zhuanzhuan\zzscf\protocol\Reset' => 'com.bj58.spat.scf.protocol.sdp.ResetProtocol');
    private string $interfaceName;

    private string $lookup;

    private array $typeMap;

    private ServerNode $serverNode;

    private RpcArgs $rpcArgs;

    private string $serviceName;

    private string $descString;


    /**
     * Invoker constructor.
     * @param string $interfaceName
     * @param string $lookup
     * @param array $typeMap
     */
    public function __construct(string $serviceName, string $interfaceName, string $lookup, array $typeMap, ServerNode $serverNode, RpcArgs $rpcArgs)
    {
        $this->serviceName = $serviceName;
        $this->interfaceName = $interfaceName;
        $this->lookup = $lookup;
        if ($typeMap) {
            $this->typeMap = array_merge($typeMap, self::$protocolTypeMap);
        } else {
            $this->typeMap = self::$protocolTypeMap;
        }
        $this->serverNode = $serverNode;
        $this->rpcArgs = $rpcArgs;
        $this->descString = 'service [' . $serviceName . '], host [' . $serverNode->getHost() . '], port [' . $serverNode->getPort() . ']';
    }


    function invoke(Invocation $invocation)
    {
        $request = $this->buildRequest($invocation);

        list($codec, $data) = $this->encode($request);
        static $socket;
        if (!$socket) {
            $socket = fsockopen($this->serverNode->getHost(), $this->serverNode->getPort(), $errno, $errStr, $this->rpcArgs->getConnectTimeout() / (float)1000);
            if (!$socket) {
                throw new RpcException(RpcExceptionCode::$CONNECT_ERROR, 'connect to ' . $this->descString . ' error , errorNO [' . $errno . ']' . ' errorStr ' . $errStr);
            }
        }
        if (!fwrite($socket, $data)) {
            throw new RpcException(RpcExceptionCode::$SEND_DATA_ERROR, 'send data to ' . $this->descString . ' error');
        }
        stream_set_timeout($socket, 0, $this->rpcArgs->getTimeout() * 1000);
        $responseHeader = fread($socket, 10);
        if (!$responseHeader) {
            if (stream_get_meta_data($socket)['timed_out']) {
                throw new RpcException(RpcExceptionCode::$REQUEST_TIMEOUT, 'request timeout ' . $this->descString);
            } else {
                throw new RpcException(RpcExceptionCode::$RECEIVE_DATA_ERROR, 'receive data error ' . $this->descString);
            }
        }
        $totalLength = unpack('V', $responseHeader, 6)[1];
        stream_set_timeout($socket, 0, $this->rpcArgs->getTimeout() * 1000);
        $responseBody = fread($socket, $totalLength);
        if (!$responseBody) {
            if (stream_get_meta_data($socket)['timed_out']) {
                throw new RpcException(RpcExceptionCode::$REQUEST_TIMEOUT, 'request timeout ' . $this->descString);
            } else {
                throw new RpcException(RpcExceptionCode::$RECEIVE_DATA_ERROR, 'receive data error ' . $this->descString);
            }
        }
        try {
            $response = $codec->decode($responseHeader . $responseBody)->getBody();
        } catch (\Throwable $e) {
            throw new RpcException(RpcExceptionCode::$CLIENT_DECODE_ERROR, 'decode data error from ' . $this->descString);
        }
        if ($response instanceof Response) {
            if ($response->getException()) {
                throw $response->getException();
            } else {
                return $response->getResult();
            }
        }
        if ($response instanceof \stdClass) {
            if ($response->__type === 'com.bj58.spat.scf.protocol.sdp.ExceptionProtocol') {
                throw new \Exception($response->errorMsg);
            }
            if ($response->__type === 'com.bj58.spat.scf.protocol.sdp.ResetProtocol') {
                throw new \Exception('server is rebooting');
            }
        }
        throw new \Exception('unkown response');
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
        $request = Message::newRequest($request, Application::getCallerKey());
        $data = $codec->encode($request);
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
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => $this->rpcArgs->getTimeout(), "usec" => 0));
        $responseData = socket_read($socket, 10);
        $responseDataLength = unpack('V', substr($responseData, 6));
        $responseData .= socket_read($socket, $responseDataLength[1] + 10 + 14);
        return $responseData;
    }


}