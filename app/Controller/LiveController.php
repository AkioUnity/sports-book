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
App::uses('Bet', 'Model');

class LiveController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Live';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Live', 'Country', 'League', 'Content.Slide', 'Event', 'Bet', 'Sport');

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

        // Allow all actions. CakePHP 2.0
        $this->Auth->allow(array('select_sport_league', 'display_sports', 'display_event'));
    }

    public function admin_index($conditions = array(), $model = null)
    {
        $sport = array();
        $result = array();

        $this->Sport->locale = 'eng';

        $this->League->setAdmin(true);

        foreach ($this->common() AS $data) {
            $sport[$data["sport_id"]] = !isset($sport[$data["sport_id"]]) ? $this->Sport->getItem($data["sport_id"]) : $sport[$data["sport_id"]];
            $result[$data["sport_id"]]['sport_id'] = $data["sport_id"];
            $result[$data["sport_id"]]['data'][] = $data;
        }

        usort($result, function($a, $b) {
            return $a["sport_id"] > $b["sport_id"];
        });

        $this->Sport->locale = Configure::read('Config.language');


        foreach ($result AS $rId => $leagues) {
            foreach ($leagues["data"] AS $lId => $League) {
                foreach ($League["Event"] AS $eId => $Event) {
                    $result[$rId]["data"][$lId]["Event"][$eId]["Bet"] = $this->Event->Bet->find('all', array(
                        'contain'       =>  array(
                            'BetPart'   =>  array(
                                'conditions'    =>  array(
                                    'BetPart.state'    => 'active',
                                )
                            )
                        ),
                        'conditions'    =>  array(
                            'Bet.event_id'  =>  $Event["id"]
                        )
                    ));
                }
            }
        }
        $this->set('data', $result);
        $this->set('sports', $this->Sport->getList());
        $this->set('Sport', $sport);
    }

    public function select_sport_league()
    {
        $sport = array();
        $result = array();

        $this->Sport->locale = 'eng';
        foreach ($this->common() AS $data) {
            $sport[$data["sport_id"]] = !isset($sport[$data["sport_id"]]) ? $this->Sport->getItem($data["sport_id"]) : $sport[$data["sport_id"]];
            $result[$data["sport_id"]]['sport_id'] = $data["sport_id"];
            $result[$data["sport_id"]]['data'][] = $data;
        }

        usort($result, function($a, $b) {
            return $a["sport_id"] > $b["sport_id"];
        });

        $this->Sport->locale = Configure::read('Config.language');

        $this->view = 'select_sport_league';

        $this->set('data', $result);
        $this->set('sports', $this->Sport->getList());
        $this->set('Sport', $sport);
    }

    /**
     * Displays events
     *
     *
     * @return void
     */
    public function display_sports()
    {
        if ($this->theme == "Redesign") {
            $this->layout = "inside";
        }

        $this->Sport->bindTranslation(array('name' => 'SportI18n'));

        $Sport = isset($this->request->params['pass'][0]) ? $this->Sport->getItem($this->request->params['pass'][0], array('SportI18n' => array('conditions' => array('SportI18n.locale' => 'eng'))), 0) : array();

        $LeagueId = isset($this->request->params['pass'][1]) ? $this->request->params['pass'][1] : null;

        if (!is_array($Sport) || empty($Sport)) {
            return $this->select_sport_league();
        }

        $League = $this->Sport->League->getLiveLeagues($Sport["Sport"]["id"], $LeagueId);

        $this->set('data', $League);
        $this->set('Sport', $Sport);
        $this->set('League', $LeagueId);
    }

    public function display_event()
    {
        if (count($this->request->params['pass']) < 1) {
            exit;
        }

        $Event = current($this->Event->Bet->BetPart->assignBetPartsToEvents(array($this->Event->findLiveEvent($this->request->params['pass'][0]))));

        if (empty($Event)) {
            $this->redirect('/' . Configure::read('Config.language'), null, true);
        }

        $Sport = $this->Event->League->Sport->getItem($Event['League']["sport_id"]);
        $Event["Sport"] = isset($Sport["Sport"]) ? $Sport["Sport"] : array();

        $EventName = explode(" - ", $Event["Event"]["name"]);
        $Event["Event"]["firstTeam"] =  trim($EventName[0]);
        $Event["Event"]["secondTeam"] =  trim($EventName[1]);


        $bet_oder = array(
            1   =>  Bet::BET_TYPE_MATCH_RESULT,
            2   =>  Bet::BET_TYPE_MATCH_WINNER,
            3   =>  Bet::BET_TYPE_DOUBLE_CHANCE,
            4   =>  Bet::BET_TYPE_UNDER_OVER
        );

        $order_id = 5;

        usort($Event["Bet"], function($a, $b) USE ($bet_oder, $order_id) {
            $a_bet_oder_id = array_search($a["type"], $bet_oder);
            $b_bet_oder_id = array_search($b["type"], $bet_oder);

            $a_bet_oder_id = $a_bet_oder_id == false ? $order_id : $a_bet_oder_id;
            $b_bet_oder_id = $b_bet_oder_id == false ? $order_id : $b_bet_oder_id;
            return $a_bet_oder_id > $b_bet_oder_id;
        });


        $this->set('slides', $this->Slide->getSlides());
        $this->set('Event', $Event);
    }


    private function common()
    {
        $League = $this->League->getLiveLeagues();
        $result = array();

        foreach ($League AS $item) {
            if (!isset($result[$item["League"]["id"]])) {
                $item["League"]["order_id"] = array();
                $result[$item["League"]["id"]] = $item["League"];
            }

            foreach($item["Event"] AS $event) {

                if (!isset($result[$item["League"]["id"]]["Event"][$event["id"]])) {
                    $item["Event"]["order_id"] = 6;

                    $result[$item["League"]["id"]]["Event"][$event["id"]] = $event;
                }

            }
        }

        unset($League);

        return $result;
    }
}