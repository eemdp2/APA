<?php
include('conexao.php');

// Quantificação por níveis
$niveis_counts = ['Nível 01' => 0, 'Nível 02' => 0, 'Nível 03' => 0, 'Nível 04' => 0];
$res_quant = $conn->query("SELECT nivel, COUNT(*) as total FROM estudantes GROUP BY nivel");
while($row = $res_quant->fetch_assoc()) {
    if (isset($niveis_counts[$row['nivel']])) {
        $niveis_counts[$row['nivel']] = $row['total'];
    }
}

// Filtro
$filtro_nivel = isset($_GET['nivel']) ? $_GET['nivel'] : '';
$query = "SELECT e.*, p.nome as professor_nome FROM estudantes e LEFT JOIN professores p ON e.professor_id = p.id WHERE 1=1";
if($filtro_nivel != '') {
    $query .= " AND e.nivel = '" . $conn->real_escape_string($filtro_nivel) . "'";
}
$estudantes_filtrados = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel de Monitoramento - EEMDP2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">📊 Monitoramento APA 2026</a>
            <div class="navbar-nav">
                <a class="nav-link active" href="index.php">Painel</a>
                <a class="nav-link" href="professores.php">Professores</a>
                <a class="nav-link" href="estudantes.php">Estudantes</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h3>📊 Quantitativo Geral por Nível (EEMDP II)</h3>
        <div class="row mb-4">
            <?php foreach($niveis_counts as $niv => $total): ?>
            <div class="col-md-3">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-header"><?php echo $niv; ?></div>
                    <div class="card-body"><h4 class="card-title"><?php echo $total; ?> Alunos</h4></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="card p-3 mb-4 shadow-sm">
            <form method="GET" action="index.php" class="row g-3">
                <div class="col-md-6">
                    <select name="nivel" class="form-select">
                        <option value="">-- Todos os Níveis --</option>
                        <option value="Nível 01" <?php if($filtro_nivel=='Nível 01') echo 'selected'; ?>>Nível 01</option>
                        <option value="Nível 02" <?php if($filtro_nivel=='Nível 02') echo 'selected'; ?>>Nível 02</option>
                        <option value="Nível 03" <?php if($filtro_nivel=='Nível 03') echo 'selected'; ?>>Nível 03</option>
                        <option value="Nível 04" <?php if($filtro_nivel=='Nível 04') echo 'selected'; ?>>Nível 04</option>
                    </select>
                </div>
                <div class="col-md-3"><button type="submit" class="btn btn-secondary w-100">Filtrar Lista</button></div>
                <div class="col-md-3"><a href="exportar.php?nivel=<?php echo urlencode($filtro_nivel); ?>" class="btn btn-success w-100">📥 Baixar Lista (CSV)</a></div>
            </form>
        </div>

        <table class="table table-striped bg-white shadow-sm rounded">
            <thead><tr><th>ID</th><th>Nome</th><th>Turma</th><th>Turno</th><th>Nível</th><th>Professor Responsável</th></tr></thead>
            <tbody>
                <?php while($e = $estudantes_filtrados->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $e['id']; ?></td>
                        <td><?php echo $e['nome']; ?></td>
                        <td><?php echo $e['turma']; ?></td>
                        <td><?php echo $e['turno']; ?></td>
                        <td><span class="badge bg-primary"><?php echo $e['nivel']; ?></span></td>
                        <td><?php echo $e['professor_nome']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
