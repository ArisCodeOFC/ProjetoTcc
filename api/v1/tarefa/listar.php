<?php
    require_once("../../../database/database.php");
    header("Content-Type: application/json");

    $input = file_get_contents("php://input");
    $body = json_decode($input);

    $idUsuario = $body->idUsuario;

    $resultado = [];

    $conexao = getDatabaseConnection();
    if ($conexao) {
        $statement = $conexao->prepare("SELECT * FROM tbl_tarefa WHERE idUsuario = ?");
        if ($statement) {
            $statement->bind_param("i", $idUsuario);
            $statement->execute();
            $statement->bind_result($_id, $_titulo, $_descricao, $_idUsuario);
            while ($statement->fetch()) {
                $resultado[] = array(
                    "id" => $_id,
                    "nome" => $_titulo,
                    "email" => $_descricao,
                    "idUsuario" => $_idUsuario
                );
            }

            $statement->close();
        }

        $conexao->close();
    }

    echo(json_encode($resultado));
?>
