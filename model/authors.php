<?php
class Authors
{
  private $conn;

  public $authorId;
  public $authorName;
  public $authorEmail;
  public $authorStatus;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  // Đọc danh sách tác giả
  public function read()
  {
    $query = "SELECT * FROM authors ORDER BY id ASC";
    $stmt = $this->conn->prepare($query);
    if ($stmt->execute()) {
      return $stmt;
    } 
    return false;
  }

  // Đọc một tác giả
  public function read_single() {
    $query = "SELECT * FROM authors WHERE id = :id LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->authorId);

    if ($stmt->execute()) {
      return $stmt;
    } 
    return false;
  }


  // create product
  function create() {
    $query = "INSERT INTO authors
              SET name = :name, email = :email, status = 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":name", $this->authorName);
    $stmt->bindParam(":email", $this->authorEmail);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  // Cập nhật thông tin tác giả
  public function update()
  {
    $query = "UPDATE authors SET name = :name, email = :email WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->authorId);
    $stmt->bindParam(':name', $this->authorName);
    $stmt->bindParam(':email', $this->authorEmail);
    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  // Xóa hoặc vô hiệu hóa tác giả
  public function delete()
  {
    $query = "UPDATE authors SET status = 0 WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->authorId);
    return $stmt->execute();
  }
}
