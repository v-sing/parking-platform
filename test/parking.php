<?php

require '../vendor/autoload.php';

$app = \VSing\ParkingPlatform\Factory::Parking([
    'http'     => [
        'timeout'  => 100,
        'base_uri' => 'https://www.xxx.com'
    ],
    'cache'    => [
        // 缓存保存目录
        'path' => __DIR__ . '/cache',
    ],
    'log'      => [
        'level' => 'debug',
        'file'  => __DIR__ . '/log/parkingplatform.log',
    ],
    'v'        => "2.0",
    'cid'      => '00001',
    'user'     => "testUser",
    'password' => '00001',
    'signKey'  => 'nbcxvghkod',
]);

//车辆进场信息-查询相似车辆
echo json_encode($app->queryCarByCarNo->get([
        "parkCode" => "停车场编号",
        "carNo"    => "车牌号"
    ]), 256) . PHP_EOL;

//按车牌生成订单
echo json_encode($app->createOrder->get([
        "businesserCode" => "商户编号",
        "parkCode"       => "停车场编号",
        "orderType"      => "订单类型填写固定值：VNP",
        "carNo"          => "车牌号",
    ]), 256) . PHP_EOL;
//按车牌生成订单（不限定车场）
echo json_encode($app->createOrder->get([
        "carNo" => "车牌号",
    ]), 256) . PHP_EOL;

//支付结果通知
echo json_encode($app->createOrder->get([
        "orderNo"     => "生成订单时的订单编号",
        "tradeStatus" => "交易状态，0:成功，1:失败",
        "isCallBack"  => "是否需回调，0:否，1:是",
        "notifyUrl"   => "回调地址-非必填",
    ]), 256) . PHP_EOL;
//订单结果查询接口
echo json_encode($app->createOrder->get([
        "orderNo" => "生成订单时的订单编号",
    ]), 256) . PHP_EOL;


