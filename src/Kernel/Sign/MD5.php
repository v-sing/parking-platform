<?php

namespace VSing\ParkingPlatform\Kernel\Sign;

class MD5
{


    /**
     * 获取签名
     * @param array $para 密的参数数组
     * @param string $accessSec 加密的key
     * @return bool|string 生产的签名
     */
    public static function getSign($para, $accessSec)
    {
        if (empty($para) || empty($accessSec)) {
            return false;
        }
        //除去待签名参数数组中的空值和签名参数
        $para = self::paraFilter($para);
        $para = self::argSort($para);
        $str = self::createLinkString($para);
        return self::md5Verify($str, $accessSec);
    }

    /**
     * 判断签名是否正确
     * @param $param
     * @param $encKey
     * @param $sign
     * @return bool
     */
    public static function isSignCorrect($param, $encKey, $sign)
    {
        if (empty($sign)) {
            return false;
        } else {
            $preStr = self::getSign($param, $encKey);
            return $preStr === $sign;
        }
    }

    /**
     * 除去数组中的空值和签名参数
     * @param array $para 签名参数组
     * @return array 获取去掉空值与签名参数后的新签名参数组
     */
    private static function paraFilter($para)
    {
        $para_filter = array();
        foreach ($para as $key=> $val){
            if ($key == "sign" || $key == "sign_type" || $key == "key" || (empty($val) && !is_numeric($val))) {
                continue;
            } else {
                $para_filter[$key] = $para[$key];
            }
        }
//        while (list ($key, $val) = each($para)) {
//            //去掉 "",null,保留数字0
//            if ($key == "sign" || $key == "sign_type" || $key == "key" || (empty($val) && !is_numeric($val))) {
//                continue;
//            } else {
//                $para_filter[$key] = $para[$key];
//            }
//        }
        return $para_filter;
    }

    /**
     * 对数组排序
     * @param array $para 排序前的数组
     * @return mixed 排序后的数组
     */
    private static function argSort($para)
    {
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param array $para 需要拼接的数组
     * @return string 拼接完成以后的字符串
     */
    private static function createLinkString($para)
    {

        $arg = urldecode(http_build_query($para));
        //如果存在转义字符，那么去掉转义
//        $arg = stripslashes($arg);
        return $arg;
    }

    /**
     * 生成签名
     * @param string $prestr 需要签名的字符串
     * @param string $sec 身份认证密钥(access_sec)
     * @return string 签名结果
     */
    private static function md5Verify($prestr, $sec)
    {
        return md5($prestr . $sec);
    }
}
