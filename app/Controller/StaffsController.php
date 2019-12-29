<?php
/**
 * Front Staffs Controller
 *
 * Handles Staffs Actions
 *
 * @package    Staffs
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class StaffsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Staffs';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Staff');

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
     * Admin Index scaffold functions
     *
     * @param array $conditions -   admin_index scaffold conditions
     * @param null  $model      -   admin_index $model name
     *
     * @return array
     */
    public function admin_index($conditions = array(), $model = null)
    {
        $user       =   $this->Auth->user();
        $group_id   =   isset($this->request->params["pass"][0]) ? $this->request->params["pass"][0] : null;
        $child_id   =   isset($this->request->params["pass"][1]) ? $this->request->params["pass"][1] : null;

        switch ($user["group_id"]) {
            case Group::ADMINISTRATOR_GROUP:
                $conditions = array('Staff.group_id <>' => Group::USER_GROUP);
                if (!is_null($group_id)) {
                    if (!is_null($child_id) && is_numeric($child_id)) {
                        switch($group_id) {
                            case Group::BRANCH_GROUP:
                                $conditions["Staff.group_id"] = Group::AGENT_GROUP;
                                $conditions["Staff.referal_id"] = $child_id;
                                break;
                            case Group::AGENT_GROUP:
                                $conditions["Staff.group_id <>"] = 0; // override
                                $conditions["Staff.group_id"] = Group::USER_GROUP;
                                $conditions["Staff.referal_id"] = $child_id;
                                break;
                        }
                    }else {
                        $conditions["Staff.group_id"] = $this->request->params["pass"][0];
                    }
                }

                break;
            case Group::BRANCH_GROUP:
                $conditions = array('Staff.group_id' => Group::AGENT_GROUP, 'Staff.referal_id' => $user["id"]);
                break;
            default:
                $conditions = array('Staff.id' => $user["id"]);
                break;
        }

        $data = parent::admin_index($conditions);

        foreach ($data AS $index => $staff) {
            if (is_numeric($staff["Staff"]["referal_id"]) && $staff["Staff"]["referal_id"] > 0 ) {
                $user = $this->Staff->find('first', array(
                    "fields"        =>  array("Staff.id", "Staff.username"),
                    "conditions"    =>  array("Staff.id" => $staff["Staff"]["referal_id"])
                ));
                $data[$index]["Staff"]["referal_id"] = isset($user["Staff"]["username"]) ? $user["Staff"]["username"] : "-";
            } else {
                $data[$index]["Staff"]["referal_id"] = "-";
            }

        }

        $this->set('data', $data);
        $this->set('group_id', $group_id);
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
        if (!empty($this->request->data)) {
            if (empty($this->request->data['Staff']['password'])) {
                $staff = $this->Staff->getItem($id);
                $this->request->data['Staff']['password'] = $staff['Staff']['password'];
            } else {
                $this->request->data['Staff']['password'] = Security::hash($this->request->data['Staff']['password'], null, true);
            }
        }

        parent::admin_edit($id);

        $this->request->data['Staff']['password'] = '';
    }

    /**
     * Admin Add scaffold functions
     *
     * @param null $id - parentId
     *
     * @return void
     */
    public function admin_add($id = null)
    {
        if (!empty($this->request->data)) {
            $this->request->data['Staff']['password'] = Security::hash($this->request->data['Staff']['password_raw'], null, true);
            $this->request->data['Staff']['status'] = 1;
        }
        parent::admin_add($id);
        $this->request->data['Staff']['password'] = '';
    }
    
    public function admin_deposit_bonus_history($id)
    {
    	//FIXME: someday in traint (php 5.4)
    	$this->view="admin_index";
    	$conditions['user_id'] = $id;
    	$ret =  parent::admin_index($conditions,'PaymentBonusUsage');
    	return $ret;
    
    }
}