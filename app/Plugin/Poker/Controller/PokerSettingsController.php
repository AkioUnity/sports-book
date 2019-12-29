<?php

App::uses('PokerAppController', 'Poker.Controller');

class PokerSettingsController extends PokerAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'PokerSettings';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Poker.PokerSetting');

    public function admin_settings()
    {
        if (!empty($this->request->data)) {
            if ($this->PokerSetting->saveSettings($this->request->data, 'pokerSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }
			
        $data = $this->PokerSetting->getPokerSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->PokerSetting->getTabs($this->request->params));
    }
}