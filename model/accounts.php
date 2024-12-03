<?php
class Accounts
{
  private $conn;

  public $accountUsername;
  public $accountPassword;
  public $accountRoleId;
  public $accountStatus;
  public $accountEmail;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  // Đọc danh sách tài khoản
  public function read()
  {
    $query = "SELECT username, email, role_id, status FROM accounts";
    $stmt = $this->conn->prepare($query);
    if ($stmt->execute()) {
      return $stmt;
    } 
    return false;
  }

  // Đọc một tài khoản
  public function read_single() {
    $query = "SELECT username, email, role_id, status FROM accounts WHERE username = :username LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':username', $this->accountUsername);

    if ($stmt->execute()) {
      return $stmt;
    } 
    return false;
  }


  // // create product
  function create() {
    $query = "INSERT INTO accounts
              SET username = :username, password = :password, role_id = :role_id, status = 1, email = :email";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":username", $this->accountUsername);
    $stmt->bindParam(":password", $this->accountPassword);
    $stmt->bindParam(":role_id", $this->accountRoleId);
    $stmt->bindParam(":email", $this->accountEmail);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  // Phương thức kiểm tra xem username có tồn tại trong cơ sở dữ liệu không
  public function usernameExists($username) {
    $query = "SELECT username FROM accounts WHERE username = :username LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return true; 
    }
    return false; 
  }

  // Cập nhật thông tin tài khoản
  public function update()
  {
    $query = "UPDATE accounts SET role_id = :role_id, status = :status WHERE username = :username";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':username', $this->accountUsername);
    $stmt->bindParam(':role_id', $this->accountRoleId);
    $stmt->bindParam(':status', $this->accountStatus);
    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  public function verifyPassword($username, $currentPassword)
  {
      $query = "SELECT password FROM accounts WHERE username = :username";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':username', $username);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          return password_verify($currentPassword, $row['password']);
      }
      return false;
  }

  public function updatePassword($username)
  {
      $query = "UPDATE accounts SET password = :password WHERE username = :username";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':password', $this->accountPassword);
      $stmt->bindParam(':username', $username);

      return $stmt->execute();
  }
}
