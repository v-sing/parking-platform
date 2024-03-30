<?php

namespace VSing\ParkingPlatform\Kernel\Sign;

class ParkMD5
{

    /**
     * 加密
     * @param array $data
     * @param string $secret
     * @return string
     */
    public static function makeSign(array $data,  string $secret): string
    {
        $sign_str = json_encode($data) . $secret;
        return strtoupper(md5($sign_str));
    }
}
