<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object file
include_once '../config/Db.php';
include_once '../objects/Book.php';
 
$database = new Db();
$db = $database->getConnection();

$book = new Book($db);

$data = $_POST;
$book->id = $data['id'];
if($book->delete()){
    http_response_code(200);
    echo json_encode(array("message" => "Book was deleted."));
}
else{
    http_response_code(503);
    echo json_encode(array("message" => "Unable to delete book."));
}