<?php
/**
 * CountryFixture
 *
 */
class CountryFixture extends CakeTestFixture {

    /**
     *
     * @var $import string
     */
    public $import = array('model' => 'Country', 'records' => true);

    /**
     * Fields / Schema for the fixture.
     * This array should match the output of Model::schema()
     *
     * @var array
     */
	public $fields = array(
		'id'                =>  array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'primary'),
		'import_id'         =>  array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'index'),
		'name'              =>  array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes'           =>  array(
			'PRIMARY'   => array('column' => 'id', 'unique' => 1),
			'import_id' => array('column' => 'import_id', 'unique' => 0)
		),
		'tableParameters'   =>  array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}