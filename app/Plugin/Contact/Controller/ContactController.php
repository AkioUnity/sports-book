<?php
/**
 * Front Pages Controller
 *
 * Handles Pages Actions
 *
 * @package    Pages
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

//App::uses('ContactAppController', 'Contact.Controller');
//App::uses('String', 'Utility');

class ContactController extends ContactAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Contact';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        'Contact.Contact'
    );



    /**
     * Called before the controller action.
     *
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('index'));
    }

    function index(){
        $this->layout = 'contact-page';
        if ($this->request->is('post')) {
            $email = new CakeEmail();
            try {
                $email->config('smtp');
            } catch (Exception $e) {
                Throw new ConfigureException('Config in email.php not found. ' . $e->getMessage());
            }

            $data = $this->request->data['Contact']; 

            //print_r($data);            

            if ($this->Contact->validates()) {   

                $isSend = $this->Email->sendMail(null, Configure::read('Contact.email'), array(
                    'subject'   =>  $this->request->data['Contact']['subject'],
                    'name'      =>  $this->request->data['Contact']['name'],
                    'email'     =>  $this->request->data['Contact']['email'],
                    'content'   =>  $this->request->data['Contact']['message']
                ));

                if ($isSend) {
                    $this->App->setMessage(__('Email successfully sent', true), 'success');

                } else {
                    $this->App->setMessage(__('Cannot send email. Please try again.', true), 'error');
                }
                
                $this->redirect('/');



/*                $this->Email->to = Configure::read('Contact.email');
                $this->Email->from = $this->data['Contact']['email'];
                $this->Email->replyTo = $this->data['Contact']['email'];
                $this->Email->subject = __d('contacts', 'New Contact', true);
                $this->Email->template = 'index';
                $this->Email->sendAs = 'text';
                $this->set('contact', $this->data);
                $this->Email->send();
                $this->Session->setFlash(__d('contact', 'contact form was submitted successfully'), '', array('status' => 'success'));*/
                //$this->redirect('/');
            }

            $this->request->data = array();
                
        }
    }



}