<?php
/**
 * Skrill payment config file
 */
$config = array(
    'Skrill.Config'  =>  array(
        'Environment'   =>  'Production',
        'Production'    =>  array(
            'Config'        =>  array(
                'submit_url'    =>  '',
                'email'                     =>  'kayzor.merchant@gmail.com',
                'secret'                    =>  'fvlguw5dt',
                "callback1"                 =>  Router::url(
                    array(
                        'language'      =>  Configure::read('Config.language'),
                        'plugin'        =>  'payments',
                        'controller'    =>  'Skrill',
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