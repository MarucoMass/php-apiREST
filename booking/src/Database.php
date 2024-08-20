<?php
class Database
{
    public function __construct(
            private string $host,
            private string $dbname,
            private string $user,
            private string $password
    ) {

    }

    public function getConnection()
    {
        $hostDB = "mysql:host=" . $this->host . ";dbname=" . $this->dbname. ";charset=utf8";

        try {
            $connection = new PDO($hostDB, $this->user, $this->password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
}
