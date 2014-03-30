<?php

namespace MYOB\AccountRight\HttpClient;

use Guzzle\Common\Event;
use Guzzle\Http\Message\Request;
use MYOB\AccountRight\Exception\ClientException;
use MYOB\AccountRight\Exception\OAuthException;


/**
 * ErrorHanlder takes care of selecting the error message from response body
 */
class ErrorHandler
{
    public function onRequestError(Event $event)
    {
        /** @var Request $request */
        $request = $event['request'];
        $response = $request->getResponse();

        $message = null;
        $code = $response->getStatusCode();
        $status = $response->getReasonPhrase();

        $body = ResponseHandler::getBody($response);

        // If HTML, whole body is taken
        if (gettype($body) == 'string') {
            $message = $body;
        }

        // If JSON, a particular field is taken and used
        if ($response->isContentType('json') && is_array($body)) {
            if (isset($body['Message'])) {
                $message = $body['Message'];
            } else if(isset($body['Errors'])){
                $errors = array();;
                foreach($body['Errors'] as $error)
                    $errors[] = "{$error['Name']}: {$error['Message']}";

                $message = implode("\n", $errors);

            } else {
                $message = $code;
            }
        }

        if (empty($message)) {
            $message = "HTTP {$code}: {$status}";
        }

        if($code === 401){
            $oauth_params = array();
            if($request->hasHeader('Authorization')){
                $auth_header = $request->getHeaders()->get('Authorization')->toArray();
                list(,$access_token) = explode(' ', $auth_header[0]);
                $oauth_params['access_token'] = $access_token;
            }
            if($request->hasHeader('x-myobapi-cftoken')){
                $auth_header = $request->getHeaders()->get('x-myobapi-cftoken')->toArray();
                $oauth_params['cf_token'] = $auth_header[0];
            }

            throw new OAuthException($message, $code, $oauth_params);
        }
        else {
            throw new ClientException($message, $code, $response);
        }

    }
}
