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

if(
    !empty($data['name']) &&
    !empty($data['publication_id']) &&
    !empty($data['authors_id'])
){
 
    $book->name = $data['name'];
    $book->publication_id = $data['publication_id'];
    $book->authors_id = $data['authors_id'];
 
    if($book->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Book was created."));
    }
    else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create book."));
    }
}
else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create book. Data is incomplete."));
}