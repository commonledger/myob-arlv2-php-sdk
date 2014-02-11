<?php

namespace MYOB\AccountRight\Exception;

use MYOB\AccountRight\HttpClient\Response;

class UnexpectedResponseException extends ClientException {

    public $response;

    public function __construct($message, Response $response){
        $this->response = $response;
        parent::__construct($message, 500);
    }

}