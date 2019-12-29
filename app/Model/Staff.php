<?php
/**
 * Staff Model
 *
 * Handles Staff Data Source Actions
 *
 * @package    Staffs.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Staff extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Staff';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = 'users';

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     *
     * @var $belongsTo array
     */
    public $belongsTo = array('Group');

    /**
     * Detailed list of hasMany associations.
     *
     * @var array
     */
    public $hasMany = array(
        0   =>  'Ticket',
        1   =>  'Deposit',
        2   =>  'Withdraw',
        3   =>  'BonusCodesUser',
        4   =>  'PaymentBonusUsage',
        5   =>  'UserSettings'
    );

    /**
     * List of validation rules.
     *
     * @var array
     */
    public $validate = array(
        'username' => array(
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'allowEmpty' => false,
                'message' => 'Alphabets and numbers only'
            ),
            'between' => array(
                'rule' => array('between', 5, 15),
                'message' => 'Between 5 to 15 characters'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This username has already been taken.'
            )
        ),
        'password_raw' => array(
            'rule' => array('minLength', '5'),
            'message' => 'Mimimum 5 characters long'
        )
    );

    private $getEdit = array(
        'Staff.id',
        'Staff.username',
        'Staff.password',
        'Staff.group_id'            => array(
            'type'              =>  'select',
            'options'           =>  array()
        ),
        'Staff.allow_betslip'       =>  array(),
        'Staff.day_stake_limit'     =>  array(),
        'Staff.operator_signature'  =>  array()
    );

    /**
     * Constructor. Binds the model's database table to the object.
     *
     * @param boolean|integer|string|array $id Set this ID for this model on startup,
     * can also be an array of options, see above.
     * @param string $table Name of database table to use.
     * @param string $ds DataSource connection name.
     */
    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);

        $this->getEdit["Staff.allow_betslip"]   = $this->getFieldHtmlConfig('select',
            array(
                'options' => array(
                    1 => __('Yes'),
                    0 =>  __('No')
                )
            )
        );
        $this->getEdit["Staff.group_id"]["options"] =   $this->Group->getAdminGroups();
        $this->getEdit["Staff.operator_signature"]  =   $this->getFieldHtmlConfig('wysihtml5');
    }

    /**
     * Returns index fields
     *
     * @return array
     */
    public function getAdminIndexSchema()
    {
        return array(
            0   =>  'Staff.id',
            1   =>  'Staff.username',
            2   =>  'Staff.balance',
            3   =>  'Staff.group_id',
            4   =>  'Staff.referal_id',
        );
    }

    /**
     * Default pagination scaffold settings
     *
     * @return array - returns default pagination settings
     */
    public function getPagination()
    {
        return array(
            'fields'        =>  array(
                'Staff.id',
                'Staff.username',
                'Staff.balance',
                'Staff.group_id'
            ),
            'conditions'    =>  array(
                'Staff.group_id <>' => Group::USER_GROUP
            ),
            'limit'         =>  Configure::read('Settings.itemsPerPage')
        );
    }

    /**
     * Returns scaffold view fields
     *
     * @param int $id - view entry id
     *
     * @return array
     */
    public function getView($id)
    {
        return $this->find('first',
            array(
                'fields'        =>  array(
                    'Staff.id',
                    'Staff.username',
                    'Staff.group_id'
                ),

                'recursive'     =>  -1,
                'conditions'    =>  array(
                    'Staff.id' => $id,
                    'Staff.group_id <>' => Group::USER_GROUP
                )
            ));
    }

    /**
     * Returns admin scaffold add fields
     *
     * @return array
     */
    public function getAdd()
    {
        return array(
            'Staff.username',
            'Staff.password_raw'    =>  array(
                'type'  =>  'password',
                'label' =>  __('Password')
            ),
            'Staff.group_id'        =>  array(
                'type'      => 'select',
                'options'   => $this->Group->getAdminGroups()
            )
        );
    }

    /**
     * Returns admin scaffold edit fields
     *
     * @return array
     */
    public function getEdit($data)
    {
        if (isset($data["Staff"]["group_id"])) {
            switch ($data["Staff"]["group_id"]) {
                case Group::AGENT_GROUP:
                    $user = $this->find('first', array(
                        "fields"        =>  array("Staff.id", "Staff.username"),
                        "conditions"    =>  array("Staff.id" => $data["Staff"]["referal_id"])
                    ));
                    $branchField = array("Staff.branch" => array("value" => isset($user["Staff"]["username"]) ? $user["Staff"]["username"] : "-", "disabled" => true));

                    $this->getEdit = array_slice($this->getEdit, 0, 3, true) + $branchField + array_slice($this->getEdit, 3, count($this->getEdit) - 1, true) ;

                    break;
            }
        }

        return $this->getEdit;
    }

    /**
     * Sets admin scaffold edit fields
     *
     * @param array $fields
     */
    public function setEdit(array $fields)
    {
        $this->getEdit = $fields;
    }

    /**
     * Returns admin scaffold tabs
     *
     * @param array $params - url params
     *
     * @return array
     */
    public function getTabs(array $params)
    {
        $conditions = array();

        switch (CakeSession::read("Auth.User.group_id")) {
            case Group::BRANCH_GROUP:
                $conditions = array(
                    'Staff.group_id' => Group::AGENT_GROUP, 'Staff.referal_id' => CakeSession::read("Auth.User.id")
                );
                break;
        }


        $tabs = array(
            0   =>  array(
                'name'      =>  __('All', true),
                'active'    =>  !isset($params["pass"][0]) ? true : false,
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'staffs',
                    'action'        =>  'admin_index',
                    0               =>  null,
                )
            ),
            Group::ADMINISTRATOR_GROUP   =>  array(
                'name'      =>  __('Admins ( %d )', $this->find('count', array("conditions" => array_merge($conditions, array('Staff.group_id' => Group::ADMINISTRATOR_GROUP))))),
                'active'    =>  isset($params["pass"][0]) && $params["pass"][0] == Group::ADMINISTRATOR_GROUP ? true : false,
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'staffs',
                    'action'        =>  'admin_index',
                    0               =>  Group::ADMINISTRATOR_GROUP
                )
            ),
            Group::BRANCH_GROUP   =>  array(
                'name'      =>  __('Branches ( %d )', $this->find('count', array("conditions" => array_merge($conditions, array('Staff.group_id' => Group::BRANCH_GROUP))))),
                'active'    =>  isset($params["pass"][0]) && $params["pass"][0] == Group::BRANCH_GROUP ? true : false,
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'staffs',
                    'action'        =>  'admin_index',
                    0               =>  Group::BRANCH_GROUP
                )
            ),
            Group::AGENT_GROUP   =>  array(
                'name'      =>  __('Agents ( %d )', $this->find('count', array("conditions" => array_merge($conditions, array('Staff.group_id' => Group::AGENT_GROUP))))),
                'active'    =>  isset($params["pass"][0]) && $params["pass"][0] == Group::AGENT_GROUP ? true : false,
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'staffs',
                    'action'        =>  'admin_index',
                    0               =>  Group::AGENT_GROUP
                )
            ),
            Group::CASHIER_GROUP   =>  array(
                'name'      =>  __('Cashiers ( %d )', $this->find('count', array("conditions" => array_merge($conditions, array('Staff.group_id' => Group::CASHIER_GROUP))))),
                'active'    =>  isset($params["pass"][0]) && $params["pass"][0] == Group::CASHIER_GROUP ? true : false,
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'staffs',
                    'action'        =>  'admin_index',
                    0               =>  Group::CASHIER_GROUP
                )
            ),
            Group::OPERATOR_GROUP   =>  array(
                'name'      =>  __('Operators ( %d )', $this->find('count', array("conditions" => array_merge($conditions, array('Staff.group_id' => Group::OPERATOR_GROUP))))),
                'active'    =>  isset($params["pass"][0]) && $params["pass"][0] == Group::OPERATOR_GROUP ? true : false,
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'staffs',
                    'action'        =>  'admin_index',
                    0               =>  Group::OPERATOR_GROUP
                )
            )
        );

        switch (CakeSession::read("Auth.User.group_id")) {
            case Group::ADMINISTRATOR_GROUP:
                return $tabs;
                break;
            case Group::BRANCH_GROUP:
                $tabs[Group::AGENT_GROUP]["active"] = true;
                return array($tabs[Group::AGENT_GROUP]);
                break;
            default:
                return array();
                break;
        }
    }

    /**
     * Model scaffold search fields wrapper
     *
     * @return array
     */
    public function getSearch()
    {
        return array(
//            'Staff.id'          =>  array('type' => 'number'),
            'Staff.username'    =>  array('type' => 'text'),
            'Staff.email'       =>  array('type' => 'text'),
//            'Staff.group_id'    =>  array('type' => 'select', 'options' => $this->Group->getAdminGroups())
        );
    }

    /**
     * Returns scaffold actions list
     *
     * @param CakeRequest $cakeRequest - cakeRequest object
     *
     * @return array
     */
    public function getActions(CakeRequest $cakeRequest)
    {
        return array(
            0   =>  array(
                'name'          =>  __('Edit', true),
                'plugin'        =>  false,
                'controller'    =>  'staffs',
                'action'        =>  'admin_edit',
                'class'         =>  'btn btn-mini btn-primary'
            ),

            1   =>  array(
                'name'          =>  __('Delete', true),
                'plugin'        =>  false,
                'controller'    =>  'staffs',
                'action'        =>  'admin_delete',
                'class'         =>  'btn btn-mini btn-danger'
            ),


            2   =>  array(
                'name'          =>  __('Fund staff', true),
                'plugin'        =>  false,
                'controller'    =>  'deposits',
                'action'        =>  'admin_add',
                'class'         =>  'btn btn-mini btn-success'
            ),

            3   =>  array(
                'name'          =>  __('Charge staff', true),
                'plugin'        =>  false,
                'controller'    =>  'withdraws',
                'action'        =>  'admin_add',
                'class'         =>  'btn btn-mini btn-warning'
            )
        );
    }
}