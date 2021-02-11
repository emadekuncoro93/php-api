<?php
namespace Src\Repository;


class OrderRepository {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function find($id)
    {
        $statement = "
            SELECT
                 *
            FROM
                order_cart
            WHERE orderId = ? and status = 1;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            error_log('failed to add order, data :', json_encode($data));
            return [];
        }
    }

    public function add(Array $data)
    {
        $userId = $data['userId'];
        $productId = $data['productId'];
        if(!$this->validateOrder($productId, $userId)){
            return 0;
        }
        $statement = "
            INSERT INTO order_cart
            (userId, productId, quantity, total_price)
                VALUES
            (:userId, :productId, :quantity, :total_price);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'userId'  => $data['userId'],
                'productId'  => $data['productId'],
                'quantity'  => $data['quantity'],
                'total_price'  => $data['total_price']

            ));
        
            return $statement->rowCount();
        } catch (\PDOException $e) {
            error_log('failed to add order, data :', json_encode($data));
            return 0;
        }
    }

    public function validateOrder($productId, $userId){
        $statement = "
            SELECT
                orderId
            FROM
                order_cart
            WHERE userId = ? and productId = ? and status = 1;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($productId, $userId));
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            if(empty($result)){
                return true;
            }
            return false;
        } catch (\PDOException $e) {
            error_log('failed to validate order, data :', json_encode($data));
            return false;
        }
    }

    public function invalidateOrder($orderId, $productId)
    {
        $statement = "
            UPDATE order_cart
            SET
                status = 0
            WHERE productId = :productId AND orderId != :orderId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'productId' => $productId,
                'orderId' => $orderId       
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function updatePaymentStatus($orderId, $status)
    {
        $statement = "
            UPDATE order_cart
            SET
                payment_status = :status
            WHERE orderId = :orderId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'status' => $status,
                'orderId' => $orderId       
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            error_log('failed update payment status');
            return false;
        }
    }

    

}