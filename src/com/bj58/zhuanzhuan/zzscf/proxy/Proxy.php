<?php


namespace com\bj58\zhuanzhuan\zzscf\proxy;


use com\bj58\zhuanzhuan\zzscf\contract\Contract;
use com\bj58\zhuanzhuan\zzscf\exception\RpcException;
use com\bj58\zhuanzhuan\zzscf\exception\RpcExceptionCode;
use com\bj58\zhuanzhuan\zzscf\invoke\Invocation;

class Proxy
{
    private  $invokers;

    private  $contract;

    /**
     * Proxy constructor.
     * @param array $invokers
     * @param Contract $contract
     */
    public function __construct(array $invokers, Contract $contract)
    {
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
                if ($code !== RpcExceptionCode::$CONNECT_ERROR && $code !== RpcExceptionCode::$SERVER_IS_SHUTTING_DOWN) {
                    throw $e;
                }
            }
            $index = ($index + 1) % count($this->invokers);
        } while ($index !== $initIndex);
        throw $exception;
    }

}