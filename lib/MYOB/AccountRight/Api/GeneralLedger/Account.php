<?php



namespace MYOB\AccountRight\Api\GeneralLedger;



use MYOB\AccountRight\Api\AbstractEndpoint;
use MYOB\AccountRight\HttpClient\HttpClient;

class Account extends AbstractEndpoint {

    private $prefix;

    public function __construct(string $prefix, HttpClient $client) {
        parent::__construct($client);

        $this->prefix = $prefix;
    }


    public function getAll(array $filters = array()){
        $response = $this->client->get("{$this->prefix}/Account", $filters);
        return $response->body;
    }


}