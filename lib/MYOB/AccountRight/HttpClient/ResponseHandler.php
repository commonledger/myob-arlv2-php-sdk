<?php

namespace MYOB\AccountRight\HttpClient;

use Guzzle\Http\Message\Response as GuzzleResponse;
use MYOB\AccountRight\Exception\ClientException;

/**
 * ResponseHandler takes care of decoding the response body into suitable type
 */
class ResponseHandler {

    public static function getBody(GuzzleResponse $response)
    {
        $body = $response->getBody(true);
        $code = $response->getStatusCode();

        // Response body is in JSON
        if ($response->isContentType('json')) {
            $body = json_decode($body, true);

            if (JSON_ERROR_NONE !== json_last_error() || !is_array($body)) {
                throw new ClientException("Malformed JSON response from MYOB", 500);
            }

            if(isset($body['error']))
                throw new ClientException($body['error'], $code, $response);


        }

        return $body;
    }

}
