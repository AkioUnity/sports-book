<?php
$config = array(
    'PaymentAppModel.Config' =>  array(
        'Environment'   =>  'Production',
        'Production'    =>  array(
            'availableProviders'    =>  array(
                0   =>  array(
                    'title' =>  'Manual',
                    'value' =>  'Manual'
                ),
                1   =>  array(
                    'title' =>  'EgoPay',
                    'value' =>  'EgoPay'
                ),
                2   =>  array(
                    'title' =>  'PerfectMoney',
                    'value' =>  'PerfectMoney'
                )
            )
        )
    )
);