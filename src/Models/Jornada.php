<?php
class Jornada {
    private $conn;
    private $table_name = "jornada_laboral";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Registrar el inicio de la jornada
    public function iniciar($id_usuario, $lat = null, $lng = null) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET id_usuario = :id_usuario, 
                      fecha = CURDATE(), 
                      hora_inicio = CURTIME(),
                      latitud_inicio = :lat,
                      longitud_inicio = :lng";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':lat', $lat);
        $stmt->bindParam(':lng', $lng);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    
public function finalizar($id_usuario) {
    // Buscar jornada de hoy para este usuario que aún no tenga hora_fin
    $query = "UPDATE " . $this->table_name . " 
              SET hora_fin = CURTIME(),
                  total_horas = TIMEDIFF(CURTIME(), hora_inicio)
              WHERE id_usuario = :id_usuario 
              AND fecha = CURDATE() 
              AND hora_fin IS NULL";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario);

    if($stmt->execute()) {
        return $stmt->rowCount() > 0;
    }
    return false;
}
}