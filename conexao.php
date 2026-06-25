<?php
// ==============================================================================
// SISTEMA DE MONITORIZAÇÃO E NÍVEIS APA 2026 - EEMCEDS / EEMDP II
// Ficheiro de Conexão com o Banco de Dados (MySQLi)
// ==============================================================================

// Forçar a exibição de erros do PHP (Ajuda a identificar e eliminar o Erro 500 na HostGator)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Credenciais do Banco de Dados fornecidas para a HostGator
$host    = "localhost";        // Mantém-se "localhost" pois os ficheiros vão correr dentro do servidor
$usuario = "leo90192_apa";     // Utilizador do banco de dados
$senha   = "#Senh@2024";       // Palavra-passe do banco de dados
$banco   = "leo90192_apa";     // Nome da base de dados

// Estabelecer ligação utilizando a extensão MySQLi
$conn = new mysqli($host, $usuario, $senha, $banco);

// Validar se a ligação foi bem-sucedida
if ($conn->connect_error) {
    // Interrompe a execução e mostra o erro exato em vez de uma tela branca genérica
    die("Erro Crítico de Ligação: Não foi possível conectar à base de dados. Detalhe: " . $conn->connect_error);
}

// Configurar o charset para utf8mb4. 
// Garante que nomes com acentos e cedilhas (ex: João, Conceição) sejam salvos corretamente.
$conn->set_charset("utf8mb4");

// Define o fuso horário padrão do sistema
date_default_timezone_set('America/Cuiaba'); 
?>
