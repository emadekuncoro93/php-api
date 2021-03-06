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

    private function BaseResponse($code, $msg=null, $data=null){
        $response['status_code_header'] = 'HTTP/1.1 200 Ok';
        $response['body'] = json_encode(['code' => $code, 'message' => $msg, 'data'=> $data]);
        return $response;
    }

    //order positive scenario
    public function checkout($data){
        
        $orderId = $data['orderId'];
        //check active order
        $is_valid_order = $this->OrderRepository->find($orderId);
        if(empty($is_valid_order)){
           return $this->BaseResponse('ERROR','order not found', $data);
        }
        $productId = $is_valid_order['productId'];
        $quantity = (int) $is_valid_order['quantity'];
        $is_paid = $this->callPaymentGateway();
        if($is_paid){ 
            //update payment status to paid
            $this->OrderRepository->updatePaymentStatus($orderId, 'paid');
            //update stock
            $update_inventory = $this->updateInventory($orderId, $productId, $quantity);
            if(!$update_inventory){
                return $this->BaseResponse('ERROR','order failed | update inventory failed', $data);  
            }
            return  $this->BaseResponse('SUCCESS','SUCCESS', $data);  ;
        }
        //update payment status to failed
        $this->OrderRepository->updatePaymentStatus($orderId, 'failed');
        return $this->BaseResponse('ERROR','order failed | payment failed', $data);
    }

    private function callPaymentGateway(){
        error_log('callPaymentGateway');
        return true;
    }

    private function resetInventory($productId, $quantity){
        error_log('resetInventory');
        $currentStock = $this->ProductRepository->getStockById($productId);
        if($currentStock < 1){
            error_log('unable proceed reset inventory while stock: '.$currentStock);
            return false;
        }
        $total = $currentStock + $quantity;
        $this->ProductRepository->update($productId, $total);
    }

    //update inventory positive scenario
    private function updateInventory($orderId, $productId, $quantity){
        error_log('updateInventory');
        $currentStock = $this->ProductRepository->getStockById($productId);
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