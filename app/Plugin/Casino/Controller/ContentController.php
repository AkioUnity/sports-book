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
        $listGames=$this->client->listGames()['Games'];
        $games=array();
        $brands=array();
        foreach ($listGames as $key => $value){
            $games[]=array('Id'=>$value['Id'],'SectionId'=>$value['SectionId']);
            if (!in_array($value['SectionId'],$brands))
                $brands[]=$value['SectionId'];

        }
        $this->set('games', $games);
        $this->set('brands', $brands);
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
            'Id'=>$this->Auth->user('username'),
            'Nick'=>$this->Auth->user('username'),
            'BankGroupId'=>'planet_TND'
        );
        $this->client->setPlayer($player);
//        die;
    }

    public function changeBalance()
    {
        $player=array(
            'PlayerId'=>$this->Auth->user('username'),
            'Amount'=>(int)($this->Auth->user('balance'))
        );
        $this->client->changeBalance($player);
    }

    //http://dev.planet1x2.com/eng/casino/content/createSession?PlayerId=admin&GameId=jacks_or_better
    public function createSession()
    {
//        $this->set_player();
//        $this->changeBalance();

        $session=array(
            'PlayerId'=>$this->Auth->user('username'),
            'GameId'=>$this->request->query['GameId']
        );
        $this->redirect($this->client->createSession($session)['SessionUrl']);
//        print_r($this->client->createSession($session));
        die;
    }

    //http://dev.planet1x2.com/eng/casino/content/createDemoSession?GameId=jacks_or_better
    public function createDemoSession()
    {
        $demoSession=array(
            'GameId'=>$this->request->query['GameId'],
            'BankGroupId'=>'planet_TND',
            'StartBalance'=>10000
        );
        $this->redirect($this->client->createDemoSession($demoSession)['SessionUrl']);
        die;
    }

    //http://dev.planet1x2.com/eng/casino/content/getBalance?PlayerId=admin
    public function getBalance()
    {
//        print_r($this->Auth->user());
        $player=array(
            'PlayerId'=>$this->request->query['PlayerId']
        );
        echo($this->client->getBalance($player)['Amount']);
        die;
    }
//http://dev.planet1x2.com/eng/casino/content/changeBalance0?PlayerId=admin&Amount=25
    public function changeBalance0()
    {
        $player=array(
            'PlayerId'=>$this->request->query['PlayerId'],
            'Amount'=>(int)($this->request->query['Amount'])
        );
        print_r($this->client->changeBalance($player));
        die;
    }

    //http://dev.planet1x2.com/eng/casino/content/listSessions
    public function listSessions()
    {
        print_r($this->client->listSessions());
        die;
    }
}