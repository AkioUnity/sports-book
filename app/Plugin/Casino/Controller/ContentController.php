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
            'url' => 'https://api.c27.games/v1/',
            'sslKeyPath' => __DIR__.'/../../../../ssl/apikey.pem',
        ));
        parent::beforeFilter();
    }

    public function index()
    {
        $name=$this->request->query('brand');
        $listGames=$this->client->listGames()['Games'];
        $games=array();
        $brands=array();
        foreach ($listGames as $key => $value){
            if (!$name){
                if (in_array($value['Id'],$this->mainGames))
                    $games[]=array('Id'=>$value['Id'],'SectionId'=>$value['SectionId']);
            }
            else if ($value['SectionId']==$name)
                $games[]=array('Id'=>$value['Id'],'SectionId'=>$value['SectionId']);
            if (!in_array($value['SectionId'],$brands))
                $brands[]=$value['SectionId'];
        }//        print_r($games);

        $this->set('games', $games);
        $this->set('brands', $brands);
        $this->layout = 'new_casino';
//        print_r($games);
//        $this->brand('netent');
    }

    public function brand($name)
    {

        $listGames=$this->client->listGames()['Games'];
        $games=array();
        $brands=array();
        foreach ($listGames as $key => $value){
            if ($value['SectionId']==$name)
                $games[]=array('Id'=>$value['Id'],'SectionId'=>$value['SectionId']);
            if (!in_array($value['SectionId'],$brands))
                $brands[]=$value['SectionId'];
        }//        print_r($games);

        $this->set('games', $games);
        $this->set('brands', $brands);
        $this->layout = 'new_casino';
//        $this->viewPath = "layout";
        $this->render('Layouts/new_casino','new_casino');
//        $this->render('/app/View/Themed/Design/Layouts/new_casino');
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
        if (!CakeSession::check('casino.balance')){
            $player=array(
                'PlayerId'=>$this->Auth->user('username')
            );
            $casinoBalance=$this->client->getBalance($player)['Amount'];
            $player_balance=$this->Auth->user('balance')*100;
            if ($casinoBalance!=$player_balance){
                $player=array(
                    'PlayerId'=>$this->Auth->user('username'),
                    'Amount'=>(int)($player_balance-$casinoBalance)
                );
                $this->client->changeBalance($player);
            }
        }
        else{
//            $diffBalance=CakeSession::read('Auth.User.balance')-CakeSession::read('old.balance')-CakeSession::read('changed.casino.balance');
            $diffBalance=CakeSession::read('Auth.User.balance')*100-CakeSession::read('casino.balance');
            if ($diffBalance!=0){
                $player=array(
                    'PlayerId'=>$this->Auth->user('username'),
                    'Amount'=>(int)($diffBalance)
                );
                $this->client->changeBalance($player);

                CakeLog::write('casino', 'ContentController------diff '.$diffBalance.' User.Balance '.CakeSession::read('Auth.User.balance').' old.blalance '.CakeSession::read('old.balance').' changed.casino.balance '.CakeSession::read('changed.casino.balance'));
            }
        }

        CakeSession::write('old.balance', CakeSession::read('Auth.User.balance'));
        CakeSession::write('casino.balance', CakeSession::read('Auth.User.balance')*100);

        CakeLog::write('casino', 'old.balance '.CakeSession::read('old.balance').'    casino.balance '.CakeSession::read('casino.balance'));

//                $this->User->setBalance($this->Auth->user('id'),$balance);
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
        print_r($this->client->getBalance($player));
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

    public $mainGames=array('bookofradeluxe_gt_html',
        'bookofraclassic_gt_html',
        'sizzling_hot_classic_html',
        'faust_gt_html',
        'bookofradeluxe6_gt_html',
        'jack_hammer_touch',
        'starburst_touch',
        'goldenark_gt_html',
        'lordoftheocean_gt_html',
        'book_of_ra_classic_html',
        'luckyladyscharmdeluxe_gt_html',
        'sizzlinghotdeluxe_gt_html',
        'cool_diamonds_original',
        'hot_scatter_original',
        'aztec_secret_original',
        'lovely_lady_original',
        'dolphinspearldeluxe_gt_html',
        'jack_hammer_2_touch',
        'reel_rush_html',
        'jack_and_the_beanstalk_touch',
        'fruitshop_christmas',
        'hot_twenty_original',
        'fruit_spin',
        'dolphinspearlclassic_gt_html',
        'allways_fruits_original',
        'redlady_gt_html',
        'joker_pro',
        'a_book_of_aztec_original',
        'joh_wzdn',
        'wild_shark_original',
        'casinova_original',
        'dazzlingdiamonds_gt_html',
        'wolf_moon_original',
        'lucky_coin_original',
        'pharaohstomb_gt_html',
        'book_of_fortune_original',
        'bells_on_fire_original',
        'lucky_bells_original',
        'dragons_kingdom_original',
        'mysticsecrets_gt_html',
        'blackjack_double_exposure',
        'diamond_cats_original',
        'fruitshop_touch',
        'motr_wzdn',
        'pharaons_gold2_classic_html',
        's777_wzdn',
        'arising_phoenix_original',
        'choy_sun_doa_html',
        'twin_spin_touch',
        'columbusdeluxe_gt_html',
        'scruffy_duck',
        'lf_wzdn',
        'bells_on_fire_hot_original',
        'hot_star_original',
        'justjewelsdeluxe_gt_html',
        'gorilla_gt_html',
        'wild_wild_west',
        'hot_seven_original',
        'dynastyofra_gt_html',
        'plentyontwenty_gt_html',
        'sizzlinghot6extragold_gt_html',
        'roaringforties_gt_html',
        'mf_wzdn',
        'alwayshotdeluxe_gt_html',
        'jb_wzdn',
        'pyramid_new',
        'admiral_nelson_original',
        'roulette_touch',
        'the_money_game_classic_html'
    );
}