<?php
    /*
        "api/v1/usuario/login.php"
        Tenta fazer o login de algum usuário

        RECEBE: {
            "email": "string",
            "senha": "string"
        }

        PRODUZ:
            401 - O usuário já está logado
            404 - Erro ao selecionar ou dados incorretos
            200 - Login bem sucedido - {
                "id": 0,
                "nome": "string",
                "email": "string"
            }
    */

    error_reporting(0);
    require_once("../../../database/database.php");
    header("Content-Type: application/json");
    session_start();

    if (isset($_SESSION["usuario"])) {
        http_response_code(401);
    } else {
        $body = json_decode(file_get_contents("php://input"));
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
            $_SESSION["usuario"] = $resultado;
            echo(json_encode($resultado));
        } else {
            http_response_code(404);
        }
    }
?>
