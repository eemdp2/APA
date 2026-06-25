<?php
include('conexao.php');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=lista_estudantes_niveis.csv');

$output = fopen('php://output');
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // Corrige acentuação no Excel
fputcsv($output, array('ID Aluno', 'Nome', 'Turma', 'Turno', 'Nivel', 'Professor Responsavel'));

$filtro_nivel = isset($_GET['nivel']) ? $_GET['nivel'] : '';
$query = "SELECT e.id, e.nome, e.turma, e.turno, e.nivel, p.nome as professor_nome FROM estudantes e LEFT JOIN professores p ON e.professor_id = p.id WHERE 1=1";
if($filtro_nivel != '') {
    $query .= " AND e.nivel = '" . $conn->real_escape_string($filtro_nivel) . "'";
}

$result = $conn->query($query);
while($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
?>