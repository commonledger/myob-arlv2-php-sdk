<?php

namespace MYOB\AccountRight\Api;

use MYOB\AccountRight\Exception\ClientException;
use MYOB\AccountRight\Exception\OAuthException;
use MYOB\AccountRight\HttpClient\HttpClient;

class Auth extends AbstractEndpoint {

    private $oauth_params = array();
    private $oauth_defaults = array(
        'base' => 'https://secure.myob.com/oauth2',
        'scope' => 'CompanyFile'
    );

    private $request_options = array(
        'request_type' => 'form'
    );

    public function __construct(array $oauth_params, HttpClient $client){
        parent::__construct($client);
        $this->oauth_params = array_merge($this->oauth_defaults, $oauth_params);
    }

    public function getAccessCodeUrl($state = null){
        $params = array(
            'client_id' => $this->oauth_params['client_id'],
            'redirect_uri' => $this->oauth_params['redirect_uri'],
            'response_type' => 'code',
            'scope' => $this->oauth_params['scope'],
            'state' => $state
        );
        return sprintf('%s/account/authorize?%s', $this->oauth_params['base'], http_build_query($params));
    }

    public function accessToken($access_code, array $options = array()){
        $params = array(
            'client_id'      => $this->oauth_params['client_id'],
            'client_secret'	 =>	$this->oauth_params['client_secret'],
            'scope'			 =>	$this->oauth_params['scope'],
            'code'			 =>	$access_code,
            'redirect_uri'	 =>	$this->oauth_params['redirect_uri'],
            'grant_type'	 =>	'authorization_code',
        );

        return $this->oAuthRequest($params, $options);
    }

    public function refreshAccessToken($refresh_token, array $options = array()) {

        $params = array(
            'client_id'				=>	$this->oauth_params['client_id'],
            'client_secret'			=>	$this->oauth_params['client_secret'],
            'refresh_token'			=>	$refresh_token,
            'grant_type'			=>	'refresh_token',
        );

        return $this->oAuthRequest($params, $options);
    }

    private function oAuthRequest(array $params, array $options = array()){
        $url = $this->oauth_params['base'] . '/v1/authorize';
        try {
            $options = array_merge($this->request_options, $options);
            $options['prepend_company_id'] = false;

            $response = $this->client->post($url, $params, $options);

        }
        catch(ClientException $error){
            throw new OAuthException($error->getMessage(), $error->getCode());
        }

        $access_token = $response->body;
        $access_token['expires'] = date('c', time() + $access_token['expires_in']);

        return $access_token;
    }

}