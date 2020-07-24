<?php


namespace test;


class ZhuanUserService extends MyApplicationService
{
    public function __construct()
    {
        parent::__construct('zzuser','Com.BJ58.ZHUANZHUAN.zzuser.contract.IZLJUserService', new ZhuanUserSeriveContract());
    }

    public function getByUid(int $uid, string $logStr)
    {
        return parent::getRef()->getByUid($uid, $logStr);
    }
}