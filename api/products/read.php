<?php
  header('Access-Control-Allow-Origin:*');
  header('Content-Type: application/json');

  include_once('../../config/db.php');
  include_once('../../model/products.php');

  $db = new db();
  $connect = $db->connect();

  $products = new Products($connect);
  $read = $products->read();

  $num = $read->rowCount();
  if ($num > 0) {
    $product_array = [];
    $product_array['data'] = []; 

    while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
      extract($row);
      $product_item = array(
        'product_id' => $id,
        'product_name' => $name,
        'product_publisher_id' => $publisher_id,
        'product_image_path' => $image_path,
        'product_create_date' => $create_date,
        'product_update_date' => $update_date,
        'product_price' => $price,
        'product_quantity' => $quantity,
        'product_supplier_id' => $supplier_id,
        'product_status' => $status,
      );

      array_push($product_array['data'], $product_item);
    }

    echo json_encode($product_array);
  } else {
    echo json_encode(array('message' => 'No products found.'));
  }
?>
