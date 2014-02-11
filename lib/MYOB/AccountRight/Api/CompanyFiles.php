<?php

namespace MYOB\AccountRight\Api;


class CompanyFiles extends AbstractEndpoint {

    public function getAll(array $options = array()){
        $options['prepend_company_id'] = false;

        $response = $this->client->get('', array(), $options);
        return $response->body;
    }

    public function get(array $options = array()){
        $response = $this->client->get('', array(), $options);
        return $response->body;
    }

}