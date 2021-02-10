<?php
namespace Src\Controller;
use Src\Controller\BaseController;
use Src\Service\OrderService;

class OrderController extends BaseController {

    private $db;
    private $requestMethod;
    private $userId;
    private $service;

    public function __construct($db, $requestMethod, $userId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
        $this->service = new OrderService($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->order();
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

    public function order(){
        $input = (array) json_decode(file_get_contents('php://input', true));
        return $this->service->add($input);

    }

    
}