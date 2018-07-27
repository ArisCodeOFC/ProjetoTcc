<?php
    /*
        POST "api/v1/tarefa/listar.php"
        Lista todas as tarefas do usuário logado

        RECEBE: {}

        PRODUZ:
            500 - O usuário não está logado
            200 - Ok - [
                {
                    "id": 0,
                    "titulo": "string",
                    "descricao": "string"
                }
            ]
    */

    error_reporting(0);
    require_once("../../../database/database.php");
    session_start();

    if (!isset($_SESSION["usuario"])) {
        http_response_code(500);
        die("Você não pode fazer isso, pois não está logado.");
    } else {
        $idUsuario = $_SESSION["usuario"]["id"];
        $resultado = [];

        $conexao = getDatabaseConnection();
        if ($conexao) {
            $statement = $conexao->prepare("SELECT id, titulo, descricao FROM tbl_tarefa WHERE idUsuario = ?");
            if ($statement) {
                $statement->bind_param("i", $idUsuario);
                $statement->execute();
                $statement->bind_result($_id, $_titulo, $_descricao);
                while ($statement->fetch()) {
                    $resultado[] = array(
                        "id" => $_id,
                        "titulo" => $_titulo,
                        "descricao" => $_descricao
                    );
                }

                $statement->close();
            }

            $conexao->close();
        }

        header("Content-Type: application/json");
        echo(json_encode($resultado));
    }
?>
