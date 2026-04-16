<?php
session_start();

// ── Conexão com o banco de dados ──────────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'ameicosmeticos');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['erro' => 'Erro ao conectar ao banco: ' . $e->getMessage()]));
}

// ── Criar tabelas se não existirem ────────────────────────────────────────────
$pdo->exec("
    CREATE TABLE IF NOT EXISTS leads (
        id              INT AUTO_INCREMENT PRIMARY KEY,
        patrocinador    VARCHAR(50)  DEFAULT 'oseiaswgi',
        nome_patrocinador VARCHAR(100) DEFAULT 'OSEIAS MILTON NERY DE FREITAS',
        tipo_pessoa     ENUM('fisica','juridica') DEFAULT 'fisica',
        nome            VARCHAR(150) NOT NULL,
        sexo            VARCHAR(20),
        cpf             VARCHAR(20),
        rg              VARCHAR(30),
        data_nascimento VARCHAR(20),
        cnpj            VARCHAR(25),
        razao_social    VARCHAR(150),
        cep             VARCHAR(10),
        logradouro      VARCHAR(200),
        numero          VARCHAR(20),
        complemento     VARCHAR(100),
        bairro          VARCHAR(100),
        cidade          VARCHAR(100),
        uf              VARCHAR(5),
        email           VARCHAR(150) NOT NULL,
        celular         VARCHAR(20)  NOT NULL,
        telefone        VARCHAR(20),
        login           VARCHAR(80)  NOT NULL,
        senha_hash      VARCHAR(255) NOT NULL,
        status          ENUM('novo','contato','fechado','perdido') DEFAULT 'novo',
        observacao      TEXT,
        criado_em       DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS admins (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        usuario    VARCHAR(50)  NOT NULL UNIQUE,
        senha_hash VARCHAR(255) NOT NULL,
        nome       VARCHAR(100),
        criado_em  DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// ── Criar admin padrão se não existir ─────────────────────────────────────────
$stmt = $pdo->query("SELECT COUNT(*) FROM admins WHERE usuario = 'admin'");
if ((int)$stmt->fetchColumn() === 0) {
    $hash = password_hash('OseiasMilton123', PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO admins (usuario, senha_hash, nome) VALUES ('admin', ?, 'Administrador')")
        ->execute([$hash]);
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function requerLogin() {
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

function h($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}
