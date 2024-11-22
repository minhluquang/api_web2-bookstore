<?php
  header('Access-Control-Allow-Origin:*');
  header('Content-Type: application/json');

  include_once('../../config/db.php');
  include_once('../../model/authors.php');

  try {
    $db = new db();
    $connect = $db->connect();
    $authors = new Authors($connect);
    $read = $authors->read();
    $num = $read->rowCount();

    if ($num > 0) {
      $author_array = [];
      $author_array['data'] = []; 

      while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $author_item = array(
          'author_id' => $id,
          'author_name' => $name,
          'author_email' => $email,
          'author_status' => $status,
        );

        array_push($author_array['data'], $author_item);
      }
      http_response_code(200);
      echo json_encode($author_array);
    } else {
      http_response_code(404);
      echo json_encode(array('message' => 'No authors found.', 'success' => false));
    }
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array(
      'message' => 'Internal Server Error',
      'error' => $e->getMessage(),
      'success' => false
    ));
  }
?>
