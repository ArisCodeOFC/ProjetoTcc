<?php
    /*
        "api/v1/tarefa/atualizar.php"
        Atualiza uma tarefa existente

        RECEBE: {
            "titulo": "string",
            "descricao": "string",
            "id": 0
        }

        PRODUZ:
            401 - O usuário não está logado
            404 - Erro ao atualizar no banco de dados ou id não encontrado
            204 - Tarefa atualizada com sucesso
    */

    error_reporting(0);
    require_once("../../../database/database.php");
    session_start();

    if (!isset($_SESSION["usuario"])) {
        http_response_code(401);
    } else {
        $body = json_decode(file_get_contents("php://input"));
        $id = $body->id;
        $titulo = $body->titulo;
        $descricao = $body->descricao;

        $resultado = null;
        $conexao = getDatabaseConnection();
        if ($conexao) {
            $statement = $conexao->prepare("UPDATE tbl_tarefa SET titulo = ?, descricao = ? WHERE id = ?");
            if ($statement) {
                $statement->bind_param("ssi", $titulo, $descricao, $id);
                $statement->execute();
                preg_match_all("!\d+!", $conexao->info, $matches);
                $resultado = $matches[0][0];
                $statement->close();
            }

            $conexao->close();
        }

        if (!$resultado) {
            http_response_code(404);
        } else {
            http_response_code(204);
        }
    }
?>
