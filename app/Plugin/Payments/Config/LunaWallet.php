<?php
/**
 * Entrazact payment config file
 */
$config = array(
    'LunaWallet.Config'  =>  array(
        'Environment'   =>  'Production',
        'Production'    =>  array(
            'Config'        =>  array(
                'submit_url'            =>  '',
                "username"              =>  "secondtestaccount",
                "api_key"               =>  "19d7e1b570ba99ba3a11e8210a2b6af8c2db5f47e71dcccc160936e05cf928bf",
                'test'                  =>  false,
                'lunawallet_root'       =>  'https://www.lunawallet.org/api'
            ),
            'Fields'    =>  array(
                "callback_url"           =>  Router::url(
                        array(
                            'language'      =>  Configure::read('Config.language'),
                            'plugin'        =>  'payments',
                            'controller'    =>  'LunaWallet',
                            'action'        =>  'callback'
                        ), true
                    )
            )
        )
    )
);