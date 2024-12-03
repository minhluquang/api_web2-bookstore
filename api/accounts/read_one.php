<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/accounts.php');

try {
    // Khởi tạo đối tượng kết nối cơ sở dữ liệu và lớp Accounts
    $db = new db();
    $connect = $db->connect();
    $account = new Accounts($connect);

    // Kiểm tra xem có parameter 'id' trong URL không
    if (isset($_GET['username'])) {
        $accountUsername = $_GET['username'];

        if (trim($accountUsername) === '') {
          http_response_code(400);
          echo json_encode(array('message' => 'Username không được để trống', 'success' => false));
          exit; 
        }

        $account->accountUsername = $accountUsername;

        // Lấy dữ liệu tài khoản theo ID
        $read = $account->read_single();

        // Kiểm tra xem truy vấn có thành công không
        if ($read && $read->rowCount() > 0) {
            $account_array = [];
            $account_array['data'] = [];

            // Lấy thông tin tài khoản
            $row = $read->fetch(PDO::FETCH_ASSOC);
            $account_item = array(
                'username' => $row['username'],
                'email' => $row['email'],
                'role_id' => $row['role_id'],
                'status' => $row['status']
            );

            // Thêm tài khoản vào mảng kết quả
            array_push($account_array['data'], $account_item);

            // Trả về phản hồi thành công
            http_response_code(200);
            echo json_encode($account_array);
        } else {
            // Nếu không tìm thấy tài khoản
            http_response_code(404);
            echo json_encode(array('message' => 'Hệ thống không tìm thấy tài khoản này.', 'success' => false));
        }
    } else {
        // Nếu không có 'id' trong URL
        http_response_code(400);
        echo json_encode(array('message' => 'Vui lòng nhập trường username', 'success' => false));
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
