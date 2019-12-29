<?php
 /**
 * Handles e-tranzact payments
 *
 * Long description for class (if any)...
 *
 * @package    Payments
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('PaymentAppController', 'Payments.Controller');

class EtranzactController extends PaymentAppController
{
    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'Etranzact';

    /**
     * Component
     *
     * @var array
     */
    public $components = array('Payments.Etranzact');

    /**
     * Models
     *
     * @var array
     */
    public $uses = array('Payments.Etranzacts', 'Deposit');

    /**
     * Index (enter data) action
     */
    public function index() {}


    /**
     * Submit data
     */
    public function submitPayment()
    {
        if(!$this->request->method('post')) {
            $this->redirect(array('plugin' => 'payments', 'controller' => $this->name), 301, true);
        }

        if(!isset($this->request->data[$this->name]['amount']) || !is_numeric($this->request->data[$this->name]['amount'])) {
            $this->redirect(array('plugin' => 'payments', 'controller' => $this->name), 301, true);
        }

        $amount = $this->request->data[$this->name]['amount'];

        $this->Etranzacts->save(array(
            'userId'        =>  $this->Auth->user('id'),
            'terminal_id'   =>  $this->config['Fields']['TERMINAL_ID'],
            'amount'        =>  $amount,
            'state'         =>  self::STATE_PENDING,
            'time'          =>  time()
        ));

        $transaction_id = $this->Etranzacts->getLastInsertID();

        $this->config['Fields']['TRANSACTION_ID'] = $transaction_id;
        $this->config['Fields']['AMOUNT'] = $amount;
        $this->config['Fields']['RESPONSE_URL'] = Router::url(array(
                'language' => Configure::read('Config.language'),
                'plugin' => 'payments',
                'controller' => strtolower($this->name),
                'action' => 'callback'
            ),  true
        );
        $this->config['Fields']['LOGO_URL'] = Router::url('/theme/ChalkPro/img/logo.png', true);
        $this->config['Fields']['EMAIL'] = $this->Auth->user('email');
        $this->config['Fields']['CHECKSUM'] = md5($amount . $this->config['Fields']['TERMINAL_ID'] . $transaction_id . $this->config['Fields']['RESPONSE_URL'] . $this->config['Config']['SECRET_KEY']);


        $this->set('submitUrl', $this->config['Config']['SUBMIT_URL']);
        $this->set('fields', $this->config['Fields']);
    }

    /**
     * Validate data
     */
    public function callback()
    {
        App::uses('HttpSocket', 'Network/Http');

        $HttpSocket = new HttpSocket();

        $paymentState = false;

        if(isset($this->request->query['TRANSACTION_ID'])) {
            $paymentData = $this->Etranzacts->find('first', array(
                'conditions' => array(
                    'id'        =>  $this->request->query['TRANSACTION_ID'],
                    'userId'    =>  $this->Auth->user('id'),
                    'state'     =>  self::STATE_PENDING
                )
            ));

            if(!empty($paymentData)) {
                $httpResponse = $HttpSocket->post($this->config['Config']['QUERY_URL'],
                    array(
                        'TERMINAL_ID'       =>  $this->config['Fields']['TERMINAL_ID'],
                        'TRANSACTION_ID'    =>  $this->request->query['TRANS_NUM'],
                        'RESPONSE_URL'      =>  Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'payments', 'controller' => strtolower($this->name), 'action' => 'callback'), true)
                    )
                );

                if($httpResponse->isOk()) {
                    $response = preg_match('/SUCCESS=.*\'>/i', $httpResponse->body, $matches);

                    if(isset($matches[0]) && !empty($matches[0])) {

                        parse_str($matches[0], $response);

                        if(isset($response['SUCCESS']) && $response['SUCCESS'] == 0)
                        {
                            $paymentData['Etranzacts']['state'] = self::STATE_COMPLETED;
                            $paymentData['Etranzacts']['transaction_id'] = $response['TRANSACTION_ID'];
                            $paymentData['Etranzacts']['cardNo'] = $response['CARD4'];

                            $DepositData = $this->Deposit->addDeposit($this->Auth->user('id'), $paymentData['Etranzacts']['amount'], null, $paymentData['Etranzacts']['transaction_id'], uniqid($this->Auth->user('id') . '-'), "");

                            $this->Deposit->setStatus($DepositData['Deposit']['id'], 'completed');

                            $this->Deposit->User->addFunds($this->Auth->user('id'), $paymentData['Etranzacts']['amount']);

                            $paymentState = true;
                        }else{
                            $paymentData['Etranzacts']['state'] = self::STATE_FAILED;
                        }

                        $this->Etranzacts->save($paymentData, false);
                    }
                }
            }
        }

        if($paymentState) {
            $this->App->setMessage(__('You have successfully made deposit for ' . $paymentData['Etranzacts']['amount']) . ' ' . Configure::read('Settings.currency'), 'success');
        }else{
            $this->App->setMessage(__('Sorry, we can not find your payment'), 'error');
        }

        $this->redirect(array('plugin' => 'payments', 'action' => 'result'), 302, true);
    }
}