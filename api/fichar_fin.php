<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../config/db.php';
require_once '../src/Models/Jornada.php';

$database = new Database();
$db = $database->getConnection();
$jornada = new Jornada($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_usuario)) {
    if($jornada->finalizar($data->id_usuario)) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Jornada finalizada con éxito"]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "No se encontró una jornada abierta para hoy"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID de usuario necesario"]);
}