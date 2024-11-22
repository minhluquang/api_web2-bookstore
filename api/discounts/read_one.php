<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/discounts.php');

try {
  // Khởi tạo đối tượng kết nối cơ sở dữ liệu và lớp Discounts
  $db = new db();
  $connect = $db->connect();
  $discounts = new Discounts($connect);

  // Kiểm tra xem có parameter 'code' trong URL không
  if (isset($_GET['code'])) {
    // Lấy giá trị 'code' từ URL
    $discountCode = $_GET['code'];
    $discounts->discountCode = $discountCode; 
    
    // Lấy dữ liệu giảm giá theo code
    $read = $discounts->read_single();

    // Kiểm tra xem truy vấn có thành công không
    if ($read && $read->rowCount() > 0) {
      $discount_array = [];
      $discount_array['data'] = [];

      // Lấy thông tin giảm giá
      $row = $read->fetch(PDO::FETCH_ASSOC);
      $discount_item = array(
        'discount_code' => $row['discount_code'],
        'discount_value' => $row['discount_value'],
        'discount_type' => $row['type'],
        'discount_start_date' => $row['start_date'],
        'discount_end_date' => $row['end_date'],
        'discount_status' => $row['status'],
        'discount_delete_date' => $row['delete_date'],
        'discount_create_date' => $row['create_date'],
        'discount_update_date' => $row['update_date'],
      );

      // Thêm giảm giá vào mảng kết quả
      array_push($discount_array['data'], $discount_item);

      // Trả về phản hồi thành công
      http_response_code(200);
      echo json_encode($discount_array);
    } else {
      // Nếu không tìm thấy giảm giá
      http_response_code(404);
      echo json_encode(array('message' => 'Discount not found.', 'success' => false));
    }
  } else {
    // Nếu không có 'code' trong URL
    http_response_code(400);
    echo json_encode(array('message' => 'Discount code is required.', 'success' => false));
  }
} catch (Exception $e) {
  // Nếu có lỗi xảy ra trong quá trình xử lý
  http_response_code(500);
  echo json_encode(array(
    'message' => 'Internal Server Error',
    'error' => $e->getMessage(),
    'success' => false
  ));
}
?>
