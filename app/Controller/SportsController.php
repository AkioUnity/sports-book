<?php
/**
 * Handles Deposits
 *
 * Handles Deposits Actions
 *
 * @package    Deposits
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');
App::uses('BethHelper', 'View/Helper');

class SportsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Sports';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Sport', 'Country', 'League', 'Content.Slide');

    /**
     * An array containing the names of helpers this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array('Beth');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     * Admin view scaffold functions
     *
     * @param int $id - view id
     *
     * @return void
     */
    public function admin_view($id = -1)
    {
        $model = $this->Sport->getItem($id);
        $countries = array();

        if ($model != null) {
            $this->Paginator->settings  =   array($this->League->name => array(
                "conditions"    =>  array(
                    'League.sport_id' => $id
                ),
                "order" =>  array(
                    'League.active' => 'DESC'
                )
            ));

            $data    =   $this->Paginator->paginate( $this->League->name );

            foreach ($data AS $item) {
                $countries[$item["Country"]["id"]] = $this->Sport->League->Country->getItem($item["Country"]["id"]);
            }

            $this->set('data', $data);
            $this->set('countries', $countries);
        }

        $this->set('model', $model);
    }

    /**
     * Displays events
     *
     *
     * @return void
     */
    public function display()
    {
        if ($this->theme == "Redesign") {
            $this->layout = "inside";
        }
        
        $this->Sport->bindTranslation(array('name' => 'SportI18n'));
        $Sport = isset($this->request->params['pass'][0]) ? $this->Sport->getItem($this->request->params['pass'][0], array('SportI18n' => array('conditions' => array('SportI18n.locale' => 'eng'))), 0) : array();

        $LeagueId = isset($this->request->params['pass'][1]) ? $this->request->params['pass'][1] : null;

        if (!is_array($Sport) || empty($Sport)) {
            $this->redirect('/');
            exit;
        }

        $result = array();

        $League = $this->Sport->League->getLeagues($Sport["Sport"]["id"], $LeagueId);

        $bet_oder = array(
            Bet::BET_TYPE_MATCH_RESULT,
            Bet::BET_TYPE_MATCH_WINNER,
            Bet::BET_TYPE_HEAD_TO_HEAD_CHAMPIONSHIP,
            Bet::BET_TYPE_OUTRIGHT_WINNER,
            Bet::BET_TYPE_WINNER,
            Bet::BET_TYPE_OUTRIGHT_CHAMPION,
            Bet::BET_TYPE_DRIVERS_WINNER,
            Bet::BET_TYPE_CHAMPIONSHIP_WINNER,
            Bet::BET_TYPE_PLACE_1_3,
            Bet::BET_TYPE_DRIVERS_CHAMPIONSHIP_WINNER,
            Bet::BET_TYPE_CONSTRUCTORS_CHAMPIONSHIP
        );

        foreach ($League AS $item) {
            if (!isset($result[$item["League"]["id"]])) {
                $item["League"]["order_id"] = array();
                $result[$item["League"]["id"]] = $item["League"];
            }

            if (!isset($result[$item["League"]["id"]]["Event"][$item["Event"]["id"]])) {
                $item["Event"]["order_id"] = 6;
                $result[$item["League"]["id"]]["Event"][$item["Event"]["id"]] = $item["Event"];
            }

            if (!isset($result[$item["League"]["id"]]["Event"][$item["Event"]["id"]]["Bet"][$item["Bet"]["id"]])) {
                $oder = array_search($item["Bet"]["type"], $bet_oder);

                $result[$item["League"]["id"]]["order_id"][] = strtotime($item["Event"]["date"]);
                $result[$item["League"]["id"]]["Event"][$item["Event"]["id"]]["order_id"] = strtotime($item["Event"]["date"]);


                $item["Bet"]["order_id"] = $oder;
                $result[$item["League"]["id"]]["Event"][$item["Event"]["id"]]["Bet"][$item["Bet"]["id"]] = $item["Bet"];
            }

            if (!isset($result[$item["League"]["id"]]["Event"][$item["Event"]["id"]]["Bet"][$item["Bet"]["id"]]["BetPart"][$item["BetPart"]["id"]])) {
                $result[$item["League"]["id"]]["Event"][$item["Event"]["id"]]["Bet"][$item["Bet"]["id"]]["BetPart"][$item["BetPart"]["id"]] = $item["BetPart"];
            }
        }

        unset($League);

        // order bet
        foreach ($result AS $i => $item) {
            foreach ($item["Event"] AS $e => $Event) {
                if (count($Event["Bet"]) > 1) {
                    usort($result[$i]["Event"][$e]["Bet"], function($a, $b) USE ($bet_oder) {
                        return $a["order_id"] > $b["order_id"];
                    });
                }
            }
        }

        // Order Events in league
        foreach ($result AS $i => $item) {
            if (count($item["Event"]) > 1) {
                usort($result[$i]["Event"], function($a, $b) USE ($bet_oder) {
                    return $a["order_id"] > $b["order_id"];
                });
            }
        }

        // Order Leagues
        usort($result, function($a, $b) USE ($bet_oder) {
            return max($a["order_id"]) > max($b["order_id"]);
        });


        $this->set('data', $result);
        $this->set('Sport', $Sport);
        $this->set('League', $LeagueId);
        $this->set('slides', $this->Slide->getSlides());
    }

    /**
     * Returns sports menu
     *
     * @return mixed
     * @throws ForbiddenException
     */
    public function getSports()
    {
        if (!$this->request->is('ajax')) {
            exit;
        }

        if ($this->theme != "Redesign") {
            if(isset($this->request->query['countryId']) && is_numeric($this->request->query['countryId'])) {
                $countryId = (int) $this->request->query['countryId'];
            }else{
                $countryId = null;
            }

            $SportsMenu = $this->Sport->getSports($countryId, false);

            $this->set('SportsMenu', $SportsMenu);

            return $SportsMenu;
        }


        $cacheKey = implode("_", array(__CLASS__, __FUNCTION__));

        $Menu = Cache::read($cacheKey);

        if (!$Menu) {
            $Leagues = $this->League->find('all', array(
                'contain'       =>  array(
                    'Country'   =>  array(
                        'fields'        =>  array(
                            'Country.id'
                        ),
                        'conditions'    => array(
                            'Country.active'     =>  1
                        )
                    ),
                    'Sport'   =>  array(
                        'fields'        =>  array(
                            'Sport.id'
                        ),
                        'conditions'    => array(
                            'Sport.active'     =>  1
                        )
                    )
                ),
                'conditions'    => array(
                    'League.active'     =>  1
                )
            ));

            $Menu   =   array();

            foreach ($Leagues AS $i => $League) {
                $Leagues[$i]["Sport"] = current($this->Sport->find('first', array(
                    'conditions'    => array(
                        "Sport.id"          =>  $League["Sport"]["id"],
                        'Sport.active'      =>  1,
                    ),
                    'contain'       =>  array(),
                    'order'         =>  array('Sport__i18n_name ASC')
                )));

                $Leagues[$i]["Country"] = current($this->Country->find('first', array(
                    'conditions'    => array(
                        "Country.id"          =>  $League["Country"]["id"],
                        'Country.active'      =>  1
                    ),
                    'contain'       =>  array(),
                    'order'         =>  array('Country__i18n_name ASC')
                )));
            }

            foreach ($Leagues AS $League) {
                $Menu[$League["Sport"]["id"]]["Sport"] = $League["Sport"];
                $Menu[$League["Sport"]["id"]]["Country"][$League["Country"]["id"]] = $League["Country"];
            }

            foreach ($Leagues AS $League) {
                $Menu[$League["Sport"]["id"]]["Country"][$League["Country"]["id"]]["League"][$League["League"]["id"]] = $League["League"];
            }

            Cache::write($cacheKey, $Menu, '2 min');
        }

        $this->set('Menu', $Menu);

        return $Menu;
    }
}