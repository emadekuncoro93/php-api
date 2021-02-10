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

}