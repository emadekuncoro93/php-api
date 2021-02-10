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
                 stock
            FROM
                products
            WHERE productId = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($productId));
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return (int) $result['stock'];
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, $stock)
    {
        $statement = "
            UPDATE products
            SET
                stock = :stock
            WHERE productId = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => $id,
                'stock'  => $stock
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

}