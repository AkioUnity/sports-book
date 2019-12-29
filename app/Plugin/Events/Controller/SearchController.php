<?php
/**
 * Front Events Search Controller
 *
 * Handles Events Search Actions
 *
 * @package    Events
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2014 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('BethHelper', 'View/Helper');
App::uses('EventsAppController', 'Events.Controller');

class SearchController extends EventsAppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Search';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Event', 'Country');

    /**
     * An array containing the names of helpers this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array('Beth');

    public function index()
    {
        $this->layout = "inside";

        if (empty($this->request->query['id']) && empty($this->request->query['name'])) {
            $this->App->setMessage(__('Sorry, no search results found', true), 'error');
            return $this->render('search/no-search-results');
        }

        $data = array('EventsLeft' => array(), 'EventsRight' => array());

        if(isset($this->request->query['name']) && $this->request->query['name'] != "" && is_numeric($this->request->query['name']))
        {
            $event = $this->Event->findEvent($this->request->query['name']);

            if (!empty($event)) {
                $Sport = $this->Event->League->Sport->getSport($event["League"]["sport_id"]);
                $event["Sport"] = $Sport["Sport"];
            }

            $this->set('searchCriteria', $this->request->query['name']);

            $events = !empty($event) ? array( $event ) : $event;
        }

        if(isset($this->request->query['name']) && $this->request->query['name'] != "" && !is_numeric($this->request->query['name']))
        {
            $this->set('searchCriteria', $this->request->query['name']);

            $events = $this->Event->findEvents($this->request->query['name']);
        }

        if(empty($events)) {
            $this->App->setMessage(__('Sorry, no search results found', true), 'error');
            return $this->render('search/no-search-results');
        }

        $events = $this->Event->Bet->BetPart->assignBetPartsToEvents($events);

        /** @var BethHelper $BethHelper */
        $BethHelper = new BethHelper(new View());

        $leftEvents = array();
        $betTypes = array();

        $mainSportBetTypes = array('versus-with-draw', 'versus', '12', '1x2');

        foreach ($events AS $eventIndex => $event) {
            foreach ($event['Bet'] AS $betIndex => $bet) {
                $betType = $BethHelper->betToPath($bet['type']);
                if (in_array($bet['type'], $mainSportBetTypes)) {
                    $betTypes[$eventIndex] = $bet['type'];
                    $Teams = explode(" - ", $event["Event"]["name"]);
                    $event["Event"]["Team"][1] = trim(current($Teams));
                    $event["Event"]["Team"][2] = trim(end($Teams));
                    $Sport = $this->Event->League->Sport->getSport($event["League"]["sport_id"]);
                    $leftEvents[$betType][] = array(
                        'Event'     =>  $event['Event'],
                        'League'    =>  $event['League'],
                        'Sport'     =>  $Sport['Sport'],
                        'Bet'       =>  $events[$eventIndex]['Bet'][$betIndex]
                    );
                    unset($events[$eventIndex]["Bet"][$betIndex]);
                }
            }
            if (empty($events[$eventIndex]["Bet"])) {
                unset($events[$eventIndex]);
            }
        }

        $data['EventsLeft'] = $leftEvents;
        $data['EventsRight'] = $events;

        if(empty($data['EventsLeft']) && empty($data['EventsRight'])) {
            $this->App->setMessage(__('No search results found', true), 'error');
            return $this->render('search/no-search-results');
        }

        $this->set('data', $data);
        $this->set('events', $data["EventsRight"]);

        return $this->render('search/display-search-results');
    }
}
