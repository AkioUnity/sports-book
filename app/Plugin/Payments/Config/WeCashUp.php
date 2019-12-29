<?php
/**
 * Skrill payment config file
 */
$config = array(
    'WeCashUp.Config'  =>  array(
        'Environment'   =>  'Production',
        'Production'    =>  array(
            'Config'        =>  array(
                'merchant_uid'          =>  'cnUoo9Bn0VWnFAPSQMaTXAfso9e2',
                'merchant_public_key'   =>  'pk_test_UI3OOre3GHlCHNiP',
                'merchant_secret'       =>  'sk_test_ma27Z4Y7PIvTXTB9fXcdosbT3aGu63XsrbJYMca5ayER',
                "callback"                  =>  Router::url(
                    array(
                        'language'      =>  Configure::read('Config.language'),
                        'plugin'        =>  'payments',
                        'controller'    =>  'WeCashUp',
                        'action'        =>  'callback'
                    ), true
                ),
                "webhoook"                  =>  Router::url(
                    array(
                        'language'      =>  Configure::read('Config.language'),
                        'plugin'        =>  'payments',
                        'controller'    =>  'WeCashUp',
                        'action'        =>  'webhook'
                    ), true
                ),
                "return_url"        =>  Router::url(
                    array(
                        'language'      =>  Configure::read('Config.language'),
                        'plugin'        =>  false,
                        'controller'    =>  'deposits',
                        'action'        =>  'index'
                    ), true
                )
            )
        )
    )
);