<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../../config/db.php');
include_once('../../model/authors.php');

// Khởi tạo đối tượng kết nối cơ sở dữ liệu và lớp Authors
$db = new db();
$connect = $db->connect();
$author = new Authors($connect);

if (isset($_GET['id'])) {
  $author->authorId = $_GET['id']; 

  if ($author->delete()) {  
    http_response_code(200);
    echo json_encode(array("message" => "Author status updated to 0.", 'success' => true));
  } else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to update author status.", 'success' => false));
  }
} else {
  http_response_code(400);
  echo json_encode(array("message" => "Unable to update author. 'authorId' is required.", 'success' => false));
}
