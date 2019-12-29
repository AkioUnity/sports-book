<?php
/**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    <package>
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('Controller', 'Controller');
App::uses('CakeEvent', 'Event');
App::uses('CakeEventManager', 'Event');
App::uses('SettingsListener', 'Event');
App::uses('View', 'View');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('PhpSpreadSheet', 'Lib/PhpSpreadsheet');
App::uses('CakeEmail', 'Network/Email');
App::uses('EmailComponent', 'Controller/Component');
App::import('Vendor', 'PhpSpreadSheet', array('file' => 'PhpSpreadsheet/src/Bootstrap.php'));
App::import('Vendor', 'dompdf', array('file' => 'dompdf' . DS . 'autoload.inc.php'));

if (!isset($_SERVER["SCRIPT_FILENAME"])) $_SERVER["SCRIPT_FILENAME"] = "";

class CreditPaymentShell extends AppShell
{
    /**
     * @internal
     */
    public $uses = array('Payments.CreditPayment', 'Payments.CreditPaymentInvoice', 'Setting', 'Currency');

    /**
     * For settings listener
     *
     * @var null
     */
    public $Auth = null;

    /**
     * CakeEventManager
     *
     * @var CakeEventManager $CakeEventManager null
     */
    protected $CakeEventManager = null;

    /**
     * @var EmailComponent $EmailComponent
     */
    protected $EmailComponent = null;

    /**
     * Constructor
     *
     *  * Attaches Event Listener
     */
    public function __construct() {
        $this->CakeEventManager = new CakeEventManager();
        $this->CakeEventManager->instance()->attach(new SettingsListener());
        $this->CakeEventManager->instance()->dispatch(new CakeEvent('Controller.App.beforeFilter', $this, array()));

        parent::__construct();
    }

    /**
     * Initializes the Shell
     */
    public function initialize() {
        $this->EmailComponent = new EmailComponent(new ComponentCollection());
        $this->EmailComponent->initialize(new Controller());

        parent::initialize();
    }

    private function pdf($invoice = array(), $user = array(), $credit = array(), $amount = array())
    {
        $view = new View(new Controller());

        $view->view = 'invoice';
        $view->layout = 'printing';
        $view->viewPath = 'CreditPaymentInvoices';
        $view->plugin = 'Payments';

        $view->set('invoice', $invoice);
        $view->set('user', $user);
        $view->set('credit', $credit);
        $view->set('amount', $amount);

        $pdfFile = TMP . 'invoices' . DS . 'pdf' . DS . 'invoice-' . $invoice["CreditPaymentInvoice"]["id"] . '.pdf';

        $CacheFolder = new Folder();

        if(!$CacheFolder->cd(TMP . 'invoices' . DS . 'pdf')) {
            new Folder(TMP . 'invoices' . DS . 'pdf', true, 0777);
        }

        $pdf = new File($pdfFile);
        $render = $view->render();

        if(!$pdf->exists()) {

            $domPdf = new \Dompdf\Dompdf();

            $domPdf->setBasePath(realpath(WWW_ROOT));

            $domPdf->loadHtml($render);

            $domPdf->render();

            $pdf->write($domPdf->output());
        }

        return array(
            0   =>  str_replace(WWW_ROOT . 'theme/Design/img/logo.png', FULL_BASE_URL . '/theme/Design/img/logo.png', $render),
            1   =>  $pdf->path
        );
    }

    /**
     * Send invoices
     */
    public function invoices()
    {
        $this->periods = array(
            0   =>  CreditPaymentInvoice::INVOICE_PERIOD_DAY_15,
            1   =>  CreditPaymentInvoice::INVOICE_PERIOD_DAY_22,
            2   =>  CreditPaymentInvoice::INVOICE_PERIOD_DAY_29,
            3   =>  CreditPaymentInvoice::INVOICE_PERIOD_DAY_36
        );

        foreach ($this->periods AS $period)
        {
            // This part creates invoices for 1 cycle and creates 2 cycle init ( 15d )
            foreach ($this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->invoices($period) AS $payment)
            {
                $data = current($payment);
                $user = $this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->Deposit->User->getItem($data["userId"], array());
                $credit = $this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getCredit($data["userId"]);

                // No data?
                if (empty($data) || empty($user) || empty($credit)) {
                    continue;
                }

                $this->invoice(null, $period, array(), $payment, $credit, $user, $credit["Deposit"]["date"], date("Y-m-d H:i:s"));
            }

            // This loop handles other all cycles
            foreach ($this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->cycle($period) AS $payment)
            {
                $data = current($payment);
                $user = $this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->Deposit->User->getItem($data["userId"], array());
                $credit = $this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getCredit($data["userId"]);

                $period = $payment["invoices"]["interval"];
                // No data?

                if (empty($data) || empty($user) || empty($credit)) {
                    continue;
                }

                switch ($payment["invoices"]["interval"]) {
                    case CreditPaymentInvoice::INVOICE_PERIOD_DAY_15:
                        $period = CreditPaymentInvoice::INVOICE_PERIOD_DAY_22;
                        break;

                    case CreditPaymentInvoice::INVOICE_PERIOD_DAY_22:
                        $period = CreditPaymentInvoice::INVOICE_PERIOD_DAY_29;
                        break;

                    case CreditPaymentInvoice::INVOICE_PERIOD_DAY_29:
                        $period = CreditPaymentInvoice::INVOICE_PERIOD_DAY_36;
                        break;
                }

                $this->invoice($payment["invoices"]["id"], $period, array("CreditPaymentInvoice" => array("id" => $payment["invoices"]["parent"])), $payment, $credit, $user, date("Y-m-d H:i:s", $payment["invoices"]["time"]), date("Y-m-d H:i:s"));
            }
        }
    }

    /**
     *
     * @param $id
     * @param $period
     * @param $parent
     * @param $invoice
     * @param $credit
     * @param $user
     * @param $lostFrom
     * @param $lostTo
     */
    private function invoice($id, $period, $parent, $invoice, $credit, $user, $lostFrom, $lostTo)
    {
        $data = current($invoice);
        $prev = array();

        switch ($period) {
            case CreditPaymentInvoice::INVOICE_PERIOD_DAY_15:
                $lost = $this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getLostAmount($data["userId"], $lostFrom, $lostTo);
                $percentage_amount = ($credit["CreditPayment"]["interest_rate"] * $lost ) / 100;
                break;
            case CreditPaymentInvoice::INVOICE_PERIOD_DAY_22:
                $prev = $this->CreditPaymentInvoice->setStatus($id, $data["userId"], CreditPaymentInvoice::INVOICE_PERIOD_DAY_15, CreditPaymentInvoice::INVOICE_STATUS_REISSUED);
                $percentage_amount =  125 + 250;
                $lost = $prev["CreditPaymentInvoice"]["amount"];
                break;
            case CreditPaymentInvoice::INVOICE_PERIOD_DAY_29:
                $prev =  $this->CreditPaymentInvoice->setStatus($id, $data["userId"], CreditPaymentInvoice::INVOICE_PERIOD_DAY_22, CreditPaymentInvoice::INVOICE_STATUS_REISSUED);
                $percentage_amount = 250;
                $lost = $prev["CreditPaymentInvoice"]["amount"];
                break;
            case CreditPaymentInvoice::INVOICE_PERIOD_DAY_36:
                $prev =  $this->CreditPaymentInvoice->setStatus($id, $data["userId"], CreditPaymentInvoice::INVOICE_PERIOD_DAY_29, CreditPaymentInvoice::INVOICE_STATUS_TRANSFERRED);
                break;
        }

        // If user lost anything since last invoice start over from 15d.
        if ($period != CreditPaymentInvoice::INVOICE_PERIOD_DAY_15 && !empty($prev))
        {
            $cycle = $this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getLostAmount($data["userId"], date("Y-m-d H:i:s", $prev[$this->CreditPaymentInvoice->name]["time"]));

            if ($cycle != 0) {
                $this->invoice(
                    null,
                    CreditPaymentInvoice::INVOICE_PERIOD_DAY_15,
                    $prev,
                    $invoice,
                    $credit,
                    $user,
                    date("Y-m-d H:i:s", $prev[$this->CreditPaymentInvoice->name]["time"]),
                    date("Y-m-d H:i:s")
                );
            }
        }

        // 36 day change status of 22 day invoice to transferred
        if ($period != CreditPaymentInvoice::INVOICE_PERIOD_DAY_36)
        {
            $amounts = array('lost' => $lost, 'percentage' => $credit["CreditPayment"]["interest_rate"], 'percentage_amount' => $percentage_amount, 'total' => ($lost + $percentage_amount));

            $invoice = $this->CreditPaymentInvoice->add(
                $parent,
                $data['userId'],
                $invoice["deposits"]["deposit_id"],
                $period,
                $lost + $percentage_amount,
                CreditPaymentInvoice::INVOICE_STATUS_PENDING,
                time()
            );

            $pdf = $this->pdf($invoice, $user, $credit, $amounts);

            $this->EmailComponent->send($user["User"]["email"], 'Redodds invoice', $pdf[0], array(), array($pdf[1]));
        }
    }
}