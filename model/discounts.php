<?php
class Discounts
{
  private $conn;

  public $discountCode;
  public $discountValue;
  public $discountType;
  public $discountStartDate;
  public $discountEndDate;
  public $discountStatus;
  public $discountDeleteDate;
  public $discountCreateDate;
  public $discountUpdateDate;
  
  public function __construct($db)
  {
    $this->conn = $db;
  }

  // Đọc danh sách mã giảm giá
  public function read()
  {
    $query = "SELECT * FROM discounts ORDER BY discount_code ASC";
    $stmt = $this->conn->prepare($query);
    if ($stmt->execute()) {
      return $stmt;
    } 
    return false;
  }

  // Đọc một mã giảm giá
  public function read_single() {
    $query = "SELECT * FROM discounts WHERE discount_code = :discountCode LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':discountCode', $this->discountCode);

    if ($stmt->execute()) {
      return $stmt;
    } 
    return false;
  }

  // create discount
  public function create() {
    $query = "INSERT INTO discounts SET
        discount_code = :discount_code,
        discount_value = :discount_value,
        type = :type,
        start_date = :start_date,
        end_date = :end_date,
        status = 1,
        create_date = NOW()";

    $stmt = $this->conn->prepare($query);

    // Ràng buộc dữ liệu
    $stmt->bindParam(':discount_code', $this->discountCode);
    $stmt->bindParam(':discount_value', $this->discountValue);
    $stmt->bindParam(':type', $this->discountType);
    $stmt->bindParam(':start_date', $this->discountStartDate);
    $stmt->bindParam(':end_date', $this->discountEndDate);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        return true;
    }
    return false;
  }

  // Cập nhật thông tin mã giảm giá
  public function update()
  {
    $query = "UPDATE discounts SET
              discount_value = :discount_value,
              type = :type,
              start_date = :start_date,
              end_date = :end_date,
              update_date = NOW()  
          WHERE discount_code = :discount_code";

    // Chuẩn bị truy vấn
    $stmt = $this->conn->prepare($query);

    // Liên kết các tham số với giá trị
    $stmt->bindParam(':discount_code', $this->discountCode);
    $stmt->bindParam(':discount_value', $this->discountValue);
    $stmt->bindParam(':type', $this->discountType);
    $stmt->bindParam(':start_date', $this->discountStartDate);
    $stmt->bindParam(':end_date', $this->discountEndDate);

    // Thực thi truy vấn
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }

  }

  // Xóa hoặc vô hiệu hóa tác giả
  public function delete()
  {
    $query = "UPDATE discounts SET
              status = 0,  -- Cập nhật trạng thái thành 0 (để tắt discount)
              delete_date = NOW()  -- Ghi nhận thời gian xóa
          WHERE discount_code = :discount_code";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':discount_code', $this->discountCode);
    return $stmt->execute();
  }
}
