<?php
namespace Src\Controller;

use Src\Service\UserService;

class CheckoutController {

    public function checkout(){
        $productId = 1;
        $quantity = 1;
        $is_valid_order = $this->checkOrder($productId);
        $is_paid = $this->callPaymentGateway();
        if($is_paid){
            $update_inventory = updateInventory($productId, $quantity);
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
        return true;
    }

    private function resetInventory($productId, $quantity){
        return true;
    }

    private function updateInventory($productId, $total_item){
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
        return 10;
    }

    private function checkOrder($productId){
        return true;
    }

    private function invalidateActiveOrder($productId){
        return true;
    }
}