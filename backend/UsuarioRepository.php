<?php
class UsuarioRepository {
    private $conn;
    private $table_name = "usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create(Usuario $usuario) {
        $query = "INSERT INTO " . $this->table_name . " (nome, email) VALUES (:nome, :email)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nome", $usuario->getNome());
        $stmt->bindParam(":email", $usuario->getEmail());

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readAll() {
        $query = "SELECT id, nome, email FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function readOne($id) {
        $query = "SELECT id, nome, email FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(Usuario $usuario) {
        $query = "UPDATE " . $this->table_name . "
                  SET nome = :nome, email = :email
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nome", $usuario->getNome());
        $stmt->bindParam(":email", $usuario->getEmail());
        $stmt->bindParam(":id", $usuario->getId());

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>