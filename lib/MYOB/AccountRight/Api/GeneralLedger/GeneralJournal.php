<?php



namespace MYOB\AccountRight\Api\GeneralLedger;



use MYOB\AccountRight\Api\AbstractEndpoint;
use MYOB\AccountRight\HttpClient\HttpClient;

class GeneralJournal extends AbstractEndpoint {

    private $prefix;

    public function __construct($prefix, HttpClient $client) {
        parent::__construct($client);

        $this->prefix = $prefix;
    }


    public function getAll(){
        $response = $this->client->get("{$this->prefix}/GeneralJournal");

        return $response->body;
    }


}