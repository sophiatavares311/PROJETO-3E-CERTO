<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduConnect - Conectando Saberes</title>
    <link rel="preconnect" href="https://googleapis.com"><link rel="preconnect" href="https://gstatic.com" crossorigin><link href="https://googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com">    // Sistema de Alertas Dinâmicos (Login/Logout) -->

    // Função para criar o balão de alerta na tela via JavaScript puro
    function mostrarAviso(mensagem, tipo) {
        // Cria o elemento na tela
        const alerta = document.createElement('div');
        alerta.className = 'alerta alerta-sucesso';
        alerta.style.position = 'fixed';
        alerta.style.top = '20px';
        alerta.style.right = '20px';
        alerta.style.zIndex = '9999';
        alerta.style.boxShadow = '0 10px 25px rgba(0,0,0,0.5)';
        alerta.style.animation = 'fadeIn 0.3s ease-in-out';
        
        // Aplica a cor com base no tipo de aviso
        if (tipo === 'logout') {
            alerta.className = 'alerta alerta-erro';
            alerta.style.background = 'rgba(239, 68, 68, 0.2)';
            alerta.style.borderColor = '#ef4444';
            alerta.style.color = '#fecaca';
        } else {
            alerta.style.background = 'rgba(45, 212, 191, 0.2)';
            alerta.style.borderColor = 'var(--neon-verde)';
            alerta.style.color = '#ccfbf1';
        }
        
        alerta.innerHTML = `<strong>Notificação:</strong> ${mensagem}`;
        document.body.appendChild(alerta);
        
        // Some automaticamente após 4 segundos
        setTimeout(() => {
            alerta.style.opacity = '0';
            alerta.style.transition = 'opacity 0.4s ease';
            setTimeout(() => alerta.remove(), 400);
        }, 4000);
    }

    // O PHP avisa o JavaScript se houve uma ação recente de login ou logout
    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.get('status') === 'logado') {
            mostrarAviso('Você entrou na conta administrativa com sucesso!', 'login');
            // Limpa o link para o aviso não reaparecer se der F5
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        
        if (urlParams.get('status') === 'deslogado') {
            mostrarAviso('Sessão encerrada. Você saiu da conta com segurança.', 'logout');
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
 </script>
    <link rel="stylesheet" href="estilo.css">
    <style>
        /* ---- Ajustes Finos de Responsividade para Celular ---- */
        @media (max-width: 768px) {
            .glass-header { padding: 15px 0; }
            .header-content { flex-direction: column; text-align: center; gap: 15px; }
            .action-nav { flex-wrap: wrap; justify-content: center; gap: 4px; padding: 6px; }
            .nav-item { padding: 8px 14px; font-size: 13px; }
            
            .portal-layout { grid-template-columns: 1fr; gap: 40px; padding: 30px 0; }
            
            .hero-block { text-align: center; }
            .hero-block h2 { font-size: 32px; line-height: 1.2; letter-spacing: -1px; }
            .hero-block p { font-size: 14px; margin: 0 auto 25px auto; }
            .btn-glow { width: 100%; justify-content: center; padding: 14px 24px; }
            
            .dashboard-preview { flex-direction: column; gap: 15px; text-align: center; margin-top: 35px; padding-top: 25px; }
            .stat-box { background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px; border: 1px solid var(--borda-vidro); }
            
            .section-title { text-align: center; margin-bottom: 15px; }
            .track-card { padding: 18px; gap: 15px; }
            .track-icon { width: 44px; height: 44px; font-size: 18px; }
            .track-info h4 { font-size: 16px; }
            .track-info p { font-size: 12.5px; }
        }
    </style>
</head>
<body class="dark-portal">
    <div class="glow-bg"><div class="ball cyan"></div><div class="ball purple"></div></div>
    <header class="glass-header">
        <div class="container header-content">
            <a href="home.php" class="brand-logo">Edu<span>.</span>connect</a>
            <nav class="action-nav">
                <a href="home.php" class="nav-item active"><i class="ph ph-squares-four"></i> Início</a>
                <a href="grade-mentorias.php" class="nav-item"><i class="ph ph-calendar"></i> Mural de Encontros</a>
                <a href="alunos.php" class="nav-item"><i class="ph ph-user-list"></i> Restrito Alunos</a>
                <a href="mentorias.php" class="nav-item"><i class="ph ph-lightning"></i> Restrito Mentorias</a>
                <?php if (isset($_SESSION['logado'])): ?>
                    <a href="logout.php" class="nav-item" style="color: #ef4444;"><i class="ph ph-sign-out"></i> Sair</a>
                <?php else: ?>
                    <a href="login.php" class="nav-item" style="color: var(--neon-verde); font-weight: 700;"><i class="ph ph-sign-in"></i> Entrar</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container portal-layout">
        <section class="hero-block">
            <div class="status-tag"><span class="pulse-dot"></span> Teste Operacional MVP</div>
            <h2>Conectando quem quer <span>aprender</span> com quem quer <span>ensinar</span>.</h2>
            <p>Plataforma inteligente de reforço escolar focada em transformar o desempenho de estudantes da rede pública através do voluntariado.</p>
            <div class="cta-group">
                <a href="grade-mentorias.php" class="btn-glow">Ver Mural de Encontros <i class="ph ph-arrow-up-right"></i></a>
            </div>
            <div class="dashboard-preview">
                <div class="stat-box"><span class="num">100%</span><span class="label">Gratuito</span></div>
                <div class="stat-box"><span class="num">Rede</span><span class="label">Pública</span></div>
                <div class="stat-box"><span class="num">Escopo</span><span class="label">MVP 1.0</span></div>
            </div>
        </section>
        <section class="tracks-block">
            <h3 class="section-title">Frentes de Aprendizado</h3>
            <div class="track-list">
                <div class="track-card">
                    <div class="track-icon math"><i class="ph ph-function"></i></div>
                    <div class="track-info"><h4>Lógica & Exatas</h4><p>Desmistificando equações, frações e geometria básica sem decoreba.</p></div>
                </div>
                <div class="track-card">
                    <div class="track-icon lang"><i class="ph ph-textbox"></i></div>
                    <div class="track-info"><h4>Escrita & Crítica</h4><p>Estruturação de redações nota mil e técnicas avançadas de interpretação.</p></div>
                </div>
                <div class="track-card">
                    <div class="track-icon science"><i class="ph ph-atom"></i></div>
                    <div class="track-info"><h4>Ciências da Natureza</h4><p>O universo da física, química e biologia aplicados de forma visual.</p></div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
