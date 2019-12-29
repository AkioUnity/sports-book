<?php
/**
 * Novinpal payment config file
 */
$config = array(
    'Novinpal.Config'  =>  array(
        'Environment'   =>  'Production',
        'Production'    =>  array(
            'Config'        =>  array(
                'submit_url'    =>  '',
                'API'                       =>  'K27tJ',
                'PASS'                      =>  'M2933493m',
                "gateway"                   =>  151,
                "callback1"                 =>  Router::url(
                    array(
                        'language'      =>  Configure::read('Config.language'),
                        'plugin'        =>  'payments',
                        'controller'    =>  'Novinpal',
                        'action'        =>  'callback'
                    ), true
                ),
                "callback2"                 =>  'http://demo.betscheme.com',
            ),
            'Fields'    =>  array(
                "callback_url"           =>  Router::url(
                    array(
                        'language'      =>  Configure::read('Config.language'),
                        'plugin'        =>  'payments',
                        'controller'    =>  'Novinpal',
                        'action'        =>  'callback'
                    ), true
                )
            )
        )
    )
);