<?php


namespace MYOB\AccountRight\Api;


use MYOB\AccountRight\HttpClient\HttpClient;

abstract class AbstractEndpoint {

    protected $client;

    public function __construct(HttpClient $client){
        $this->client = $client;
    }

}