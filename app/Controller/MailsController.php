<?php
/**
 * Front Mails Controller
 *
 * Handles Mails Actions
 *
 * @package    Mails
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class MailsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Mails';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Mail', 'User');

    /**
     * Array containing the names of components this controller uses.
     *
     * @var array
     */
    public $components = array();

    /**
     * An array containing the names of helpers this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array();

    /**
     * Called before the controller action.
     *
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
    }

    /**
     * User contacts form
     *
     * @return void
     */
    public function contact()
    {
        if (!empty($this->request->data)) {
            $this->Mail->set($this->request->data);
            if ($this->Mail->validates())
            {
                $isSend = $this->Email->sendMail(null, Configure::read('Settings.contactMail'), array(
                    'subject'   =>  $this->request->data['Mail']['subject'],
                    'name'      =>  $this->request->data['Mail']['name'],
                    'email'     =>  $this->request->data['Mail']['email'],
                    'content'   =>  $this->request->data['Mail']['message']
                ));

                if ($isSend) {
                    $this->App->setMessage(__('Email successfully sent', true), 'success');
                } else {
                    $this->App->setMessage(__('Cannot send email. Please try again.', true), 'error');
                }
            }
            $this->request->data = array();
        }
    }

    /**
     * Admin Index scaffold functions
     *
     * @param array $conditions -   admin_index scaffold conditions
     * @param null  $model      -   admin_index $model name
     *
     * @return array
     */
    function admin_index($conditions = array(), $model = null)
    {
        if (!empty($this->request->data)) {
            if ($this->Mail->validates()) {
                $isSend = $this->Email->sendMail(null, $this->request->data['Mail']['to'], array(
                    'subject'   =>  $this->request->data['Mail']['subject'],
                    'content'   =>  $this->request->data['Mail']['content']
                ));
                if ($isSend) {
                    $this->Admin->setMessage(__('Email successfully sent', true), 'success');
                } else {
                    $this->Admin->setMessage(__('Cannot send email. Please try again.', true), 'error');
                }
            }

            $this->request->data = array();
        }

        $this->set('tabs', $this->Mail->getTabs($this->request->params));
    }

    public function admin_all() {
        if (!empty($this->request->data)) {
            //get all mails
            $bcc = $this->User->getAllEmails();
            App::uses('Validation', 'Utility');
            foreach ($bcc as $key => $to) {
                if (!Validation::email($to)) {
                    unset($bcc[$key]);
                }
            }

            foreach ($bcc AS $bc) {
                $isSend = $this->Email->sendMail(null, $bc, array(
                    'subject'   =>  $this->request->data['Mail']['subject'],
                    'content'   =>  $this->request->data['Mail']['content']
                ));
            }

            if ($isSend) {
                $this->Admin->setMessage(__('Emails successfully sent', true), 'success');
            } else {
                $this->Admin->setMessage(__('One or more email cannot be send. Please try again.', true), 'error');
            }

            $this->request->data = array();
        }
        $this->set('tabs', $this->Mail->getTabs($this->request->params));
    }
}