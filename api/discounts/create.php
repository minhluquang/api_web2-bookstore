<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../../config/db.php');
include_once('../../model/discounts.php');

$db = new db();
$connect = $db->connect();
$discount = new Discounts($connect);
$data = json_decode(file_get_contents("php://input"));

// Đảm bảo dữ liệu không rỗng
if (
    !empty($data->discount_code) &&
    !empty($data->discount_value) &&
    !empty($data->type) &&
    !empty($data->start_date) &&
    !empty($data->end_date)
) {
    // Gán dữ liệu từ yêu cầu vào đối tượng `discount`
    $discount->discountCode = $data->discount_code;
    $discount->discountValue = $data->discount_value;
    $discount->discountType = $data->type;
    $discount->discountStartDate = $data->start_date;
    $discount->discountEndDate = $data->end_date;

    // Tạo discount
    if ($discount->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Discount was created.", 'success' => true));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create discount.", 'success' => false));
    }
} else {
    // Nếu dữ liệu không đầy đủ
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create discount. Data is incomplete.", 'success' => false));
}
?>
