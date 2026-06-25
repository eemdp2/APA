<?php
// Configurações de acesso ao banco de dados MySQL na HostGator
$host = "localhost";
$usuario = "leo90192_apa";
$senha = "#Senh@2024";
$banco = "leo90192_apa";

// Criar a conexão utilizando a extensão MySQLi do PHP
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verificar se ocorreu algum erro na tentativa de conexão
if ($conn->connect_error) {
    die("Erro crítico: Não foi possível conectar ao banco de dados. Motivo: " . $conn->connect_error);
}

// Define o charset para utf8mb4 para garantir que acentos (á, é, í), 
// cedilhas (ç) e caracteres especiais nos nomes dos alunos não fiquem corrompidos.
$conn->set_charset("utf8mb4");
?>