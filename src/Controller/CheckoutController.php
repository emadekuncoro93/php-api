<?php
namespace Src\Controller;
use Src\Controller\BaseController;
use Src\Service\UserService;

class CheckoutController extends BaseController {

    private $db;
    private $requestMethod;
    private $userId;
    private $service;
    private $total = 1;


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
        error_log('start checkout process');
        $productId = 1;
        $quantity = 1;
        $is_valid_order = $this->checkOrder($productId);
        $is_paid = $this->callPaymentGateway();
        if($is_paid){
            $update_inventory = $this->updateInventory($productId, $quantity);
            if(!$update_inventory){
                $this->resetInventory($productId, $quantity);  
                return false;  
            }
            error_log('total :'.$this->total);
            return true;
        }
        $this->resetInventory($productId, $quantity);
        error_log('total :'.$this->total);
        return false;
    }

    private function callPaymentGateway(){
        error_log('callPaymentGateway');
        return true;
    }

    private function resetInventory($productId, $quantity){
        error_log('resetInventory');
        $this->total += $quantity;
        return true;
    }

    private function updateInventory($productId, $total_item){
        error_log('updateInventory');
        $currentStock = $this->getTotalInventory();
        if($currentStock - $total_item > 0){
            return true;
        }else if($currentStock - $total_item == 0){
            //if this is last item but there's active order, invalidate all other order with same productId
            $this->invalidateActiveOrder($productId);
            return true;
        }
        return false;
    }

    private function getTotalInventory(){
        error_log('getTotalInventory');
        return $this->total;
    }

    private function checkOrder($productId){
        error_log('checkOrder');
        return true;
    }

    private function invalidateActiveOrder($productId){
        error_log('invalidateActiveOrder');
        return true;
    }
}