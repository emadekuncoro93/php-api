<?php
namespace Src\Repository;

class ProductRepository {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    
    public function getStockById($productId)
    {
        $statement = "
            SELECT
                 Stock
            FROM
                products
            WHERE productId = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($productId));
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result->stock;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, $quantity)
    {
        $currenctStock = $this->getStockById($id);
        $quantity += $currenctStock;
        $statement = "
            UPDATE products
            SET
                stock = :stock
            WHERE productId = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $productId,
                'stock'  => $quantity
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

}