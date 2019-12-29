<?php
/**
 * Front Leagues Controller
 *
 * Handles Leagues Actions
 *
 * @package    Leagues
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class LeaguesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Leagues';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('League', "Events.Event");

    /**
     * Admin add league
     *
     * @param null $sportId
     */
    public function admin_add($sportId = null)
    {
        if (!empty($this->request->data)) {
            if (!is_null($sportId)) {
                $this->request->data['League']['sport_id'] = $sportId;
            }

            $this->request->data['League']['import_id'] = 0;
            $this->request->data['League']['order'] = 1;
            $this->request->data['League']['min_bet'] = 0;
            $this->request->data['League']['max_bet'] = 0;
            $this->request->data['League']['updated'] = time();
            $this->request->data['League']['feed_type'] = "Manual";
        }

        parent::admin_add();

        $this->set('fields', $this->League->getAdd($sportId));
    }

    /**
     * Admin add league
     *
     * @param null $sportId
     */
    public function admin_addLeague($sportId = null)
    {
        if (!empty($this->request->data)) {
            if ($sportId != null)
                $this->request->data['League']['sport_id'] = $sportId;
        }

        $this->admin_add();

        $this->request->data['League']['sport_id'] = $sportId;

        $this->view = 'admin_add';
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
        $model = $this->League->getItem($id);
        $data = array();

        if ($model != null) {

            $this->Paginator->settings  =   array($this->Event->name => array(
                "conditions"    =>  array(
                    'Event.league_id' => $id
                ),
                "order" =>  array(
                    'Event.id' => 'DESC'
                )
            ));

            $data = $this->Paginator->paginate( $this->Event->name );
        }

        $this->set('model', $model);
        $this->set('data', $data);
    }
}