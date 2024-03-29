<?php


//https://www.jieshunglobal.com/

//https://www.jieshunglobal.com


require '../vendor/autoload.php';


$app = \VSing\ParkingPlatform\Factory::Parking([
    'http'     => [
        'timeout'  => 100,
        'base_uri' => 'https://www.jieshunglobal.com/jsaims/as'
    ],
    'v'        => "2.0",
    'cid'      => '00001',
    'user'     => "testUser",
    'password' => '888888',
    'signKey'  => 'nbcxvghkod',
]);

$result = $app->queryParkSpace->get([
]);

var_dump($result->getBodyContents());
exit;
