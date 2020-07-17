<?php


namespace com\bj58\zhuanzhuan\zzscf\invoke;


use com\bj58\zhuanzhuan\zzscf\api\application\Application;
use com\bj58\zhuanzhuan\zzscf\api\config\RpcArgs;
use com\bj58\zhuanzhuan\zzscf\api\config\ServerNode;
use com\bj58\zhuanzhuan\zzscf\api\exception\RpcException;
use com\bj58\zhuanzhuan\zzscf\api\exception\RpcExceptionCode;
use com\bj58\zhuanzhuan\zzscf\protocol\Exception;
use com\bj58\zhuanzhuan\zzscf\protocol\KeyValuePair;
use com\bj58\zhuanzhuan\zzscf\protocol\Message;
use com\bj58\zhuanzhuan\zzscf\protocol\Request;
use com\bj58\zhuanzhuan\zzscf\protocol\Reset;
use com\bj58\zhuanzhuan\zzscf\protocol\Response;
use com\bj58\zhuanzhuan\zzscf\protocol\ScfCodec;
use com\bj58\zhuanzhuan\zzscf\trace\RadarSpanContext;
use com\bj58\zhuanzhuan\zzscf\util\TraceUtils;

class Invoker
{
    static $protocolTypeMap = array('com\bj58\zhuanzhuan\zzscf\protocol\Request' => 'com.bj58.spat.scf.protocol.sdp.RequestProtocol',
        'com\bj58\zhuanzhuan\zzscf\protocol\Response' => 'com.bj58.spat.scf.protocol.sdp.ResponseProtocol',
        'com\bj58\zhuanzhuan\zzscf\protocol\KeyValuePair' => 'com.bj58.spat.scf.protocol.utility.KeyValuePair',
        'com\bj58\zhuanzhuan\zzscf\protocol\Exception' => 'com.bj58.spat.scf.protocol.sdp.ExceptionProtocol',
        'com\bj58\zhuanzhuan\zzscf\protocol\Reset' => 'com.bj58.spat.scf.protocol.sdp.ResetProtocol');
    private $interfaceName;

    private $lookup;

    private $typeMap;

    private $serverNode;

    private $rpcArgs;

    private $serviceName;

    private $descString;


    /**
     * Invoker constructor.
     * @param string $interfaceName
     * @param string $lookup
     * @param array $typeMap
     */
    public function __construct(string $serviceName, string $interfaceName, ?string $lookup, array $typeMap, ServerNode $serverNode, RpcArgs $rpcArgs)
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
        $codec = new ScfCodec($this->typeMap);

        $requestBinaryData = $this->encodeRequest($codec, $request);
        static $socket;
        //连接
        if (!$socket) {
            $socket = $this->getSocket();
        }
        //写入
        if (!fwrite($socket, $requestBinaryData)) {
            throw new RpcException(RpcExceptionCode::$SEND_DATA_ERROR, 'send data to ' . $this->descString . ' error');
        }
        $responseData = $this->readResponseData($socket);
        //反序列化
        $message = $this->decodeResponse($codec, $responseData);

        $response = $message->getBody();
        if ($response instanceof Response) {
            if ($response->getException()) {
                throw $response->getException();
            } else {
                return $response->getResult();
            }
        }
        if ($response instanceof Reset) {
            throw new RpcException(RpcExceptionCode::$SERVER_IS_SHUTTING_DOWN, 'sever is shutting down, ' . $this->descString);
        }
        if ($response instanceof Exception) {
            throw new RpcException(RpcExceptionCode::$EXCEPTION, $response->getErrorMsg());
        }
        throw new RpcException(RpcExceptionCode::$UNDEFINED_RESPONSE, 'undefined response type [' . $message->getType() . ']');
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
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
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
        if (Application::getAppName()) {
            $spanContext = new RadarSpanContext();
            TraceUtils::$context = $spanContext;
            $request->setAttachments(array('application.name' => Application::getAppName(),
                '_RADAR_SPAN_CONTEXT' => $spanContext->getTraceId() . ':::' . $spanContext->getSampleTTL()));
        }
        $request->setParaKVList($kvList);
        return $request;
    }

    private function getSocket()
    {
        $socket = @fsockopen($this->serverNode->getHost(), $this->serverNode->getPort(), $errno, $errStr, $this->rpcArgs->getConnectTimeout() / (float)1000);
        if (!$socket) {
            throw new RpcException(RpcExceptionCode::$CONNECT_ERROR, 'connect to ' . $this->descString . ' error , errorNO [' . $errno . ']' . ' errorStr ' . $errStr);
        }
        return $socket;
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

    /**
     * @param $socket
     * @return false|string
     * @throws RpcException
     */
    private function readHeader($socket)
    {
        stream_set_timeout($socket, 0, $this->rpcArgs->getTimeout() * 1000);
        $responseHeader = fread($socket, 10);
        if (!$responseHeader) {
            if (stream_get_meta_data($socket)['timed_out']) {
                throw new RpcException(RpcExceptionCode::$REQUEST_TIMEOUT, 'request timeout ' . $this->descString);
            } else {
                throw new RpcException(RpcExceptionCode::$RECEIVE_DATA_ERROR, 'receive data error ' . $this->descString);
            }
        }
        return $responseHeader;
    }

    /**
     * @param $socket
     * @param $totalLength
     * @return false|string
     * @throws RpcException
     */
    private function readBody($socket, $totalLength)
    {
        stream_set_timeout($socket, 0, $this->rpcArgs->getTimeout() * 1000);
        $responseBody = fread($socket, $totalLength);
        if (!$responseBody) {
            if (stream_get_meta_data($socket)['timed_out']) {
                throw new RpcException(RpcExceptionCode::$REQUEST_TIMEOUT, 'request timeout ' . $this->descString);
            } else {
                throw new RpcException(RpcExceptionCode::$RECEIVE_DATA_ERROR, 'receive data error ' . $this->descString);
            }
        }
        return $responseBody;
    }

    /**
     * @param ScfCodec $codec
     * @param Request $request
     * @return string
     * @throws RpcException
     */
    private function encodeRequest(ScfCodec $codec, Request $request): string
    {
        try {
            $requestBinaryData = $codec->encode(Message::newRequest($request, Application::getCallerKey()));
        } catch (\Throwable $e) {
            throw new RpcException(RpcExceptionCode::$CLIENT_ENCODE_ERROR, '', $e);
        }
        return $requestBinaryData;
    }

    /**
     * @param ScfCodec $codec
     * @param string $responseData
     * @return Message
     * @throws RpcException
     */
    private function decodeResponse(ScfCodec $codec, string $responseData): Message
    {
        try {
            $message = $codec->decode($responseData);
        } catch (\Throwable $e) {
            throw new RpcException(RpcExceptionCode::$CLIENT_DECODE_ERROR, 'decode data error from ' . $this->descString, $e);
        }
        return $message;
    }

    /**
     * @param $socket
     * @return string
     * @throws RpcException
     */
    private function readResponseData($socket): string
    {
//读header
        $responseHeader = $this->readHeader($socket);
        //解析长度
        $totalLength = unpack('V', $responseHeader, 6)[1];
        //读body
        $responseBody = $this->readBody($socket, $totalLength);
        $responseData = $responseHeader . $responseBody;
        return $responseData;
    }


}