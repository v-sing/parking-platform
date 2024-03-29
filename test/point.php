<?php
require '../vendor/autoload.php';


$app = \VSing\ParkingPlatform\Factory::Point([
    'http'         => [
        'timeout'  => 100,
        'base_uri' => 'https://openapi.xxx.cn'
    ],
    'openid'       => 'xxx',
    'secret'       => 'xx',
    'user_account' => 'xx'
]);

//获取店铺列表
echo json_encode($app->store->get([
        'pageIndex' => 0,
        'pageSize'  => 200
    ]), 256) . PHP_EOL;

//获取用户余额
echo json_encode($app->memberInfo->get(['cardId' => 'xx']), 256) . PHP_EOL;
echo json_encode($app->coin->get(['cardId' => 'xx', 'point' => -2, 'meno' => '备注', 'isSendMsg' => 'false']), 256) . PHP_EOL;
