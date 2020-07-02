<?php


namespace com\bj58\zhuanzhuan\zzscf\proxy;


use com\bj58\zhuanzhuan\zzscf\contract\Contract;
use com\bj58\zhuanzhuan\zzscf\invoke\Invocation;

class Proxy
{
    private array $invokers;

    private Contract $contract;

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
        return $this->invokers[$index]->invoke($invocation);
    }

}