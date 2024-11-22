<?php
  header('Access-Control-Allow-Origin:*');
  header('Content-Type: application/json');

  include_once('../../config/db.php');
  include_once('../../model/discounts.php');

  try {
    $db = new db();
    $connect = $db->connect();
    $discounts = new Discounts($connect);
    $read = $discounts->read();
    $num = $read->rowCount();

    if ($num > 0) {
      $discount_array = [];
      $discount_array['data'] = []; 

      while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $discount_item = array(
          'discount_code' => $discount_code,
          'discount_value' => $discount_value,
          'discount_type' => $type,
          'discount_start_date' => $start_date,
          'discount_end_date' => $end_date,
          'discount_status' => $status,
          'discount_delete_date' => $delete_date,
          'discount_create_date' => $create_date,
          'discount_update_date' => $update_date,
        );

        array_push($discount_array['data'], $discount_item);
      }
      http_response_code(200);
      echo json_encode($discount_array);
    } else {
      http_response_code(404);
      echo json_encode(array('message' => 'No discounts found.'));
    }
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array(
      'message' => 'Internal Server Error',
      'error' => $e->getMessage()
    ));
  }
?>
