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

    INSERT INTO users
        (email, firstname, lastname)
    VALUES
        ('test1@mail.com', 'Krasimir', 'Hristozov'),
        ('test2@mail.com', 'Maria', 'Hristozova'),
        ('test3@mail.com', 'Masha', 'Hristozova'),
        ('test4@mail.com', 'Jane', 'Smith'),
        ('test5@mail.com', 'Anna', 'Harrelson');
EOS;

try {
    $createTable = $dbConnection->exec($statement);
    echo "Success!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}