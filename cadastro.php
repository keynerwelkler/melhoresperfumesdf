<?php
// ── Conexão com o banco ───────────────────────────────────────────────────────
$dbErro = '';

$_dbHost = 'localhost';
$_host   = strtolower(preg_replace('/:\d+$/', '', (string)($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '')));
$_addr   = $_SERVER['SERVER_ADDR'] ?? '';
$_isLocal = in_array($_host, ['localhost','127.0.0.1'], true)
          || in_array($_addr, ['127.0.0.1','::1'], true)
          || PHP_SAPI === 'cli';

if ($_isLocal) {
    $_dbName = 'ameicosmeticos';
    $_dbUser = 'root';
    $_dbPass = '';
} else {
    $_dbName = 'oseiasmilton_ameicosmeticos';
    $_dbUser = 'oseiasmilton_ameicosmeticos';
    $_dbPass = 'xMstkPqkzQNysNAbzQUh';
}

try {
    $pdo = new PDO(
        "mysql:host={$_dbHost};dbname={$_dbName};charset=utf8mb4",
        $_dbUser, $_dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES => false]
    );

    // Garante que a tabela existe (igual ao admin/config.php)
    $pdo->exec("CREATE TABLE IF NOT EXISTS leads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        patrocinador VARCHAR(50) DEFAULT 'oseiaswgi',
        nome_patrocinador VARCHAR(100) DEFAULT 'OSEIAS MILTON NERY DE FREITAS',
        tipo_pessoa ENUM('fisica','juridica') DEFAULT 'fisica',
        nome VARCHAR(150) NOT NULL, sexo VARCHAR(20),
        cpf VARCHAR(20), rg VARCHAR(30), data_nascimento VARCHAR(20),
        cnpj VARCHAR(25), razao_social VARCHAR(150),
        cep VARCHAR(10), logradouro VARCHAR(200), numero VARCHAR(20),
        complemento VARCHAR(100), bairro VARCHAR(100), cidade VARCHAR(100), uf VARCHAR(5),
        email VARCHAR(150) NOT NULL, celular VARCHAR(20) NOT NULL, telefone VARCHAR(20),
        login VARCHAR(80) NOT NULL, senha_hash VARCHAR(255) NOT NULL,
        status ENUM('novo','contato','fechado','perdido') DEFAULT 'novo',
        observacao TEXT, criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

} catch (PDOException $e) {
    $pdo = null;
    $dbErro = 'Erro de conexão com o banco de dados. Tente novamente em instantes.';
    // Para depuração — remova após resolver em produção:
    // $dbErro .= ' (' . $e->getMessage() . ')';
}

// ── Funções de validação ──────────────────────────────────────────────────────
function validarCPF(string $cpf): bool {
    $cpf = preg_replace('/\D/', '', $cpf);
    if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) return false;
    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) $d += (int)$cpf[$c] * ($t + 1 - $c);
        $d = ((10 * $d) % 11) % 10;
        if ((int)$cpf[$c] !== $d) return false;
    }
    return true;
}

function validarCelular(string $cel): bool {
    $cel = preg_replace('/\D/', '', $cel);
    return strlen($cel) === 11 && $cel[2] === '9';
}

// ── Processar formulário ──────────────────────────────────────────────────────
$d = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    $campos = ['nome','sexo','cpf','rg','data_nascimento','cnpj','razao_social',
               'cep','logradouro','numero','complemento','bairro','cidade','uf',
               'email','celular','login','senha','senha_confirm','tipo_pessoa'];
    $d = [];
    foreach ($campos as $c) $d[$c] = trim($_POST[$c] ?? '');

    // Validação básica
    $erros = [];
    if (!$d['nome'])         $erros[] = 'Informe o Nome Completo.';
    if (!$d['email'])        $erros[] = 'Informe o E-mail.';
    if (!$d['celular'])      $erros[] = 'Informe o Celular / WhatsApp.';
    elseif (!validarCelular($d['celular'])) $erros[] = 'Celular inválido. Informe um número de celular com DDD (ex: 61 9 8229-0919).';
    if (!$d['cpf'])          $erros[] = 'Informe o CPF.';
    elseif (!validarCPF($d['cpf'])) $erros[] = 'CPF inválido. Verifique o número informado.';
    if (!$d['login'])        $erros[] = 'Informe o Login desejado.';
    if (!$d['senha'])        $erros[] = 'Informe a Senha.';
    if (strlen($d['senha']) < 6) $erros[] = 'A senha deve ter mínimo 6 caracteres.';
    if ($d['senha'] !== $d['senha_confirm']) $erros[] = 'As senhas não conferem.';

    // Verificar login duplicado
    if (!$erros && $d['login']) {
        $chk = $pdo->prepare("SELECT COUNT(*) FROM leads WHERE login = ?");
        $chk->execute([$d['login']]);
        if ((int)$chk->fetchColumn() > 0) $erros[] = 'Este login já está em uso. Escolha outro.';
    }

    if ($erros) {
        $dbErro = implode('<br>', $erros);
    } else {
        $hash = password_hash($d['senha'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO leads
            (tipo_pessoa, nome, sexo, cpf, rg, data_nascimento,
             cnpj, razao_social, cep, logradouro, numero, complemento, bairro, cidade, uf,
             email, celular, login, senha_hash)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->execute([
            $d['tipo_pessoa'] ?: 'fisica',
            $d['nome'], $d['sexo'], $d['cpf'], $d['rg'], $d['data_nascimento'],
            $d['cnpj'], $d['razao_social'],
            $d['cep'], $d['logradouro'], $d['numero'], $d['complemento'],
            $d['bairro'], $d['cidade'], $d['uf'],
            $d['email'], $d['celular'],
            $d['login'], $hash
        ]);

        // ── Notificação por e-mail ────────────────────────────────────────────
        $enderecoCompleto = trim(implode(', ', array_filter([
            $d['logradouro'], $d['numero'], $d['complemento'],
            $d['bairro'], $d['cidade'], $d['uf'], $d['cep']
        ])));

        $corpoEmail = "Nova inscrição recebida no site Amei Cosméticos!\r\n";
        $corpoEmail .= str_repeat("=", 50) . "\r\n\r\n";
        $corpoEmail .= "DADOS PESSOAIS\r\n";
        $corpoEmail .= "Nome completo : " . $d['nome']            . "\r\n";
        $corpoEmail .= "Sexo          : " . $d['sexo']            . "\r\n";
        $corpoEmail .= "CPF           : " . $d['cpf']             . "\r\n";
        $corpoEmail .= "RG            : " . $d['rg']              . "\r\n";
        $corpoEmail .= "Nascimento    : " . $d['data_nascimento']  . "\r\n\r\n";
        $corpoEmail .= "CONTATO\r\n";
        $corpoEmail .= "E-mail        : " . $d['email']           . "\r\n";
        $corpoEmail .= "Celular/WPP   : " . $d['celular']         . "\r\n\r\n";
        $corpoEmail .= "ENDEREÇO\r\n";
        $corpoEmail .= $enderecoCompleto . "\r\n\r\n";
        $corpoEmail .= "CONTA\r\n";
        $corpoEmail .= "Login         : " . $d['login']           . "\r\n\r\n";
        $corpoEmail .= str_repeat("=", 50) . "\r\n";
        $corpoEmail .= "Acesse o painel: https://www.melhoresperfumesdf.com.br/admin/\r\n";

        $cabecalhos  = "From: noreply@melhoresperfumesdf.com.br\r\n";
        $cabecalhos .= "Reply-To: " . $d['email'] . "\r\n";
        $cabecalhos .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $cabecalhos .= "X-Mailer: PHP/" . phpversion();

        $assunto = '=?UTF-8?B?' . base64_encode('🔔 Novo Cadastro: ' . $d['nome']) . '?=';
        @mail('oseiascw@gmail.com',       $assunto, $corpoEmail, $cabecalhos);
        @mail('kew.welkler@hotmail.com',  $assunto, $corpoEmail, $cabecalhos);
        // ─────────────────────────────────────────────────────────────────────

        $primeiroNome = htmlspecialchars(explode(' ', $d['nome'])[0]);
        header('Location: obrigado.php?nome=' . urlencode($primeiroNome));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Cadastro Gratuito de Revendedor | Melhores Perfumes DF — Perfumes Árabes e Importados</title>
<meta name="description" content="Cadastre-se grátis e revenda perfumes árabes e importados em Brasília DF com lucro de até 150%. Sem consulta ao SPC e Serasa. Revendedor autorizado Amei Cosméticos."/>
<meta name="keywords" content="cadastro revendedor perfumes DF, ser revendedor perfumes Brasília, cadastro gratuito perfumes atacado, revenda perfumes sem SPC Brasília, trabalhar com perfumes importados DF, renda extra Brasília, cadastro Melhores Perfumes DF"/>
<meta name="robots" content="index, follow"/>
<meta name="author" content="Melhores Perfumes DF"/>
<link rel="canonical" href="https://www.melhoresperfumesdf.com.br/cadastro.php"/>

<!-- Open Graph -->
<meta property="og:type" content="website"/>
<meta property="og:url" content="https://www.melhoresperfumesdf.com.br/cadastro.php"/>
<meta property="og:title" content="Cadastro Gratuito de Revendedor | Melhores Perfumes DF"/>
<meta property="og:description" content="Cadastre-se grátis e revenda perfumes árabes e importados em Brasília DF com lucro de até 150%. Sem SPC e Serasa!"/>
<meta property="og:image" content="https://www.melhoresperfumesdf.com.br/uploads/1/2/9/1/12910257/background-images/334998943.png"/>
<meta property="og:locale" content="pt_BR"/>
<meta property="og:site_name" content="Melhores Perfumes DF"/>

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="Cadastro Gratuito | Melhores Perfumes DF"/>
<meta name="twitter:description" content="Revenda perfumes árabes e importados em Brasília com até 150% de lucro. Sem SPC e Serasa!"/>
<meta name="twitter:image" content="https://www.melhoresperfumesdf.com.br/uploads/1/2/9/1/12910257/background-images/334998943.png"/>

<!-- Schema.org JSON-LD -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "@id": "https://www.melhoresperfumesdf.com.br/cadastro.php#webpage",
  "url": "https://www.melhoresperfumesdf.com.br/cadastro.php",
  "name": "Cadastro Gratuito de Revendedor | Melhores Perfumes DF",
  "description": "Cadastre-se grátis e revenda perfumes árabes e importados em Brasília DF com lucro de até 150%.",
  "isPartOf": {"@id": "https://www.melhoresperfumesdf.com.br/#website"},
  "inLanguage": "pt-BR"
}
</script>

	<link id="wsite-base-style" rel="stylesheet" type="text/css" href="../cdn2.editmysite.com/css/sitesd186.css?buildTime=1234" />
<!-- ═══ MENU TESTE — remover este bloco para voltar ao original ═══ -->
<style>
.paris-header {
    background: rgba(10,10,10,0.96) !important;
    border-top: none !important;
    border-bottom: 1px solid rgba(255,131,69,0.25) !important;
    backdrop-filter: blur(12px) !important;
    -webkit-backdrop-filter: blur(12px) !important;
}
.paris-header .container { display: flex !important; align-items: center !important; padding: 0 32px !important; min-height: 68px !important; }
.paris-header .logo { display: flex !important; align-items: center !important; }
.desktop-nav { display: flex !important; align-items: center !important; }
.desktop-nav ul { float: none !important; display: flex !important; align-items: center !important; gap: 4px !important; margin: 0 !important; padding: 0 !important; }
.desktop-nav ul li { float: none !important; }
.desktop-nav ul li a {
    color: rgba(255,255,255,0.8) !important;
    font-family: 'Inter', 'Lato', sans-serif !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    letter-spacing: 1.2px !important;
    text-transform: uppercase !important;
    padding: 13px 22px !important;
    border-radius: 50px !important;
    background: transparent !important;
    transition: all .25s ease !important;
    border: 1px solid transparent !important;
}
.desktop-nav ul li a:hover { color: #fff !important; background: rgba(255,255,255,0.08) !important; border-color: rgba(255,255,255,0.15) !important; }
.desktop-nav ul li#pg_cadastro a {
    background: linear-gradient(135deg, #ff8345, #e05000) !important;
    color: #fff !important;
    border-color: transparent !important;
    box-shadow: 0 4px 16px rgba(255,131,69,0.4) !important;
    font-weight: 700 !important;
    padding: 9px 22px !important;
}
.desktop-nav ul li#pg_cadastro a:hover { transform: translateY(-2px) !important; box-shadow: 0 8px 24px rgba(255,131,69,0.55) !important; }
</style>
<!-- ═══ FIM MENU TESTE ═══════════════════════════════════════════════════ -->
	<link rel="stylesheet" type="text/css" href="../cdn2.editmysite.com/css/old/fancybox81dc.css?1234" />
	<link rel="stylesheet" type="text/css" href="../cdn2.editmysite.com/css/social-icons4315.css?buildtime=1234" media="screen,projection" />
	<link rel="stylesheet" type="text/css" href="files/main_style9838.css?1646657415" title="wsite-theme-css" />
	<link href='https://fonts.googleapis.com/css?family=Lato:400,300,300italic,700,400italic,700italic&subset=latin,latin-ext' rel='stylesheet' type='text/css' />
	<link href='https://fonts.googleapis.com/css?family=Work+Sans:400,300,200,700&subset=latin,latin-ext' rel='stylesheet' type='text/css' />
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,300italic,700,400italic,700italic&subset=latin,latin-ext' rel='stylesheet' type='text/css' />
	<link href='https://fonts.googleapis.com/css?family=Architects+Daughter&subset=latin,latin-ext' rel='stylesheet' type='text/css' />

	<style>
	.wsite-background {background-image: url("uploads/1/2/9/1/12910257/background-images/889148728.jpg") !important;background-repeat: no-repeat !important;background-position: 50% 50% !important;background-size: 100% !important;background-color: transparent !important;background: inherit;}
	body.wsite-background {background-attachment: fixed !important;}.wsite-background.wsite-custom-background{ background-size: cover !important}
	.wsite-menu-default a {letter-spacing: 1px !important;}
	.wsite-menu a {font-family:"Roboto" !important;}
	@media screen and (min-width: 767px) {
		.wsite-menu-default a {font-size:10px !important;}
	}

	/* ── Formulário ── */
	.cadastro-wrap {
		background: #fff;
		max-width: 860px;
		margin: 40px auto 60px;
		padding: 40px 50px;
		border-radius: 6px;
		box-shadow: 0 2px 18px rgba(0,0,0,.10);
		font-family: 'Work Sans', sans-serif;
	}
	.cadastro-wrap h2 {
		font-family: 'Architects Daughter', cursive !important;
		color: #ff7800 !important;
		font-size: 22px !important;
		margin: 28px 0 8px !important;
		border-bottom: 2px solid #ff8345;
		padding-bottom: 6px;
	}
	.cadastro-wrap h2:first-child { margin-top: 0 !important; }
	.aviso-topo {
		text-align: center;
		color: #555;
		margin-bottom: 28px;
		font-size: 15px;
	}
	.aviso-topo span { color: #ff6c00; font-weight: 600; }

	.form-row {
		display: flex;
		gap: 16px;
		margin-bottom: 16px;
		flex-wrap: wrap;
	}
	.form-group {
		display: flex;
		flex-direction: column;
		flex: 1;
		min-width: 180px;
	}
	.form-group.full { flex: 1 1 100%; }
	.form-group label {
		font-size: 13px;
		font-weight: 600;
		color: #333;
		margin-bottom: 5px;
	}
	.form-group label .req { color: #e03030; margin-left: 2px; }
	.form-group input,
	.form-group select {
		padding: 10px 14px;
		border: 1px solid #d0d0d0;
		border-radius: 4px;
		font-size: 14px;
		font-family: 'Work Sans', sans-serif;
		color: #333;
		transition: border-color .2s;
		background: #fff;
	}
	.form-group input:focus,
	.form-group select:focus {
		outline: none;
		border-color: #ff8345;
		box-shadow: 0 0 0 3px rgba(255,131,69,.15);
	}
	.form-group input[readonly] {
		background: #f5f5f5;
		color: #666;
		cursor: default;
	}
	.form-group input::placeholder { color: #aaa; }



	/* Senha toggle */
	.senha-wrapper {
		position: relative;
	}
	.senha-wrapper input { width: 100%; box-sizing: border-box; padding-right: 44px; }
	.senha-toggle {
		position: absolute;
		right: 12px;
		top: 50%;
		transform: translateY(-50%);
		cursor: pointer;
		color: #ff8345;
		font-size: 18px;
		user-select: none;
	}

	/* Info login */
	.login-info {
		font-size: 12px;
		color: #666;
		margin-top: 5px;
		line-height: 1.6;
	}
	.login-info strong { color: #333; }

	/* Botão enviar */
	.btn-cadastrar {
		display: block;
		width: 100%;
		margin-top: 32px;
		padding: 16px;
		background: #ff8345;
		color: #fff;
		border: none;
		border-radius: 5px;
		font-size: 17px;
		font-weight: 700;
		font-family: 'Work Sans', sans-serif;
		letter-spacing: 1px;
		cursor: pointer;
		transition: background .2s;
		text-transform: uppercase;
	}
	.btn-cadastrar:hover { background: #e06a2a; }

	/* Mensagens */
	.msg-erro {
		background: #fdecea;
		color: #c0392b;
		border: 1px solid #f5c6cb;
		border-radius: 4px;
		padding: 12px 16px;
		margin-bottom: 20px;
		font-size: 14px;
		display: none;
	}
	@media (max-width: 600px) {
		.cadastro-wrap { padding: 24px 18px; margin: 20px 12px 40px; }
		.patrocinador-row { flex-direction: column; }
	}
	</style>

	<script>
	var STATIC_BASE = 'http://cdn1.editmysite.com/';
	var ASSETS_BASE = 'http://cdn2.editmysite.com/';
	var STYLE_PREFIX = 'wsite';
	</script>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1495331788955259');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1495331788955259&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
</head>

<body class="wsite-page-index wsite-theme-light"><div class="wrapper">

	<!-- HEADER -->
	<div class="paris-header">
		<div class="container">
			<a class="hamburger" aria-label="Menu" href="#"><span></span></a>
			<div class="logo"><span class="wsite-logo">
				<span class="wsite-title-placeholder">&nbsp;</span>
			</span></div>
			<div class="nav desktop-nav"><ul class="wsite-menu-default">
				<li class="wsite-menu-item-wrap">
					<a href="index.php" class="wsite-menu-item">In&iacute;cio</a>
				</li>
				<li id="active" class="wsite-menu-item-wrap">
					<a href="cadastro.php" class="wsite-menu-item">Cadastro</a>
				</li>
			</ul></div>
		</div>
	</div>

	<!-- CONTEÚDO PRINCIPAL -->
	<div class="main-wrap">
		<div id="wsite-content" class="wsite-elements wsite-not-footer">
		<div class="wsite-section-wrap">
		<div class="wsite-section wsite-body-section wsite-section-bg-color wsite-custom-background" style="background-color:#f4f4f4;padding:10px 0 40px;">
			<div class="wsite-section-content">
				<div class="container">

					<div class="cadastro-wrap">

						<!-- Logo -->
						<div style="text-align:center;margin-bottom:20px;">
							<img src="uploads/1/2/9/1/12910257/logo-amei-2025-png_1.png" alt="Amei Cosm&eacute;ticos" style="max-width:200px;" />
						</div>

						<div class="aviso-topo"><span>Por favor</span>, preencha todos os campos obrigat&oacute;rios.</div>

						<?php if ($dbErro): ?>
						<div class="msg-erro" id="msgErro" style="display:block;"><?= $dbErro ?></div>
						<?php else: ?>
						<div class="msg-erro" id="msgErro"></div>
						<?php endif; ?>

						<form id="formCadastro" method="POST" action="cadastro.php">

							<input type="hidden" name="tipo_pessoa" value="fisica" />

							<!-- DADOS PESSOAIS -->
							<h2>Dados Pessoais</h2>
							<div class="form-row">
								<div class="form-group" style="flex:3;">
									<label for="nomeCompleto">Nome Completo <span class="req">*</span></label>
									<input type="text" id="nomeCompleto" name="nome" placeholder="Ex: Maria da Silva" required value="<?= htmlspecialchars($d['nome'] ?? '') ?>" />
								</div>
								<div class="form-group" style="flex:1;min-width:130px;">
									<label for="sexo">Sexo <span class="req">*</span></label>
									<select id="sexo" name="sexo" required>
										<option value="">Selecione</option>
										<option value="F" <?= ($d['sexo'] ?? 'F') === 'F' ? 'selected' : '' ?>>Feminino</option>
										<option value="M" <?= ($d['sexo'] ?? '') === 'M' ? 'selected' : '' ?>>Masculino</option>
									</select>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label for="cpf">CPF <span class="req">*</span></label>
									<input type="text" id="cpf" name="cpf" placeholder="Ex: 123.456.789-01" maxlength="14" required value="<?= htmlspecialchars($d['cpf'] ?? '') ?>" />
									<span id="cpf-erro" style="display:none;color:#c0392b;font-size:12px;margin-top:4px;">CPF inválido</span>
								</div>
								<div class="form-group">
									<label for="rg">RG <span class="req">*</span></label>
									<input type="text" id="rg" name="rg" placeholder="Informe seu RG" required value="<?= htmlspecialchars($d['rg'] ?? '') ?>" />
								</div>
								<div class="form-group">
									<label for="dataNasc">Data de Nascimento <span class="req">*</span></label>
									<input type="text" id="dataNasc" name="data_nascimento" placeholder="Ex: 01/01/2000" maxlength="10" required value="<?= htmlspecialchars($d['data_nascimento'] ?? '') ?>" />
								</div>
							</div>

							<!-- ENDEREÇO -->
							<h2>Endere&ccedil;o</h2>
							<div class="form-row">
								<div class="form-group" style="max-width:200px;">
									<label for="cep">CEP <span class="req">*</span></label>
									<input type="text" id="cep" name="cep" placeholder="Ex: 13606-238" maxlength="9" required value="<?= htmlspecialchars($d['cep'] ?? '') ?>" />
								</div>
								<div class="form-group" style="flex:2;">
									<label for="logradouro">Logradouro</label>
									<input type="text" id="logradouro" name="logradouro" placeholder="Rua, Avenida..." readonly value="<?= htmlspecialchars($d['logradouro'] ?? '') ?>" />
								</div>
								<div class="form-group" style="max-width:120px;">
									<label for="numero">N&uacute;mero <span class="req">*</span></label>
									<input type="text" id="numero" name="numero" placeholder="N&ordm;" required value="<?= htmlspecialchars($d['numero'] ?? '') ?>" />
								</div>
							</div>
							<div class="form-row">
								<div class="form-group">
									<label for="complemento">Complemento</label>
									<input type="text" id="complemento" name="complemento" placeholder="Apto, Bloco..." value="<?= htmlspecialchars($d['complemento'] ?? '') ?>" />
								</div>
								<div class="form-group">
									<label for="bairro">Bairro</label>
									<input type="text" id="bairro" name="bairro" placeholder="Bairro" readonly value="<?= htmlspecialchars($d['bairro'] ?? '') ?>" />
								</div>
								<div class="form-group">
									<label for="cidade">Cidade</label>
									<input type="text" id="cidade" name="cidade" placeholder="Cidade" readonly value="<?= htmlspecialchars($d['cidade'] ?? '') ?>" />
								</div>
								<div class="form-group" style="max-width:90px;">
									<label for="uf">UF</label>
									<input type="text" id="uf" name="uf" placeholder="UF" readonly maxlength="2" value="<?= htmlspecialchars($d['uf'] ?? '') ?>" />
								</div>
							</div>

							<!-- CONTATO -->
							<h2>Informa&ccedil;&otilde;es Para Contato</h2>
							<div class="form-row">
								<div class="form-group full">
									<label for="email">Email <span class="req">*</span></label>
									<input type="email" id="email" name="email" placeholder="Ex. ameicosmeticos@gmail.com" required value="<?= htmlspecialchars($d['email'] ?? '') ?>" />
								</div>
							</div>
							<div class="form-row">
								<div class="form-group">
									<label for="celular">Celular / WhatsApp <span class="req">*</span></label>
									<input type="text" id="celular" name="celular" placeholder="Ex: (99) 9 1234-5678" maxlength="16" required value="<?= htmlspecialchars($d['celular'] ?? '') ?>" />
									<span id="celular-erro" style="display:none;color:#c0392b;font-size:12px;margin-top:4px;">Número inválido. Informe um celular com DDD.</span>
								</div>
							</div>

							<!-- CONTA -->
							<h2>Sua Conta</h2>
							<div class="form-row">
								<div class="form-group full">
									<label for="login">Login <span class="req">*</span></label>
									<input type="text" id="login" name="login" placeholder="Informe seu login" required value="<?= htmlspecialchars($d['login'] ?? '') ?>" />
									<div class="login-info">
										* O login escolhido <strong>n&atilde;o poder&aacute; ser alterado</strong> posteriormente<br>
										** Este login ser&aacute; utilizado para acessar sua loja/site personalizada(o)<br>
										Seus links de divulga&ccedil;&atilde;o ficar&atilde;o assim:<br>
										loja: www.ameicosmeticos.com.br/loja/<br>
										site: www.ameicosmeticos.com.br/
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group">
									<label for="senha">Senha <span class="req">*</span></label>
									<div class="senha-wrapper">
										<input type="password" id="senha" name="senha" required autocomplete="new-password" />
										<span class="senha-toggle" onclick="toggleSenha('senha', this)">&#128065;</span>
									</div>
								</div>
								<div class="form-group">
									<label for="senhaConfirm">Redigite a senha <span class="req">*</span></label>
									<div class="senha-wrapper">
										<input type="password" id="senhaConfirm" name="senha_confirm" required autocomplete="new-password" />
										<span class="senha-toggle" onclick="toggleSenha('senhaConfirm', this)">&#128065;</span>
									</div>
								</div>
							</div>

							<button type="submit" class="btn-cadastrar">&gt; FINALIZAR CADASTRO &lt;</button>

						</form>
					</div>

				</div>
			</div>
		</div>
		</div>
		</div>
	</div>

	<!-- FOOTER -->
	<div class="footer-wrap">
		<div class="container">
			<div class="footer"><div class="wsite-elements wsite-footer">
			<div><div style="height:0px;overflow:hidden;width:100%;"></div>
			<hr class="styled-hr" style="width:100%;"></hr>
			<div style="height:0px;overflow:hidden;width:100%;"></div></div>

			<div><div class="wsite-multicol"><div class="wsite-multicol-table-wrap" style="margin:0 -15px;">
			<table class="wsite-multicol-table"><tbody class="wsite-multicol-tbody"><tr class="wsite-multicol-tr">
				<td class="wsite-multicol-col" style="width:25.7%;padding:0 15px;">
					<div><div class="wsite-image wsite-image-border-none" style="padding:10px 0;text-align:center;">
						<img src="uploads/1/2/9/1/12910257/2350881_orig.png" alt="Imagem" style="width:auto;max-width:100%" />
					</div></div>
				</td>
				<td class="wsite-multicol-col" style="width:32.3%;padding:0 15px;">
					<div class="paragraph" style="text-align:center;"><font size="3"><strong>ENTRE EM CONTATO</strong><br /><a href="https://wa.me/5561982290919" target="_blank" rel="noopener" style="color:inherit;text-decoration:none;">Whatsapp: (61) 98229-0919</a><br /><a href="mailto:oseiascw@gmail.com">oseiascw@gmail.com</a></font></div>
				</td>
				<td class="wsite-multicol-col" style="width:42%;padding:0 15px;">
					<div class="paragraph" style="text-align:center;">
						<font color="#ffffff"><em><font size="2">Beneficiando o maior n&uacute;mero de pessoas!</font></em></font><br /><br />
						<font size="2">A melhor empresa para se ganhar dinheiro com produtos reais, voc&ecirc; vai Amar!</font><br />
						<span style="color:rgb(255,255,255)">Copyright &copy; 2014</span><br />
						<font size="2">Todos os Direitos Reservados.</font>
					</div>
				</td>
			</tr></tbody></table>
			</div></div></div>
			</div></div>
		</div>
	</div>
</div>

<!-- MENU MOBILE -->
<div class="nav mobile-nav"> <a class="hamburger" aria-label="Menu" href="#"><span></span></a>
	<ul class="wsite-menu-default">
		<li class="wsite-menu-item-wrap"><a href="index.php" class="wsite-menu-item">In&iacute;cio</a></li>
		<li id="active" class="wsite-menu-item-wrap"><a href="cadastro.php" class="wsite-menu-item">Cadastro</a></li>
	</ul>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="files/theme/plugins.js"></script>
<script type="text/javascript" src="files/theme/custom.js"></script>
<script>
// ── Toggle senha ────────────────────────────────────────────────────────────
function toggleSenha(id, el) {
	var input = document.getElementById(id);
	if (input.type === 'password') { input.type = 'text'; el.style.opacity = '0.5'; }
	else { input.type = 'password'; el.style.opacity = '1'; }
}

// ── Alternar campos PF / PJ ─────────────────────────────────────────────────

// ── Máscara + validação CPF ──────────────────────────────────────────────────
function validarCPFjs(cpf) {
	cpf = cpf.replace(/\D/g, '');
	if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
	for (var t = 9; t < 11; t++) {
		var d = 0;
		for (var c = 0; c < t; c++) d += parseInt(cpf[c]) * (t + 1 - c);
		d = ((10 * d) % 11) % 10;
		if (parseInt(cpf[c]) !== d) return false;
	}
	return true;
}
function checarCPF() {
	var v = document.getElementById('cpf').value;
	var erro = document.getElementById('cpf-erro');
	var digits = v.replace(/\D/g,'');
	if (digits.length > 0) {
		erro.style.display = (digits.length === 11 && validarCPFjs(digits)) ? 'none' : 'block';
	} else {
		erro.style.display = 'none';
	}
}
document.getElementById('cpf').addEventListener('input', function() {
	var v = this.value.replace(/\D/g,'');
	v = v.replace(/(\d{3})(\d)/,'$1.$2');
	v = v.replace(/(\d{3})(\d)/,'$1.$2');
	v = v.replace(/(\d{3})(\d{1,2})$/,'$1-$2');
	this.value = v;
	// Só mostra erro durante digitação se já completou os 11 dígitos
	var digits = v.replace(/\D/g,'');
	if (digits.length === 11) checarCPF();
	else document.getElementById('cpf-erro').style.display = 'none';
});
document.getElementById('cpf').addEventListener('blur', checarCPF);

// ── Máscara Data ────────────────────────────────────────────────────────────
document.getElementById('dataNasc').addEventListener('input', function() {
	var v = this.value.replace(/\D/g,'');
	v = v.replace(/(\d{2})(\d)/,'$1/$2');
	v = v.replace(/(\d{2})(\d)/,'$1/$2');
	this.value = v;
});

// ── Máscara CEP + busca ViaCEP ──────────────────────────────────────────────
document.getElementById('cep').addEventListener('input', function() {
	var v = this.value.replace(/\D/g,'');
	v = v.replace(/(\d{5})(\d)/,'$1-$2');
	this.value = v;
	if (v.replace('-','').length === 8) buscarCEP(v.replace('-',''));
});

function buscarCEP(cep) {
	fetch('https://viacep.com.br/ws/' + cep + '/json/')
		.then(function(r){ return r.json(); })
		.then(function(d) {
			if (!d.erro) {
				document.getElementById('logradouro').value = d.logradouro || '';
				document.getElementById('bairro').value     = d.bairro     || '';
				document.getElementById('cidade').value     = d.localidade || '';
				document.getElementById('uf').value         = d.uf         || '';
				document.getElementById('numero').focus();
			}
		}).catch(function(){});
}

// ── Máscara + validação Celular / WhatsApp ───────────────────────────────────
function checarCelular() {
	var v = document.getElementById('celular').value;
	var erro = document.getElementById('celular-erro');
	var digits = v.replace(/\D/g,'');
	if (digits.length > 0) {
		erro.style.display = (digits.length === 11 && digits[2] === '9') ? 'none' : 'block';
	} else {
		erro.style.display = 'none';
	}
}
document.getElementById('celular').addEventListener('input', function() {
	var v = this.value.replace(/\D/g,'');
	v = v.replace(/^(\d{2})(\d)/,'($1) $2');
	v = v.replace(/(\d{5})(\d)/,'$1-$2');
	this.value = v;
	// Só mostra erro durante digitação se já completou os 11 dígitos
	var digits = this.value.replace(/\D/g,'');
	if (digits.length === 11) checarCelular();
	else document.getElementById('celular-erro').style.display = 'none';
});
document.getElementById('celular').addEventListener('blur', checarCelular);

// ── Validação client-side antes de enviar ───────────────────────────────────
document.getElementById('formCadastro').addEventListener('submit', function(e) {
	var erro  = document.getElementById('msgErro');
	var nome  = document.getElementById('nomeCompleto').value.trim();
	var email = document.getElementById('email').value.trim();
	var cel   = document.getElementById('celular').value.trim();
	var login = document.getElementById('login').value.trim();
	var senha = document.getElementById('senha').value;
	var conf  = document.getElementById('senhaConfirm').value;

	erro.style.display = 'none';

	var cpf   = document.getElementById('cpf').value.trim();
	var cpfDigits = cpf.replace(/\D/g,'');

	if (!nome)               { e.preventDefault(); mostrarErro(erro, 'Informe o Nome Completo.'); return; }
	if (!email)              { e.preventDefault(); mostrarErro(erro, 'Informe o E-mail.'); return; }
	if (!cpf)                { e.preventDefault(); mostrarErro(erro, 'Informe o CPF.'); return; }
	if (!validarCPFjs(cpfDigits)) { e.preventDefault(); mostrarErro(erro, 'CPF inválido. Verifique o número informado.'); return; }
	if (!cel)                { e.preventDefault(); mostrarErro(erro, 'Informe o Celular / WhatsApp.'); return; }
	var celDigits = cel.replace(/\D/g,'');
	if (celDigits.length !== 11 || celDigits[2] !== '9') { e.preventDefault(); mostrarErro(erro, 'Celular inválido. Informe um número com DDD (ex: 61 9 8229-0919).'); return; }
	if (!login)              { e.preventDefault(); mostrarErro(erro, 'Informe o Login desejado.'); return; }
	if (!senha)              { e.preventDefault(); mostrarErro(erro, 'Informe a Senha.'); return; }
	if (senha.length < 6)    { e.preventDefault(); mostrarErro(erro, 'A senha deve ter mínimo 6 caracteres.'); return; }
	if (senha !== conf)      { e.preventDefault(); mostrarErro(erro, 'As senhas não conferem.'); return; }

	// Tudo ok → deixa o form submeter normalmente via POST para o PHP
	document.querySelector('.btn-cadastrar').textContent = 'Enviando...';
	document.querySelector('.btn-cadastrar').disabled = true;
});

function mostrarErro(el, msg) {
	el.innerHTML = msg;
	el.style.display = 'block';
	el.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

<!-- Botão flutuante WhatsApp -->
<style>
.whatsapp-float {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}
.whatsapp-float .wa-bubble {
    background: #fff;
    color: #333;
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 4px 14px rgba(0,0,0,0.18);
    white-space: nowrap;
    animation: waBubblePop 0.4s ease 1.2s both;
    opacity: 0;
}
.whatsapp-float .wa-btn {
    width: 60px;
    height: 60px;
    background: #25D366;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 16px rgba(37,211,102,0.55);
    animation: waPulse 1.8s ease-in-out infinite;
    position: relative;
    flex-shrink: 0;
}
.whatsapp-float .wa-btn svg {
    width: 32px;
    height: 32px;
    fill: #fff;
}
.whatsapp-float .wa-badge {
    position: absolute;
    top: -3px;
    right: -3px;
    background: #e53935;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    animation: waBadgeBounce 1s ease 2s infinite;
}
@keyframes waPulse {
    0%   { box-shadow: 0 0 0 0 rgba(37,211,102,0.55); }
    70%  { box-shadow: 0 0 0 16px rgba(37,211,102,0); }
    100% { box-shadow: 0 0 0 0 rgba(37,211,102,0); }
}
@keyframes waBubblePop {
    from { opacity: 0; transform: scale(0.8) translateX(10px); }
    to   { opacity: 1; transform: scale(1) translateX(0); }
}
@keyframes waBadgeBounce {
    0%, 100% { transform: scale(1); }
    50%       { transform: scale(1.25); }
}
@media (max-width: 480px) {
    .whatsapp-float { bottom: 18px; right: 16px; }
    .whatsapp-float .wa-bubble { display: none; }
}
</style>
<a class="whatsapp-float" href="https://wa.me/5561982290919?text=Ol%C3%A1%2C%20vim%20pelo%20site%20da%20Amei%20Cosm%C3%A9ticos%20e%20quero%20saber%20mais!" target="_blank" rel="noopener" aria-label="Fale conosco pelo WhatsApp">
    <span class="wa-bubble">Olá, como posso ajudar? 👋</span>
    <span class="wa-btn">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M16 0C7.163 0 0 7.163 0 16c0 2.822.736 5.469 2.027 7.77L0 32l8.437-2.007A15.934 15.934 0 0 0 16 32c8.837 0 16-7.163 16-16S24.837 0 16 0zm0 29.333a13.28 13.28 0 0 1-6.771-1.845l-.485-.288-5.007 1.192 1.23-4.882-.317-.502A13.267 13.267 0 0 1 2.667 16C2.667 8.636 8.636 2.667 16 2.667S29.333 8.636 29.333 16 23.364 29.333 16 29.333zm7.27-9.986c-.398-.199-2.354-1.162-2.72-1.294-.365-.133-.631-.199-.897.199-.265.398-1.029 1.294-1.261 1.56-.232.265-.465.298-.863.1-.398-.199-1.681-.62-3.202-1.977-1.183-1.056-1.982-2.361-2.214-2.759-.232-.398-.025-.613.174-.811.179-.179.398-.465.597-.698.199-.232.265-.398.398-.664.133-.265.066-.498-.033-.697-.1-.199-.897-2.162-1.229-2.96-.324-.777-.653-.672-.897-.684l-.764-.013c-.265 0-.697.1-.1062.498-.365.398-1.395 1.362-1.395 3.322s1.428 3.853 1.627 4.119c.199.265 2.811 4.291 6.812 6.021.952.411 1.695.657 2.274.841.955.304 1.825.261 2.513.158.767-.114 2.354-.962 2.686-1.891.332-.93.332-1.727.232-1.891-.099-.165-.365-.265-.763-.464z"/></svg>
        <span class="wa-badge">1</span>
    </span>
</a>
</body>
</html>
