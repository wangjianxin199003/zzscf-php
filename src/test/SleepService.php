<?php


namespace test;


class SleepService extends MyApplicationService
{
    /**
     * SleepService constructor.
     */
    public function __construct()
    {
        parent::__construct("servertest1", 'Com.BJ58.ZHUANZHUAN.wjx.scf.server.test1.contract.SleepService', new SleepServiceContract());
    }

    public function sleep(int $time)
    {
        return parent::getRef()->sleep($time);
    }
}