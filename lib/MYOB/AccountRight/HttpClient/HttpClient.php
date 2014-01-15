<?php

namespace MYOB\AccountRight\HttpClient;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Message\RequestInterface;

/**
 * Main HttpClient which is used by Api classes
 */
class HttpClient
{
    protected $options = array(
        'base'    => 'https://api.myob.com/accountright/',
        'user_agent' => 'myob-accountright-php-sdk/0.0.1 (https://github.com/commonledger/myob-php-sdk)'
    );

    protected $headers = array(
        'x-myobapi-version' => 'v2'
    );

    private $company_id;
    private $client_id;

    private $auth_handler;
    private $error_handler;

    public function __construct($client_id, array $options = array()){

        $this->client_id = $client_id;
        $this->options = array_merge($this->options, $options);

        $this->headers = array_merge($this->headers, array(
            'user-agent' => $this->options['user_agent'],
        ));

        if (isset($this->options['headers'])) {
            $this->headers = array_merge($this->headers, array_change_key_case($this->options['headers']));
            unset($this->options['headers']);
        }

        $client = new GuzzleClient($this->options['base'], $this->options);
        $this->client  = $client;

        $this->error_handler = new ErrorHandler();
        $listener = array($this->error_handler, 'onRequestError');
        $this->client->getEventDispatcher()->addListener('request.error', $listener);

        $this->auth_handler = new AuthHandler($client_id);
        $listener = array($this->auth_handler, 'onRequestBeforeSend');
        $this->client->getEventDispatcher()->addListener('request.before_send', $listener);
    }

    public function setAccessToken($access_token){
        $this->auth_handler->setAccessToken($access_token);
    }

    public function setCompanyId($company_id) {
        $this->company_id = $company_id;
    }

    public function setCompanyFileAuth($user, $password) {
        $this->auth_handler->setCompanyFileAuth($user, $password);
    }

    public function get($path, array $params = array(), array $options = array())
    {
        return $this->request($path, null, 'GET', array_merge($options, array('query' => $params)));
    }

    public function post($path, $body, array $options = array())
    {
        return $this->request($path, $body, 'POST', $options);
    }

    public function patch($path, $body, array $options = array())
    {
        return $this->request($path, $body, 'PATCH', $options);
    }

    public function delete($path, $body, array $options = array())
    {
        return $this->request($path, $body, 'DELETE', $options);
    }

    public function put($path, $body, array $options = array())
    {
        return $this->request($path, $body, 'PUT', $options);
    }

    /**
     * Intermediate function which does three main things
     *
     * - Transforms the body of request into correct format
     * - Creates the requests with give parameters
     * - Returns response body after parsing it into correct format
     */
    public function request($path, $body = null, $httpMethod = 'GET', array $options = array())
    {
        $headers = array();

        $options = array_merge($this->options, $options);

        if (isset($options['headers'])) {
            $headers = $options['headers'];
            unset($options['headers']);
        }

        $headers = array_merge($this->headers, array_change_key_case($headers));

        unset($options['body']);

        unset($options['base']);
        unset($options['user_agent']);

        $request = $this->createRequest($httpMethod, $path, null, $headers, $options);

        if ($httpMethod != 'GET') {
            $request = $this->setBody($request, $body, $options);
        }

        try {
            $response = $this->client->send($request);
        } catch (\LogicException $e) {
            throw new \ErrorException($e->getMessage());
        } catch (\RuntimeException $e) {
            throw new \RuntimeException($e->getMessage());
        }

        return new Response($this->getBody($response), $response->getStatusCode(), $response->getHeaders());
    }

    /**
     * Creating a request with the given arguments
     *
     * If api_version is set, appends it immediately after host
     */
    public function createRequest($httpMethod, $path, $body = null, array $headers = array(), array $options = array())
    {
        if(!empty($this->company_id) && (!isset($options['prepend_company_id']) || $options['prepend_company_id']))
            $path = "{$this->company_id}/{$path}";

        return $this->client->createRequest($httpMethod, $path, $headers, $body, $options);
    }

    /**
     * Get response body in correct format
     */
    public function getBody($response)
    {
        return ResponseHandler::getBody($response);
    }

    /**
     * Set request body in correct format
     */
    public function setBody(RequestInterface $request, $body, $options)
    {
        return RequestHandler::setBody($request, $body, $options);
    }

}
