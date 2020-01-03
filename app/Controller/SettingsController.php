<?php
/**
 * Handles Settings
 *
 * Handles Script Settings
 *
 * @package    Settings
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class SettingsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Settings';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Setting');

    /**
     * Admin Index scaffold functions
     *
     * @param array $conditions -   admin_index scaffold conditions
     * @param null  $model      -   admin_index $model name
     *
     * @return array
     */
    public function admin_index($conditions = array(), $model = null)
    {
        if (!empty($this->request->data)) {            
            if ($this->Setting->saveSettings($this->request->data, 'generalSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('can\'t save settings.', true), 'error');
            }
        }

        $data = $this->Setting->getGeneralSettings();
        $this->request->data = $data;

        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->request->params));
        
        $this->loadModel('Currency');
        $this->loadModel('Languages');

        $currencies = $this->Currency->getList();
        $locales = $this->Language->getLanguagesListIds();

        $this->set('currencies', $currencies);
        $this->set('locales', $locales);
    }

    public function admin_seo()
    {
        if (!empty($this->request->data)) {            
            if ($this->Setting->saveSettings($this->request->data, 'seoSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }
        $data = $this->Setting->getSeoSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->request->params));
    }
    
    public function admin_warnings()
    {
        if (!empty($this->request->data)) {            
            if ($this->Setting->saveSettings($this->request->data, 'warningsSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }
        $data = $this->Setting->getWarningsSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->request->params));
    }

    public function admin_tickets() {
        if (!empty($this->request->data)) {            
            if ($this->Setting->saveSettings($this->request->data, 'ticketsSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }
        $data = $this->Setting->getTicketsSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->request->params));
    }

    public function admin_deposits() {
        if (!empty($this->request->data)) {            
            if ($this->Setting->saveSettings($this->request->data, 'depositsSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }
        $data = $this->Setting->getDepositsSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->request->params));
    }

    public function admin_depositsRisks() {
        if (!empty($this->request->data)) {            
//            if ($this->Setting->saveSettings($this->request->data, 'depositsRisksSettings')) {
//                $this->Admin->setMessage(__('Settings saved.', true), 'success');
//            } else {
//                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
//            }
        }
        $data = $this->Setting->getDepositsRisksSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->request->params));
    }

    public function admin_withdraws() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'withdrawsSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
            }
        }
        $data = $this->Setting->getWithdrawsSettings();
        $this->request->data = $data;        
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->request->params));
    }

    /**
     * Withdraws Risks
     */
    public function admin_withdrawsRisks()
    {
        if (!empty($this->request->data)) {
//            if ($this->Setting->saveSettings($this->request->data, 'withdrawsRisksSettings')) {
//                $this->Admin->setMessage(__('Settings saved.', true), 'success');
//            } else {
//                $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
//            }
        }
        $data = $this->Setting->getWithdrawsRisksSettings();

        $this->request->data = $data;

        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->request->params));
    }
       
    public function admin_referral() {
    	if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'referralSettings')) {
    			$this->Admin->setMessage(__('Settings saved.', true), 'success');
    		} else {
    			$this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
    		}
    	}
    	$data = $this->Setting->getReferralSettings();
    	$this->request->data = $data;
    	$this->set('data', $data);
    	$this->set('tabs', $this->Setting->getTabs($this->request->params));
    }

    /**
     * Admin promo
     */
    public function admin_promo()
    {
    	if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'promoSettings')) {
    			$this->Admin->setMessage(__('Settings saved.', true), 'success');
    		} else {
    			$this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
    		}
    	}

    	$data = $this->Setting->getPromoSettings();

    	$this->request->data = $data;

    	$this->set('data', $data);
    	$this->set('tabs', $this->Setting->getTabs($this->request->params));
    }
    
    public function admin_deposit() {

        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'depositSettings')) {
        $this->Admin->setMessage(__('Settings saved.', true), 'success');
        } else {
        $this->Admin->setMessage(__('Can\'t save settings.', true), 'error');
        }
        }
        $data = $this->Setting->getDepositsSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->request->params));

    }
}