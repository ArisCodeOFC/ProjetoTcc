<?php
    /*
        "api/v1/tarefa/excluir.php"
        Exclui uma tarefa existente

        RECEBE: {
            "id": 0
        }

        PRODUZ:
            401 - O usuário não está logado
            404 - Erro ao excluir no banco de dados ou id não encontrado
            204 - Tarefa excluída com sucesso
    */

    require_once("../../../database/database.php");
    header("Content-Type: application/json");
    session_start();

    if (!isset($_SESSION["usuario"])) {
        http_response_code(401);
    } else {
        $body = json_decode(file_get_contents("php://input"));
        $id = $body->id;

        $result = false;

        $conexao = getDatabaseConnection();
        if ($conexao) {
            $statement = $conexao->prepare("DELETE FROM tbl_tarefa WHERE id = ?");
            if ($statement) {
                $statement->bind_param("i", $id);
                $statement->execute();
                $result = $statement->affected_rows;
                $statement->close();
            }

            $conexao->close();
        }

        if ($result < 0) {
            http_response_code(404);
        } else {
            http_response_code(204);
        }
    }
?>
