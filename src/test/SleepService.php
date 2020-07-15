<?php


namespace test;


class SleepService extends MyApplicationService
{
    private $ref;

    /**
     * SleepService constructor.
     */
    public function __construct()
    {
        parent::__construct("servertest1", new SleepServiceContract());
    }

    public function sleep(int $time)
    {
        return parent::getRef()->sleep($time);
    }


}