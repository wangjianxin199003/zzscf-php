<?php


namespace Com\BJ58\ZHUANZHUAN\ZZscf\Protocol;


class Reset
{
    private  $msg;

    /**
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @param string $msg
     */
    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }


}