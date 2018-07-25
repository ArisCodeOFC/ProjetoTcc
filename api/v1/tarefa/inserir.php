<?php
    /*
        "api/v1/tarefa/inserir.php"
        Cadastra uma nova tarefa para um usuário

        RECEBE: {
            "titulo": "string",
            "descricao": "string",
            "idUsuario": 0
        }

        PRODUZ:
            401 - O usuário não está logado
            404 - Erro ao inserir no banco de dados
            200 - Tarefa inserida - {
                "id": 0
            }
    */

    error_reporting(0);
    require_once("../../../database/database.php");
    header("Content-Type: application/json");
    session_start();

    if (!isset($_SESSION["usuario"])) {
        http_response_code(401);
    } else {
        $body = json_decode(file_get_contents("php://input"));
        $titulo = $body->titulo;
        $descricao = $body->descricao;
        $idUsuario = $body->idUsuario;

        $conexao = getDatabaseConnection();
        if ($conexao) {
            $statement = $conexao->prepare("INSERT INTO tbl_tarefa VALUES (NULL, ?, ?, ?)");
            if ($statement) {
                $statement->bind_param("ssi", $titulo, $descricao, $idUsuario);
                $statement->execute();
                $id = $statement->insert_id;
                $statement->close();
            }

            $conexao->close();
        }

        if (!$id) {
            http_response_code(404);
        } else {
            echo(json_encode(array("id" => $id)));
        }
    }
?>
