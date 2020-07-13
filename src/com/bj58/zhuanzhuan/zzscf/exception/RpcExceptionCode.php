<?php


namespace com\bj58\zhuanzhuan\zzscf\exception;


class RpcExceptionCode
{
    // 连接失败
    public static string $CONNECT_ERROR = 'CONNECT_ERROR';

    // 客户端编码异常
    public static string $CLIENT_ENCODE_ERROR = 'CLIENT_ENCODE_ERROR';

    // 客户端解码异常
    public static string $CLIENT_DECODE_ERROR = 'CLIENT_DECODE_ERROR';

    // 发送数据异常
    public static string $SEND_DATA_ERROR = 'SEND_DATA_ERROR';

    // 请求超时
    public static string $REQUEST_TIMEOUT = 'REQUEST_TIMEOUT';
    // 接收数据失败
    public static string $RECEIVE_DATA_ERROR = 'RECEIVE_DATA_ERROR';


}