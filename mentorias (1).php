<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) { header("Location: login.php"); exit; }
include 'conexao.php';
$msg = ''; $tipo_msg = '';

if (isset($_POST['registrar_mentoria'])) {
    $aluno_id = $_POST['aluno_id']; $mentor = trim($_POST['mentor']); $data_m = $_POST['data_mentoria']; $modalidade = $_POST['modalidade']; $link_local = trim($_POST['link_local']); $resumo = trim($_POST['resumo']);
    if (!empty($aluno_id) && !empty($mentor) && !empty($data_m) && !empty($modalidade) && !empty($resumo)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO mentorias (aluno_id, mentor, data_mentoria, modalidade, link_local, resumo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$aluno_id, $mentor, $data_m, $modalidade, $link_local, $resumo]);
            $msg = "Mentoria lançada com sucesso!"; $tipo_msg = "sucesso";
        } catch (Exception $e) { $msg = "Erro ao salvar atendimento."; $tipo_msg = "erro"; }
    }
}

if (isset($_GET['excluir'])) {
    $stmt = $pdo->prepare("DELETE FROM mentorias WHERE id = ?");
    $stmt->execute([$_GET['excluir']]);
    header("Location: mentorias.php"); exit;
}

$alunos = $pdo->query("SELECT id, nome FROM alunos ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
$mentorias = $pdo->query("SELECT m.*, a.nome as aluno_nome FROM mentorias m JOIN alunos a ON m.aluno_id = a.id ORDER BY m.data_mentoria DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>EduConnect - Lançamento de Mentorias</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://googleapis.com"><link rel="preconnect" href="https://gstatic.com" crossorigin><link href="https://googleapis.com/css2?family=Space+Grotesk:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com"></script>
    <link rel="stylesheet" href="estilo.css">
    <style>
        @media (max-width: 600px) {
            .header-content { flex-direction: column; text-align: center; }
            .action-nav { flex-wrap: wrap; justify-content: center; margin-top: 10px; }
            .nav-item { padding: 8px 12px; font-size: 13px; }
        }
    </style>
</head>
<body class="dark-portal">
    <header class="glass-header">
        <div class="container header-content">
            <a href="home.php" class="brand-logo">Edu<span>.</span>connect</a>
            <nav class="action-nav">
                <a href="home.php" class="nav-item"><i class="ph ph-squares-four"></i> Início</a>
                <a href="grade-mentorias.php" class="nav-item"><i class="ph ph-calendar"></i> Mural de Encontros</a>
                <a href="alunos.php" class="nav-item"><i class="ph ph-user-list"></i> Restrito Alunos</a>
                <a href="mentorias.php" class="nav-item active"><i class="ph ph-lightning"></i> Restrito Mentorias</a>
                <a href="logout.php" class="nav-item" style="color: #ef4444;"><i class="ph ph-sign-out"></i> Sair</a>
            </nav>
        </div>
    </header>
    <div class="container" style="padding-top: 30px;">
        <?php if ($msg): ?><div class="alerta alerta-<?= $tipo_msg ?>" id="notificacao"><?= $msg ?></div><?php endif; ?>

        <div class="track-card" style="margin-bottom: 30px; display: block;">
            <h3 style="font-family: var(--fonte-titulo); margin-bottom: 20px; color: #fff;">Registrar Encontro (Painel Master)</h3>
            <form method="POST" action="mentorias.php">
                <div class="grupo-form">
                    <label>Aluno</label>
                    <select name="aluno_id" required>
                        <option value="">Selecione o aluno...</option>
                        <?php foreach($alunos as $a): ?><option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nome']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="grupo-form"><label>Nome do Mentor</label><input type="text" name="mentor" required></div>
                <div class="grupo-form"><label>Data</label><input type="date" name="data_mentoria" required></div>
                <div class="grupo-form">
                    <label>Modalidade</label>
                    <select name="modalidade" required>
                        <option value="Presencial">Presencial</option>
                        <option value="Online">Online</option>
                    </select>
                </div>
                <div class="grupo-form"><label>Local ou Link da Sala</label><input type="text" name="link_local" placeholder="Ex: Sala 4 ou Link do Zoom" required></div>
                <div class="grupo-form"><label>Resumo do Encontro</label><textarea name="resumo" rows="3" required></textarea></div>
                <button type="submit" name="registrar_mentoria" class="btn-glow" style="padding: 12px 24px; border-radius:6px;">Gravar Sessão</button>
            </form>
        </div>

        <div class="track-card" style="display: block;">
            <h3 style="font-family: var(--fonte-titulo); margin-bottom: 20px; color: #fff;">Histórico de Encontros (Modo Edição)</h3>
            <div style="overflow-x: auto;">
                <table class="tabela-custom">
                    <thead><tr><th>Aluno</th><th>Mentor</th><th>Data</th><th>Modalidade</th><th>Resumo</th><th>Ações</th></tr></thead>
                    <tbody>
                        <?php foreach ($mentorias as $m): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($m['aluno_nome']) ?></strong></td>
                            <td><?= htmlspecialchars($m['mentor']) ?></td>
                            <td><?= date('d/m/Y', strtotime($m['data_mentoria'])) ?></td>
                            <td><?= $m['modalidade'] ?> (<?= htmlspecialchars($m['link_local']) ?>)</td>
                            <td><?= htmlspecialchars($m['resumo']) ?></td>
                            <td><a href="mentorias.php?excluir=<?= $m['id'] ?>" style="color: #ef4444; text-decoration: none;" onclick="return confirm('Excluir?')">Excluir</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        const alerta = document.getElementById('notificacao');
        if (alerta) { setTimeout(() => { alerta.style.display = 'none'; }, 4000); }
    </script>
</body>
</html>
