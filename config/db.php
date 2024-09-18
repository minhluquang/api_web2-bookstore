<?php
  class db {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "backend_web2";
    public $conn;

    public function connect() {
      try {
        $this->conn = new PDO("mysql:host=" . $this->servername . ";dbname=" . $this->db . "", $this->username, $this->password);
        // set the PDO error mode to exception
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected successfully";
      } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
      }
      return $this->conn;
    }
  }
?>
