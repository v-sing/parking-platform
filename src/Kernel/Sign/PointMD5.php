<?php

namespace VSing\ParkingPlatform\Kernel\Sign;

class PointMD5
{

    /**
     * 加密
     * @param array $data
     * @param string $openId
     * @param string $secret
     * @return string
     */
    public static function getSign(array $data, string $openId, string $secret): string
    {
        $sign_str = $openId . $secret . time() . json_encode($data);
        return strtoupper(md5($sign_str));
    }
}
