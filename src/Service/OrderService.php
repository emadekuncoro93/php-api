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
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $code = $result > 0 ? 'SUCCESS' : 'ERROR';
        $response['body'] = json_encode(['code' => $code, 'data'=> $data]);
        return $response;
    }
}