<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once 'backend/Database.php';
require_once 'backend/Usuario.php';
require_once 'backend/UsuarioRepository.php';

$database = new Database();
$db = $database->getConnection();

$usuarioRepo = new UsuarioRepository($db);

$method = $_SERVER['REQUEST_METHOD'];

$action = $_GET['action'] ?? '';

$data = json_decode(file_get_contents('php://input'));

switch ($action) {
    case 'create':
        if ($method == 'POST') {
            $usuario = new Usuario();
            $usuario->setNome($data->nome);
            $usuario->setEmail($data->email);

            if ($usuarioRepo->create($usuario)) {
                echo json_encode(['message' => 'Usuário criado com sucesso.']);
            } else {
                echo json_encode(['message' => 'Erro ao criar usuário.']);
            }
        }
        break;

    case 'readAll':
        if ($method == 'GET') {
            $usuarios = $usuarioRepo->readAll();
            echo json_encode($usuarios);
        }
        break;

     case 'readOne':
        if ($method == 'GET' && isset($_GET['id'])) {
            $usuario = $usuarioRepo->readOne($_GET['id']);
            if($usuario) {
                echo json_encode($usuario);
            } else {
                 http_response_code(404);
                 echo json_encode(['message' => 'Usuário não encontrado.']);
            }
        }
        break;

    case 'update':
         if ($method == 'POST') { 
            $usuario = new Usuario();
            $usuario->setId($data->id);
            $usuario->setNome($data->nome);
            $usuario->setEmail($data->email);

            if ($usuarioRepo->update($usuario)) {
                echo json_encode(['message' => 'Usuário atualizado com sucesso.']);
            } else {
                echo json_encode(['message' => 'Erro ao atualizar usuário.']);
            }
        }
        break;

    case 'delete':
         if ($method == 'POST') {
            $id = $data->id;
            if ($usuarioRepo->delete($id)) {
                echo json_encode(['message' => 'Usuário deletado com sucesso.']);
            } else {
                echo json_encode(['message' => 'Erro ao deletar usuário.']);
            }
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['message' => 'Ação inválida.']);
        break;
}
?>