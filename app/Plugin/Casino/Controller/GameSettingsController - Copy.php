<?php

App::uses('CasinoAppController', 'Casino.Controller');

class GameSettingsController extends CasinoAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'GameSettings';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Casino.GameSetting');

    public function admin_soccer_hero()
    {
        if (!empty($this->request->data)) {
            if ($this->GameSetting->saveSettings($this->request->data, 'soccerHeroSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }
            
        $data = $this->GameSetting->getSoccerHeroSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->GameSetting->getTabs($this->request->params));
    }

    public function admin_highlow()
    {
        if (!empty($this->request->data)) {
            if ($this->GameSetting->saveSettings($this->request->data, 'highLowSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }
			
        $data = $this->GameSetting->getHighLowSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->GameSetting->getTabs($this->request->params));
    }
	
	public function admin_blackjack()
    {
        if (!empty($this->request->data)) {
            if ($this->GameSetting->saveSettings($this->request->data, 'blackJackSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }
			
        $data = $this->GameSetting->getBlackJackSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->GameSetting->getTabs($this->request->params));
    }
	
	public function admin_roulette()
    {
        if (!empty($this->request->data)) {
            if ($this->GameSetting->saveSettings($this->request->data, 'rouletteSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }
			
        $data = $this->GameSetting->getRouletteSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->GameSetting->getTabs($this->request->params));
    }
	
	public function admin_baccarat()
    {
        if (!empty($this->request->data)) {
            if ($this->GameSetting->saveSettings($this->request->data, 'baccaratSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }
			
        $data = $this->GameSetting->getBaccaratSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->GameSetting->getTabs($this->request->params));
    }
	
	public function admin_stud()
    {
        if (!empty($this->request->data)) {
            if ($this->GameSetting->saveSettings($this->request->data, 'studSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }
			
        $data = $this->GameSetting->getStudSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->GameSetting->getTabs($this->request->params));
    }
	
	public function admin_scratch()
    {
        if (!empty($this->request->data)) {
            if ($this->GameSetting->saveSettings($this->request->data, 'scratchSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }
			
        $data = $this->GameSetting->getScratchSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->GameSetting->getTabs($this->request->params));
    }
	
	public function admin_slot_christmas()
    {
        if (!empty($this->request->data)) {
            if ($this->GameSetting->saveSettings($this->request->data, 'slotChristmasSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }

        $data = $this->GameSetting->getSlotChristmasSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->GameSetting->getTabs($this->request->params));
    }
	
	public function admin_slot_chicken()
    {
        if (!empty($this->request->data)) {
            if ($this->GameSetting->saveSettings($this->request->data, 'slotChickenSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }

        $data = $this->GameSetting->getSlotChickenSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->GameSetting->getTabs($this->request->params));
    }
	
	public function admin_slot_ramses()
    {
        if (!empty($this->request->data)) {
            if ($this->GameSetting->saveSettings($this->request->data, 'slotRamsesSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }

        $data = $this->GameSetting->getSlotRamsesSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->GameSetting->getTabs($this->request->params));
    }
	
	public function admin_slot_space()
    {
        if (!empty($this->request->data)) {
            if ($this->GameSetting->saveSettings($this->request->data, 'slotSpaceSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }

        $data = $this->GameSetting->getSlotSpaceSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->GameSetting->getTabs($this->request->params));
    }
	
	public function admin_slot_fruits()
    {
        if (!empty($this->request->data)) {
            if ($this->GameSetting->saveSettings($this->request->data, 'slotFruitsSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }

        $data = $this->GameSetting->getSlotFruitsSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->GameSetting->getTabs($this->request->params));
    }
}