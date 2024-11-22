<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/authors.php');

try {
  // Khởi tạo đối tượng kết nối cơ sở dữ liệu và lớp Authors
  $db = new db();
  $connect = $db->connect();
  $author = new Authors($connect);

  // Kiểm tra xem có parameter 'id' trong URL không
  if (isset($_GET['id'])) {
    // Lấy giá trị 'id' từ URL
    $authorId = $_GET['id'];
    $author->authorId = $authorId; 
    
    // Lấy dữ liệu tác giả theo ID
    $read = $author->read_single();

    // Kiểm tra xem truy vấn có thành công không
    if ($read && $read->rowCount() > 0) {
      $author_array = [];
      $author_array['data'] = [];

      // Lấy thông tin tác giả
      $row = $read->fetch(PDO::FETCH_ASSOC);
      $author_item = array(
        'author_id' => $row['id'],
        'author_name' => $row['name'],
        'author_email' => $row['email'],
        'author_status' => $row['status'],
      );

      // Thêm tác giả vào mảng kết quả
      array_push($author_array['data'], $author_item);

      // Trả về phản hồi thành công
      http_response_code(200);
      echo json_encode($author_array);
    } else {
      // Nếu không tìm thấy tác giả
      http_response_code(404);
      echo json_encode(array('message' => 'Author not found.'));
    }
  } else {
    // Nếu không có 'id' trong URL
    http_response_code(400);
    echo json_encode(array('message' => 'Author ID is required.'));
  }
} catch (Exception $e) {
  // Nếu có lỗi xảy ra trong quá trình xử lý
  http_response_code(500);
  echo json_encode(array(
    'message' => 'Internal Server Error',
    'error' => $e->getMessage()
  ));
}
?>
