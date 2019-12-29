<?php

App::uses('AppHelper', 'View/Helper');
App::uses('News', 'Content.Model');

class NewsHelper extends AppHelper
{
    /**
     * Helper name
     *
     * @var string
     */
    public $name = 'Content.News';

    /**
     * Ticket Model Object
     *
     * @var $News News
     */
    private $News = null;

    /**
     * Helpers list
     *
     * @var array
     */
    public $helpers = array(
        0   =>  'Ajax',
        1   =>  'Session',
        2   =>  'Time'
    );

    /**
     * Default Constructor
     *
     * @param View $View The View this helper is being attached to.
     * @param array $settings Configuration settings for the helper.
     */
    public function __construct(View $View, $settings = array()) {
        $this->News = new News();
        parent::__construct($View, $settings);
    }

    /**
     * Returns news list
     *
     * @param int $limit
     *
     * @return array
     */
    public function getNews($limit = 5) {
        return  $this->News->getNews($limit);
    }
}