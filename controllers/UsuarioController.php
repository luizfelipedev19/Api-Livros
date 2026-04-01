<?php
require_once __DIR__ . '/../models/Usuarios.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../utils/verificarEmail.php';


class UsuarioController
{
    private Usuarios $usuarioModel;
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->usuarioModel = new Usuarios($db);
        $this->conn = $db;
    }


    //método que atualiza a foto de perfil do usuário
    public function atualizarFoto(): void {
        $usuario = AuthMiddleware::autenticar();
        $idUsuario = $usuario->data->id_usuario;


        //pega o corpo bruto do body
        $rawInput = file_get_contents("php://input");

        file_put_contents(__DIR__ . '/../debug_back.log', 
        "RAW: $rawInput" . PHP_EOL, 
        FILE_APPEND
    );

        $data = json_decode($rawInput, true);
        $urlFoto = $data["url_foto"] ?? null;

        if(!$urlFoto){
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "mensagem" => "A URL da foto não pode ser vazia"
            ]);
            return;
        }

        if(!filter_var($urlFoto, FILTER_VALIDATE_URL)){
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "mensagem" => "A URL da foto é inválida"
            ]);
            return;
        }
        $atualizado = $this->usuarioModel->atualizarFoto($idUsuario, $urlFoto);


        if(!$atualizado){
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "mensagem" => "Erro ao atualziar a foto de perfil"
            ]);
            return;
        }
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "mensagem" => "Foto de perfil atualizada com sucesso",
            "foto_perfil" => $urlFoto
        ]);
    }


    //função para editar os dados do usuário logado, como nome e email
    function editarUsuarioLogado(): void {
        $usuario = AuthMiddleware::autenticar();

        //pegando o id do usuário que vem no usuário autenticado
        $idUsuario = $usuario->data->id_usuario;
        
        //pegando o UUID do usuário autenticado
        $uuid = $usuario->data->UUID;

        //pegando o que vem do body
        $dados = json_decode(file_get_contents("php://input"), true) ?? [];

        //instanciando a classe verificarEmail
        $validar = new verificarEmail($this->conn);


        //verificando se o e-mail existe e se já está em uso por outro usuário
        if(isset($dados['email'])){
            if($validar->verificarEmailEmUso($dados['email'], $uuid)){
                http_response_code(409);
                echo json_encode([
                    "success" => false,
                    "mensagem" => "O email já está em uso por outro usuário"
                ]);
                return;
            }
        }

        $atualizado = $this->usuarioModel->editarUsuario($idUsuario, $uuid, $dados);

        if(!$atualizado){
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "mensagem" => "Erro ao atualizar usuário"
            ]);
            return;
        }

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "mensagem" => "Usuário atualizado com sucesso",
            "detail" => [
                "Usuario" => [
                "id_usuario" => $idUsuario]
                ]
        ]);

    }

    function deletarUsuario(): void {
    // Recupera o usuário autenticado através do token
    $usuario = AuthMiddleware::autenticar();

    // pegando o ID do usuário logado
    $idUsuario = $usuario->data->id_usuario;

    //excluindo o usuário do banco de dados
    $deletado = $this->usuarioModel->deletarUsuario($idUsuario);

    // Verifica se houve falha na exclusão
    if (!$deletado) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "mensagem" => "Erro ao deletar usuário"
        ]);
        return;
    }

    // Retorna sucesso na operação
    http_response_code(200);
    echo json_encode([
        "success" => true,
        "mensagem" => "Usuário deletado com sucesso"
    ]);
}

    function listarUsuario(): void {
    // Recupera o usuário autenticado através do token
    $usuario = AuthMiddleware::autenticar();

    // pegando o UUID do usuário logado 
    $uuid = $usuario->data->UUID;

    // Busca os dados do usuário no banco
    $dadosUsuario = $this->usuarioModel->listarUsuario($uuid);

    // Verifica se o usuário foi encontrado
    if (!$dadosUsuario) {
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "mensagem" => "Usuário não encontrado"
        ]);
        return;
    }

    // Retorna os dados do usuário
    http_response_code(200);
    echo json_encode([
        "success" => true,
        "mensagem" => "Usuário encontrado",
        "detail" => [
            "Usuario" => $dadosUsuario
        ]
    ]);
}

    
}