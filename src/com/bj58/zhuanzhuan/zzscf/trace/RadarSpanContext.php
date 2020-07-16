<?php


namespace com\bj58\zhuanzhuan\zzscf\trace;


use com\bj58\zhuanzhuan\zzscf\util\SystemEnvUtils;

class RadarSpanContext
{
    private $traceId;
    private $spanId;
    private $sampleTTL;

    /**
     * RadarSpanContext constructor.
     */
    public function __construct()
    {
        $ipv4 = SystemEnvUtils::getIPV4();
        if (!$ipv4) {
            $ipv4 = random_int(0, 2147483647);
        }
        $this->traceId = str_pad(dechex(time()), 8, '0', STR_PAD_LEFT)
            . str_pad(dechex(ip2long($ipv4)), 8, '0', STR_PAD_LEFT)
            . str_pad(dechex(getmypid()), 8, '0', STR_PAD_LEFT)
            . str_pad(dechex(random_int(0, 2147483647)), 8,'0', STR_PAD_LEFT);
        $this->spanId = str_pad(dechex(random_int(0, 2147483647)), 8,'0', STR_PAD_LEFT)
            . str_pad(dechex(random_int(0, 2147483647)), 8,'0', STR_PAD_LEFT);
        $this->sampleTTL = -1;

    }

    /**
     * @return string
     */
    public function getTraceId(): string
    {
        return $this->traceId;
    }

    /**
     * @return string
     */
    public function getSpanId(): string
    {
        return $this->spanId;
    }

    /**
     * @return int
     */
    public function getSampleTTL(): int
    {
        return $this->sampleTTL;
    }


}