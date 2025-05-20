<?php
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'conferencia_entregas';

$conexao = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conexao) {
    die("Erro ao conectar ao banco: " . mysqli_connect_error());
}
?>
