<?php
namespace Src\Service;
use Src\Repository\OrderRepository;
use Src\Repository\ProductRepository;

class OrderService {
    private $db;
    private $OrderRepository;

    public function __construct($db)
    {
        $this->db = $db;
        $this->OrderRepository = new OrderRepository($db);
    }

    public function add($data){
        $result = $this->OrderRepository->add($data);
        if($result == 0){
            return $this->badRequestResponse('order exist');
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(['code' => 'SUCCESS', 'data'=> $data]);
        return $response;
    }

    
    public function badRequestResponse($msg="Bad Request")
    {
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'code' => 'ERROR',
            'message' => $msg
        ]);
        return $response;
    }
}