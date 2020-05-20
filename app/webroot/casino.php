<?php
use outcomebet\casino25\api\client\Client;
require __DIR__.'/../../vendor/autoload.php';
$client = new Client(array(
    'url' => 'https://api.casinovegas.org/v1/',
    'sslKeyPath' => __DIR__.'/../../ssl/apikey.pem',
));

$bankGroup=array('Id'=>"planet_TND",'Currency'=>'TND');
var_export($client->setBankGroup($bankGroup));
die;
//https://planet1x2.com/casino.php?GameId=super_hot_20_html
if (isset($_GET["GameId"])){
    $demoSession=array(
        'GameId'=>$_GET["GameId"],
        'BankGroupId'=>'planet',
        'StartBalance'=>10000
    );
    print_r($client->createDemoSession($demoSession));
}
else{
    print_r($client->listGames());
}