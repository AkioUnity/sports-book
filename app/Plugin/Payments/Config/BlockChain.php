<?php
/**
 * Entrazact payment config file
 */
$config = array(
    'BlockChain.Config'  =>  array(
        'Environment'   =>  'Production',
        'Production'    =>  array(
            'Config'        =>  array(
                'submit_url'            =>  '',
                "api_key"               =>  "7ce0258c-5f24-4525-b29d-838adaa39785",
                'test'                  =>  false,
                'blockchain_root'       =>  'https://blockchain.info/',
                'secret'                =>  null,
                'xpub'                  =>  'xpub6D8PXB3FLvPkzKi8a1ErPPWyKUJkYqhRSRTfdqam2UtJJzZgELUhcAJLANr4pgteVvprEiFJYKQdbirtHMFBo1SPUiyXLQkxwmPzcMbKMiW'

            ),
            'Fields'    =>  array(
                "callback_url"           =>  Router::url(
                        array(
                            'language'      =>  Configure::read('Config.language'),
                            'plugin'        =>  'payments',
                            'controller'    =>  'BlockChain',
                            'action'        =>  'callback'
                        ), true
                    )
            )
        )
    )
);