<?php
namespace Src\Repository;


class OrderRepository {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
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

}