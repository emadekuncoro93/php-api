<?php
namespace Src\Controller;
use Src\Controller\BaseController;
use Src\Service\CheckoutService;

class CheckoutController extends BaseController {

    private $db;
    private $requestMethod;
    private $userId;
    private $service;


    public function __construct($db, $requestMethod, $userId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
        $this->service = new CheckoutService($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->checkout();
                break;
            default:
                $response =  $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    public function checkout(){
        $input = (array) json_decode(file_get_contents('php://input', true));
        return $this->service->checkout($input);
    }
}