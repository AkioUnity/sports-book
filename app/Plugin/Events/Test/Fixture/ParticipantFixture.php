<?php
/**
 * ParticipantFixture
 *
 */
class ParticipantFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'primary'),
		'import_id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'index', 'comment' => 'Participant import id'),
		'country_id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'comment' => 'Participant country id'),
		'name' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Fist and Last names of the participant', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'import_id' => array('column' => 'import_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '',
			'import_id' => '',
			'country_id' => '',
			'name' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
		),
	);

}
