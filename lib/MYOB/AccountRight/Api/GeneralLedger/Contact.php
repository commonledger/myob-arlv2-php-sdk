<?php



namespace MYOB\AccountRight\Api\GeneralLedger;



use MYOB\AccountRight\Api\AbstractEndpoint;
use MYOB\AccountRight\HttpClient\HttpClient;

class Contact extends AbstractEndpoint {

    private $prefix;

    public function __construct($prefix, HttpClient $client) {
        parent::__construct($client);

        $this->prefix = 'Contact';
    }

    public function getAll($type = 'Customer', array $filters = array()){
        $response = $this->client->get("{$this->prefix}/{$type}", $filters);
        return $response->body;
    }


}