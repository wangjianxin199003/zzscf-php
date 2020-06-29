<?php


namespace com\bj58\zhuanzhuan\config;


class ScfV2Encoder
{
    const HEADER_LENGTH = 14;
    const TIRESIAS_LENGTH_FIELD_LENGTH = 2;
    const KEY_LENGTH_FIELD_LENGTH = 2;

    public static function encode(Message $message)
    {
        $factory = new \HessianFactory();
        $writer = $factory->getWriter(null, new \HessianOptions());
        $entity = $writer->writeValue($message->getBody());
        $key = $writer->writeValue($message->getCallerKey());
        $totalLength = self::HEADER_LENGTH + self::TIRESIAS_LENGTH_FIELD_LENGTH + self::KEY_LENGTH_FIELD_LENGTH + strlen($key) + strlen($entity);
        // version
        $data = pack('c', $message->getVersion());
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
        $data .= pack('c', 0);
        // key length field
        $data .= pack('s', strlen($key));
        // key data
        $data .= $key;
        // body
        $data .= $entity;

        return $data;
    }

}