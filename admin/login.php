<?php
require_once 'config.php';

// Já logado → vai direto pro painel
if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha   = $_POST['senha'] ?? '';

    if ($usuario && $senha) {
        $stmt = $pdo->prepare("SELECT id, senha_hash, nome FROM admins WHERE usuario = ? LIMIT 1");
        $stmt->execute([$usuario]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($senha, $admin['senha_hash'])) {
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_nome'] = $admin['nome'];
            session_regenerate_id(true);
            header('Location: index.php');
            exit;
        }
    }
    $erro = 'Usuário ou senha incorretos.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin — Amei Cosméticos</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .login-card {
        background: #fff;
        border-radius: 16px;
        padding: 48px 44px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 25px 60px rgba(0,0,0,.35);
    }
    .logo-wrap { text-align: center; margin-bottom: 32px; }
    .logo-wrap img { max-width: 160px; margin-bottom: 12px; }
    .badge {
        display: inline-block;
        background: #fff4ee;
        color: #ff6c00;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 20px;
        border: 1px solid #ffd4b5;
    }
    h1 { font-size: 22px; font-weight: 700; color: #1a1a2e; text-align: center; margin-bottom: 6px; }
    .subtitulo { text-align: center; font-size: 13px; color: #888; margin-bottom: 28px; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 6px; }
    .input-wrap { position: relative; }
    .input-wrap .icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #bbb; font-size: 16px; }
    .form-group input {
        width: 100%;
        padding: 12px 14px 12px 40px;
        border: 1.5px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        color: #333;
        transition: border-color .2s, box-shadow .2s;
        background: #fafafa;
    }
    .form-group input:focus {
        outline: none;
        border-color: #ff8345;
        box-shadow: 0 0 0 3px rgba(255,131,69,.15);
        background: #fff;
    }
    .form-group input::placeholder { color: #bbb; }
    .toggle-senha {
        position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
        cursor: pointer; color: #bbb; font-size: 15px; user-select: none;
    }
    .toggle-senha:hover { color: #ff8345; }
    .lembrar { display: flex; align-items: center; gap: 8px; margin-bottom: 24px; }
    .lembrar input[type="checkbox"] { accent-color: #ff8345; width: 15px; height: 15px; }
    .lembrar label { font-size: 13px; color: #666; cursor: pointer; }
    .btn-entrar {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #ff8345, #ff6c00);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        letter-spacing: 0.5px;
        transition: opacity .2s, transform .15s;
        box-shadow: 0 4px 14px rgba(255,108,0,.35);
    }
    .btn-entrar:hover { opacity: .92; transform: translateY(-1px); }
    .msg-erro {
        background: #fdecea;
        color: #c0392b;
        border: 1px solid #f5c6cb;
        border-radius: 7px;
        padding: 11px 14px;
        font-size: 13px;
        margin-bottom: 18px;
    }
    .rodape-login { text-align: center; margin-top: 28px; font-size: 12px; color: #bbb; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="logo-wrap">
        <img src="../uploads/1/2/9/1/12910257/logo-amei-2025-png_1.png" alt="Amei Cosméticos"/>
        <br/>
        <span class="badge">🔒 Área Administrativa</span>
    </div>

    <h1>Bem-vindo de volta!</h1>
    <p class="subtitulo">Acesse o painel de controle de leads</p>

    <?php if ($erro): ?>
        <div class="msg-erro"><?= h($erro) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="usuario">Usuário</label>
            <div class="input-wrap">
                <span class="icon">👤</span>
                <input type="text" id="usuario" name="usuario"
                       placeholder="Digite seu usuário"
                       value="<?= h($_POST['usuario'] ?? '') ?>"
                       autocomplete="username" required/>
            </div>
        </div>
        <div class="form-group">
            <label for="senha">Senha</label>
            <div class="input-wrap">
                <span class="icon">🔒</span>
                <input type="password" id="senha" name="senha"
                       placeholder="Digite sua senha"
                       autocomplete="current-password" required/>
                <span class="toggle-senha" onclick="toggleSenha()">👁</span>
            </div>
        </div>
        <div class="lembrar">
            <input type="checkbox" id="lembrar" name="lembrar"/>
            <label for="lembrar">Manter conectado</label>
        </div>
        <button type="submit" class="btn-entrar">Entrar no Painel</button>
    </form>

    <div class="rodape-login">Amei Cosméticos © <?= date('Y') ?> — Painel Administrativo</div>
</div>
<script>
function toggleSenha() {
    var inp = document.getElementById('senha');
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
