<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
include_once '../config/Db.php';
include_once '../objects/Publication.php';
 
$database = new Db();
$db = $database->getConnection();
 
$publication = new Publication($db);
 
$stmt = $publication->read();
$num = $stmt->rowCount();
 
if($num>0){
    $publications_arr=array();
    $publications_arr["records"]=array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $publication_item=array(
            "id" => $id,
            "name" => $name,
        );
        array_push($publications_arr["records"], $publication_item);
    }
    http_response_code(200);
    echo json_encode($publications_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No publications found.")
    );
}