<?php
namespace Src\Service;
use Src\Repository\UserRepository;

class UserService {
    private $db;
    private $UserRepository;

    public function __construct($db)
    {
        $this->db = $db;
        $this->UserRepository = new UserRepository($db);
    }

    public function getAllUser(){
        $result = $this->UserRepository->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(['code' => 'SUCCESS', 'data' => $result]);
        return $response;
    }

    public function getUser($id)
    {
        $result = $this->UserRepository->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(['code' => 'SUCCESS', 'data' => $result]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode([
            'code' => 'ERROR',
            'message' => 'data not found'
        ]);
        return $response;
    }
}
