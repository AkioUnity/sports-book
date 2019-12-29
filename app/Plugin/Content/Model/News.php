<?php
/**
 * News Model
 *
 * Handles News Data Actions
 *
 * @package    News.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class News extends AppModel {

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'News';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'title'     => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'summary'   => array(
            'type'      => 'text',
            'length'    => null,
            'null'      => false
        ),
        'content'   => array(
            'type'      => 'text',
            'length'    => null,
            'null'      => false
        ),
        'created'   => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => false
        ),
        'modified'  => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => false
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
            2   =>  'summary',
            3   =>  'content'
        )
    );

    // Use a different model
    public $translateModel = 'Content.NewsI18n';

    /**
     * Use a different table for translate table
     *
     * @var string $translateTable
     */
    public $translateTable = 'news_i18n';

    /**
     * List of validation rules.
     *
     * @var $validate array
     */
    public $validate = array(
        'title'     => array(
            'rule' => 'notEmpty', 'message' => 'This field cannot be left blank'
        ),
        'summary'   => array(
            'rule' => 'notEmpty', 'message' => 'This field cannot be left blank'
        ),
        'content'   => array(
            'rule' => 'notEmpty', 'message' => 'This field cannot be left blank'
        )
    );

    /**
     * Returns index fields
     *
     * @return array
     */
    public function getAdminIndexSchema()
    {
        return array(
            0   =>  'News.id',
            1   =>  'News.title',
            2   =>  'News.Summary'
        );
    }

    /**
     * Returns news
     *
     * @param int $limit
     * @return array
     */
    function getNews($limit = 4)
    {
        $options['limit'] = $limit;
        $options['order'] = 'News.created DESC';
        $data = $this->find('all', $options);
        return $data;
    }

    /**
     * Default pagination scaffold settings
     *
     * @return array - returns default pagination settings
     */
    public function getPagination()
    {
        $options['fields'] = array(
            'News.id',
            'News.title',
            'News.summary',
            'News.content'
        );
        return $options;
    }

    /**
     * Returns search
     *
     * @return array
     */
    public function getSearch()
    {
        return array();
    }

    /**
     * Returns scaffold translate fields
     *
     * @return array
     */
    public function getTranslate()
    {
        return array(
            'News.title'    =>  array(
                'type'                  =>  'text'
            ),

            'News.summary'  =>  array(
                'class'                 => 'span12 wysihtml5',
                'label'                 =>  array('style' => 'font-weight:bold'),
                'required'              =>  false
            ),

            'News.content'  =>  array(
                'class'                 => 'span12 ckeditor',
                'label'                 =>  array('style' => 'font-weight:bold')
            )
        );
    }

    /**
     * Add entry fields
     *
     * @return array
     */
    public function getAdd()
    {
        return array(
            'News.title'    =>  array(
                'type'                  =>  'text'
            ),

            'News.summary'  =>  array(
                'class'                 => 'span12 wysihtml5',
                'label'                 =>  array('style' => 'font-weight:bold'),
                'required'              =>  false
            ),

            'News.content'  =>  array(
                'class'                 => 'span12 ckeditor',
                'label'                 =>  array('style' => 'font-weight:bold')
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
        return array(
            'News.title'    =>  array(
                'type'                  =>  'text'
            ),

            'News.summary'  =>  array(
                'type'                  => 'textarea',
                'class'                 => 'span12 wysihtml5',
                'required'              =>  false
            ),

            'News.content'  =>  array(
                'type'                  => 'textarea',
                'class'                 => 'span12 ckeditor'
            )
        );
    }
}