<?php
    require_once("../../../database/database.php");
    header("Content-Type: application/json");

    $input = file_get_contents("php://input");
    $body = json_decode($input);

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
?>
