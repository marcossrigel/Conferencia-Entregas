<?php
$dbHost = getenv("MYSQLHOST");
$dbUsername = getenv("MYSQLUSER");
$dbPassword = getenv("MYSQLPASSWORD");
$dbName = getenv("MYSQLDATABASE");    
$dbPort = getenv("MYSQLPORT");

$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName, $dbPort);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}
?>
