<?php
    /*
        POST "api/v1/tarefa/atualizar.php"
        Atualiza uma tarefa existente

        RECEBE: {
            "titulo": "string",
            "descricao": "string",
            "id": 0
        }

        PRODUZ:
            500 - Erro de validação ou erro ao inserir no banco de dados
            204 - Tarefa atualizada com sucesso
    */

    error_reporting(0);
    require_once("../../../database/database.php");
    session_start();

    $body = json_decode(file_get_contents("php://input"));
    $id = $body->id;
    $titulo = $body->titulo;
    $descricao = $body->descricao;

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
        http_response_code(500);
        die("Não foi possível atualizar a tarefa.");
    } else {
        http_response_code(204);
    }
?>
