<?php
/**
 * App Model
 *
 * Handles App Data Source Actions
 *
 * @package    App.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('Model', 'Model');
App::uses('CakeEvent', 'Event');

App::uses('AppListener', 'Event');
App::uses('SettingsListener', 'Event');
App::uses('UserListener', 'Event');
App::uses('LanguageListener', 'Event');
App::uses('StaffListener', 'Event');
App::uses('UserSettingsListener', 'Event');

class AppModel extends Model
{
    /**
     * @var array
     */
    public $insert_ids = array();

    /**
     * Returns model name
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns model plural name
     *
     * @return string
     */
    public function getPluralName()
    {
        return Inflector::pluralize($this->name);
    }

    /**
     * MySQL types to HTML4/5
     *
     * @var array
     */
    protected $_types = array(
        'int'       =>  'number',
        'tinyint'   =>  'number',
        'text'      =>  'text',
        'string'    =>  'text'
    );

    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);

        $this->getEventManager()->attach(new AppListener());
        $this->getEventManager()->attach(new SettingsListener());
        $this->getEventManager()->attach(new UserListener());
        $this->getEventManager()->attach(new LanguageListener());
        $this->getEventManager()->attach(new StaffListener());
    }

    /**
     * Called during validation operations, before validation. Please note that custom
     * validation rules can be defined in $validate.
     *
     * @param array $options
     *
     * @return bool
     */
    public function beforeValidate($options = array())
    {

        $this->getEventManager()->dispatch(new CakeEvent('Model.' . $this->getName() . '.beforeValidate', $this, array(

        )));

        return parent::beforeValidate($options);
    }

    /**
     * Called after each successful save operation.
     * Saves insert ids
     *
     * @param bool  $created - True if this save created a new record
     * @param array $options - Options passed from Model::save().
     *
     * @return bool|void
     */
    public function afterSave($created, $options = array())
    {
        if ($created) {
            $this->insert_ids[] = $this->getInsertID();
            return true;
        }
        return false;
    }

    /**
     * Default pagination scaffold settings
     *
     * @return array - returns default pagination settings
     */
    public function getPagination()
    {
        return array(
            'limit' =>  Configure::read('Settings.itemsPerPage')
        );
    }

    /**
     * Returns validation fields
     *
     * @return array
     */
    public function getValidation()
    {
        return $this->validate;
    }

    /**
     * Returns index data
     *
     * @param array $data - data array
     *
     * @return array
     */
    public function getAdminIndex($data = array())
    {
        if (empty($data)) {
            return array();
        }

        $newData = $data;

        if (isset($data[$this->name])) {
            $data = array('0' => $data);
        }

        foreach ($data as &$row) {
            foreach ($row[$this->name] as $key => $value) {
                $model = $this->belongs($key);
                if ($model != false) {
                    $options['recursive'] = 0;
                    $options['conditions'] = array($model . '.id' => $value);

                    $parent = ($this->{$model}->find('first', $options));

                    if (isset($parent[$model]['name'])) {
                        $row[$this->name][$key] = $parent[$model]['name'];
                    } elseif (isset($parent[$model]['username'])) {
                        $row[$this->name][$key] = $parent[$model]['username'];
                    }

                }
                if ($key == 'active') {
                    if ($value == 1) {
                        $row[$this->name][$key] = __('Yes', true);
                    } else {
                        $row[$this->name][$key] = __('No', true);
                    }
                }
            }
        }

        if (isset($newData[$this->name])) {
            $data = $data[0];
        }

        return $data;
    }

    /**
     * Returns index fields
     *
     * @return array
     */
    public function getAdminIndexSchema()
    {
        $options['fields'] = array();

        foreach ($this->_schema as $key => $value) {
            if ($key != 'order') {
                $options['fields'][] = $this->name . '.' . $key;
            }
        }
        return $options['fields'];
    }

    /**
     * Returns admin scaffold add fields
     *
     * @return array
     */
    public function getAdd()
    {
        $fields = array();

        foreach ($this->_schema as $key => $value) {
            if (($key != 'id') && ($key != 'order') && ($key != 'min_bet') && ($key != 'max_bet')) {
                $fields[] = $this->name . '.' . $key;
            }
        }

        return $this->getBelongings($fields);
    }

    /**
     * Returns admin scaffold edit fields
     *
     * @return array
     */
    public function getEdit($data)
    {
        $fields = array();

        foreach ($this->_schema as $key => $value) {
            if (($key != 'id') && ($key != 'order')) {
                $fields[] = $this->name . '.' . $key;
            }
        }
        return $this->getBelongings($fields);
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
        $options['fields'] = array();
        $options['recursive'] = -1;
        $options['conditions'] = array($this->name . '.id' => $id);

        foreach ($this->_schema as $key => $value) {
            if (($key != 'id') && ($key != 'order')) {
                $options['fields'][] = $this->name . '.' . $key;
            }
        }

        return $this->find('first', $options);
    }

    /**
     * Returns scaffold translate fields
     *
     * @return array
     */
    public function getTranslate()
    {
        $fields = $this->actsAs['Translate'];

        if (is_array($fields)) {
            return array_merge(array('id'), $fields);
        }

        return array();
    }

    /**
     * Model scaffold search fields wrapper
     *
     * @return array
     */
    public function getSearch()
    {
        if (!$this->_schema || !is_array($this->_schema)) {
            return array();
        }

        $type = 'text';
        $options['fields'] = array();

        foreach ($this->_schema as $key => $value) {
            if (($key == 'id') || ($key == 'order')) {
                continue;
            }

            if (isset($this->_types[$value['type']])) {
                $type = $this->_types[$value['type']];
            }

            $options['fields'][$this->name . '.' . $key] = array(
                'type'  =>  $type
            );

        }

        return $options['fields'];
    }

    /**
     * Assigns additional belongings
     *
     * @param $fields
     * @return mixed
     */
    public function getBelongings($fields)
    {
        foreach ($fields as $key => $value) {
            // If passed field with additional params
            if(is_array($value)) {
                $value = $key;
            }

            $model = $this->belongs($value);

            if ($model !== false) {
                unset($fields[$key]);
                $list = array_merge(array(null => '----'), $this->{$model}->find('list'));
                $fields[$value] = array(
                    'type' => 'select',
                    'options' => $list
                );
            }
        }
        return $fields;
    }



    /**
     *
     * @param string $value
     * @return bool|string
     */
    public function belongs($value)
    {
        $value = str_replace($this->name . '.', '', $value);
        $belonging = str_replace('_id', '', $value);
        $belonging = ucfirst($belonging);
        if (isset($this->belongsTo[$belonging])) {
            return $belonging;
        }
        return false;
    }

    /**
     * Returns fields schema type by field name
     *
     * @param $fieldName
     * @return null
     */
    public function getFieldType($fieldName)
    {
        $nameParts = explode('.', $fieldName);

        if(count($nameParts) == 2) {
            $fieldName = $nameParts[1];
        }

        if(isset($this->_schema[$fieldName]['type'])) {
            return $this->_schema[$fieldName]['type'];
        }

        return null;
    }

    /**
     * Returns field escaped condition
     *
     * @param null $fieldType
     * @param $fieldName
     * @return array
     */
    public function prepareField($fieldType = null, $fieldName)
    {
        $condition = array();

        if($fieldType == null) {
            $fieldType = $this->getFieldType($fieldName);
        }

        switch($fieldType) {
            case 'int':
            case 'tinyint':
            case 'bigint':
            case 'enum':
                $condition = array( $fieldName . ' = ?' => array( $fieldName ) );
                break;
            case 'string':
            case 'datetime':
                $condition = array( $fieldName.  ' LIKE ?' => array( $fieldName ) );
                break;

        }

        return $condition;
    }

    /**
     * Returns search fields
     *
     * @param array $data - fields data array
     *
     * @return array
     */
    public function getSearchFields(array $data)
    {
        $conditions = array();
        $searchFields = $this->getSearch();

        foreach ($data[$this->name] as $key => $value)
        {
            if(isset($searchFields[$this->name . '.' . $key]))
            {
                $conditions[$this->name . '.' . $key] = $this->prepareField(null, $this->name . '.' . $key);
            }
        }
        return $conditions;
    }

    /**
     * Returns field part
     *
     * @param $field
     * @param $type
     * @return null
     * @throws Exception
     */
    public function getFieldData($field, $type)
    {
        $fieldParts = explode('.', $field);

        if(count($fieldParts) == 1) {
            $fieldParts = array(
                0   =>  null,
                1   =>  $fieldParts
            );
        }

        switch($type)
        {
            case 'model':
                return $fieldParts[0];
                break;
            case 'name':
                return $fieldParts[1];
                break;
        }

        return null;
    }

    /**
     * Assigns escaped value from fieldValues to fieldMap
     *
     * @param $fieldMap
     * @param $fieldsValues
     * @param array $skipValues
     * @return array
     */
    public function assignFieldValues($fieldMap, $fieldsValues, $skipValues = array(''))
    {
        $result = array();

        foreach($fieldMap AS $query => $fields) {
            foreach($fields AS $fieldIndex => $field) {
                if(isset($fieldsValues[$this->getFieldData($field, 'name')]))
                {
                    if(in_array($fieldsValues[$this->getFieldData($field, 'name')], $skipValues))
                        return array();

                    $fieldValue = $fieldsValues[$this->getFieldData($field, 'name')];

                    /** TODO: on second diff value put in external function */
                    if(strpos(strtolower($query), 'like') !== false) {
                        $fieldValue = '%'.$fieldValue.'%';
                    }

                    $result[$query][$fieldIndex] = $fieldValue;
                }
            }
        }

        return $result;
    }

    /**
     * Builds and returns search conditions
     *
     * @param $data
     * @return array
     */
    public function getSearchConditions($data)
    {
        if(!isset($data[$this->name])) {
            return array();
        }

        $searchFields = $this->getSearch();

        $searchFieldsConditions = $this->getSearchFields($data);

        $missingConditions = array_diff(array_keys($searchFields), array_keys($searchFieldsConditions));

        foreach($missingConditions AS $missingCondition) {
            $fieldData = $this->prepareField(null, $missingCondition);

            if(!empty($fieldData)) {
                $searchFieldsConditions[$missingCondition] = $fieldData;
            }
        }

        $conditions = array();

        foreach ($data[$this->name] as $fieldName => $fieldValue)
        {
            /** If we don't have matched the field */
            if(!isset($searchFields[$this->name . '.' . $fieldName])) {
                $searchFields[$this->name . '.' . $fieldName] = $fieldValue;
            }

            /** If we don't have map for this the field */
            if(empty($searchFieldsConditions) || !isset($searchFieldsConditions[$this->name . '.' . $fieldName])) {
                continue;
            }

            $fieldMap = $searchFieldsConditions[$this->name . '.' . $fieldName];

            $conditions[] = $this->assignFieldValues($fieldMap, $data[$this->name]);
        }

        return array_filter($conditions);
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
                'plugin'        =>  $cakeRequest->params['plugin'],
                'controller'    =>  $cakeRequest->params['controller'],
                'action'        =>  'admin_view',
                'class'         =>  'btn btn-mini'
            ),

            1   =>  array(
                'name'          =>  __('Edit', true),
                'plugin'        =>  $cakeRequest->params['plugin'],
                'controller'    =>  $cakeRequest->params['controller'],
                'action'        =>  'admin_edit',
                'class'         =>  'btn btn-mini btn-primary'
            ),

            2   =>  array(
                'name'          =>  __('Delete', true),
                'plugin'        =>  $cakeRequest->params['plugin'],
                'controller'    =>  $cakeRequest->params['controller'],
                'action'        =>  'admin_delete',
                'class'         =>  'btn btn-mini btn-danger'
            )
        );
    }

    /**
     * Get item wrapper
     *
     * @param int   $id        - Item Id
     * @param array $contain   - Contain array
     * @param int   $recursive - Recursive
     *
     * @return array
     */
    public function getItem($id, $contain = array(), $recursive = -1)
    {
        return $this->find('first', array(
            'recursive'     =>  $recursive,
            'contain'       =>  $contain,
            'conditions'    =>  array(
                $this->name . '.id' =>  $id
            )
        ));
    }

    /**
     * Find item wrapper
     *
     * @param $conditions
     * @param $recursive
     * @param null $contain
     * @param null $limit
     * @return array
     */
    public function findItem($conditions, $recursive = -1, $contain = null, $limit = null) {
        $options = array();
        foreach ($conditions as $key => $value) {
            if (!empty($value)) {
                $options['conditions'][$this->name . '.' . $key . ' LIKE'] = '%' . $value . '%';
            }
        }
        if (isset($limit)) {
            $options['limit'] = $limit;
        }
        if (isset($contain)) {
            $this->contain($contain);
        } else {
            $options['recursive'] = $recursive;
        }
        return $this->find('all', $options);
    }

    /**
     * Returns count
     *
     * @param array $options
     * @return array
     */
    public function getCount($options = array())
    {
        return $this->find('count', array('conditions' => $options));
    }

    /**
     * Returns parent id
     *
     * @param $id
     * @return null
     */
    public function getParentId($id) {
        if (isset($this->belongsTo)) {
            $options['conditions'] = array(
                $this->name . '.id' => $id
            );
            $options['recursive'] = 0;
            $parent = $this->find('first', $options);
            $belongsTo = array_keys($this->belongsTo);
            $belongsTo = $belongsTo[0];
            return $parent[$belongsTo]['id'];
        }
        return null;
    }

    /**
     * Returns parent
     *
     * @return array|null
     */
    public function getParent() {
        if (!empty($this->belongsTo)) {
            $belongsTo = array_keys($this->belongsTo);
            $belongsTo = $belongsTo[0];
            return $belongsTo;
        }
        return null;
    }

    /**
     * Returns model name
     *
     * @param $name
     * @return string
     */
    public function getModelName($name)
    {
        if($name == 'App') {
            return $this->name;
        }
        
        return Inflector::singularize($name);
    }

    /**
     * Returns admin scaffold tabs
     *
     * @param array $params - url params
     *
     * @return array
     */
    public function getTabs(array $params) {

        $tabs = array(
            $params['controller'] . 'admin_index'   =>  array(
                'name'  =>  __('List', true),
                'url'   =>  array(
                    'plugin'        =>  $params['plugin'],
                    'controller'    =>  $params['controller'],
                    'action'        =>  'admin_index'
                )
            ),
            $params['controller'] . 'admin_add'     =>  array(
                'name'  =>  __('Create', true),
                'url'   =>  array(
                    'plugin'        =>  $params['plugin'],
                    'controller'    =>  $params['controller'],
                    'action'        =>  'admin_add'
                )
            )
        );

        $tabs[$params['controller'] . 'admin_add'] = array(
            'name' => __('Create', true),
            'url' => (array('plugin' => $params['plugin'], 'controller' => $params['controller'], 'action' => 'admin_add'))
        );

        if (isset($params['action'])) {
            if (($params['action'] == 'admin_view') || ($params['action'] == 'admin_edit')) {
                $tabs[$params['controller'] . 'admin_view'] = array(
                    'name' => __('View', true),
                    'url' => (array('plugin' => $params['plugin'], 'controller' => $params['controller'], 'action' => 'admin_view', $params['pass'][0]))
                );
                $tabs[$params['controller'] . 'admin_edit'] = array(
                    'name' => __('Edit', true),
                    'url' => (array('plugin' => $params['plugin'], 'controller' => $params['controller'], 'action' => 'admin_edit', $params['pass'][0]))
                );
            }
        }
        if (!isset($tabs[$params['controller'] . $params['action']]['name'])) {
            $tabs[$params['controller'] . $params['action']]['name'] = Inflector::humanize(str_replace('admin_', '', $params['action']));
        }
        $tabs[$params['controller'] . $params['action']]['active'] = 1;
        $tabs[$params['controller'] . $params['action']]['url'] = '#';

        return $tabs;
    }

    public function isOrderable() {
        $schema = $this->schema();
        if (isset($schema['order']))
            return true;
        return false;
    }

    public function getOrderBy()
    {
        return array();
    }

    public function moveUp($id) {
        $model = $this->name;
        $options['conditions'] = array(
            $model . '.id' => $id
        );
        $item = $this->find('first', $options);
        $order = $item[$model]['order'];
        $options['conditions'] = array(
            $model . '.order <' => $order
        );
        $options['order'] = $model . '.order DESC';
        $item2 = $this->find('first', $options);
        if (empty($item2))
            return;
        $item[$model]['order'] = $item2[$model]['order'];
        $item2[$model]['order'] = $order;
        $this->save($item);
        $this->save($item2);
    }

    public function moveDown($id) {
        $model = $this->name;
        $options['conditions'] = array(
            $model . '.id' => $id
        );
        $item = $this->find('first', $options);
        $order = $item[$model]['order'];
        $options['conditions'] = array(
            $model . '.order >' => $order
        );
        $options['order'] = $model . '.order ASC';
        $item2 = $this->find('first', $options);
        if (empty($item2))
            return;
        $item[$model]['order'] = $item2[$model]['order'];
        $item2[$model]['order'] = $order;
        $this->save($item);
        $this->save($item2);
    }

    public function findLastOrder() {
        $model = $this->name;
        $options['order'] = $model . '.order DESC';
        $item = $this->find('first', $options);
        if (!empty($item))
            return $item[$model]['order'];
        return 0;
    }

    /**
     * Returns theme field types
     *
     * @param $fieldType
     * @param $data
     * @return array
     */
    public function getFieldHtmlConfig($fieldType, $data = array())
    {
        $id = '';
        $label = null;
        $style = '';
        $options = array();

        if(isset($data['label'])) {
            $label = $data['label'];
        }

        if(isset($data['id'])) {
            $id = $data['id'];
        }

        if(isset($data['style'])) {
            $style = $data['style'];
        }

        if(isset($data['options'])) {
            $options = $data['options'];
        }

        $fields = array(
            /** Date type */
            'date'          =>  array(
                'div'               =>  'control-group',
                'class'             =>  'm-ctrl-medium date-picker',
                'before'            =>  '<div class="controls">',
                'type'              =>  'text',
                'after'             =>  '</div>',
                'data-date-format'  =>  'yyyy-mm-dd',
                'label'             =>  $label
            ),

            'datetime'      =>  array(
                'div'               =>  'control-group',
                'class'             =>  'm-ctrl-medium date-picker',
                'before'            =>  '<div class="controls">',
                'type'              =>  'text',
                'after'             =>  '</div>',
                'data-date-format'  =>  'yyyy-mm-dd'
            ),

            /** Number type */
            'number'        =>  array(
                'type'              =>  'number',
                'min'               =>  0,
                'step'              =>  'any',
                'label'             =>   $label != null ?  $label : ''
            ),

            /** Currency type */
            'currency'      =>  array(
                'style'     =>  $style != '' ? $style : 'width: 145px;',
                'div'       =>  'control-group',
                'before'    =>  '<div class="controls" style=""><label for="'. $id .'" style="position: relative; top: 0px;">'. $label .'</label><div class="input-prepend input-append"><span class="add-on">'. Configure::read('Settings.currency') .'</span>',
                'type'      =>  'text',
                'after'     =>  '<span class="add-on">.00</span></div></div>',
                'label'     =>  false
            ),

            /** Switch type */
            'switch'        =>  array(
                'style'             =>  '',
                'div'               =>  'control-group',
                'before'            =>  '<div style="'.$style.'" class="controls"><label for="'. $id .'">'. $label .'</label><div class="transition-value-toggle-button">',
                'type'              =>  'checkbox',
                'class'             => 'toggle',
                'after'             =>  '</div></div>',
                'label'             =>  false
            ),

            /** Select type */
            'select'    =>  array(
                'type'              =>  'select',
                'label'             =>   $label,
                'options'           =>  $options
            ),

            'auto-complete' =>  array(
                'type'              =>  'select',
                'data-provide'      =>  'typeahead',
                'data-items'        =>  '['. implode(',', $options) .']'
            ),

            'wysihtml5'     =>  array(
                'class'             => 'span12 wysihtml5',
                'style'             =>  'height: 80px;',
                'type'              =>  'textarea',
                'required'          =>  false
            ),

            'ckeditor'    =>  array(
                'class'             =>  'span12 ckeditor',
                'type'              =>  'textarea'
            )
        );

        if(!isset($fields[$fieldType]))
            return array();

        return $fields[$fieldType];
    }

    public function dateToStart($date)
    {
        return gmdate('Y-m-d H:i:s', strtotime($date . ' 00:00'));
    }

    public function dateToEnd($date)
    {
        return gmdate('Y-m-d H:i:s', strtotime($date . ' 23:59'));
    }

    public function getLastQuery()
    {
        $dbo = $this->getDatasource();
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);
        return $lastLog['query'];
    }

    public function gtQueryLog(){
        $dbo = $this->getDatasource();
        return $dbo->getLog();
    }
}