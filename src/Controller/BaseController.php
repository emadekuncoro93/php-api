<?php
namespace Src\Controller;

class BaseController {
    public function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode([
            'code' => 'ERROR',
            'message' => 'Unhandled Request'
        ]);
        return $response;
    }
}