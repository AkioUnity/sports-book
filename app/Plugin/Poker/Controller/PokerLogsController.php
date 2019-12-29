<?php

App::uses('PokerAppController', 'Poker.Controller');

class PokerLogsController extends PokerAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'PokerLogs';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Poker.PokerLog', 'User');
	
    public function index()
    {
        $this->layout = 'user-panel';
        $userId = $this->Auth->user('id');
        $this->Paginator->settings  =   array($this->PokerLog->name => array(
            "conditions"    =>  array(
                'PokerLog.user_id'    => $userId
            ),
            "order" =>  array(
                'PokerLog.created' => 'DESC'
            ),
            "limit" =>  20
        ));

        $pokerlogs = $this->Paginator->paginate( $this->PokerLog->name );

        $this->set(compact('pokerlogs'));
    }
}