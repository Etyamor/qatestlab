<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/Db.php';
include_once '../objects/Book.php';
 
$database = new Db();
$db = $database->getConnection();
 
$book = new Book($db);
 
$data = $_POST;
 
$book->id = $data['id'];
$book->name = $data['name'];
$book->authors_id = $data['authors_id'] ? $data['authors_id'] : false;
$book->publication_id = $data['publication_id'];

if($book->update()){
    http_response_code(200);
    echo json_encode(array("message" => "book was updated."));
}
else{
    http_response_code(503);
    echo json_encode(array("message" => "Unable to update book."));
}
?>