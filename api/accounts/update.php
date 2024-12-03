<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../../config/db.php');
include_once('../../model/accounts.php');

// Khởi tạo kết nối cơ sở dữ liệu
$db = new db();
$connect = $db->connect();

// Khởi tạo đối tượng Accounts
$account = new Accounts($connect);

// Lấy dữ liệu từ body request
$data = json_decode(file_get_contents("php://input"));

// Kiểm tra dữ liệu đầu vào
if (isset($data->username) && isset($data->role_id) && isset($data->status)) {
    $account->accountUsername = $data->username;

    // Kiểm tra username có tồn tại trong database
    if (!$account->usernameExists($account->accountUsername)) {
      http_response_code(404);
      echo json_encode(array("message" => "Hệ thống không tìm thấy username cần update.", "success" => false));
      exit();
    }

    // Kiểm tra giá trị hợp lệ của status và role_id
    if (!in_array($data->status, [0, 1])) {
      http_response_code(400);
      echo json_encode(array("message" => "Giá trị status không hợp lệ. Chỉ chấp nhận 0 hoặc 1.", "success" => false));
      exit();
    }

    if (!in_array($data->role_id, [1, 2, 3])) {
      http_response_code(400);
      echo json_encode(array("message" => "Giá trị role_id không hợp lệ. Chỉ chấp nhận 1, 2 hoặc 3.", "success" => false));
      exit();
    }

    $account->accountRoleId = $data->role_id;
    $account->accountStatus = $data->status;

    echo json_encode($account->accountStatus);

    // Gọi phương thức update để cập nhật
    if ($account->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Hệ thống cập nhật thành công tài khoản.", "success" => true));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Hệ thống không thể cập nhật thành công tài khoản.", "success" => false));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Hệ thống không thể cập nhật tài khoản vì dữ liệu không hợp lệ.", "success" => false));
}
?>
