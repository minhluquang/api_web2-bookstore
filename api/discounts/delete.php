<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../../config/db.php');
include_once('../../model/discounts.php');

// Khởi tạo đối tượng kết nối cơ sở dữ liệu và lớp Discounts
$db = new db();
$connect = $db->connect();
$discount = new Discounts($connect);

// Kiểm tra xem có 'id' trong URL không
if (isset($_GET['code'])) {
  $discount->discountCode = $_GET['code'];

  // Thực hiện cập nhật discount status thành 0
  if ($discount->delete()) {
    http_response_code(200);
    echo json_encode(array("message" => "Discount status updated to 0.", 'success' => true));
  } else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to update discount status.", 'success' => false));
  }
} else {
  http_response_code(400);
  echo json_encode(array("message" => "Unable to update discount. 'discountId' is required.", 'success' => false));
}
?>
