<?php
require __DIR__ . '/../lib/PMDump.php';

class Helper {
    const TEST_DB_NAME = 'pmdumpdbfortest';
    const ROWS = 35;

    public static function getPDOConnection() 
    {
        return new PDO('mysql:host=localhost;dbname=test', 'root', '');
    }

    public static function initializeData(PDO $pdo)
    {
        self::cleanData($pdo);
        $pdo->exec("CREATE DATABASE " . self::TEST_DB_NAME);
        $pdo->exec("USE " . self::TEST_DB_NAME);
        $pdo->exec("CREATE TABLE t1(id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, name varchar(10) NOT NULL)");
        $pdo->exec("CREATE TABLE t2(sid INT, name varchar(10) NOT NULL)");

        $stmt1 = $pdo->prepare("INSERT INTO t1(name) VALUES(?)");
        $stmt2 = $pdo->prepare("INSERT INTO t2(sid, name) VALUES(?, ?)");
        for ($i = 1; $i <= self::ROWS; $i++) {
            $stmt1->execute(array('t1_name_' . $i));
            $stmt2->execute(array($i * rand(1, $i), 't2_name_' . $i));
        }

        $stmt1->closeCursor();
        $stmt2->closeCursor();
    }

    public static function cleanData(PDO $pdo)
    {
        $pdo->exec("DROP DATABASE IF EXISTS " . self::TEST_DB_NAME);
    }
}
