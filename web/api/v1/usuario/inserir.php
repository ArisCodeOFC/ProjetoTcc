<?php
    /*
        POST "api/v1/usuario/inserir.php"
        Cadastra um novo usuário

        RECEBE: {
            "nome": "string",
            "email": "string",
            "senha": "string"
        }

        PRODUZ:
            500 - Erro de validação / erro ao inserir no banco de dados
            200 - Cadastro realizado - {
                "id": 0
            }
    */

    error_reporting(0);
    require_once("../../../database/database.php");
    session_start();

    $body = json_decode(file_get_contents("php://input"));
    $nome = $body->nome;
    $email = $body->email;
    $senha = $body->senha;

    $erro = null;
    if (isset($_SESSION["usuario"])) {
        $erro = "Você não pode fazer isso, pois já está logado.";
    } else if (empty($nome)) {
        $erro = "O nome não pode estar vazio.";
    } else if (empty($email)) {
        $erro = "O email não pode estar vazio.";
    } else if (empty($senha)) {
        $erro = "A senha não pode estar vazia.";
    } else if (strlen($nome) > 255) {
        $erro = "O nome não pode possuir mais do que 255 caracteres.";
    } else if (strlen($email) > 255) {
        $erro = "O email não pode possuir mais do que 255 caracteres.";
    } else if (strlen($senha) > 255) {
        $erro = "A senha não pode possuir mais do que 255 caracteres.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "O email inserido não é válido.";
    }

    if ($erro) {
        http_response_code(500);
        die($erro);
    }

    $conexao = getDatabaseConnection();
    $resultado = false;

    if ($conexao) {
        $statement = $conexao->prepare("SELECT 1 FROM tbl_usuario WHERE email = ?");
        if ($statement) {
            $statement->bind_param("s", $email);
            $statement->execute();
            $statement->bind_result($_usuarioExistente);
            if ($statement->fetch() && $_usuarioExistente) {
                http_response_code(500);
                die("Este email já está cadastrado.");
            }

            $statement->close();
        }

        $statement = $conexao->prepare("INSERT INTO tbl_usuario VALUES (NULL, ?, ?, ?)");
        if ($statement) {
            $statement->bind_param("sss", $nome, $email, $senha);
            $statement->execute();
            $resultado = $statement->insert_id;
            $statement->close();
        }

        $conexao->close();
    }

    if (!$resultado) {
        http_response_code(500);
        die("Não foi possível concluir o seu cadastro.");
    } else {
        header("Content-Type: application/json");
        echo(json_encode(array("id" => $resultado)));
    }
?>
