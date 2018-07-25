<?php
    /*
        "api/v1/usuario/inserir.php"
        Cadastra um novo usuário

        RECEBE: {
            "nome": "string",
            "email": "string",
            "senha": "string"
        }

        PRODUZ:
            401 - O usuário já está logado
            404 - Erro ao inserir no banco de dados
            200 - Cadastro realizado - {
                "id": 0
            }
    */

    error_reporting(0);
    require_once("../../../database/database.php");
    header("Content-Type: application/json");
    session_start();

    if (isset($_SESSION["usuario"])) {
        http_response_code(401);
    } else {
        $body = json_decode(file_get_contents("php://input"));
        $nome = $body->nome;
        $email = $body->email;
        $senha = $body->senha;

        $conexao = getDatabaseConnection();
        $resultado = false;

        if ($conexao) {
            $statement = $conexao->prepare("INSERT INTO tbl_usuario VALUES (NULL, ?, ?, ?)");
            if ($statement) {
                $statement->bind_param("sss", $nome, $email, $senha);
                $statement->execute();
                $resultado = $statement->insert_id;
                $statement->close();
            }

            $conexao->close();
        }

        if (!$resultado) {
            http_response_code(404);
        } else {
            echo(json_encode(array("id" => $resultado)));
        }
    }
?>
