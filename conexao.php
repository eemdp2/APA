<?php
// ==============================================================================
// FICHEIRO DE CONEXÃO COM O BANCO DE DADOS (MySQLi)
// Escola Estadual Militar Dom Pedro II - Tenente Coronel Evandro Dias de Souza
// ==============================================================================

// Ativar a exibição de erros do PHP (Essencial para diagnosticar erros 500 em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configurações de Acesso ao Banco de Dados da HostGator
$host    = "localhost";        // Mantém-se "localhost" porque os ficheiros correm dentro do servidor
$usuario = "leo90192_apa";     // O seu utilizador do cPanel
$senha   = "#Senh@2024";       // A palavra-passe definida
$banco   = "leo90192_apa";     // O nome exato da base de dados

// Efetuar a ligação utilizando a extensão orientada a objetos MySQLi
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verificar se ocorreu alguma falha na ligação
if ($conn->connect_error) {
    // Se falhar, exibe o erro e interrompe a execução para evitar que o site dê uma tela em branco genérica
    die("Erro Crítico: Não foi possível estabelecer ligação à base de dados. Detalhe: " . $conn->connect_error);
}

// Define o charset de comunicação para utf8mb4.
// Isto é CRUCIAL para que acentuações (á, ê, õ), cedilhas (ç) e caracteres complexos
// nos nomes dos alunos não fiquem corrompidos ou gerem erros de codificação no sistema.
$conn->set_charset("utf8mb4");

// Opcional: Configura o fuso horário para que os registos de tempo coincidam com o servidor local
date_default_timezone_set('America/Cuiaba'); 

?>
