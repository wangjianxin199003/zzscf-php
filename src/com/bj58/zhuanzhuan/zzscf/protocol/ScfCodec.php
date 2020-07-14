<?php
namespace com\bj58\zhuanzhuan\zzscf\protocol;


use com\bj58\zhuanzhuan\zzscf\serialization\HessianFactory;
use com\bj58\zhuanzhuan\zzscf\serialization\HessianOptions;
use com\bj58\zhuanzhuan\zzscf\serialization\HessianStream;

class ScfCodec
{
    const HEADER_LENGTH = 14;
    const TIRESIAS_LENGTH_FIELD_LENGTH = 2;
    const KEY_LENGTH_FIELD_LENGTH = 2;
    private  $typeMap;

    /**
     * ScfCodec constructor.
     * @param array|string[] $typeMap
     */
    public function __construct($typeMap)
    {
        $this->typeMap = $typeMap;
    }


    public function encode(Message $message)
    {
        $factory = new HessianFactory();
        $options = new HessianOptions();
        $options->typeMap = $this->typeMap;
        $writer = $factory->getWriter(null,$options );
        $entity = $writer->writeValue($message->getBody());
        $key = $writer->writeValue($message->getCallerKey());
        $tiresias = $writer->writeValue($message->getTiresiasData());
        $totalLength = self::HEADER_LENGTH + self::TIRESIAS_LENGTH_FIELD_LENGTH + strlen($tiresias) + self::KEY_LENGTH_FIELD_LENGTH + strlen($key) + strlen($entity);
        $data = pack('ccccc', 18, 17, 13, 10, 9);
        // version
        $data .= pack('c', $message->getVersion());
        // totalLength
        $data .= pack('V', $totalLength);
        // messageId
        $data .= pack('V', $message->getId());
        // serverId
        $data .= pack('c', 0);
        // entityType
        $data .= pack('c', $message->getType());
        // compress
        $data .= pack('c', 0);
        // serialization
        $data .= pack('c', SerializationConst::HESSIAN);
        // platform
        $data .= pack('c', 3);
        // tiresias length  field
        $data .= pack('s', strlen($tiresias));
        // tiresias
        $data .= $tiresias;
        // key length field
        $data .= pack('s', strlen($key));
        // key data
        $data .= $key;
        // body
        $data .= $entity;
        $data .= pack('ccccc', 9, 10, 13, 17, 18);
        return $data;
    }

    /**
     * 解析message 当前只支持解析v1
     * @param $data
     * @return Message
     */
    public function decode($data)
    {
//        echo 'dataLength' . strlen($data) . "\n";
        $message = new Message();
        $version = unpack('c', $data, 5);
        $message->setVersion($version[1]);
//        print 'version'.$version[1]."\n";
        $totalLength = unpack('V', $data, 6);
//        print 'totalLength' . $totalLength[1] . "\n";
        $id = unpack('V', $data, 10);
//        print 'id' . $id [1]. "\n";
        $message->setId($id[1]);
//        $serverId = unpack('c', $data, 14);
        $messageType = unpack('c', $data, 15);
//        echo 'type' . $messageType[1] . "\n";
        $message->setType($messageType[1]);
        $compressType = unpack('c', $data, 16);
//        echo 'compressType' . $compressType[1] . "\n";
        $serialization = unpack('c', $data, 17);
//        echo "serialization" . $serialization[1] . "\n";
        $platform = unpack('c', $data, 18);
        if ($version[1] === 2){
            $tiresiasLength = unpack('s', $data, 19);
            $keyLength = unpack('s', $data, 21 + $tiresiasLength[1]);
        }

        $message->setPlatform($platform[1]);

//        echo 'paltform'.$platform[1]."\n";

        $hessianFactory = new HessianFactory();
        $options = new HessianOptions();
        $options->typeMap = $this->typeMap;
        $length = strlen($data) - 14 - 10;
//        echo 'bodyLength' . $length . "\n";
        $bodyData = '';
        if ($version[1] === 2){
            $bodyData = substr($data, 19 + 2 + 2 + $tiresiasLength[1] + $keyLength[1], $length);
        }else{
            $bodyData = substr($data, 19 , $length);
        }
        $reader = $hessianFactory->getParser(new HessianStream($bodyData, null), $options);
        $body = $reader->parse();
        $message->setBody($body);
        return $message;
    }

}