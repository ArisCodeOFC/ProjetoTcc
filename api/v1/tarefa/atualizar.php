<?php
    require_once("../../../database/database.php");
    header("Content-Type: application/json");

    $input = file_get_contents("php://input");
    $body = json_decode($input);

    $id = $body->id;
    $titulo = $body->titulo;
    $descricao = $body->descricao;

    $result = null;

    $conexao = getDatabaseConnection();
    if ($conexao) {
        $statement = $conexao->prepare("SELECT id FROM tbl_tarefa WHERE id = ?");
        if ($statement) {
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->bind_result($result);
            if ($statement->fetch() && $result) {
                $statement->close();
                $statement2 = $conexao->prepare("UPDATE tbl_tarefa SET titulo = ?, descricao = ? WHERE id = ?");
                if ($statement) {
                    $statement2->bind_param("ssi", $titulo, $descricao, $id);
                    $statement2->execute();
                    $statement2->close();
                }

            } else {
                $statement->close();
            }
        }

        $conexao->close();
    }

    if (!$result) {
        http_response_code(404);
    } else {
        echo(json_encode(array("sucesso" => true)));
    }
?>
