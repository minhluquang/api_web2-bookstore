<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../../config/db.php');
include_once('../../model/accounts.php');
include_once('../../model/deliveryInfoes.php');
include_once('../../model/verifyCodes.php');

// Khởi tạo kết nối cơ sở dữ liệu và lớp Accounts
$db = new db();
$connect = $db->connect();
$account = new Accounts($connect);
$deliveryInfo = new DeliveryInfoes($connect);
$verifyCode = new VerifyCodes($connect);

// Lấy dữ liệu JSON được gửi đến
$data = json_decode(file_get_contents("php://input"));

// Định nghĩa các biểu thức chính quy
$regexSpecialChar = "/[^a-zA-Z0-9]/"; // Kiểm tra ký tự đặc biệt
$regexFullName = "/[a-zA-ZÀ-ỹ]+(\s[a-zA-ZÀ-ỹ]+){1,}$/"; // Tên đúng định dạng
$regexPhoneNumber = "/^0[0-9]{9}$/"; // Số điện thoại 10 chữ số, bắt đầu bằng 0
$regexEmail = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/"; // Định dạng email
$regexAddress = "/^\d+[A-Za-z]?(\/\d+)?\s[a-zA-ZÀ-ỹ\s]+$/"; // Địa chỉ cơ bản

// Kiểm tra từng trường dữ liệu
if (empty($data->username)) {
    $error = "Tên đăng nhập không được để trống.";
} else if (preg_match($regexSpecialChar, $data->username)) {
    $error = "Tên đăng nhập không chứa ký tự đặc biệt.";
} else if (strlen($data->username) < 8) {
    $error = "Tên đăng nhập phải từ 8 ký tự.";
} else if ($account->usernameExists($data->username)) {
    $error = "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.";
} else if (empty($data->fullName)) {
    $error = "Họ và tên không được để trống.";
} else if (!preg_match($regexFullName, $data->fullName)) {
    $error = "Nhập họ và tên đúng định dạng (ví dụ: Lữ Minh).";
} else if (empty($data->phoneNumber)) {
    $error = "Số điện thoại không được để trống.";
} else if (!preg_match($regexPhoneNumber, $data->phoneNumber)) {
    $error = "Số điện thoại không hợp lệ.";
} else if (empty($data->email)) {
    $error = "Email không được để trống.";
} else if (!preg_match($regexEmail, $data->email)) {
    $error = "Email không hợp lệ.";
} else if (empty($data->password)) {
    $error = "Mật khẩu không được để trống.";
} else if (strlen($data->password) < 8) {
    $error = "Mật khẩu phải từ 8 ký tự trở lên.";
} else if (empty($data->province)) {
    $error = "Vui lòng chọn tỉnh thành phố.";
} else if (empty($data->address)) {
    $error = "Vui lòng nhập địa chỉ.";
} else if (!preg_match($regexAddress, $data->address)) {
    $error = "Nhập địa chỉ không đúng định dạng (ví dụ: 173A/32 Dương Quảng Hàm).";
} else if (empty($data->role_id)) {
    $error = "Vui lòng chọn loại tài khoản.";
}

// Nếu có lỗi, trả về lỗi
if (isset($error)) {
    http_response_code(400);
    echo json_encode(array(
        "message" => "Hệ thống không thể tạo tài khoản.",
        "error" => $error,
        "success" => false
    ));
    exit();
}

// Gán giá trị cho các thuộc tính của lớp Accounts
$account->accountUsername = $data->username;
$deliveryInfo->deliveryInfoUsername = $data->username;
$deliveryInfo->deliveryInfoFullname = $data->fullName;
$deliveryInfo->deliveryInfoPhoneNumber = $data->phoneNumber;
$account->accountEmail = $data->email;
$account->accountPassword = password_hash($data->password, PASSWORD_DEFAULT); // Hash mật khẩu
$deliveryInfo->deliveryInfoProvince = $data->province;
$deliveryInfo->deliveryInfoDistrict = $data->district;
$deliveryInfo->deliveryInfoWard = $data->ward;
$deliveryInfo->deliveryInfoAddress = $data->address;
$account->accountRoleId = $data->role_id;

$verifyCode->verifyCodeEmail = $data->email;
$verifyCode->verifyCodeCode = '';

// Gọi phương thức create() để tạo tài khoản
if ($verifyCode->create() && $account->create() && $deliveryInfo->create()) {
    http_response_code(201);
    echo json_encode(array("message" => "Hệ thống tạo tài khoản thành công.", 'success' => true));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Hệ thống không thể tạo tài khoản", 'success' => false));
}
?>
