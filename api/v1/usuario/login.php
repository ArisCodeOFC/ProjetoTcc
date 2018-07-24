<?php
    require_once("../../../database/database.php");
    header("Content-Type: application/json");

    $input = file_get_contents("php://input");
    $body = json_decode($input);

    $email = $body->email;
    $senha = $body->senha;

    $resultado = null;

    $conexao = getDatabaseConnection();
    if ($conexao) {
        $statement = $conexao->prepare("SELECT id, nome, senha FROM tbl_usuario WHERE email = ? AND senha = ?");
        if ($statement) {
            $statement->bind_param("ss", $email, $senha);
            $statement->execute();
            $statement->bind_result($_id, $_nome, $_email);
            if ($statement->fetch()) {
                $resultado = array(
                    "id" => $_id,
                    "nome" => $_nome,
                    "email" => $_email
                );
            }

            $statement->close();
        }

        $conexao->close();
    }

    if ($resultado) {
        echo(json_encode($resultado));
    } else {
        http_response_code(404);
    }
?>
