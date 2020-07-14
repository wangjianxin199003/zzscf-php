<?php


namespace com\bj58\zhuanzhuan\zzscf\protocol;


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