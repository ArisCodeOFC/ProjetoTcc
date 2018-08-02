<?php
    /*
        POST "api/v1/usuario/logout.php"
        Destrói a sessão de um usuário já logado

        RECEBE: {}
        PRODUZ:
            500 - O usuário não está logado
            200 - Ok
    */

    error_reporting(0);
    session_start();
    if (isset($_SESSION["usuario"])) {
        unset($_SESSION["usuario"]);
    } else {
        http_response_code(500);
        die("Você não pode fazer isso, pois não está logado.");
    }
?>
