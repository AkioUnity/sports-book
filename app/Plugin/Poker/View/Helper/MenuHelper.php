<?php

App::uses('AppHelper', 'View/Helper');
App::uses('Menu', 'Casino.Model');

class MenuHelper extends AppHelper
{
    /**
     * Helper name
     *
     * @var string
     */
    public $name = 'Menu';

    /**
     *
     * @var $Menu Menu
     */
    private $Menu = null;

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
        $this->Menu = new Menu();
        parent::__construct($View, $settings);
    }

    /**
     * Menu wrapper
     *
     * @param string $type
     * @return array
     */
    public function getMenu($type = 'top') {
        switch($type) {
            case 'top':
                return $this->Menu->getMenuById(Menu::MENU_TYPE_TOP);
                break;
            case 'sidebar':
                return $this->Menu->getMenuById(Menu::MENU_TYPE_TOP);
                break;
            case 'bottom':
                return $this->Menu->getMenuById(Menu::MENU_TYPE_BOTTOM);
                break;
            default:
                return array();
                break;
        }
    }
}