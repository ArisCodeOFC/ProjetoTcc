<?php
    /*
        POST "api/v1/tarefa/inserir.php"
        Cadastra uma nova tarefa para o usuário logado

        RECEBE: {
            "titulo": "string",
            "descricao": "string"
        }

        PRODUZ:
            500 - Erro de validação ou erro ao inserir no banco de dados
            200 - Tarefa inserida - {
                "id": 0
            }
    */

    error_reporting(0);
    require_once("../../../database/database.php");
    session_start();

    $body = json_decode(file_get_contents("php://input"));
    $titulo = $body->titulo;
    $descricao = $body->descricao;

    $erro = null;
    if (!isset($_SESSION["usuario"])) {
        $erro = "Você não pode fazer isso, pois não está logado.";
    } else if (empty($titulo)) {
        $erro = "O título não pode estar vazio.";
    } else if (empty($descricao)) {
        $erro = "A descrição não pode estar vazia.";
    } else if (strlen($titulo) > 255) {
        $erro = "O título não pode possuir mais do que 255 caracteres.";
    }

    if ($erro) {
        http_response_code(500);
        die($erro);
    }

    $idUsuario = $_SESSION["usuario"]["id"];

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
        http_response_code(500);
        die("Não foi possível inserir a tarefa.");
    } else {
        header("Content-Type: application/json");
        echo(json_encode(array("id" => $id)));
    }
?>
