<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 'PhpSpreadSheet', array('file' => 'PhpSpreadsheet/src/Bootstrap.php'));

class CreditPaymentInvoicesController extends AppController
{

    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'CreditPaymentInvoices';

    /**
     * Component
     *
     * @var array
     */
    public $components = array();

    /**
     * Models
     *
     * @var array
     */
    public $uses = array('Payments.CreditPaymentInvoice', 'Withdraw', 'Deposit');

    /**
     * Called before the controller action.
     *
     * @return void
     *
     * @throws Exception
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function admin_export()
    {
        $model = $this->__getModel();
        $conditions = array();

        $this->request->data[$model] = $this->request->query;

        if (!empty($this->request->data)) {
            $conditions = array_merge(
                $conditions,
                $this->{$model}->getSearchConditions($this->request->data)
            );

            if (!empty($conditions)) {

                $invoices = $this->CreditPaymentInvoice->getExport($conditions);

                // Create new Spreadsheet object
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                //Set active sheet index to the first sheet,
                //and add some data
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', __('CUSTOMER NO'))
                    ->setCellValue('B1', __('PERSNR'))
                    ->setCellValue('C1', __('NAME'))
                    ->setCellValue('D1', __('ADDRESS LINE 1'))
                    ->setCellValue('E1', __('ADDRESS LINE 2'))
                    ->setCellValue('F1', __('CITY'))
                    ->setCellValue('G1', __('POSTCODE'))
                    ->setCellValue('H1', __('TELEPHONE'))
                    ->setCellValue('I1', __('EMAIL'))
                    ->setCellValue('J1', __('COUNTRY'))
                    ->setCellValue('K1', __('INVOICE NO'))
                    ->setCellValue('L1', __('REFERENCE'))
                    ->setCellValue('M1', __('AGREEMENT DATE'))
                    ->setCellValue('N1', __('INVOICE DATE'))
                    ->setCellValue('O1', __('TERMS'))
                    ->setCellValue('P1', __('INTEREST %'))
                    ->setCellValue('Q1', __('PAYMENT DATE'))
                    ->setCellValue('R1', __('LOAN AMOUNT'))
                    ->setCellValue('S1', __('TOTAL DUE'))
                    ->setCellValue('T1', __('CURRENCY'));

                // Set worksheet title
                $spreadsheet->getActiveSheet()->setTitle('Invoices');

                /// Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $spreadsheet->setActiveSheetIndex(0);

                foreach ($invoices AS $index => $invoice)
                {
                    $row = $index + 2;
                    $spreadsheet->setActiveSheetIndex(0)
                                ->setCellValue('A' . $row, $invoice["User"]["id"])
                                ->setCellValue('B' . $row, __('PERSNR'))
                                ->setCellValue('C' . $row, sprintf("%s %s ( %s )", $invoice["User"]["first_name"], $invoice["User"]["last_name"], $invoice["User"]["username"]))
                                ->setCellValue('D' . $row, $invoice["User"]["address1"])
                                ->setCellValue('E' . $row, $invoice["User"]["address2"])
                                ->setCellValue('F' . $row, $invoice["User"]["city"])
                                ->setCellValue('G' . $row, $invoice["User"]["zip_code"])
                                ->setCellValue('H' . $row, $invoice["User"]["mobile_number"])
                                ->setCellValue('I' . $row, $invoice["User"]["email"])
                                ->setCellValue('J' . $row, $invoice["User"]["country"])
                                ->setCellValue('K' . $row, $invoice["CreditPaymentInvoice"]["id"])
                                ->setCellValue('L' . $row, __('REFERENCE'))
                                ->setCellValue('M' . $row, date("Y-m-d H:i:s", $invoice["Payment"]["time"]))
                                ->setCellValue('N' . $row, date("Y-m-d H:i:s", $invoice["CreditPaymentInvoice"]["time"]))
                                ->setCellValue('O' . $row, __('TERMS'))
                                ->setCellValue('P' . $row, $invoice["CreditPaymentsDeposits"]["interest_rate"])
                                ->setCellValue('Q' . $row, __('PAYMENT DATE'))
                                ->setCellValue('R' . $row, $invoice["Deposit"]["amount"])
                                ->setCellValue('S' . $row, date("Y-m-d H:i:s", $invoice["CreditPaymentInvoice"]["time"]))
                                ->setCellValue('T' . $row, Configure::read('Settings.currency'));
                }


                // Set style for header
                $spreadsheet->getActiveSheet()->getStyle('A1:T1')->applyFromArray(array(
                    'font'      =>  array('bold' => true),
                    'alignment' =>  array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
                ));

                // Set with
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'. 'invoices' .'.xls"');
                header('Cache-Control: max-age=0');

                //new code:
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            }
        }

        $this->set('actions', $this->{$model}->getActions($this->request));
        $this->set('controller', $this->params['controller']);
    }
}