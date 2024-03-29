<?php


//https://www.jieshunglobal.com/

//https://www.jieshunglobal.com


require '../vendor/autoload.php';


$app = \VSing\ParkingPlatform\Factory::Parking([
    'http' => [
        'timeout' => 100,
        'base_uri' => 'https://www.jieshunglobal.com/jsaims/as'
    ]
]);


    $result=$app->queryParkSpace->get([
'version'=>'2.0'
    ]);

    var_dump($result);exit;
