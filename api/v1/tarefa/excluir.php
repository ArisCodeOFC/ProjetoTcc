<?php
    require_once("../../../database/database.php");
    header("Content-Type: application/json");

    $input = file_get_contents("php://input");
    $body = json_decode($input);

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
        echo(json_encode(array("sucesso" => true)));
    }
?>
