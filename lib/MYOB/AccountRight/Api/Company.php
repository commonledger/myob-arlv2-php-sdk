<?php



namespace MYOB\AccountRight\Api;


class Company extends AbstractEndpoint {

    private $prefix = 'Company';

    public function get(){
        $response = $this->client->get("Company");
        return $response->body;
    }

    public function preferences(){
        return new Company\Preferences($this->prefix, $this->client);
    }

}