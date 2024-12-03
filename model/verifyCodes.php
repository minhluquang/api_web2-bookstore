<?php
class VerifyCodes
{
  private $conn;

  public $verifyCodeEmail;
  public $verifyCodeCode;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  // Đọc danh sách tài khoản
  // public function read()
  // {
  //   $query = "SELECT username, email, role_id, status FROM accounts";
  //   $stmt = $this->conn->prepare($query);
  //   if ($stmt->execute()) {
  //     return $stmt;
  //   } 
  //   return false;
  // }

  // Đọc một tài khoản
  // public function read_single() {
  //   $query = "SELECT username, email, role_id, status FROM accounts WHERE username = :username LIMIT 1";

  //   $stmt = $this->conn->prepare($query);
  //   $stmt->bindParam(':username', $this->accountUsername);

  //   if ($stmt->execute()) {
  //     return $stmt;
  //   } 
  //   return false;
  // }

  // // create product
  function create() {
    $query = "INSERT INTO verify_code
              SET email = :email, code = :code, time_send = NOW()";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":email", $this->verifyCodeEmail);
    $stmt->bindParam(":code", $this->verifyCodeCode);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  // // Cập nhật thông tin tác giả
  // public function update()
  // {
  //   $query = "UPDATE authors SET name = :name, email = :email WHERE id = :id";
  //   $stmt = $this->conn->prepare($query);
  //   $stmt->bindParam(':id', $this->authorId);
  //   $stmt->bindParam(':name', $this->authorName);
  //   $stmt->bindParam(':email', $this->authorEmail);
  //   if ($stmt->execute()) {
  //     return true;
  //   }
  //   return false;
  // }

  // // Xóa hoặc vô hiệu hóa tác giả
  // public function delete()
  // {
  //   $query = "UPDATE authors SET status = 0 WHERE id = :id";
  //   $stmt = $this->conn->prepare($query);
  //   $stmt->bindParam(':id', $this->authorId);
  //   return $stmt->execute();
  // }
}
