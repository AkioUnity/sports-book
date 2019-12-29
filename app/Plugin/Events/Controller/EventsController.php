<?php
/**
 * Front Events Controller
 *
 * Handles Events Actions
 *
 * @package    Events
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('EventsAppController', 'Events.Controller');
App::uses('BethHelper', 'View/Helper');

class EventsController extends EventsAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Events';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Event', 'Country', 'Content.Slide');

    /**
     * An array containing the names of helpers this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array('Beth', 'Language');

    public function beforeFilter()
    {
        $this->Auth->allow('rangeEvents', 'display');

        parent::beforeFilter();
    }

    public function admin_index($conditions = array(), $model = null)
    {
        if (is_numeric($conditions)) {
            $conditions = array("Event.league_id" => $conditions);
        } else {
            $conditions = array();
        }
        if (isset($_GET["getMenu"])) {
            exec('php ' . ROOT . '/' . 'app/Console/cake.php Cron5Min execute');
            $this->Admin->setMessage(__('Renew  successfully scheduled', true), 'success');
            $this->redirect(array('language' => Configure::read('Config.language'), 'plugin' => null, 'controller' => 'events', 'admin' => 'admin', 'action' => 'admin_index'), null, true);
        }
        $data = parent::admin_index($conditions, $model);

        $status = array(
            Event::EVENT_STATUS_CANCELLED   =>  __("Cancelled"),
            Event::EVENT_STATUS_DELETED   =>  __("Deleted"),
            Event::EVENT_STATUS_FINISHED   =>  __("Finished"),
            Event::EVENT_STATUS_IN_PROGRESS   =>  __("In progress"),
            Event::EVENT_STATUS_INTERRUPTED   =>  __("Interrupted"),
            Event::EVENT_STATUS_NOT_STARTED   =>  __("Not Started"),
            Event::EVENT_STATUS_UNKNOWN   =>  __("Unknown"),
        );

        $eventLink = Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'events', 'action' => 'admin_view'), true);

        foreach ($data AS $item => $value) {
            $data[$item]["Event"]["status"] = isset($status[$data[$item]["Event"]["status"]]) ? $status[$data[$item]["Event"]["status"]] : __("Unknown");
            $data[$item]["Event"]["type"] = __("Prematch");
            $data[$item]["Event"]["league_id"] = sprintf('<a href="/eng/admin/events/events/index/%d">%s</a>', $value["League"]["id"], $value["League"]["name"]);
            $data[$item]["Event"]["name"] = sprintf('<a href="%s/%d">%s</a>', $eventLink, $value["Event"]["id"], $value["Event"]["name"]);
        }

        $this->set('data', $data);
    }

    function admin_results($eventId) {
        if (isset($eventId)) {
            $events = $this->Event->find('all');
        } else {
            $events = $this->Event->find('all');
        }
        $this->set('events', $events);
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
        if (empty($this->request->data['Event']['id'])) {
            $event = $this->Event->getItem($id);
            $data = $this->Event->getBets($id);

            foreach ($data AS $index => $bet) {
                foreach ($bet["BetPart"] AS $indexBetPart =>  $betPart) {
                    $tickets = $this->Event->Bet->BetPart->TicketPart->find('all', array(
                        'contain'       =>  array('Ticket'),
                        'fields'        =>  array('Ticket.amount'),
                        'conditions'    =>  array(
                            'TicketPart.bet_part_id'    => $betPart['id']
                        )
                    ));
                    $betPart["tickets_count"] = count($tickets);
                    $betPart["tickets_stake"] = array_sum(array_map(function($ticket){
                        return $ticket["Ticket"]["amount"];
                    }, $tickets));


                    $data[$index]["BetPart"][$indexBetPart] = $betPart;
                }
            }

            $this->set('event', $event);
            $this->set('data', $data);
        } else {
            //its search
            $bet = $this->Event->Bet->getItem($this->request->data['Event']['id']);
            if (!empty($bet)) {
                $event['Event'] = $bet['Event'];
                $data = $this->Event->getBets($event['Event']['id']);
                $this->set('event', $event);
                $this->set('data', $data);
            }
        }
        if (!isset($this->request->params['pass'][0])) {
            if (isset($event['Event']['id'])) {
                $this->request->params['pass'][0] = $event['Event']['id'];
            } else {
                $this->request->params['pass'][0] = 0;
            }
        }
        $this->set('tabs', $this->Event->getTabs($this->request->params));
    }

    /**
     * Admin Add scaffold functions
     *
     * @param null $id - parentId
     *
     * @return void
     */
    public function admin_add($sportId = null, $leagueId = null)
    {
        if (is_null($sportId)) {
            $this->redirect(array('language' => Configure::read('Config.language'), 'plugin' => null, 'controller' => 'sports', 'admin' => 'admin', 'action' => 'admin_index'), null, true);
        }
        if (isset($this->request->data["Event"]) && !empty($this->request->data["Event"])) {
            $this->request->data["Event"]["import_id"] = 0;
            $this->request->data["Event"]["status"] = 0;
            $this->request->data["Event"]["type"] = 1;
            $this->request->data["Event"]["result"] = "";
            $this->request->data["Event"]["feed_type"] = "Manual";
            $this->request->data["Event"]["date"] = !empty($this->request->data["Event"]["date"]) ? gmdate("Y-m-d H:i:s", strtotime($this->request->data["Event"]["date"])) : "";
            $this->request->data["Event"]["league_id"] = is_null($leagueId) ? $this->request->data["Event"]["league_id"] : $leagueId;

            exec('php ' . ROOT . '/' . 'app/Console/cake.php Cron5Min execute');

        }
        parent::admin_add(null);

        $this->set('fields', $this->Event->getAdd($sportId, $leagueId));
    }


    /**
     * Admin print
     *
     * @param null $SportId  - SportId
     * @param null $dateFrom - Date From
     * @param null $dateTo   - Date to
     */
    public function admin_print($SportId = null, $dateFrom = null, $dateTo = null)
    {
        App::uses('Bet', 'Model');

        ini_set('max_execution_time', 0);

        $this->__getModel();

        $Sports = array(
            'select'    =>  __('Select Sport')
        );

        foreach ($this->Event->League->Sport->getSportsList() AS $sport) {
            $Sports[$sport['id']] = $sport['name'];
        }

        if ($dateFrom == null) {
            $dateFrom = gmdate('Y-m-d');
        } else {
            $dateFrom = gmdate('Y-m-d', strtotime($dateFrom));
        }

        if ($dateTo == null) {
            $dateTo = gmdate('Y-m-d');
        } else {
            $dateTo = gmdate('Y-m-d', strtotime($dateTo));
        }

        if ($SportId != null) {

            $this->layout = false;

            /** Create new empty worksheet */
            $this->PhpExcel->createWorksheet();

            /** Set global font settings */
            $this->PhpExcel->getDefaultStyle()
                ->getFont()
                ->setName('Sylfaen')
                ->setSize(8);

            /** Prepare logo area */
            $this->PhpExcel->getActiveSheet()->mergeCells('C1:D1');
            $this->PhpExcel->getActiveSheet()
                ->getStyle('C1:D1')
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor();


            // Create new picture object
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName(Configure::read('Settings.websiteName'));
            $objDrawing->setDescription(Configure::read('Settings.websiteName'));
            $objDrawing->setPath(APP . 'View' . DS .  'Themed' . DS . $this->theme . DS  . WEBROOT_DIR . DS . 'img' . DS . 'printing_logo.png');
            $objDrawing->setCoordinates('C1');
            $objDrawing->setWidth(300);
            $objDrawing->setHeight(38);
            $objDrawing->setWorksheet($this->PhpExcel->getActiveSheet());

            $this->PhpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
            $this->PhpExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(50);

            $this->PhpExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->PhpExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            /** /Prepare logo area */

            /** Prepare date area */
            $this->PhpExcel->getActiveSheet()->mergeCells('M1:X1');
            $this->PhpExcel->getActiveSheet()->fromArray(array(__('Date %s - %s', $dateFrom, $dateTo)), null, 'M1');
            $this->PhpExcel->getActiveSheet()->getStyle('M1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->PhpExcel->getActiveSheet()->getStyle('M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->PhpExcel->getActiveSheet()->getStyle('M1')->applyFromArray(array('font' => array('size' => 12)));
            /** /Prepare date area */

            $Sport = $this->Event->League->Sport->getPrintSportLeagues($SportId);

            if (isset($Sport['League']) && is_array($Sport['League'])) {

                $RowIndex       =   3;
                $ColumnIndex    =   'C';

                foreach ($Sport['League'] AS $League) {

                    $PrintingBets   = $this->Event->Bet->getPrintingsBets($Sports[$SportId]);
                    $Events         = $this->Event->getPrintingEvents($SportId, $League['id'], array_keys($PrintingBets), $dateFrom, $dateTo);

                    if (empty($Events)) {
                        continue;
                    }

                    foreach ($Events AS $Event) {

                        $this->Country->contain();
                        $Country = $this->Country->getCountry($Event['League']['country_id']);
                        $CountryName = isset( $Country['Country']['name']) ?  $Country['Country']['name'] : 'Unknown Country';

                        /** League / etc / etc / etct */
                        $this->PhpExcel->getActiveSheet()->mergeCells(str_replace('#index#', $RowIndex, 'A#index#:C#index#'));
                        $this->PhpExcel->getActiveSheet()->fromArray(array(__('%s / %s / %s', $Sport['Sport']['name'], $CountryName, $Event['League']['name'])), null, str_replace('#index#', $RowIndex, 'A#index#'));
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex, 'A#index#'))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex, 'A#index#'))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


                        $from           = $ColumnIndex;
                        $from           = ++$from;
                        $to             = $from;
                        $totalOutcomes  = count($PrintingBets);

                        foreach ($PrintingBets AS $OutcomeIndex => $Outcome) {
                            $totalOdds = count($Outcome['header']);

                            if ($OutcomeIndex == ($totalOutcomes - 1)) {
                                $totalOdds = $totalOdds -1;
                            }

                            for ($i=1; $i < $totalOdds; $i++) { ++$to; };

                            $template   = array('{from}' => $from, '{to}' => $to, '{rowIndex}' => $RowIndex);
                            $keys       = array_keys($template);
                            $values     = array_values($template);

                            $this->PhpExcel->getActiveSheet()->mergeCells(str_replace($keys, $values, '{from}{rowIndex}:{to}{rowIndex}'));
                            $this->PhpExcel->getActiveSheet()->fromArray(array($Outcome['type']), null, str_replace($keys, $values, '{from}{rowIndex}'));
                            $this->PhpExcel->getActiveSheet()->getStyle(str_replace($keys, $values, '{from}{rowIndex}'))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                            $this->PhpExcel->getActiveSheet()->getStyle(str_replace($keys, $values, '{from}{rowIndex}'))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                            $from = ++$to;
                        }

                        /** Code Title */
                        $this->PhpExcel->getActiveSheet()->fromArray(array('Code'), null, str_replace('#index#', $RowIndex + 1, 'A#index#'));
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex + 1, 'A#index#'))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex + 1, 'A#index#'))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex + 1, 'A#index#'))->getFont()->setBold(true);

                        /** Date Title */
                        $this->PhpExcel->getActiveSheet()->fromArray(array('Date'), null, str_replace('#index#', $RowIndex + 1, 'B#index#'));
                        $this->PhpExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex + 1, 'B#index#'))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex + 1, 'B#index#'))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex + 1, 'B#index#'))->getFont()->setBold(true);

                        /** Event Title */
                        $this->PhpExcel->getActiveSheet()->fromArray(array('Event'), null, str_replace('#index#', $RowIndex + 1, 'C#index#'));
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex + 1, 'C#index#'))->getFont()->setBold(true);

                        $headerFrom           = $ColumnIndex;

                        foreach ($PrintingBets AS $Outcome) {
                            foreach ($Outcome['header'] AS $header) {
                                $headerTemplate   = '{headerFrom}{rowIndex}';
                                $headerKeys       = array('{headerFrom}', '{rowIndex}');
                                $headerValues     = array(++$headerFrom, $RowIndex + 1);
                                $this->PhpExcel->getActiveSheet()->fromArray(array($header), null, str_replace($headerKeys, $headerValues, $headerTemplate));
                                $this->PhpExcel->getActiveSheet()->getStyle(str_replace($headerKeys, $headerValues, $headerTemplate))->getFont()->setBold(true);
                                $this->PhpExcel->getActiveSheet()->getStyle(str_replace($headerKeys, $headerValues, $headerTemplate))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                $this->PhpExcel->getActiveSheet()->getStyle(str_replace($headerKeys, $headerValues, $headerTemplate))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                $this->PhpExcel->getActiveSheet()->getColumnDimension($headerValues[0])->setWidth(5);
                                $from           = ++$from;
                            }
                        }

                        /** Code Value */
                        $this->PhpExcel->getActiveSheet()->fromArray(array($Event['Event']['id']), null, str_replace('#index#', $RowIndex + 2, 'A#index#'));
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex + 2, 'A#index#'))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex + 2, 'A#index#'))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex + 2, 'A#index#'))->getFont()->setBold(true);

                        /** Date Value */
                        $this->PhpExcel->getActiveSheet()->fromArray(array($Event['Event']['date']), null, str_replace('#index#', $RowIndex + 2, 'B#index#'));
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex + 2, 'B#index#'))->getFont()->setBold(true);

                        /** Event Value */
                        $this->PhpExcel->getActiveSheet()->fromArray(array($Event['Event']['name']), null, str_replace('#index#', $RowIndex + 2, 'C#index#'));
                        $this->PhpExcel->getActiveSheet()->getStyle(str_replace('#index#', $RowIndex + 2, 'C#index#'))->getFont()->setBold(true);

                        $Outcomes = array();

                        foreach ($PrintingBets AS $Outcome) {
                            $Outcomes[$Outcome['type']] = array(
                                'name'  =>  $Outcome['title'],
                                'type'  =>  $Outcome['type'],
                                'odds'  =>  $Outcome['body'],
                                'order' =>  $Outcome['order']
                            );
                        }

                        foreach ($Event['Bet'] AS $Bet) {

                            $BetOdds = array_map(function ($BetPart) { return $BetPart['BetPart']['odd']; }, $this->Event->Bet->BetPart->getBetParts($Bet['id'], array('odd')));

                            switch($Bet['type']) {
                                case Bet::BET_TYPE_EUROPEAN_HANDICAP:

                                    $lines = array_map(function($BetPart) { return $BetPart['BetPart']['line']; }, $this->Event->Bet->BetPart->getBetParts($Bet['id'], array('line')));

                                    array_unshift($BetOdds, is_array($lines) && isset($lines[0]) ? $lines[0] : '-');

                                    break;
                            }

                            $Outcomes[$Bet['type']]['odds'] = $BetOdds + $Outcomes[$Bet['type']]['odds'];
                        }
//
                        usort($Outcomes, function($a, $b) { return $a["order"] > $b["order"]; });
//
                        $OddFrom    =   $ColumnIndex;

                        foreach ($Outcomes AS $Outcome) {
                            foreach($Outcome['odds'] AS $Odd) {
                                $OddTemplate   = '{oddFrom}{rowIndex}';
                                $OddKeys       = array('{oddFrom}', '{rowIndex}');
                                $OddValues     = array(++$OddFrom, $RowIndex + 2);
                                $this->PhpExcel->getActiveSheet()->fromArray(array($Odd), null, str_replace($OddKeys, $OddValues, $OddTemplate));
                                $this->PhpExcel->getActiveSheet()->getStyle(str_replace($OddKeys, $OddValues, $OddTemplate))->getFont()->setBold(true);
                                $this->PhpExcel->getActiveSheet()->getStyle(str_replace($OddKeys, $OddValues, $OddTemplate))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                $this->PhpExcel->getActiveSheet()->getStyle(str_replace($OddKeys, $OddValues, $OddTemplate))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                $this->PhpExcel->getActiveSheet()->getColumnDimension($OddValues[0])->setWidth(5);
                            }
                        }

                        /** @var int $RowIndex */
                        $RowIndex = $RowIndex + 3; // todo set lines height const?
                    }
                }
            }

            $this->PhpExcel->output();
        }


        $this->set('Sports', $Sports);
        $this->set('dateFrom', $dateFrom);
        $this->set('dateTo', $dateTo);
    }

    public function rangeEvents()
    {
        $this->layout = "inside";

        $this->request->data["Event"] = $this->request->query;

        if (!isset($_GET["start"]) || !isset($_GET["end"])) {
            $this->App->setMessage(__('Sorry, no search results found', true), 'error');
            return $this->render('rangeEvents/no-search-results');
        }

        if (empty($_GET["start"]) || empty($_GET["end"])) {
            $this->App->setMessage(__('Sorry, no search results found', true), 'error');
            return $this->render('rangeEvents/no-search-results');
        }

        $data = array('EventsLeft' => array(), 'EventsRight' => array());
        $betEvents = array();

        $events = $this->Event->getRangeEvents($_GET["start"], $_GET["end"]);
        $this->set('model', $this->Event->name);

        if(empty($events)) {
            $this->App->setMessage(__('Sorry, no search results found', true), 'error');
            return $this->render('rangeEvents/no-search-results');
        }

        foreach ($events AS $e) {
            $betEvents[$e["Event"]["id"]] = $e;
        }

        $events = $this->Event->Bet->BetPart->assignBetPartsToEvents($events);

        foreach($events AS $i =>$event) {
            $Sport = $this->Event->League->Sport->getItem($event['League']["sport_id"]);
            $events[$i]["Sport"] = isset($Sport["Sport"]) ? $Sport["Sport"] : array();
        }

        $newEvents = array();

        foreach ($events AS $index => $event) {
            if (!isset($newEvents[$event["League"]["id"]])) {
                $newEvents[$event["League"]["id"]] = $event;
            } else {
                $newEvents[$event["League"]["id"]]["Bet"] = array_merge($newEvents[$event["League"]["id"]]["Bet"], $event["Bet"]);
            }
            unset($events[$index]);
        }

        $data['EventsRight'] = $newEvents;

        if(empty($data['EventsLeft']) && empty($data['EventsRight'])) {
            $this->App->setMessage(__('No search results found', true), 'error');
            return $this->render('rangeEvents/no-search-results');
        }

        $this->set('data', $data);
        $this->set('events', $data["EventsRight"]);
        $this->set('betEvents', $betEvents);

        return $this->render('rangeEvents/display-search-results');
    }

    public function display()
    {
        if (count($this->request->params['pass']) < 1) {
            exit;
        }

        $Event = current($this->Event->Bet->BetPart->assignBetPartsToEvents(array($this->Event->findEvent($this->request->params['pass'][0]))));

        if (empty($Event)) {
            $this->redirect('/' . Configure::read('Config.language'), null, true);
        }

        $Sport = $this->Event->League->Sport->getItem($Event['League']["sport_id"]);
        $Event["Sport"] = isset($Sport["Sport"]) ? $Sport["Sport"] : array();

        $EventName = explode(" - ", $Event["Event"]["name"]);
        $Event["Event"]["firstTeam"] =  isset($EventName[0]) ? trim($EventName[0]): "";
        $Event["Event"]["secondTeam"] =  isset($EventName[1]) ? trim($EventName[1]) : "";


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
}