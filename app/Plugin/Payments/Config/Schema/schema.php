<?php
class PaymentSchema extends CakeSchema {

    public function before($event = array()) {
        return true;
    }

    public function after($event = array()) {
    }

    public $payments_payments = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'primary'),
        'userId' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'comment' => 'UserId'),
        'transaction_id' => array('type' => 'biginteger', 'null' => true, 'default' => null, 'length' => 22, 'comment' => 'Payment provider transaction id'),
        'amount' => array('type' => 'float', 'null' => false, 'default' => null, 'comment' => 'Payment amount'),
        'state' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8_general_ci', 'comment' => 'Payment state', 'charset' => 'utf8'),
        'model' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'comment' => 'Payment model', 'charset' => 'utf8'),
        'time' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 9, 'comment' => 'Payment time'),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $payments_ego_pay_deposits = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'primary'),
        'payment_app_id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'index'),
        'currency' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 5, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'hash' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'payment_app_id' => array('column' => 'payment_app_id', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $payments_ego_pay_withdraws = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'primary'),
        'payment_app_id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'index'),
        'account' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'amount' => array('type' => 'float', 'null' => false, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'payment_app_id' => array('column' => 'payment_app_id', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $payments_perfect_money_deposits = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'primary'),
        'payment_app_id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22),
        'payee_account' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'payment_amount' => array('type' => 'float', 'null' => true, 'default' => null),
        'payment_units' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'payment_batch_num' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'payer_account' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'timestampgmt' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 9),
        'v2_hash' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $payments_perfect_money_withdraws = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'primary'),
        'payment_app_id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'index'),
        'account' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'amount' => array('type' => 'float', 'null' => false, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'payment_app_id' => array('column' => 'payment_app_id', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );
}