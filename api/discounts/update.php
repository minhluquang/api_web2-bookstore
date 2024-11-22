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

// Make sure data is not empty
if (!empty($data->discount_code) && !empty($data->discount_value) && !empty($data->type) && !empty($data->start_date) && !empty($data->end_date)) {
  $discount->discountCode = $data->discount_code;
  $discount->discountValue = $data->discount_value;
  $discount->discountType = $data->type;
  $discount->discountStartDate = $data->start_date;
  $discount->discountEndDate = $data->end_date;

  // Attempt to update the discount
  if ($discount->update()) {
    http_response_code(200);  // Success
    echo json_encode(array("message" => "Discount was updated.", 'success' => true));
  } else {
    http_response_code(503);  // Service unavailable
    echo json_encode(array("message" => "Unable to update discount.", 'success' => false));
  }
} else {
  // If data is incomplete
  http_response_code(400);  // Bad request
  echo json_encode(array("message" => "Unable to update discount. Data is incomplete.", 'success' => false));
}
