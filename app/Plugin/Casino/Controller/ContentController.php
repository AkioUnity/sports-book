<?php

App::uses('CasinoAppController', 'Casino.Controller');

class ContentController extends CasinoAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Content';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('User');
	
    public function index() 
    {
        $this->layout = 'casino';
        /*
        $this->set('data', $result);
        $this->set('Sport', $Sport);
        $this->set('League', $LeagueId);
        $this->set('slides', $this->Slide->getSlides());
        */
    }

    
}