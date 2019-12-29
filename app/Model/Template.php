<?php
/**
 * Template Model
 *
 * Handles Template Data Source Actions
 *
 * @package    Templates.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Template extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Template';

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
            'length'    => 100,
            'null'      => false
        ),
        'subject'   => array(
            'type'      => 'string',
            'length'    => 100,
            'null'      => false
        ),
        'content'   => array(
            'type'      => 'text',
            'length'    => null,
            'null'      => false
        )
    );

    // Use a different model
    public $translateModel = 'TemplateI18n';

    /**
     * Use a different table for translate table
     *
     * @var string $translateTable
     */
    public $translateTable = 'templates_i18n';

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array(
        'Containable',
        'Translate' => array(
            0   =>  'title',
            1   =>  'subject',
            2   =>  'content'
        )
    );

    /**
     * List of validation rules.
     *
     * @var array
     */
    public $validate = array(
        'subject' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'content' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank')
    );

    /**
     * Returns scaffold actions list
     *
     * @param CakeRequest $cakeRequest - cakeRequest object
     *
     * @return array
     */
    public function getActions(CakeRequest $cakeRequest)
    {
        $actions = parent::getActions($cakeRequest);
        unset($actions[2]);
        return $actions;
    }

    /**
     * Default pagination scaffold settings
     *
     * @return array - returns default pagination settings
     */
    public function getPagination()
    {
        return array(
            'fields'    =>  array(
                'Template.id',
                'Template.subject',
                'Template.content'
            ),
            'limit'     =>  Configure::read('Settings.itemsPerPage')
        );
    }

    /**
     * Returns scaffold edit fields
     *
     * @return array
     */
    public function getEdit($data)
    {
        $fields = array(
            'Template.id',
            'Template.subject',
            'Template.content'  =>  array(
                'class'                 => 'span12 ckeditor',
            )
        );
        return $fields;
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
        $options['fields'] = array(
            'Template.subject',
            'Template.content'
        );
        $options['recursive'] = -1;
        $options['conditions'] = array(
            'Template.id' => $id
        );

        $data = $this->find('first', $options);
        return $data;
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
        $tabs = parent::getTabs($params);
        unset($tabs['templatesadmin_add']);
        return $tabs;
    }
}