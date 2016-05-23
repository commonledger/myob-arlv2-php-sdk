<?php


namespace MYOB\AccountRight\Api\GeneralLedger;


use MYOB\AccountRight\Api\AbstractEndpoint;
use MYOB\AccountRight\HttpClient\HttpClient;

class JournalTransaction extends AbstractEndpoint {

    private $prefix;

    public function __construct($prefix, HttpClient $client) {
        parent::__construct($client);

        $this->prefix = $prefix;
    }


    public function getAll(array $filters = array()){

        try{
            $response = $this->client->get("{$this->prefix}/JournalTransaction", $filters);
        } catch (Exception $e) {
            echo $e->getMessage();
            throw $e;
        }

        return $response->body;
    }


}