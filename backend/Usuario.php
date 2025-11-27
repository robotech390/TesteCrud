<?php
class Usuario {
    private $id;
    private $nome;
    private $email;

    public function getId() { 
        return $this->id; 
    }

    public function getNome() { 
        return $this->nome; 
    }

    public function getEmail() { 
        return $this->email; 
    }

    public function setId($id) { 
        $this->id = $id;
    }

    public function setNome($nome) { 
        $this->nome = htmlspecialchars(strip_tags($nome)); 
    }

    public function setEmail($email) { 
        $this->email = htmlspecialchars(strip_tags($email)); 
    }
}
?>