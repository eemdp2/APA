<?php
// ==============================================================================
// SISTEMA DE MONITORIZAÇÃO E NÍVEIS APA 2026 - EEMCEDS / EEMDP II
// PAINEL PRINCIPAL: QUANTIFICADORES E LISTAGEM DE ESTUDANTES
// ==============================================================================

// Inclui o arquivo completo de conexão com o banco de dados da HostGator
include('conexao.php');

// 1. QUANTIFICAÇÃO: Inicializa os contadores para cada nível de aprendizagem
$niveis_counts = [
    'Nível 01' => 0, 
    'Nível 02' => 0, 
    'Nível 03' => 0, 
    'Nível 04' => 0
];

// Consulta o banco para somar a quantidade real de estudantes em cada nível
$res_quant = $conn->query("SELECT nivel, COUNT(*) as total FROM estudantes GROUP BY nivel");
if ($res_quant) {
    while($row = $res_quant->fetch_assoc()) {
        if (isset($niveis_counts[$row['nivel']])) {
            $niveis_counts[$row['nivel']] = $row['total'];
        }
    }
}

// 2. FILTRAGEM: Captura o nível selecionado pelo usuário no formulário (se houver)
$filtro_nivel = isset($_GET['nivel']) ? $_GET['nivel'] : '';

// Monta a consulta SQL trazendo os dados dos alunos e juntando com o nome do professor responsável
$query = "SELECT e.*, p.nome as professor_nome 
          FROM estudantes e 
          LEFT JOIN professores p ON e.professor_id = p.id 
          WHERE 1=1";

if ($filtro_nivel != '') {
    $query .= " AND e.nivel = '" . $conn->real_escape_string($filtro_nivel) . "'";
}

// Executa a listagem dos estudantes filtrados/gerais
$estudantes_filtrados = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Monitoramento - EEMDP2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">静态 📊 Monitoramento APA 2026</a>
            <div class="navbar-nav">
                <a class="nav-link active" href="index.php">Painel Geral</a>
                <a class="nav-link" href="professores.php">Professores</a>
                <a class="nav-link" href="estudantes.php">Estudantes</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h3 class="mb-4">📊 Quantitativo Geral por Nível (EEMDP II)</h3>
        
        <div class="row mb-4">
            <?php foreach($niveis_counts as $niv => $total): ?>
            <div class="col-md-3">
                <div class="card text-white bg-dark mb-3 shadow-sm">
                    <div class="card-header fw-bold"><?php echo $niv; ?></div>
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $total; ?> Alunos</h4>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="card p-3 mb-4 shadow-sm bg-white">
            <form method="GET" action="index.php" class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Filtrar Estudantes por Nível:</label>
                    <select name="nivel" class="form-select">
                        <option value="">-- Todos os Níveis --</option>
                        <option value="Nível 01" <?php if($filtro_nivel == 'Nível 01') echo 'selected'; ?>>Nível 01</option>
                        <option value="Nível 02" <?php if($filtro_nivel == 'Nível 02') echo 'selected'; ?>>Nível 02</option>
                        <option value="Nível 03" <?php if($filtro_nivel == 'Nível 03') echo 'selected'; ?>>Nível 03</option>
                        <option value="Nível 04" <?php if($filtro_nivel == 'Nível 04') echo 'selected'; ?>>Nível 04</option>
                    </select>
                </div>
                <div class="col-md-3 pt-4">
                    <button type="submit" class="btn btn-secondary w-100">Aplicar Filtro</button>
                </div>
                <div class="col-md-3 pt-4">
                    <a href="exportar.php?nivel=<?php echo urlencode($filtro_nivel); ?>" class="btn btn-success w-100">📥 Emitir Lista (CSV)</a>
                </div>
            </form>
        </div>

        <div class="card shadow-sm p-3 bg-white">
            <h4 class="mb-3">Lista de Estudantes</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID / Matrícula</th>
                            <th>Nome Completo</th>
                            <th>Turma</th>
                            <th>Turno</th>
                            <th>Nível</th>
                            <th>Professor Responsável</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($estudantes_filtrados && $estudantes_filtrados->num_rows > 0): ?>
                            <?php while($e = $estudantes_filtrados->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $e['id']; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($e['nome'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($e['turma'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo $e['turno']; ?></td>
                                    <td>
                                        <span class="badge bg-primary px-3 py-2">
                                            <?php echo $e['nivel']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($e['professor_nome'] ?? 'Não atribuído', ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Nenhum estudante cadastrado ou encontrado para este filtro.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
