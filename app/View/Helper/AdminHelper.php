<?php

App::uses('AppHelper', 'View/Helper');
App::uses('AppModel', 'Model');

class AdminHelper extends AppHelper
{
    /**
     * Helper name
     *
     * @var string
     */
    public $name = 'Admin';

    /**
     * Application model
     *
     * @var AppModel $AppModel
     */
    public $AppModel = null;

    public function getSingularName() {
        return ucfirst($this->params['controller']);
    }

    public function getPluralName() {
        return ucfirst(Inflector::pluralize($this->params['controller']));
    }
}