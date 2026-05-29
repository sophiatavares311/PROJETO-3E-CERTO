<?php
session_start();
include 'conexao.php';
// Busca todas as mentorias gravadas para exibição pública
$mentorias = $pdo->query("SELECT m.*, a.nome as aluno_nome FROM mentorias m JOIN alunos a ON m.aluno_id = a.id ORDER BY m.data_mentoria DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>EduConnect - Mural de Mentorias</title>
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
                <a href="grade-mentorias.php" class="nav-item active"><i class="ph ph-calendar"></i> Mural de Encontros</a>
                <a href="alunos.php" class="nav-item"><i class="ph ph-user-list"></i> Restrito Alunos</a>
                <a href="mentorias.php" class="nav-item"><i class="ph ph-lightning"></i> Restrito Mentorias</a>
                <?php if (isset($_SESSION['logado'])): ?><a href="logout.php" class="nav-item" style="color: #ef4444;"><i class="ph ph-sign-out"></i> Sair</a><?php endif; ?>
            </nav>
        </div>
    </header>
    <div class="container" style="padding-top: 40px;">
        <div class="track-card" style="display: block;">
            <h2 style="font-family: var(--fonte-titulo); margin-bottom: 10px; color: #fff;">Mural Público de Mentorias Realizadas</h2>
           
            <div style="overflow-x: auto;">
                <table class="tabela-custom">
                    <thead><tr><th>Estudante Beneficiado</th><th>Mentor Voluntário</th><th>Data do Encontro</th><th>Formato</th><th>Conteúdo Trabalhado</th></tr></thead>
                    <tbody>
                        <?php if (count($mentorias) == 0): ?>
                            <tr><td colspan="5" style="text-align: center; color: var(--texto-apagado);">Nenhum encontro público registrado ainda.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($mentorias as $m): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($m['aluno_nome']) ?></strong></td>
                            <td><?= htmlspecialchars($m['mentor']) ?></td>
                            <td><?= date('d/m/Y', strtotime($m['data_mentoria'])) ?></td>
                            <td><?= $m['modalidade'] ?> (<?= htmlspecialchars($m['link_local']) ?>)</td>
                            <td><?= htmlspecialchars($m['resumo']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
