<?php
/**
 * PayPal payment config file
 */
$config = array(
    'PayPalModel.Config'  =>  array(
        'Environment'   =>  'Production',
        'Production'    =>  array(
            'Config'        =>  array(
                'submit_url'    =>  '',
                'client_id'     =>  'AaMrGJ3ulg4fp5I9_gKnLwjmSW1p7cSunlypfz6T5P4uqBugOIPi3Rr4vIcaGXMxQtkjThbdItdospqp',
                'client_secret' =>  'EPH152sgW2IJ4IYVXf8jk2T5C_UA_BAIeQjoE1TJh8bS5nz05aaMFIl7pTyxWsn71Vp-OoxFVhhNWaOC',
                "callback1"                 =>  Router::url(
                    array(
                        'language'      =>  Configure::read('Config.language'),
                        'plugin'        =>  'payments',
                        'controller'    =>  'Paypal',
                        'action'        =>  'callback'
                    ), true
                ),
                "callback2"                 =>  'http://demo.planet1x2.com',
            ),
            'Fields'    =>  array(
                "callback_url"           =>  Router::url(
                    array(
                        'language'      =>  Configure::read('Config.language'),
                        'plugin'        =>  'payments',
                        'controller'    =>  'Paypal',
                        'action'        =>  'callback'
                    ), true
                )
            )
        )
    )
);