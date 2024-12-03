<?php
class DeliveryInfoes
{
  private $conn;

  public $deliveryInfoId;
  public $deliveryInfoUsername;
  public $deliveryInfoFullname;
  public $deliveryInfoPhoneNumber;
  public $deliveryInfoAddress;
  public $deliveryInfoProvince;
  public $deliveryInfoDistrict;
  public $deliveryInfoWard;

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
    $query = "INSERT INTO delivery_infoes
              SET user_info_id = :user_info_id, user_id = :user_id, fullname = :fullname, 
              phone_number = :phone_number, address = :address, 
              city = :city, district = :district, ward = :ward";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":user_info_id", $this->deliveryInfoId);
    $stmt->bindParam(":user_id", $this->deliveryInfoUsername);
    $stmt->bindParam(":fullname", $this->deliveryInfoFullname);
    $stmt->bindParam(":phone_number", $this->deliveryInfoPhoneNumber);
    $stmt->bindParam(":address", $this->deliveryInfoAddress);
    $stmt->bindParam(":city", $this->deliveryInfoProvince);
    $stmt->bindParam(":district", $this->deliveryInfoDistrict);
    $stmt->bindParam(":ward", $this->deliveryInfoWard);

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
