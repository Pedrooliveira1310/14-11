<?php
$host = "localhost";
$usuario = "root";
$senha = "Senai@118";
$banco = "drpeanut";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error){
    die("Erro de conexão: " . $conn->connect_error);
}
?>
