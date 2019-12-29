<?php
/**
 * Handles Bets
 *
 * Handles Bets Actions
 *
 * @package    Bets
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class BetsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Bets';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Bet');

    /**
     * Admin Add scaffold functions
     *
     * @param null $id - parentId
     *
     * @return void
     */
    public function admin_add($id = null)
    {
        $this->__getModel();

        //$event =
        $betTypes = $this->Bet->getBetTypes();
        $eventId = $this->request->params["pass"][0];
        $betType = isset($this->request->params["pass"][1]) ? $this->request->params["pass"][1] : null;

        $this->viewPath = 'Bets';

        if (!empty($this->request->data)) {
            $this->request->data['Bet']['event_id'] = $eventId;
            $this->request->data['Bet']['type'] = $this->request->data['Bet']['name'];


            foreach ($this->request->data["BetPart"] AS $i => $BetPart) {
                $this->request->data["BetPart"][$i]["odd"] = str_replace(',', '.',  $this->request->data["BetPart"][$i]["odd"]);
                if (isset($this->request->data['Bet']['bet_line'])) {
                    $this->request->data["BetPart"][$i]["line"] = $this->request->data['Bet']['bet_line'];
                }
            }

            if ($this->Bet->saveAll($this->request->data)) {
                $this->Admin->setMessage(__('Bet added', true), 'success');
               // $this->redirect(array('controller' => 'events', 'action' => 'view', $eventId));
            } else {
                $this->Admin->setMessage(__('Error adding bet', true), 'error');
            }
        }

        $tabs = $this->Bet->getTabs($this->request->params);

        $tabs["Betsadmin_index"]["url"] = array(
            "plugin"        =>  'events',
            "controller"    =>  'events',
            'action'        =>  'admin_index'
        );

        $this->set('bet_type', $betType);
        $this->set('bet_types', $betTypes);
        $this->set('event_id', $eventId);
        $this->set('tabs', $tabs);
    }

    /**
     * Admin Edit scaffold functions
     *
     * @param int $id - edit item id
     *
     * @return void
     */
    public function admin_edit($id)
    {
        $this->__getModel();
        $this->viewPath = 'Bets';
        if (!empty($this->request->data)) {     
            $this->request->data['Bet']['id'] = $id;

            foreach ($this->request->data["BetPart"] AS $i => $BetPart) {
                $this->request->data["BetPart"][$i]["odd"] = str_replace(',', '.',  $this->request->data["BetPart"][$i]["odd"]);
            }

            if ($this->Bet->saveAll($this->request->data)) {
                $this->Admin->setMessage(__('Bet added', true), 'success');
                $this->redirect(array('plugin' => 'events', 'controller' => 'events', 'action' => 'view', $this->Bet->getParentId($id)));
            } else {
                $this->Admin->setMessage(__('Error adding bet', true), 'error');
            }
        }
        
        $this->request->data = $this->Bet->getItem($id);
        $this->set('data', $this->request->data);
    }
}