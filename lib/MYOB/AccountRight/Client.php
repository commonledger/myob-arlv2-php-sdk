<?php

namespace MYOB\AccountRight;

use MYOB\AccountRight\HttpClient\HttpClient;

class Client {

    protected $client_id;
    protected $httpClient;


    public function __construct($client_id){
        $this->client_id = $client_id;

        $this->httpClient = new HttpClient($this->client_id);
    }

    public function setAccessToken($access_token){
        $this->httpClient->setAccessToken($access_token);
    }

    public function setCompany($company_id, $user, $password) {
        $this->httpClient->setCompanyId($company_id);
        $this->httpClient->setCompanyFileAuth($user, $password);
    }

    public function auth($oauth_params){
        return new Api\Auth($oauth_params, $this->httpClient);
    }

    public function companyFiles(){
        return new Api\CompanyFiles($this->httpClient);
    }

    public function generalLedger() {
        return new Api\GeneralLedger($this->httpClient);
    }

    public function company(){
        return new Api\Company($this->httpClient);
    }

}