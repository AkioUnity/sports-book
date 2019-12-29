<?php 
class PageSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

    public $pages_i18n = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
        'locale' => array('type' => 'string', 'null' => false, 'length' => 6, 'key' => 'index'),
        'model' => array('type' => 'string', 'null' => false, 'key' => 'index'),
        'foreign_key' => array('type' => 'integer', 'null' => false, 'length' => 10, 'key' => 'index'),
        'field' => array('type' => 'string', 'null' => false, 'key' => 'index'),
        'content' => array('type' => 'text', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'locale' => array('column' => 'locale', 'unique' => 0), 'model' => array('column' => 'model', 'unique' => 0), 'row_id' => array('column' => 'foreign_key', 'unique' => 0), 'field' => array('column' => 'field', 'unique' => 0))
    );

    public $menu_i18n = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
        'locale' => array('type' => 'string', 'null' => false, 'length' => 6, 'key' => 'index'),
        'model' => array('type' => 'string', 'null' => false, 'key' => 'index'),
        'foreign_key' => array('type' => 'integer', 'null' => false, 'length' => 10, 'key' => 'index'),
        'field' => array('type' => 'string', 'null' => false, 'key' => 'index'),
        'content' => array('type' => 'text', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'locale' => array('column' => 'locale', 'unique' => 0), 'model' => array('column' => 'model', 'unique' => 0), 'row_id' => array('column' => 'foreign_key', 'unique' => 0), 'field' => array('column' => 'field', 'unique' => 0))
    );

    public $pages = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
        'title' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'comment' => 'Page title'),
        'url' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'comment' => 'Page url'),
        'content' => array('type' => 'text', 'null' => true, 'default' => null, 'comment' => 'Page content'),
        'keywords' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 255, 'comment' => 'Page meta keywords'),
        'description' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 255, 'comment' => 'Page meta description'),
        'active' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1,  'comment' => 'Page active'),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
    );

    public $menu = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
        'parent_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'comment' => 'Tree ParentId'),
        'lft' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'comment' => 'Node lft value'),
        'rght' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'comment' => 'Node rght value'),
        'title' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'comment' => 'Page title'),
        'url' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'comment' => 'Page url'),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
    );

}
