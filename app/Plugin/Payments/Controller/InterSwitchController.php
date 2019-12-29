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

class InterSwitchController extends PaymentAppController
{
    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'InterSwitch';

    /**
     * Component
     *
     * @var array
     */
//    public $components = array('Payments.InterSwitch');

    /**
     * Models
     *
     * @var array
     */
    public $uses = array('Payments.InterSwitch', 'Deposit');

    /**
     * Index (enter data) action
     */
    public function index() {}

    /**
     * Submit payment data
     *
     * @return void
     */
    public function submitPayment()
    {
        if (!$this->request->method('post')) {
            $this->redirect(array('plugin' => 'payments', 'controller' => $this->name), null, true);
        }

        if (!isset($this->request->data[$this->name]['amount']) || !is_numeric($this->request->data[$this->name]['amount'])) {
            $this->redirect(array('plugin' => 'payments', 'controller' => $this->name), null, true);
        }

        $interSwitchData = array(
            'product_id'    =>  $this->config['Fields']['product_id'],
            'pay_item_id'   =>  $this->config['Fields']['pay_item_id'],
            'hash'          =>  null
        );

        $paymentAppData = array(
            'userId'            =>  $this->Auth->user('id'),
            'transaction_id'    =>  null,
            'amount'            =>  str_replace(',', '.', $this->request->data[$this->name]['amount']),
            'state'             =>  self::STATE_PENDING,
            'model'             =>  $this->InterSwitch->name,
            'time'              =>  time()
        );

        $paymentIds                         =   $this->InterSwitch->savePayment($interSwitchData, $paymentAppData);
        $this->config['Fields']['txn_ref']  =   $paymentIds['paymentAppId'] . 60000;
        $this->config['Fields']['amount']   =   $paymentAppData['amount'];
        $hash                               =   $this->InterSwitch->generateHash($this->config);

        $this->InterSwitch->updatePayment($paymentIds['interSwitchId'], array(
            'hash'  =>  $hash
        ));

        $this->set('submitUrl', $this->config['Config']['submit_url']);
        $this->set('fields', $this->config['Fields']);
    }

    /**
     * Validate data
     */
    public function callback()
    {
        $url = 'https://stageserv.interswitchng.com/test_paydirect/Pay/ResponsePost/?resp=Z4&desc=,%20mac%20validation%20failed&txnRef=6460000&payRef=&retRef=&cardNum=0&apprAmt=0&url=http%3a%2f%2fchalkpro.dev.com%2fpayments%2fInterSwitch%2fcallback';

        $url = parse_url($url);

        foreach (explode('&', $url['query']) as $key=>$value) {
            echo "$key = " . urldecode($value) . "<br />\n";
        }

//        var_dump(parse_str($url['query']));

        exit;

        App::uses('HttpSocket', 'Network/Http');

        $HttpSocket = new HttpSocket();

        $paymentState = false;

        if(isset($this->request->query['TRANSACTION_ID'])) {
            $paymentData = $this->InterSwitch->find('first', array(
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