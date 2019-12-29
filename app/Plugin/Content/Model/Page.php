<?php
/**
 * Page Model
 *
 * Handles Page Data Source Actions
 *
 * @package    Pages.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */


App::uses('ContentAppModel', 'Content.Model');
App::uses('PageI18n', 'Content.Model');

class Page extends ContentAppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Page';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'    => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'title' => array(
            'type'      => 'string',
            'length'    => 100,
            'null'      => false
        ),
        'url'   => array(
            'type'      => 'string',
            'length'    => 100,
            'null'      => false
        ),
        'content'   => array(
            'type'      => 'text',
            'length'    => false,
            'null'      => true
        ),
        'keywords'  => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => true
        ),
        'description' => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => true
        ),
        'active'    => array(
            'type'      => 'tinyint',
            'length'    => 1,
            'null'      => true
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
        'Translate' => array(
            1   =>  'title',
            2   =>  'url',
            3   =>  'content',
            4   =>  'keywords',
            5   =>  'description',
            6   =>  'active'
        )
    );

    // Use a different model
    public $translateModel = 'Content.PageI18n';

    /**
     * Use a different table for translate table
     *
     * @var string $translateTable
     */
    public $translateTable = 'pages_i18n';

    /**
     * Page status active value
     */
    const PAGE_STATUS_ACTIVE     =   1;

    /**
     * Page status inactive value
     */
    const PAGE_STATUS_INACTIVE  =   0;

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
            0   =>  'Page.id',
            1   =>  'Page.title',
            2   =>  'Page.url'
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
                'controller'    =>  'pages',
                'action'        =>  'admin_view',
                'class'         =>  'btn btn-mini'
            ),

            1   =>  array(
                'name'          =>  __('Edit', true),
                'plugin'        =>  'content',
                'controller'    =>  'pages',
                'action'        =>  'admin_edit',
                'class'         =>  'btn btn-mini btn-primary'
            ),

            2   =>  array(
                'name'          =>  __('Delete', true),
                'plugin'        =>  'content',
                'controller'    =>  'pages',
                'action'        =>  'admin_delete',
                'class'         =>  'btn btn-mini btn-danger'
            )
        );
    }

    /**
     * List of validation rules.
     *
     * @var array
     */
    public $validate = array(
        'title'     =>  array(
            'rule' => 'notEmpty', 'message' => 'This field cannot be left blank'
        ),
        'url'       =>  array(
            'rule' => 'notEmpty', 'message' => 'This field cannot be left blank'
        ),
        'content'   =>  array(
            'rule' => 'notEmpty', 'message' => 'This field cannot be left blank'
        )
    );
    /**
     * Create page fields
     *
     * @return array
     */
    public function getAdd()
    {
        return array(
            'Page.title',
            'Page.url',
            'Page.keywords',
//            'Page.description'  =>  $this->getFieldHtmlConfig('wysihtml5'),
            'Page.content'      =>  $this->getFieldHtmlConfig('ckeditor'),
            'Page.active'       =>  $this->getFieldHtmlConfig('switch')
        );
    }

    /**
     * Edit page fields
     *
     * @return array
     */
    public function getEdit($data)
    {
        return array(
            'Page.id',
            'Page.title',
            'Page.url',
            'Page.keywords',
//            'Page.description'  =>  array('type' => 'textarea', 'class' => 'span12 wysihtml5'),
            'Page.content'      =>  array('type' => 'textarea', 'class' => 'span12 ckeditor'),
            'Page.active'       =>  $this->getFieldHtmlConfig('switch')
        );
    }

    /**
     * Search relations
     *
     * @return array
     */
    public function getSearch()
    {
        return array(
            'Page.id'           =>  array('type' => 'number'),
            'Page.title'        =>  array('type' => 'text'),
            'Page.url'          =>  array('type' => 'text'),
            'Page.content'      =>  array('type' => 'text'),
            'Page.keywords'     =>  array('type' => 'text'),
//            'Page.description'  =>  array('type' => 'text')
        );
    }

    /**
     * Returns translate fields
     *
     * @return array
     */
    public function getTranslate()
    {
        return array (
            'Page.id',
            'Page.title',
            'Page.url',
            'Page.keywords',
//            'Page.description'  =>  $this->getFieldHtmlConfig('wysihtml5'),
            'Page.content'      =>  $this->getFieldHtmlConfig('ckeditor'),
            'Page.active'       =>  $this->getFieldHtmlConfig('switch')
        );
    }

    /**
     * Returns pages urls
     *
     * @return array
     */
    public function getUrls()
    {
        $options['fields'] = array(
            'Page.url'
        );

        $data = $this->find('all', $options);

        $urls = array();

        foreach ($data as $page) {
            $urls[$page['Page']['url']] = $page['Page']['url'];
        }

        return $urls;
    }
}