<?php



namespace MYOB\AccountRight\Api\GeneralLedger;

use MYOB\AccountRight\Api\AbstractEndpoint;
use MYOB\AccountRight\Exception\UnexpectedResponseException;
use MYOB\AccountRight\HttpClient\HttpClient;

class AccountingProperties extends AbstractEndpoint {

    private $prefix;

    public function __construct($prefix, HttpClient $client) {
        parent::__construct($client);

        $this->prefix = $prefix;
    }


    public function get(array $filters = array()){
        $response = $this->client->get("{$this->prefix}/AccountingProperties", $filters);
        if(isset($response->body['Items']) && is_array($response->body['Items']))
            return array_pop($response->body['Items']);
        else
            throw new UnexpectedResponseException("Couldn't parse AccountingProperties", $response);
    }


}