<?php
App::uses('Participant', 'Events.Model');

/**
 * Participant Test Case
 *
 */
class ParticipantTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.events.participant',
		'plugin.events.import',
		'plugin.events.country',
		'plugin.events.league',
		'plugin.events.sport',
		'plugin.events.event',
		'plugin.events.bet',
		'plugin.events.bet_part'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Participant = ClassRegistry::init('Events.Participant');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Participant);

		parent::tearDown();
	}

}
