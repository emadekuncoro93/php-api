<?php
namespace Src\Service;
use Src\Repository\OrderRepository;
use Src\Repository\ProductRepository;

class CheckoutService {
    private $db;
    private $OrderRepository;
    private $ProductRepository;

    public function __construct($db)
    {
        $this->db = $db;
        $this->OrderRepository = new OrderRepository($db);
        $this->ProductRepository = new ProductRepository($db);
    }

    private function BaseException($msg=null, $data=null){
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode(['code' => 'ERROR', 'message' => $msg, 'data'=> $data]);
        return $response;
    }

    public function checkout($data){
        
        $orderId = $data['orderId'];
        $is_valid_order = $this->OrderRepository->find($orderId);
        if(empty($is_valid_order)){
           return $this->BaseException('order not found', $data);
        }
        $productId = $is_valid_order['productId'];
        $quantity = (int) $is_valid_order['quantity'];
        $is_paid = $this->callPaymentGateway();
        if($is_paid){
            $update_inventory = $this->updateInventory($orderId, $productId, $quantity);
            if(!$update_inventory){
                $this->resetInventory($productId, $quantity);  
                return false;  
            }
            return true;
        }
        $this->resetInventory($productId, $quantity);
        return false;
    }

    private function callPaymentGateway(){
        error_log('success callPaymentGateway');
        return true;
    }

    private function resetInventory($productId, $quantity){
        error_log('resetInventory');
        $currentStock = $this->ProductRepository->getStockById($productId);
        $total = $currentStock + $quantity;
        $this->ProductRepository->update($productId, $total);
    }

    private function updateInventory($orderId, $productId, $quantity){
        error_log('updateInventory');
        $currentStock = $this->ProductRepository->getStockById($productId);
        if($currentStock < 1){
            error_log('stock empty');
            return false;
        }
        $remain = $currentStock - $quantity;
        if($remain > 0){
            $this->ProductRepository->update($productId, $remain);
            return true;
        }else if($remain == 0){
            $this->ProductRepository->update($productId, $remain);
            error_log('invalidate order');
            //if this is last item but there's active order, invalidate all other order with same productId except current order id
            $this->OrderRepository->invalidateOrder($orderId, $productId);
            return true;
        }
        return false;
    }

}