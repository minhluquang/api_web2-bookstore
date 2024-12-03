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
if (
    isset($data->username) &&
    isset($data->current_password) &&
    isset($data->new_password) &&
    isset($data->repeat_password)
) {
    $account->accountUsername = $data->username;

    // Kiểm tra username có tồn tại trong database
    if (!$account->usernameExists($account->accountUsername)) {
        http_response_code(404);
        echo json_encode(array("message" => "Hệ thống không tìm thấy username.", "success" => false));
        exit();
    }

    // Kiểm tra password hiện tại
    if (!$account->verifyPassword($data->username, $data->current_password)) {
        http_response_code(400);
        echo json_encode(array("message" => "Mật khẩu hiện tại không đúng.", "success" => false));
        exit();
    }

    // Kiểm tra độ dài mật khẩu mới
    if (strlen($data->new_password) < 8) {
        http_response_code(400);
        echo json_encode(array("message" => "Mật khẩu mới phải từ 8 ký tự trở lên.", "success" => false));
        exit();
    }

    // Kiểm tra mật khẩu mới và mật khẩu lặp lại
    if ($data->new_password !== $data->repeat_password) {
        http_response_code(400);
        echo json_encode(array("message" => "Mật khẩu mới và mật khẩu lặp lại không khớp.", "success" => false));
        exit();
    }

    // Cập nhật mật khẩu mới
    $account->accountPassword = password_hash($data->new_password, PASSWORD_DEFAULT);
    if ($account->updatePassword($data->username)) {
        http_response_code(200);
        echo json_encode(array("message" => "Hệ thống đã cập nhật mật khẩu thành công.", "success" => true));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Hệ thống không thể cập nhật mật khẩu.", "success" => false));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Dữ liệu không hợp lệ.", "success" => false));
}
?>
