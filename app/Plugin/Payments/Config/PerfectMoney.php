<?php
/**
 * Entrazact payment config file
 */
$config = array(
    'PerfectMoney.Config'  =>  array(
        'Environment'   =>  'Production',
        'Production'    =>  array(
            'Config'        =>  array(
                'submit_url'        =>  'https://perfectmoney.is/api/step1.asp',
                'signature'         =>  '62P9W74aEREHzzizKgBEzuqGJ',
                'validates'             =>  array(
                    'payments'   =>  array(),
                    'withdraws'  =>  array(
                        'account'   =>  array(
                            'notEmpty'  =>  array(
                                'required'  =>  true,
                                'rule'      =>  array('notEmpty'),
                                'message'   =>  __("Account field are required and should be no larger than 50 characters long.")
                            ),
                            'maxLength' =>  array(
                                'required'  =>  true,
                                'rule'      =>  array('maxLength', 50),
                                'message'   =>  __("Account field are required and should be no larger than 50 characters long.")
                            )
                        ),
                        'amount'    =>  array(
                            'required'   =>  array(
                                'required'  =>  true,
                                'rule'      =>  array('money', 'left'),
                                'message'   =>  __("Amount field are required and should be a valid monetary amount.")
                            ),
                            'min'       =>  array(
                                'rule'      =>  array('validateWithdrawMinAmount'),
                                'message'   =>  __('Lowest amount for cash out is %s%s', Configure::read('Settings.minWithdraw'), Configure::read('Settings.currency'))
                            ),
                            'max'       =>  array(
                                'rule'      =>  array('validateWithdrawMaxAmount'),
                                'message'   =>  __('Highest amount for cash out is %s%s', Configure::read('Settings.maxWithdraw'), Configure::read('Settings.currency'))
                            ),
                            'balance'   =>  array(
                                'rule'      =>  array('validateWithdrawBalance'),
                                'message'   =>  __('Not enough balance for requested cash out transaction.')
                            ),
                            'pending'   =>  array(
                                'rule'      =>  array('validateWithdrawHasPending'),
                                'message'   =>  __('Cannot add new cash out transaction while previous withdrawal transactions are not completed.')
                            )
                        )
                    )
                ),
                'callback_fields'   =>  array(
                    0   =>  'PAYMENT_ID',
                    1   =>  'PAYEE_ACCOUNT',
                    2   =>  'PAYMENT_AMOUNT',
                    3   =>  'PAYMENT_UNITS',
                    4   =>  'PAYMENT_BATCH_NUM',
                    5   =>  'PAYER_ACCOUNT',
                    6   =>  'TIMESTAMPGMT'
                )
            ),
            'Fields'    =>  array(
                'PAYEE_ACCOUNT'         =>  "U2966133",
                'PAYEE_NAME'            =>  "ChalkPro PerfectMoney",
                "PAYMENT_ID"            =>  null,
                "PAYMENT_AMOUNT"        =>  null,
                'PAYMENT_UNITS'         =>  "USD",
                "SUGGESTED_MEMO"        =>  "ChalkPro PerfectMoney Test Account",
                "PAYMENT_URL_METHOD"    =>  "LINK",
                "NOPAYMENT_URL"         =>  Router::url(
                        array(
                            'language'      =>  Configure::read('Config.language'),
                            'plugin'        =>  false,
                            'controller'    =>  'deposits',
                            'action'        =>  'choose'
                        ), true
                    ),
                "NOPAYMENT_URL_METHOD"  =>  "LINK",
                "BAGGAGE_FIELDS"        =>  "",
                "STATUS_URL"            =>  Router::url(
                        array(
                            'language'      =>  Configure::read('Config.language'),
                            'plugin'        =>  'payments',
                            'controller'    =>  'PerfectMoney',
                            'action'        =>  'callback'
                        ), true
                    ),
                "PAYMENT_URL"           =>  Router::url(
                        array(
                            'language'      =>  Configure::read('Config.language'),
                            'plugin'        =>  'payments',
                            'controller'    =>  'PerfectMoney',
                            'action'        =>  'success'
                        ), true
                    )
            )
        )
    )
);