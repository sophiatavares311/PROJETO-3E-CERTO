<?php
session_start();
$erro = '';

define('USER_ADMIN', 'admin');
define('PASS_ADMIN', 'admin123');

if (isset($_POST['logar'])) {
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    if ($usuario === USER_ADMIN && $senha === PASS_ADMIN) {
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $usuario;
     header("Location: home.php?status=logado");
        exit;
    } else {
        $erro = "Usuário ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduConnect - Login Administrativo</title>
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
    <style>
        .login-container { display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .login-card { width: 100%; max-width: 400px; padding: 40px 30px; box-sizing: border-box; }
        .login-logo { text-align: center; font-family: var(--fonte-titulo); font-size: 32px; font-weight: 700; margin-bottom: 30px; color: #fff; }
        .login-logo span { color: var(--neon-verde); }
    </style>
</head>
<body class="dark-portal">
    <div class="glow-bg"><div class="ball cyan"></div></div>
    <div class="login-container">
        <div class="track-card login-card" style="display: block;">
            <div class="login-logo">Edu<span>.</span>connect</div>
            <?php if ($erro): ?><div class="alerta alerta-erro"><?= $erro ?></div><?php endif; ?>
            <form method="POST" action="login.php">
                <div class="grupo-form">
                    <label>Usuário Administrador</label>
                    <input type="text" name="usuario" placeholder="Ex: admin" required>
                </div>
                <div class="grupo-form">
                    <label>Senha de Acesso</label>
                    <input type="password" name="senha" placeholder="••••••••" required>
                </div>
                <button type="submit" name="logar" class="btn-glow" style="width: 100%; justify-content: center; margin-top: 10px; border-radius: 6px;">Entrar no Sistema</button>
            </form>
        </div>
    </div>
</body>
</html>
