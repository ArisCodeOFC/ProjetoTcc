<?php
    function getDatabaseConnection() {
        try {
            mysqli_report(MYSQLI_REPORT_STRICT);
            $conexao = new mysqli("127.0.0.1", "root", "", "db_app_tcc");
            $conexao->autocommit(true);
            $conexao->set_charset("utf8");
            return $conexao;
        } catch (Exception $e) {
            http_response_code(500);
            die("Erro interno no banco de dados.");
        }
    }
?>
