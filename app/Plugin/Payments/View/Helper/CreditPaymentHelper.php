<?php

App::uses('Helper', 'View');
App::uses('CreditPayment', 'Payments.Model');

class CreditPaymentHelper extends Helper
{
    /**
     * Helper name
     *
     * @var string
     */
    public $name = 'CreditPayment';

    /**
     * Default Constructor
     *
     * @param View $View The View this helper is being attached to.
     * @param array $settings Configuration settings for the helper.
     */
    public function __construct(View $View, $settings = array()) {
        $this->CreditPayment = new CreditPayment();
        parent::__construct($View, $settings);
    }

    public function balance($userId, $balance, $admin = false)
    {
        $currency = Configure::read('Settings.currency');
        $balanceDecimalPlaces = intval(Configure::read('Settings.balance_decimal_places'));
        $balanceFormatted = number_format($balance, $balanceDecimalPlaces, '.', ' ');

        if (!$admin) {
            return sprintf("%s %s", $balanceFormatted, $currency);
        }

        $creditPayment = $this->CreditPayment->getCredit($userId);

        if (empty($creditPayment)) {
            return sprintf("%s %s", $balanceFormatted, $currency);
        }

        $paymentBalance = $balance - $creditPayment["CreditPayment"]["starting_balance"];
        $paymentBalance = $paymentBalance - $creditPayment["PaymentAppModel"]["amount"];
        $paymentBalance = number_format($paymentBalance, $balanceDecimalPlaces,'.', ' ');

        return sprintf(
            "%s %s ( %s [ %s %s ] )",
            $balanceFormatted,
            $currency,
            $creditPayment["CreditPayment"]["risk_level"],
            $paymentBalance,
            $currency
        );
    }
}