<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../../config/db.php');
include_once('../../model/authors.php');

$db = new db();
$connect = $db->connect();
$author = new Authors($connect);
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(!empty($data->authorId) && !empty($data->authorName) && !empty($data->authorEmail)){
    $author->authorId = $data->authorId;
    $author->authorName = $data->authorName;
    $author->authorEmail = $data->authorEmail;

    if($author->update()){
      http_response_code(201);
      echo json_encode(array("message" => "Author was updated."));
    }
    else{
      http_response_code(503);
      echo json_encode(array("message" => "Unable to update author."));
    }
} else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update author. Data is incomplete."));
}
?>
