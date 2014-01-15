<?php


namespace MYOB\AccountRight\Exception;


use Exception;

class ClientException extends Exception {

    public $response = null;
    public $code = null;

    public function __construct($message, $code) {
        $this->code = $code;

        parent::__construct($message);
    }
}