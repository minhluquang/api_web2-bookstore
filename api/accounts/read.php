<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

// Kiểm tra phương thức request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  http_response_code(405);
  echo json_encode(array("message" => "Phương thức không được cho phép", "success" => false));
  exit();
}

include_once('../../config/db.php');
include_once('../../model/accounts.php');

try {
  $db = new db();
  $connect = $db->connect();
  $accounts = new Accounts($connect);
  $read = $accounts->read();
  $num = $read->rowCount();

  if ($num > 0) {
    $account_array = [];
    $account_array['data'] = [];

    while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
      extract($row);
      $account_item = array(
        'username' => $username,
        'role_id' => $role_id,
        'status' => $status,
        'email' => $email,
      );

      array_push($account_array['data'], $account_item);
    }
    http_response_code(200);
    echo json_encode($account_array);
  } else {
    http_response_code(404);
    echo json_encode(array('message' => 'Không tìm thấy tài khoản.', 'success' => false));
  }
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(array(
    'message' => 'Internal Server Error',
    'error' => $e->getMessage(),
    'success' => false
  ));
}
