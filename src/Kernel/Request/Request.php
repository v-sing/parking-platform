<?php

namespace VSing\ParkingPlatform\Kernel\request;

class request
{


    /**
     * Http post request
     * @param $url
     * @param array $params
     * @param int $timeout
     * @return bool|mixed
     */
    public static function postUrl($url, $params = array(), $timeout = 30)
    {
        //编码特殊字符
        $p = http_build_query($params);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        // 设置header
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $p);
        // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 运行cURL，请求网页
        $data = curl_exec($curl);
        if ($data === false) {
            return false;
        } else {
            return $data;
        }
    }

    /**
     * Http get request
     * @param $url
     * @param array $param
     * @return mixed
     */
    public static function getUrl($url, $param = array())
    {
        $url = self::buildUrl($url, $param);
        return self::get($url);
    }

    /**
     * Http get request
     * @param $url
     * @param int $timeout
     * @return mixed
     */
    public static function get($url, $timeout = 30)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        return $response;
    }

    /**
     * Build request url
     * @param $url
     * @param $param
     * @return string
     */
    private static function buildUrl($url, $param)
    {
        $url = rtrim(trim($url), "?");
        $url = $url . "?";
        $query = "";
        if (!empty($param)) {
            $query = http_build_query($param);
        }
        return $url . $query;
    }

}
