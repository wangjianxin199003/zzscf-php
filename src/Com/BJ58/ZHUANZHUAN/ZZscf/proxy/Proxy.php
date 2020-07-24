<?php


namespace Com\BJ58\ZHUANZHUAN\ZZscf\Proxy;


use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Contract\Contract;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Exception\RpcException;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Exception\RpcExceptionCode;
use Com\BJ58\ZHUANZHUAN\ZZscf\Api\Invoke\Invocation;

class Proxy
{
    private $invokers;

    private $contract;

    /**
     * Proxy constructor.
     * @param array $invokers
     * @param Contract $contract
     */
    public function __construct(array $invokers, Contract $contract)
    {
        if (count($invokers) === 0) {
            throw new \Exception('argument invokers can not be empty');
        }
        $this->invokers = $invokers;
        $this->contract = $contract;
    }


    public function __call($name, $arguments)
    {
        $invocation = new Invocation();
        $invocation->setMethodName($this->contract->getRemoteMethodName($name));
        $invocation->setArgs($arguments);
        $invocation->setParameterTypes($this->contract->getRemoteParameterTypes($name));
        $index = rand(0, count($this->invokers) - 1);
        $initIndex = $index;
        $exception = null;
        do {
            try {
                $result = $this->invokers[$index]->invoke($invocation);
                return $result;
            } catch (RpcException $e) {
                $exception = $e;
                $code = $e->getErrorCode();
                if ($code !== RpcExceptionCode::$CONNECT_ERROR && $code !== RpcExceptionCode::$SERVER_IS_SHUTTING_DOWN && $code != RpcExceptionCode::$SEND_DATA_ERROR) {
                    throw $e;
                }
            }
            $index = ($index + 1) % count($this->invokers);
        } while ($index !== $initIndex);
        throw new RpcException(RpcExceptionCode::$NO_AVAILABLE_SERVER, $this->invokers[0]->getServiceName() . 'has no available server', $exception);
    }

}