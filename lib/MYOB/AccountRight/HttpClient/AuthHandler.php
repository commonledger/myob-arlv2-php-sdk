<?php

namespace MYOB\AccountRight\HttpClient;

use Guzzle\Common\Event;
use Guzzle\Http\Message\Request;

/**
 * AuthHandler takes care of devising the auth type and using it
 */
class AuthHandler {

    private $client_id;
    private $access_token;
    private $company_file_user;
    private $company_file_password;

    public function __construct($client_id, $access_token = null, $company_file_user = null, $company_file_password = null){
        $this->client_id = $client_id;
        $this->access_token = $access_token;
        $this->company_file_user = $company_file_user;
        $this->company_file_password = $company_file_password;
    }

    public function onRequestBeforeSend(Event $event){

        /** @var Request $request */
        $request =  $query = $event['request'];

        $headers = array(
            'x-myobapi-key' => $this->client_id
        );

        if(!empty($this->access_token))
            $headers['Authorization'] = "Bearer {$this->access_token}";

        if(!empty($this->company_file_user))
            $headers['x-myobapi-cftoken'] = base64_encode("{$this->company_file_user}:{$this->company_file_password}");

        $request->addHeaders($headers);

    }

    /**
     * @param mixed $client_id
     */
    public function setClientId($client_id) {
        $this->client_id = $client_id;
    }

    /**
     * @param null $access_token
     */
    public function setAccessToken($access_token) {
        $this->access_token = $access_token;
    }

    public function setCompanyFileAuth($user, $password) {
        $this->company_file_user = $user;
        $this->company_file_password = $password;
    }

}
