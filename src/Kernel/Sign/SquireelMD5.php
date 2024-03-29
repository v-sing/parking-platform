<?php

namespace VSing\ParkingPlatform\Kernel\Sign;

class SquireelMD5
{

    public static function makeSign($data,$secret){

        $filter = array();
        foreach ($data as $key=> $val){
            if ($key == "sign" || (empty($val) && !is_numeric($val))) {
                continue;
            } else {
                $filter[$key] = $val;
            }
        }
        ksort($filter);

        $string=$secret;
        foreach ($filter as $key=>$value){
            $string.=$key.$value;
        }
        $string.=$secret;

        return md5( $string);

    }

    /**
     * 回调签名验证
     * @param $data
     * @param $secret
     * @param $sign
     * @return bool
     */
    public static function checkSign($data,$secret,$sign){

        $filter = array();
        foreach ($data as $key=> $val){
            if ($key == "sign") {
                continue;
            } else {
                $filter[$key] = $val;
            }
        }
        ksort($filter);

        $string=$secret;
        foreach ($filter as $key=>$value){
            $string.=$key.$value;
        }
        $string.=$secret;
        return strtoupper(md5( $string))==$sign;
    }

}
