<?php
    require_once("../../../database/database.php");
    header("Content-Type: application/json");

    $input = file_get_contents("php://input");
    $body = json_decode($input);

    $nome = $body->nome;
    $email = $body->email;
    $senha = $body->senha;

    $conexao = getDatabaseConnection();
    $id = false;

    if ($conexao) {
        $statement = $conexao->prepare("INSERT INTO tbl_usuario VALUES (NULL, ?, ?, ?)");
        if ($statement) {
            $statement->bind_param("sss", $nome, $email, $senha);
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
