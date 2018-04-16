<?php

namespace Optimus\Heimdal\Formatters;

use Exception;
use Illuminate\Http\JsonResponse;
use Optimus\Heimdal\Formatters\ExceptionFormatter;

class HttpExceptionFormatter extends ExceptionFormatter
{
    public function format(JsonResponse $response, Exception $e, array $reporterResponses)
    {
        if (count($headers = $e->getHeaders())) {
            $response->headers->add($headers);
        }

        $response->setStatusCode($e->getStatusCode());

        $data = array_merge($response->getData(true), [
            'code'   => $e->getStatusCode(),
            'message'   => $e->getMessage(),
        ]);

        if ($this->debug) {
            $data = array_merge($data,[
                'exception' => (string) $e,
                'line'   => $e->getLine(),
                'file'   => $e->getFile()
            ]);
        }

        $response->setData($data);
    }
}
