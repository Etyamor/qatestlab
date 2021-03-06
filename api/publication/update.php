<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/Db.php';
include_once '../objects/Publication.php';
 
$database = new Db();
$db = $database->getConnection();
 
$publication = new Publication($db);
 
$data = $_POST;
 
$publication->id = $data['id'];
$publication->name = $data['name'];

if($publication->update()){
    http_response_code(200);
    echo json_encode(array("message" => "Publication was updated."));
}
else{
    http_response_code(503);
    echo json_encode(array("message" => "Unable to update publication."));
}
?>