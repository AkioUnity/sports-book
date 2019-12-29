<?php

namespace Blockchain\Receive;

use \Blockchain\Blockchain;

class Receive {
    public function __construct(Blockchain $blockchain) {
        $this->blockchain = $blockchain;
    }

    public function generate($address, $callback=null) {
        $params = array(
            'method'=>'create',
            'address'=>$address
        );
        if(!is_null($callback)) {
            $params['callback'] = $callback;
        }

        return new ReceiveResponse($this->blockchain->post('api/receive', $params));
    }
}