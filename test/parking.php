<?php


//https://www.jieshunglobal.com/

//https://www.jieshunglobal.com


require '../vendor/autoload.php';


$app = \VSing\ParkingPlatform\Factory::Parking([
    'http'     => [
        'timeout'  => 100,
        'base_uri' => 'https://www.jieshunglobal.com'
    ],
    'cache'    => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => __DIR__ . '/cache',
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],
    'log' => [
        'level' => 'info',
        'file'  => __DIR__.'/tmp/easywechat.log',
    ],
    'v'        => "2.0",
    'cid'      => '00001',
    'user'     => "testUser",
    'password' => '00001',
    'signKey'  => 'nbcxvghkod',
]);
//

//http://www.jslife.com.cn/jsaims/login
//$result = $app->queryParkSpace->get([
//]);

//var_dump($result);
//exit;
//echo json_encode($app->login->get(), 256) . PHP_EOL;

echo json_encode($app->queryCarByCarNo->get([
        "parkCode" => "g3v3_1",
        "carNo"    => "粤B-211222"
    ]), 256) . PHP_EOL;

