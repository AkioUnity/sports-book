<?php
use outcomebet\casino25\api\client\Client;
require __DIR__.'/../../vendor/autoload.php';
$client = new Client(array(
    'url' => 'https://api.casinovegas.org/v1/',
    'sslKeyPath' => __DIR__.'/../../ssl/apikey.pem',
));

//$bankGroup=array('Id'=>"new_bank_group",'Currency'=>'EUR');
//var_export($client->setBankGroup($bankGroup));

//https://planet1x2.com/casino.php?GameId=super_hot_20_html
if (isset($_GET["GameId"])){
    $demoSession=array(
        'GameId'=>$_GET["GameId"],
        'BankGroupId'=>'new_bank_group',
        'StartBalance'=>10000
    );
    print_r($client->createDemoSession($demoSession));
}
else{
    print_r($client->listGames());
}