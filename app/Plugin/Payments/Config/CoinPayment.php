<?php
/**
 * Skrill payment config file
 */
$config = array(
    'CoinPayment.Config'  =>  array(
        'Environment'   =>  'Production',
        'Production'    =>  array(
            'Config'        =>  array(
                'merchant_id'   =>  '0ef59df961c5ed22d1dbafb1cddccba3',
                'ipn_secret'    =>  'test123',
                'public_key'    =>  '50eaad9248d549be74e6a1af5eacecc94964a725d3c1eb924853fa53e654922c',
                'private_key'   =>  'FA57A465c6D76C767635894e1b0874681E151Ca3607829ecD18b1838c416861C',
                "callback"                  =>  Router::url(
                    array(
                        'language'      =>  Configure::read('Config.language'),
                        'plugin'        =>  'payments',
                        'controller'    =>  'CoinPayments',
                        'action'        =>  'callback'
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
            ),
            'Fields'    =>  array(
                "callback_url"           =>  Router::url(
                    array(
                        'language'      =>  Configure::read('Config.language'),
                        'plugin'        =>  'payments',
                        'controller'    =>  'Skrill',
                        'action'        =>  'callback'
                    ), true
                )
            )
        )
    )
);