<?php


$curl = curl_init();
$url = 'http://api.srvmgr.zhuaninc.com/sdk/getNewConfig?callerKey=' . urlencode('Tm8dDO1dPUY4QqRU8r/kAw==') . '&serviceName=servertest1'.'&clientConfigTime=0';
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($curl);

if ($result){
    $jsonResult = json_decode($result);
    if ($jsonResult->status->code === 0){
        $configXml = $jsonResult->result->scfXml;
        $dir = __DIR__;
        $root = '';
        $index = strpos($dir, '/');
        if ($index !== 0){
            $index = strpos($dir, '\\');
            $root = substr($dir, 0, $index);
        }
        if (!is_dir("$root/tmp/zhuanzhuan/zzscf/refrenceConfig/" . urlencode('Tm8dDO1dPUY4QqRU8r/kAw=='))) {
            mkdir("$root/tmp/zhuanzhuan/zzscf/refrenceConfig/" . urlencode('Tm8dDO1dPUY4QqRU8r/kAw=='), 0777, true);
        };
        $fd = fopen("$root/tmp/zhuanzhuan/zzscf/refrenceConfig/" . urlencode('Tm8dDO1dPUY4QqRU8r/kAw==') . '/servertest1', 'w');
        if ($fd){
            $lock = flock($fd, LOCK_EX);
            if ($lock){
                fwrite($fd, $result);
            }
        }
    }
}
echo filemtime("$root/tmp/zhuanzhuan/zzscf/refrenceConfig/" . urlencode('Tm8dDO1dPUY4QqRU8r/kAw==') . '/servertest1');
//var_dump($result);
