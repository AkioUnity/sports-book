<?php
/**
 * Menu Model
 *
 * Handles Menu Data Source Actions
 *
 * @package    Pages.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('ContentAppModel', 'Content.Model');

class Menu extends ContentAppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Menu';

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
            'type'      => 'string',
            'length'    => 10,
            'null'      => true
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
        'title'     =>  array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'url'       =>  array(
            'type'      => 'string',
            'length'    => 255,
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
        'Translate' => array(
            0   =>  'title',
            1   =>  'url'
        )
    );

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable bool
     */
    public $useTable = 'menu';

    /**
     * Use a different table for translate table
     *
     * @var string $translateTable
     */
    public $translateTable = 'menu_i18n';

    /**
     * Top menu id
     */
    const MENU_TYPE_TOP = 4;

    /**
     * Sidebar menu id
     */
    const MENU_TYPE_SIDEBAR = 3;

    /**
     * Bottom menu id
     */
    const MENU_TYPE_BOTTOM = 2;

    /**
     * Constructor. Binds the model's database table to the object.
     *
     * @param bool $id    - Set this ID for this model on startup
     *                      can also be an array of options, see above.
     * @param null $table - Name of database table to use.
     * @param null $ds    - DataSource connection name.
     */
    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->locale = Configure::read('Config.language');
    }

    /**
     * Returns index fields
     *
     * @return array
     */
    public function getAdminIndexSchema()
    {
        return array(
            0   =>  'Menu.id',
            1   =>  'Menu.title',
            2   =>  'Menu.url'
        );
    }

    /**
     * Returns menu tree by id
     *
     * @param int $id - child id
     *
     * @return mixed - child data
     */
    public function getMenuById($id)
    {
        return $this->children($id);
    }

    /**
     * Default pagination settings
     *
     * @return array
     */
    public function getPagination()
    {
        return array(
            'limit' => 10,
            'fields' => array(
                'Menu.id',
                'Menu.title',
                'Menu.url'
            )
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
        return $this->find(
            'first',
            array(
                'fields'        =>  array(
                    'Menu.id',
                    'Menu.parent_id',
                    'Menu.title',
                    'Menu.url'
                ),
                'recursive'     =>  -1,
                'conditions'    =>  array(
                    'Menu.id' => $id
                )
            )
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
                'plugin'        =>  'content',
                'controller'    =>  'menus',
                'action'        =>  'admin_index',
                'class'         =>  'btn btn-mini'
            ),

            1   =>  array(
                'name'          =>  __('Edit', true),
                'plugin'        =>  'content',
                'controller'    =>  'menus',
                'action'        =>  'admin_edit',
                'class'         =>  'btn btn-mini btn-primary'
            ),

            2   =>  array(
                'name'          =>  __('Delete', true),
                'plugin'        =>  'content',
                'controller'    =>  'menus',
                'action'        =>  'admin_delete',
                'class'         =>  'btn btn-mini btn-danger'
            )
        );
    }

    /**
     * Returns scaffold add fields
     *
     * @return array
     */
    public function getAdd()
    {
        return array(
            'Menu.parent_id'    =>  array(
                'type' => 'select',
                'options' => $this->find(
                    'list',
                    array(
                        'fields'        => array('Menu.id', 'Menu.title'),
                        'conditions'    => array('Menu.parent_id = 1')
                    )
                )
            ),
            'Menu.title',
            'Menu.url'
        );
    }

    /**
     * Returns scaffold edit fields
     *
     * @return array
     */
    public function getEdit($data)
    {
        return array(
            'Menu.parent_id'    =>  array(
                'type' => 'select',
                'options' => $this->find(
                    'list',
                    array(
                        'fields'        => array('Menu.id', 'Menu.title'),
                        'conditions'    => array('Menu.parent_id = 1')
                    )
                )
            ),
            'Menu.title',
            'Menu.url'
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
        $menuKey    =   'menusadmin_index';
        $parentTabs =   parent::getTabs($params);
        $rootMenus  =   $this->find(
            'all',
            array(
                'fields'        =>  array('id', 'title'),
                'conditions'    =>  array('Menu.parent_id = 1')
            )
        );

        if (isset($parentTabs[$menuKey])) {
            $parentTabs[$menuKey]['name'] = __('List menus');
            if (!empty($params['pass'])) {
                $parentTabs[$menuKey]['url'] = array(
                    'plugin'        =>  'content',
                    'controller'    =>  'menus',
                    'action'        =>  'admin_index'
                );
            }
        }

        foreach ($rootMenus AS $parent) {
            $active = false;
            if ($params['action'] == 'admin_index') {
                if (in_array($parent['Menu']['id'], $params['pass'])) {
                    $active = true;
                }
            }
            $parentTabs[] = array(
                'name'      =>  $parent['Menu']['title'],
                'active'    =>  $active,
                'url'       =>  array(
                    'plugin'        =>  'content',
                    'controller'    =>  'menus',
                    'action'        =>  'admin_index',
                    0               =>  $parent['Menu']['id']
                )
            );
        }

        return $parentTabs;
    }
}