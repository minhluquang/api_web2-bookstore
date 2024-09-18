<?php
  class Products {
    private $conn;

    public $productId;
    public $productName;
    public $productPublisherId;
    public $productImagePath;
    public $productCreateDate;
    public $productUpdateDate;
    public $productPrice;
    public $productQuantity;
    public $productSupplierId;
    public $productStatus;

    // Connect database
    public function __construct($db)
    {
      $this->conn = $db;
    }

    // Read data
    public function read() {
      $query = "SELECT * FROM products ORDER BY id DESC";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
    }
  }
?>
