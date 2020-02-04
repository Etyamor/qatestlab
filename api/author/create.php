<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/Db.php';
include_once '../objects/Author.php';
 
$database = new Db();
$db = $database->getConnection();
 
$author = new Author($db);
 
$data = $_POST;

if(
    !empty($data['name'])
){
 
    $author->name = $data['name'];
 
    if($author->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Author was created."));
    }
    else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create author."));
    }
}
else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create author. Data is incomplete."));
}