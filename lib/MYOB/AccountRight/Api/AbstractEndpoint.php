<?php


namespace MYOB\AccountRight\Api;


use MYOB\AccountRight\HttpClient\HttpClient;

abstract class AbstractEndpoint {

    protected $client;

    public function __construct(HttpClient $client){
        $this->client = $client;
    }

    // Obtain any item from the MYOB API - the uri should be the expected MYOB URL e.g. "Contact/Customer/[UID]"
    public function getOne($uri) {
        $filters = array(
            '$top' => 1,
            '$skip' => 0
        );
        $response = $this->client->get($uri, $filters);
        return $response->body;
    }
}

