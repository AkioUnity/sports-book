<?php
/**
 * Entrazact payment config file
 */
$config = array(
    'InterSwitch.Config'  =>  array(
        'Environment'   =>  'Test',
        'Production'    =>  array(
            'Config'        =>  array(
                'Submit_url'    =>  'https://stageserv.interswitchng.com/test_paydirect/pay',
                'mac_key'       =>  null
            ),
            'Fields'    =>  array(
                'Fields'    =>  array(
                    'product_id'        =>  4220,
                    'pay_item_id'       =>  101,
                    'amount'            =>  null,
                    'currency'          =>  566,
                    'txn_ref'           =>  null,
                    'hash'              =>  null,
                    'site_redirect_url' =>  Router::url(
                        array(
                            'language'      =>  Configure::read('Config.language'),
                            'plugin'        =>  'payments',
                            'controller'    =>  'InterSwitch',
                            'action'        =>  'callback'
                            ), true
                    )
                )
            )
        ),
        'Test'              =>  array(
            'Config'        =>  array(
                'submit_url'    =>  'https://stageserv.interswitchng.com/test_paydirect/pay',
                'mac_key'       =>  null
            ),
            'Fields'    =>  array(
                'product_id'        =>  4220,
                'pay_item_id'       =>  101,
                'amount'            =>  null,
                'currency'          =>  566,
                'txn_ref'           =>  null,
                'hash'              =>  '199F6031F20C63C18E2DC6F9CBA7689137661A05ADD4114ED10F5AFB64BE625B6A9993A634F590B64887EEB93FCFECB513EF9DE1C0B53FA33D287221D75643AB',
                'site_redirect_url' =>  Router::url(
                    array(
                        'language'      =>  Configure::read('Config.language'),
                        'plugin'        =>  'payments',
                        'controller'    =>  'InterSwitch',
                        'action'        =>  'callback'
                        ), true
                )
            )
        )
    )
);