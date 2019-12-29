<?php

App::uses('AppModel', 'Model');

class FeedsAppModel extends AppModel {
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Feed';

    /**
     * Table name for this Model.
     *
     * @var string
     */
    public $table = 'feeds';
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
        'name'      => array(
            'type'      => 'string',
            'length'    => 20,
            'null'      => false
        ),
        'url'       => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'timezone'  => array(
            'type'      => 'string',
            'length'    => 20,
            'null'      => false
        ),
        'active'    => array(
            'type'      => 'tinyint',
            'length'    => 1,
            'null'      => false
        ),
        'last_update' => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => false
        )
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

        unset($actions[1]);
        unset($actions[2]);

        $actions[]   =  array(
            'name'          => __('Update', true),
            'action'        => 'update',
            'controller'    => NULL,
            'class'         => 'btn btn-mini btn-warning'
        );

        return $actions;
    }

    /**
     * Returns tabs
     *
     * @param $params
     * @return array
     */
    public function getTabs(array $params)
    {
        $tabs = parent::getTabs($params); // list add search

        if(isset($tabs['feedsadmin_add'])) { unset($tabs['feedsadmin_add']); }

        $tabs['feedsadmin_updateAll'] = array(
            'name'      =>  __('Update All', true),
            'active'    =>  $params['action'] == 'updateAll',
            'url'       =>  array(
                'plugin'        =>  'feeds',
                'controller'    =>  'feeds',
                'action'        =>  'updateAll'
            )
        );

        return $tabs;
    }

    /**
     * Get feed
     *
     * @param $id
     * @return array
     */
    public function getFeed($id) {
        $options['conditions'] = array('Feed.id' => $id);
        return $this->find('first', $options);
    }

    /**
     * Save update time
     *
     * @param $id
     * @param $date
     */
    public function updated($id, $date)
    {
        $options['conditions'] = array(
            'Feed.id' => $id
        );

        $data = $this->find('first', $options);

        $data['Feed']['last_update'] = $date;

        $this->save($data);
    }

    /**
     * Returns active feeds
     *
     * @return array
     */
    public function getActiveFeeds()
    {
        $options['conditions'] = array(
            'active' => 1
        );
        $feeds = $this->find('all', $options);
        return $feeds;
    }

    /**
     * Returns index fields
     *
     * @return array
     */
    public function getIndex() {
        $options['fields'] = array(
            'Feed.id',
            'Feed.name',
            'Feed.timezone',
            'Feed.active',
            'Feed.last_update'
        );
        return $options;
    }

    /**
     * Returns add fields
     *
     * @return array|mixed
     */
    public function getAdd() {
        return array(
            'Feed.name',
            'Feed.url',
            'Feed.timezone',
            'Feed.active'
        );
    }

    /**
     * Returns edit fields
     *
     * @return array|mixed
     */
    public function getEdit($data) {
        return array(
            'Feed.name',
            'Feed.url',
            'Feed.timezone',
            'Feed.active'
        );
    }

    /**
     * Returns search fields
     *
     * @return array
     */
    public function getSearch() {
        return array(
            'Feed.name',
            'Feed.url',
            'Feed.timezone',
            'Feed.active'
        );
    }
}