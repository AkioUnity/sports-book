<?php
/**
 * Group Model
 *
 * Handles Group Data Source Actions
 *
 * @package    Groups.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Group extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Group';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'parent_id' => array(
            'type'      => 'int',
            'length'    => 10,
            'null'      => true
        ),
        'name'     =>  array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'lft'       => array(
            'type'      => 'int',
            'length'    => 10,
            'null'      => false
        ),
        'rght'      => array(
            'type'      => 'int',
            'length'    => 10,
            'null'      => false
        ),
        'created'   =>  array(
            'type'      =>  'datetime',
            'length'    =>  null,
            'null'      =>  false
        ),
        'modified'  =>  array(
            'type'      =>  'datetime',
            'length'    =>  null,
            'null'      =>  false
        )
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array(
        'Tree',
        'Acl'   => array('type' => 'requester')
    );

    /**
     * Detailed list of hasMany associations.
     *
     * @var $hasMany array
     */
    public $hasMany = 'User';

    /**
     * User group cont value
     */
    const GUEST_GROUP = 1;

    /**
     * User group cont value
     */
    const USER_GROUP = 2;

    /**
     * Admin group const value
     */
    const ADMINISTRATOR_GROUP = 3;

    /**
     * Operator group const value
     */
    const OPERATOR_GROUP = 4;

    /**
     * Cashier group cont value
     */
    const CASHIER_GROUP = 5;

    /**
     * Agent group cont value
     */
    const AGENT_GROUP = 6;

    /**
     * Agent group cont value
     */
    const BRANCH_GROUP = 7;
    /**
     * Initialize for static
     *
     * @return null
     */
    public function init()
    {
        return null;
    }

    /**
     * Acl callback
     *
     * @return null
     */
    public function parentNode()
    {
        return null;
    }

    /**
     * Returns index fields
     *
     * @return array
     */
    public function getAdminIndexSchema()
    {
        return array(
            0   =>  'Group.id',
            1   =>  'Group.name',
            2   =>  'Group.created',
            3   =>  'Group.modified'
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
                'name'          =>  __('View', true),
                'plugin'        =>  null,
                'controller'    =>  'groups',
                'action'        =>  'admin_index',
                'class'         =>  'btn btn-mini'
            ),

            1   =>  array(
                'name'          =>  __('Edit', true),
                'plugin'        =>  null,
                'controller'    =>  'groups',
                'action'        =>  'admin_edit',
                'class'         =>  'btn btn-mini btn-primary'
            ),

            2   =>  array(
                'name'          =>  __('Delete', true),
                'plugin'        =>  null,
                'controller'    =>  'groups',
                'action'        =>  'admin_delete',
                'class'         =>  'btn btn-mini btn-danger'
            )
        );
    }

    /**
     * Returns admin scaffold add fields
     *
     * @return array
     */

    public function getAdd()
    {
        return array(
            'Group.parent_id'    =>  array(
                'type' => 'select',
                'options' => array_merge(array(0 => '---'), $this->find(
                        'list',
                        array(
                            'fields'        => array('Group.id', 'Group.name')
                        )
                    )
            )),
            'Group.name'
        );
    }

    /**
     * Returns admin tabs
     *
     * @param array $params - url params
     *
     * @return array
     */
    public function getTabs(array $params)
    {
        $key    =   'groupsadmin_index';
        $parentTabs =   parent::getTabs($params);
        $root  =   $this->find(
            'all',
            array(
                'fields'        =>  array('id', 'name'),
                'conditions'    =>  array('Group.parent_id = 0')
            )
        );

        if (isset($parentTabs[$key])) {
            $parentTabs[$key]['name'] = __('List groups');
            if (!empty($params['pass'])) {
                $parentTabs[$key]['url'] = array(
                    'plugin'        =>  null,
                    'controller'    =>  'groups',
                    'action'        =>  'admin_index'
                );
            }
        }

        foreach ($root AS $parent) {
            $active = false;
            if ($params['action'] == 'admin_index') {
                if (in_array($parent['Group']['id'], $params['pass'])) {
                    $active = true;
                }
            }
            $parentTabs[] = array(
                'name'      =>  $parent['Group']['name'],
                'active'    =>  $active,
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'groups',
                    'action'        =>  'admin_index',
                    0               =>  $parent['Group']['id']
                )
            );
        }

        return $parentTabs;
    }
    /**
     * Returns groups
     *
     * @return mixed
     */
    public function getGroups()
    {
        $groups = array();
        $data = $this->find('all');

        foreach ($data as &$group) {
            $groups[$group['Group']['id']] = $group['Group']['name'];
        }

        return $groups;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getAdminGroups()
    {
        $groups         =   $this->getGroups();
        $currentUser    =   CakeSession::read('Auth.User');
//        echo ("getAdminGroups");
        if (empty($currentUser)) {
            $adminGroups = $this->find('all', array(
                'contain'       =>  array(),
                'fields'        =>  array('Group.id'),
                'conditions'    =>  array(
                    "NOT"               => array( "Group.id" => array(self::GUEST_GROUP, self::USER_GROUP) ),
                    "Group.parent_id"   =>  0
                )
            ));

            $adminGroups = array_map(function($group){
                return $group["Group"]["id"];
            }, $adminGroups);

        } else {
            switch ($currentUser["group_id"]) {
                case Group::ADMINISTRATOR_GROUP:
                    $adminGroups = $this->find('all', array(
                        'contain'       =>  array(),
                        'fields'        =>  array('Group.id'),
                        'conditions'    =>  array(
                            "NOT"               => array( "Group.id" => array(self::GUEST_GROUP, self::USER_GROUP) ),
                            "Group.parent_id"   =>  0
                        )
                    ));

                    $adminGroups = array_map(function($group){
                        return $group["Group"]["id"];
                    }, $adminGroups);
                    break;
                case Group::BRANCH_GROUP:
                    $adminGroups = array(
                        0   =>  self::AGENT_GROUP
                    );
                    break;
                default:
                    $adminGroups = array();
                    break;
            }
        }

        foreach ($groups AS $groupId => $group) {
            if (!in_array($groupId, $adminGroups)) {
                unset($groups[$groupId]);
            }
        }

        return $groups;
    }
}