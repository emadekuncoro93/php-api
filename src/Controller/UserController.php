<?php
namespace Src\Controller;

use Src\Service\UserService;

class UserController {

    private $db;
    private $requestMethod;
    private $userId;
    private $service;

    public function __construct($db, $requestMethod, $userId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
        $this->service = new UserService($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->userId) {
                    $response = $this->getUser($this->userId);
                } else {
                    $response = $this->getAllUsers();
                };
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllUsers()
    {
        return $this->service->getAllUser();
    }

    private function getUser($id)
    {
        return $this->service->getUser($id);
    }

}
