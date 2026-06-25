<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $turma = $_POST['turma'];
    $turno = $_POST['turno'];
    $nivel = $_POST['nivel'];
    $professor_id = $_POST['professor_id'];
    
    $stmt = $conn->prepare("INSERT INTO estudantes (id, nome, turma, turno, nivel, professor_id) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE nome=?, turma=?, turno=?, nivel=?, professor_id=?");
    $stmt->bind_param("isssssssssi", $id, $nome, $turma, $turno, $nivel, $professor_id, $nome, $turma, $turno, $nivel, $professor_id);
    $stmt->execute();
    header("Location: estudantes.php");
    exit();
}

$professores = $conn->query("SELECT * FROM professores ORDER BY nome ASC");
$estudantes = $conn->query("SELECT e.*, p.nome as professor_nome FROM estudantes e LEFT JOIN professores p ON e.professor_id = p.id ORDER BY e.nome ASC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Estudantes - Monitoramento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">📊 Monitoramento APA 2026</a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Painel</a>
                <a class="nav-link" href="professores.php">Professores</a>
                <a class="nav-link active" href="estudantes.php">Estudantes</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card p-3 shadow-sm mb-4">
                    <h4>Novo Estudante / Nível</h4>
                    <form method="POST" action="estudantes.php">
                        <div class="mb-2"><label>ID Aluno</label><input type="number" name="id" class="form-control" required></div>
                        <div class="mb-2"><label>Nome Completo</label><input type="text" name="nome" class="form-control" required></div>
                        <div class="mb-2"><label>Turma</label><input type="text" name="turma" class="form-control" placeholder="Ex: 6º Ano A" required></div>
                        <div class="mb-2"><label>Turno</label><select name="turno" class="form-select"><option>Matutino</option><option>Vespertino</option></select></div>
                        <div class="mb-2"><label>Nível de Aprendizagem</label><select name="nivel" class="form-select"><option>Nível 01</option><option>Nível 02</option><option>Nível 03</option><option>Nível 04</option></select></div>
                        <div class="mb-3"><label>Professor Responsável</label>
                            <select name="professor_id" class="form-select" required>
                                <option value="">-- Selecione --</option>
                                <?php while($prof = $professores->fetch_assoc()): ?>
                                    <option value="<?php echo $prof['id']; ?>"><?php echo $prof['nome']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Cadastrar e Classificar</button>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card p-3 shadow-sm">
                    <h4>Alunos Classificados</h4>
                    <table class="table table-striped table-sm">
                        <thead><tr><th>ID</th><th>Nome</th><th>Turma</th><th>Nível</th><th>Professor</th></tr></thead>
                        <tbody>
                            <?php while($e = $estudantes->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $e['id']; ?></td>
                                    <td><?php echo $e['nome']; ?></td>
                                    <td><?php echo $e['turma']; ?></td>
                                    <td><span class="badge bg-info text-dark"><?php echo $e['nivel']; ?></span></td>
                                    <td><?php echo $e['professor_nome']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>