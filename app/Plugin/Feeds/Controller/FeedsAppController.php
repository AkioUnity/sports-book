<?php

App::uses('AppController', 'Controller');

class FeedsAppController extends AppController
{
    public $name = 'FeedsApp';

    public $uses = array('Feeds.OddService', 'Country', 'Sport', 'League', 'Event', 'Bet', 'BetPart');

    /**
     * Method which implements countries importing / updating
     *
     * @return mixed
     */
    public function importCountries() {}

    /**
     * Method which implements sports importing / updating
     *
     * @return mixed
     */
    public function importSports() {}

    /**
     * Method which implements leagues importing / updating
     *
     * @return mixed
     */
    public function importLeagues() {}

    /**
     * Method which implements events importing / updating
     *
     * @return mixed
     */
    public function importEvents() {}
}