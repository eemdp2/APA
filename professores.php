<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $componente = $_POST['componente'];
    
    $stmt = $conn->prepare("INSERT INTO profesores (id, nome, componente) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE nome=?, componente=?");
    $stmt->bind_param("issss", $id, $nome, $componente, $nome, $componente);
    $stmt->execute();
    header("Location: professores.php");
    exit();
}

$professores = $conn->query("SELECT * FROM professores ORDER BY nome ASC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Professores - Monitoramento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">📊 Monitoramento APA 2026</a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Painel</a>
                <a class="nav-link active" href="professores.php">Professores</a>
                <a class="nav-link" href="estudantes.php">Estudantes</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h4>Novo Professor</h4>
                    <form method="POST" action="professores.php">
                        <div class="mb-3"><label>ID / Matrícula</label><input type="number" name="id" class="form-control" required></div>
                        <div class="mb-3"><label>Nome Completo</label><input type="text" name="nome" class="form-control" required></div>
                        <div class="mb-3"><label>Componente Curricular</label><select name="componente" class="form-select"><option>Língua Portuguesa</option><option>Matemática</option></select></div>
                        <button type="submit" class="btn btn-primary w-100">Salvar Professor</button>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card p-3 shadow-sm">
                    <h4>Professores Cadastrados</h4>
                    <table class="table table-striped">
                        <thead><tr><th>ID Matrícula</th><th>Nome</th><th>Componente</th></tr></thead>
                        <tbody>
                            <?php while($p = $professores->fetch_assoc()): ?>
                                <tr><td><?php echo $p['id']; ?></td><td><?php echo $p['nome']; ?></td><td><?php echo $p['componente']; ?></td></tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>