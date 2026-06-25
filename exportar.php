<?php
// ==============================================================================
// SISTEMA DE MONITORIZAÇÃO E NÍVEIS APA 2026 - EEMCEDS / EEMDP II
// EXPORTAÇÃO DE DADOS EM FORMATO CSV (COMPATÍVEL COM EXCEL)
// ==============================================================================

// Inclui a conexão com o banco de dados
include('conexao.php');

// Captura o filtro de nível enviado pela URL (se houver)
$filtro_nivel = isset($_GET['nivel']) ? $_GET['nivel'] : '';

// Forçar cabeçalhos HTTP corretos para que o navegador entenda que é um texto/CSV e NÃO um executável
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="lista_estudantes_apa.csv"');
header('Cache-Control: max-age=0');

// Abre o fluxo de saída do PHP para escrita do arquivo
$output = fopen('php://output', 'w');

// Insere o BOM (Byte Order Mark) para que o Excel em português abra com os acentos e cedilhas corretos
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Cabeçalho das colunas da tabela no Excel
fputcsv($output, array('ID Aluno', 'Nome do Estudante', 'Turma', 'Turno', 'Nivel de Aprendizagem', 'Professor Responsavel'));

// Monta a consulta SQL idêntica ao do painel para respeitar o filtro selecionado
$query = "SELECT e.id, e.nome, e.turma, e.turno, e.nivel, p.nome as professor_nome 
          FROM estudantes e 
          LEFT JOIN professores p ON e.professor_id = p.id 
          WHERE 1=1";

if ($filtro_nivel != '') {
    $query .= " AND e.nivel = '" . $conn->real_escape_string($filtro_nivel) . "'";
}

$result = $conn->query($query);

if ($result) {
    while($row = $result->fetch_assoc()) {
        // Envia linha por linha para o arquivo CSV de download
        fputcsv($output, array(
            $row['id'],
            $row['nome'],
            $row['turma'],
            $row['turno'],
            $row['nivel'],
            $row['professor_nome'] ?? 'Não atribuído'
        ));
    }
}

fclose($output);
exit();
?>
