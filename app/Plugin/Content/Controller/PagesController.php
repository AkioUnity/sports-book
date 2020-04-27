<?php
/**
 * Front Pages Controller
 *
 * Handles Pages Actions
 *
 * @package    Pages
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('ContentAppController', 'Content.Controller');
App::uses('String', 'Utility');

class PagesController extends ContentAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Pages';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        0   =>  'Content.Page',
        1   =>  'Content.News',
        2   =>  'Content.Slide',
        3   =>  'Content.PageI18n',
        4   =>  'Sport'
    );

    /**
     * Called before the controller action.
     *
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('display', 'main'));
    }

    /**
     * Main controller page
     *
     * @return void
     */
    public function main()       //    /eng
    {
         
        if (!isset($this->request->params["language"])) {
            $this->layout = 'intro';
            $events = array();
            $stats = array();

            $firstEvent = array();
            $otherEvent = array();

            $introEvents = array(
                array('leagueId' => 7),
                array('leagueId' => 2),
                array('leagueId' => 6),
                array('leagueId' => 4),
            );

            while(!empty($introEvents)) {
                $introEvent = array_pop($introEvents);
                foreach ($this->Sport->League->Event->getIndexEvents($introEvent["leagueId"], Bet::BET_TYPE_MATCH_RESULT) AS $Event) {
                    $Teams = explode(" - ", $Event["Event"]["name"]);
                    $Event["Event"]["Team"][1] = trim(current($Teams));
                    $Event["Event"]["Team"][2] = trim(end($Teams));

                    $events[$introEvent["leagueId"]][] = $Event;
                    $stats[$introEvent["leagueId"]] =   isset($stats[$introEvent["leagueId"]]) ?
                                                        $stats[$introEvent["leagueId"]] + count($Event["Bet"]) :
                                                        count($Event["Bet"]);

                }
            }

            arsort($stats);

            foreach ($stats AS $leagueId => $count) {
                if ($count <= 0) {
                    continue;
                }

                $firstEvent = $events[$leagueId][0];
                $stats[$leagueId]--;
                unset($events[$leagueId][0]);
                break;
            }

            foreach ($stats AS $leagueId => $count) {
                if ($count <= 0) {
                    continue;
                }

                $otherEvent = $events[$leagueId];
                $stats[$leagueId] = 0;
                unset($events[$leagueId][0]);
                break;
            }

            array_values($otherEvent);
            $this->set('firstEvent', $firstEvent);
            $this->set('otherEvent', array_values($otherEvent));
        }

        $addToTranslate = array( __("Football"), __("Basketball"),  __("Tennis"), __("IceHockey"));

        $this->set('lastMinuteBets', $this->Sport->League->getLastMinuteBets());
        $this->set('lastMinuteBetsClass', array("Football" => "tab-fot", "Basketball" => "tab-bas", "Tennis" => "tab-ten", "IceHockey" => "tab-ice"));
        $this->set('news', $this->News->getNews());
        $this->set('slides', $this->Sport->League->Event->getSliderEvents());
    }

    /**
     * Displays page content
     *
     * @param string $url - page url
     *
     * @return void
     */
    public function display($url = 'main')
    {
        $show_slider        = 0;
        $showLastMinuteBets = 0;
        $showNews           = 0;

        $query = array(
            'conditions' => array(
                'I18n__url`.`content'     => $url,
                'I18n__active`.`content'  => 1
            )
        );

        if ($url == 'main') {
            $show_slider = 1;
            $showLastMinuteBets = 1;
            $showNews = 1;
        }

        $page = $this->Page->find('first', $query);

        //fallback to main page
        if (empty($page)) {
            $show_slider    = 1;
            $query['conditions']['I18n__url`.`content'] = 'main';
            $page           = $this->Page->find('first', $query);
        }

        $title              =   isset($page['Page']['title'])       ?   $page['Page']['title']          : null;
        $title_for_layout   =   $title;
        $content            =   isset($page['Page']['content'])     ?   $page['Page']['content']        : null;
        $keywords           =   isset($page['Page']['keywords'])    ?   $page['Page']['keywords']       : null;
        $description        =   isset($page['Page']['description']) ?   $page['Page']['description']    : null;

        $this->set(compact('showNews', 'description', 'keywords', 'title', 'content', 'title_for_layout', 'show_slider', 'showLastMinuteBets'));
    }
}