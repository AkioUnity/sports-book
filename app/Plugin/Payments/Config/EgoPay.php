<?php
/**
 * Entrazact payment config file
 */
$config = array(
    'EgoPay.Config'  =>  array(
        'Environment'   =>  'Production',
        'Production'    =>  array(
            'Config'        =>  array(
                'submit_url'            =>  EgoPaySci::EGOPAY_PAYMENT_URL,
                'egopay_store_id'       =>  'YLQ5LNA5WQEX',
                'egopay_store_password' =>  'Dig7qQpRkLU70P1aqvEyWasSW1dvVPLQ',
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
                'callback_fields'       =>  array(
                    0   =>  'sId',
                    1   =>  'fAmount',
                    2   =>  'sCurrency',
                    3   =>  'fFee',
                    4   =>  'sType',
                    5   =>  'iTypeId',
                    6   =>  'sEmail',
                    7   =>  'sDetails',
                    8   =>  'sStatus',
                    9   =>  'cf_1',
                    10  =>  'sDate',
                    11  =>  'checksum'
                )
            ),
            'Fields'    =>  array(
                'amount'            =>  null,
                'currency'          =>  "USD",
                "description"       =>  "",
                "cf_1"              =>  "",
                "success_url"            =>  Router::url(
                        array(
                            'language'      =>  Configure::read('Config.language'),
                            'plugin'        =>  'payments',
                            'controller'    =>  'EgoPay',
                            'action'        =>  '/'
                        ), true
                    ),
                "fail_url"              =>  Router::url(
                        array(
                            'language'      =>  Configure::read('Config.language'),
                            'plugin'        =>  'payments',
                            'controller'    =>  'EgoPay',
                            'action'        =>  '/'
                        ), true
                    ),
                "callback_url"           =>  Router::url(
                        array(
                            'language'      =>  Configure::read('Config.language'),
                            'plugin'        =>  'payments',
                            'controller'    =>  'EgoPay',
                            'action'        =>  'callback'
                        ), true
                    )
            )
        )
    )
);