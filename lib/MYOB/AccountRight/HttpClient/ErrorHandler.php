<?php

namespace MYOB\AccountRight\HttpClient;

use Guzzle\Common\Event;
use Guzzle\Http\Message\Request;
use MYOB\AccountRight\Exception\ClientException;


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
        throw new ClientException($message, $code, $response);
    }
}
