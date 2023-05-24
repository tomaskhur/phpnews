<?php

class Database
{
    const HOST = 'localhost';
    const DBNAME = 'phpnews';
    const USER = 'root';
    const PASS = '';

    private $conn;

    public function __construct()
    {
        $this->conn = new PDO('mysql:host=' . self::HOST . ';dbname=' . self::DBNAME, self::USER, self::PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $this->conn->query('SET NAMES utf8');
    }

    public function selectAll($sql, $params = [])
    {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetchAll();
    }

    public function selectOne($sql, $data)
    {
        $stmt = $this->execute($sql, $data);
        return $stmt->fetch();
    }

    public function insert($sql, $params = [])
    {
        $stmt = $this->execute($sql, $params);
        $newId = $this->conn->lastInsertId();
        return $newId;
    }

    public function update($sql, $params = [])
    {
         $stmt = $this->execute($sql, $params);
    }

    public function delete($sql, $params = [])
    {
        $stmt = $this->execute($sql, $params);
    }

    public function execute($sql, $paramas = [])
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($paramas);

        return $stmt;
    }
}