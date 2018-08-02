<?php
    /*
        POST "api/v1/tarefa/excluir.php"
        Exclui uma tarefa existente

        RECEBE: {
            "id": 0
        }

        PRODUZ:
            500 - Erro ao excluir no banco de dados ou id não encontrado
            204 - Tarefa excluída com sucesso
    */

    require_once("../../../database/database.php");
    session_start();

    $body = json_decode(file_get_contents("php://input"));
    $id = $body->id;

    if (!isset($_SESSION["usuario"])) {
        http_response_code(500);
        die("Você não pode fazer isso, pois não está logado.");
    } else {
        $resultado = false;

        $conexao = getDatabaseConnection();
        if ($conexao) {
            $statement = $conexao->prepare("DELETE FROM tbl_tarefa WHERE id = ?");
            if ($statement) {
                $statement->bind_param("i", $id);
                $statement->execute();
                $resultado = $statement->affected_rows;
                $statement->close();
            }

            $conexao->close();
        }

        if ($resultado < 0) {
            http_response_code(500);
            die("Não foi possível excluir a tarefa.");
        } else {
            http_response_code(204);
        }
    }
?>
