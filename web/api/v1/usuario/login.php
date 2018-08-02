<?php
    /*
        POST "api/v1/usuario/login.php"
        Tenta fazer o login de algum usuário

        RECEBE: {
            "email": "string",
            "senha": "string"
        }

        PRODUZ:
            500 - Erro ao selecionar ou dados incorretos
            200 - Login bem sucedido - {
                "id": 0,
                "nome": "string",
                "email": "string"
            }
    */

    error_reporting(0);
    require_once("../../../database/database.php");
    session_start();

    $body = json_decode(file_get_contents("php://input"));
    $email = $body->email;
    $senha = $body->senha;

    $erro = null;
    if (isset($_SESSION["usuario"])) {
        $erro = "Você não pode fazer isso, pois já está logado.";
    } else if (empty($email)) {
        $erro = "O email não pode estar vazio.";
    } else if (empty($senha)) {
        $erro = "A senha não pode estar vazia.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "O email inserido não é válido.";
    }

    if ($erro) {
        http_response_code(500);
        die($erro);
    }

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

    if (!$resultado) {
        http_response_code(500);
        die("Usuário ou senha incorretos.");
    } else {
        $_SESSION["usuario"] = $resultado;
        header("Content-Type: application/json");
        echo(json_encode($resultado));
    }
?>
