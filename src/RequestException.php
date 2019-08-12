<?php

namespace GuzzleHelper;

class RequestException extends \Exception
{
    private $response;

    public function __construct(
        $message,
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        if($previous->hasResponse())
        {
            $response = $previous->getResponse();
            $this->response = [
                'status_code' => $response->getStatusCode(),
                'body' => $response->getBody()->getContent(),
                'header' => ($response->hasHeader()) ? $response->getHeaders() : []
            ];
        }
        else
            $this->response = null;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
