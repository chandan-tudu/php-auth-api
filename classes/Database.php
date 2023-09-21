<?php
class Database
{
    private $db_host = DB_HOST;
    private $db_name = DB_NAME;
    private $db_username = DB_USER;
    private $db_password = DB_PASSWORD;
    private $conn;

    public function __construct()
    {
        $dsn = "mysql:host={$this->db_host};dbname={$this->db_name};charset=utf8";
        $db_connection = new PDO($dsn, $this->db_username, $this->db_password);
        $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn = $db_connection;
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }

    # To run prepared query dynamically
    public function run_prepare(string $sql, array $bindData = []): PDOStatement
    {
        $stmt = $this->conn->prepare($sql);
        foreach ($bindData as $key => $val) {
            $stmt->bindValue($key, $val[0], $val[1]);
        }
        $stmt->execute();
        return $stmt;
    }
}
