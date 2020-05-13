<?php

App::uses('CasinoAppController', 'Casino.Controller');

use outcomebet\casino25\api\client\Client;
require __DIR__.'/../../../../vendor/autoload.php';

class ContentController extends CasinoAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Content';
    public $client;
    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('User');

    public function beforeFilter()
    {
        $this->client = new Client(array(
            'url' => 'https://api.casinovegas.org/v1/',
            'sslKeyPath' => __DIR__.'/../../../../ssl/apikey.pem',
        ));
        parent::beforeFilter();
    }

    public function index() 
    {
        $this->layout = 'new_casino';
        $this->set('games', $this->client->listGames()['Games']);
        /*
        $this->set('data', $result);
        $this->set('Sport', $Sport);
        $this->set('League', $LeagueId);
        $this->set('slides', $this->Slide->getSlides());
        */
    }
//http://dev.planet1x2.com/eng/casino/content/api
    public function api()
    {
        print_r($this->client->listGames()['Games']);
        die;
    }
//http://dev.planet1x2.com/eng/casino/content/set_player
    public function set_player()
    {
        $player=array(
            'Id'=>'admin',
            'Nick'=>'admin',
            'BankGroupId'=>'planet'
        );
        print_r($this->client->setPlayer($player));
        die;
    }
    //http://dev.planet1x2.com/eng/casino/content/getBalance?PlayerId=admin
    public function getBalance()
    {
        $player=array(
            'PlayerId'=>$this->request->query['PlayerId']
        );
        print_r($this->client->getBalance($player));
        die;
    }
    //http://dev.planet1x2.com/eng/casino/content/createSession?PlayerId=admin&GameId=jacks_or_better
    public function createSession()
    {
        $session=array(
            'PlayerId'=>$this->request->query['PlayerId'],
            'GameId'=>$this->request->query['GameId']
        );
        print_r($this->client->createSession($session));
        die;
    }

    //http://dev.planet1x2.com/eng/casino/content/createDemoSession?GameId=jacks_or_better
    public function createDemoSession()
    {
        $demoSession=array(
            'GameId'=>$this->request->query['GameId'],
            'BankGroupId'=>'planet',
            'StartBalance'=>10000
        );
        $this->redirect($this->client->createDemoSession($demoSession)['SessionUrl']);
        die;
    }
}