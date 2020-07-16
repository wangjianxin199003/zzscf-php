<?php


namespace com\bj58\zhuanzhuan\zzscf\api\exception;


class RpcExceptionCode
{
    // 连接失败
    public static  $CONNECT_ERROR = 'CONNECT_ERROR';

    // 客户端编码异常
    public static $CLIENT_ENCODE_ERROR = 'CLIENT_ENCODE_ERROR';

    // 客户端解码异常
    public static $CLIENT_DECODE_ERROR = 'CLIENT_DECODE_ERROR';

    // 发送数据异常
    public static $SEND_DATA_ERROR = 'SEND_DATA_ERROR';

    // 请求超时
    public static  $REQUEST_TIMEOUT = 'REQUEST_TIMEOUT';
    // 接收数据失败
    public static  $RECEIVE_DATA_ERROR = 'RECEIVE_DATA_ERROR';
    // 服务端正在关闭
    public static  $SERVER_IS_SHUTTING_DOWN = 'SERVER_IS_SHUTTING_DOWN';
    //普通异常
    public static $EXCEPTION = 'EXCEPTION';
    // 未定义的返回类型
    public static $UNDEFINED_RESPONSE = 'UNDEFINED_RESPONSE';
    // 没有可用节点
    public static $NO_AVAILABLE_SERVER = 'NO_AVAILABLE_SERVER';


}