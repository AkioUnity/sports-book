<?php
/**
 * Entrazact payment config file
 */
$config = array(
    'Etranzact.Config'  =>  array(
        'Config'    =>  array(
            'SUBMIT_URL'    =>  'http://demo.etranzact.com/WebConnectPlus/caller.jsp',
            'QUERY_URL'     =>  'http://demo.etranzact.com/WebConnectPlus/query.jsp',
            'SECRET_KEY'    =>  'DEMO_KEY'
        ),
        'Fields'    =>  array(
            'TERMINAL_ID'       =>  '0000000001',
            'TRANSACTION_ID'    =>  null,
            'AMOUNT'            =>  null,
            'DESCRIPTION'       =>  __('Deposit'),
            'RESPONSE_URL'      =>  null,
            'LOGO_URL'          =>  null,
            'CURRENCY_CODE'     =>  'NGN',
            'EMAIL'             =>  null,
            'CHECKSUM'          =>  null,
        )
    )
);