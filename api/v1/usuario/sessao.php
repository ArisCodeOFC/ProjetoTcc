<?php
    /*
        POST "api/v1/usuario/sessao.php"
        Checa se a pessoa que está acessando a página possui permissão de acesso

        RECEBE: {
            "arquivo": "string"
        }

        PRODUZ:
            500 - Não autorizado a acessar a página
            204 - Acesso autorizado
            200 - Acesso autorizado e usuário logado - {
                "id": 0,
                "nome": "string",
                "email": "string"
            }
    */

    error_reporting(0);
    session_start();

    $body = json_decode(file_get_contents("php://input"));
    $arquivo = $body->arquivo;

    if (isset($arquivo) && isset($_SESSION["usuario"])) {
        if ($arquivo == "login.html" || $arquivo == "cadastroUser.html") {
            http_response_code(500);
            die("index.html");
        } else {
            header("Content-Type: application/json");
            echo(json_encode($_SESSION["usuario"]));
        }

    } else {
        if ($arquivo == "login.html" || $arquivo == "cadastroUser.html") {
            http_response_code(204);
        } else {
            http_response_code(500);
            die("login.html");
        }
    }
?>
