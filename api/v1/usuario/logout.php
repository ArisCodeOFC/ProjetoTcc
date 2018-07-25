<?php
    /*
        "api/v1/usuario/logout.php"
        Destrói a sessão de um usuário já logado

        RECEBE: {}
        PRODUZ:
            401 - O usuário não está logado
            200 - Ok
    */

    error_reporting(0);
    session_start();
    if (isset($_SESSION["usuario"])) {
        session_destroy();
    } else {
        http_response_code(401);
    }
?>
