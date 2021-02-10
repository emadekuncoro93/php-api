<?php
require 'bootstrap.php';

$statement = <<<EOS
    
    CREATE TABLE IF NOT EXISTS users (
        id INT NOT NULL AUTO_INCREMENT,
        email VARCHAR(100) NOT NULL,
        firstname VARCHAR(100) NOT NULL,
        lastname VARCHAR(100) NOT NULL,
        is_active INT DEFAULT 1,
        PRIMARY KEY (id)
    ) ENGINE=INNODB;

    CREATE TABLE IF NOT EXISTS products (
        productId INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        stock INT DEFAULT 0,
        price INT DEFAULT 0,
        PRIMARY KEY (productId)
    ) ENGINE=INNODB;

    CREATE TABLE IF NOT EXISTS order_cart (
        orderId INT NOT NULL AUTO_INCREMENT,
        userId INT NOT NULL,
        productId INT NOT NULL,
        quantity INT NOT NULL,
        total_price INT NOT NULL,
        payment_status VARCHAR(50) NOT NULL,
        status INT DEFAULT 1,
        PRIMARY KEY (orderId)
    ) ENGINE=INNODB;

    INSERT INTO users
        (email, firstname, lastname)
    VALUES
        ('test1@mail.com', 'Krasimir', 'Hristozov'),
        ('test2@mail.com', 'Maria', 'Hristozova'),
        ('test3@mail.com', 'Masha', 'Hristozova'),
        ('test4@mail.com', 'Jane', 'Smith'),
        ('test5@mail.com', 'Anna', 'Harrelson');

    INSERT INTO products
        (name, stock, price)
    VALUES
        ('p1', 5, 10000),
        ('p2', 5, 20000),
        ('p3', 5, 30000),
        ('p4', 5, 40000),
        ('p5', 5, 50000);
EOS;

try {
    $createTable = $dbConnection->exec($statement);
    echo "Success!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}