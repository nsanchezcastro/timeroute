<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../config/db.php';
require_once '../src/Models/Jornada.php';

$database = new Database();
$db = $database->getConnection();
$jornada = new Jornada($db);

// Leer los datos del Body de la petición
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_usuario)) {
    // Guardar lat/lng
    $lat = isset($data->latitud) ? $data->latitud : null;
    $lng = isset($data->longitud) ? $data->longitud : null;

    $id_creado = $jornada->iniciar($data->id_usuario, $lat, $lng);

    if($id_creado) {
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "message" => "Jornada iniciada correctamente",
            "id_jornada" => $id_creado
        ]);
    } else {
        http_response_code(503);
        echo json_encode(["status" => "error", "message" => "Error al registrar el fichaje"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID de usuario necesario"]);
}