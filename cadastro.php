<?php
// ── Conexão com o banco ───────────────────────────────────────────────────────
$dbErro = $dbSucesso = '';

define('DB_HOST', 'localhost');
define('DB_NAME', 'ameicosmeticos');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
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
}

// ── Processar formulário ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    $campos = ['nome','sexo','cpf','rg','data_nascimento','cnpj','razao_social',
               'cep','logradouro','numero','complemento','bairro','cidade','uf',
               'email','celular','telefone','login','senha','senha_confirm',
               'tipo_pessoa','patrocinador'];
    $d = [];
    foreach ($campos as $c) $d[$c] = trim($_POST[$c] ?? '');

    // Validação básica
    $erros = [];
    if (!$d['nome'])         $erros[] = 'Informe o Nome Completo.';
    if (!$d['email'])        $erros[] = 'Informe o E-mail.';
    if (!$d['celular'])      $erros[] = 'Informe o Celular.';
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
            (patrocinador, nome_patrocinador, tipo_pessoa, nome, sexo, cpf, rg, data_nascimento,
             cnpj, razao_social, cep, logradouro, numero, complemento, bairro, cidade, uf,
             email, celular, telefone, login, senha_hash)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->execute([
            $d['patrocinador'] ?: 'oseiaswgi',
            'OSEIAS MILTON NERY DE FREITAS',
            $d['tipo_pessoa'] ?: 'fisica',
            $d['nome'], $d['sexo'], $d['cpf'], $d['rg'], $d['data_nascimento'],
            $d['cnpj'], $d['razao_social'],
            $d['cep'], $d['logradouro'], $d['numero'], $d['complemento'],
            $d['bairro'], $d['cidade'], $d['uf'],
            $d['email'], $d['celular'], $d['telefone'],
            $d['login'], $hash
        ]);
        $dbSucesso = 'Cadastro realizado com sucesso! Em breve entraremos em contato, ' . htmlspecialchars(explode(' ', $d['nome'])[0]) . '!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
	<title>Cadastro de Consultor(a) | Amei Cosm&eacute;ticos</title>
	<meta name="description" content="Cadastre-se como consultor(a) Amei Cosméticos e comece a ganhar dinheiro revendendo produtos de qualidade." />
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<link id="wsite-base-style" rel="stylesheet" type="text/css" href="../cdn2.editmysite.com/css/sitesd186.css?buildTime=1234" />
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
	.wsite-button-inner {}
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

	/* Patrocinador */
	.patrocinador-row {
		display: flex;
		gap: 12px;
		align-items: flex-end;
		margin-bottom: 16px;
		flex-wrap: wrap;
	}
	.patrocinador-row .form-group { flex: 1; min-width: 160px; }
	.patrocinador-row .form-group.nome { flex: 2; }
	.btn-pesquisar {
		padding: 10px 22px;
		background: #ff8345;
		color: #fff;
		border: none;
		border-radius: 4px;
		font-size: 14px;
		font-weight: 600;
		cursor: pointer;
		white-space: nowrap;
		height: 42px;
		align-self: flex-end;
		transition: background .2s;
	}
	.btn-pesquisar:hover { background: #e06a2a; }

	/* Radio */
	.radio-group {
		display: flex;
		gap: 24px;
		margin-bottom: 20px;
		align-items: center;
	}
	.radio-group label {
		display: flex;
		align-items: center;
		gap: 6px;
		font-size: 14px;
		font-weight: 500;
		color: #333;
		cursor: pointer;
	}
	.radio-group input[type="radio"] {
		accent-color: #ff8345;
		width: 16px;
		height: 16px;
	}

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
	.msg-sucesso {
		background: #eafaf1;
		color: #1e8449;
		border: 1px solid #a9dfbf;
		border-radius: 4px;
		padding: 14px 16px;
		margin-bottom: 20px;
		font-size: 14px;
		display: none;
		text-align: center;
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
				<li class="wsite-menu-item-wrap">
					<a href="revendedor.php" class="wsite-menu-item">Quero ser Consultor(a)</a>
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

						<?php if ($dbSucesso): ?>
						<div class="msg-sucesso" id="msgSucesso" style="display:block;"><?= $dbSucesso ?></div>
						<?php else: ?>
						<div class="msg-sucesso" id="msgSucesso"></div>
						<?php endif; ?>

						<form id="formCadastro" method="POST" action="cadastro.php" <?= $dbSucesso ? 'style="display:none"' : '' ?>>

							<!-- PATROCINADOR -->
							<h2>Patrocinador</h2>
							<div class="patrocinador-row">
								<div class="form-group">
									<label for="patrocinador">Informe o patrocinador:</label>
									<input type="text" id="patrocinador" name="patrocinador" value="oseiaswgi" readonly />
								</div>
								<button type="button" class="btn-pesquisar" onclick="buscarPatrocinador()">Pesquisar</button>
								<div class="form-group nome">
									<label for="nomePatrocinador">Nome do Patrocinador</label>
									<input type="text" id="nomePatrocinador" name="nome_patrocinador" value="OSEIAS MILTON NERY DE FREITAS" readonly />
								</div>
							</div>

							<!-- TIPO DE PESSOA -->
							<div class="radio-group">
								<label><input type="radio" name="tipo_pessoa" value="fisica" checked onchange="alternarTipo(this)"> Pessoa F&iacute;sica</label>
								<label><input type="radio" name="tipo_pessoa" value="juridica" onchange="alternarTipo(this)"> Pessoa Jur&iacute;dica</label>
							</div>

							<!-- DADOS PESSOAIS -->
							<h2>Dados Pessoais</h2>
							<div class="form-row">
								<div class="form-group" style="flex:3;">
									<label for="nomeCompleto">Nome Completo <span class="req">*</span></label>
									<input type="text" id="nomeCompleto" name="nome" placeholder="Ex: Maria da Silva" required />
								</div>
								<div class="form-group" style="flex:1;min-width:130px;">
									<label for="sexo">Sexo <span class="req">*</span></label>
									<select id="sexo" name="sexo" required>
										<option value="">Selecione</option>
										<option value="F" selected>Feminino</option>
										<option value="M">Masculino</option>
									</select>
								</div>
							</div>

							<div class="form-row" id="campos-fisica">
								<div class="form-group">
									<label for="cpf">CPF <span class="req">*</span></label>
									<input type="text" id="cpf" name="cpf" placeholder="Ex: 123.456.789-01" maxlength="14" required />
								</div>
								<div class="form-group">
									<label for="rg">RG <span class="req">*</span></label>
									<input type="text" id="rg" name="rg" placeholder="Informe seu RG" required />
								</div>
								<div class="form-group">
									<label for="dataNasc">Data de Nascimento <span class="req">*</span></label>
									<input type="text" id="dataNasc" name="data_nascimento" placeholder="Ex: 01/01/2000" maxlength="10" required />
								</div>
							</div>

							<div class="form-row" id="campos-juridica" style="display:none;">
								<div class="form-group">
									<label for="cnpj">CNPJ <span class="req">*</span></label>
									<input type="text" id="cnpj" name="cnpj" placeholder="Ex: 00.000.000/0001-00" maxlength="18" />
								</div>
								<div class="form-group">
									<label for="razaoSocial">Raz&atilde;o Social <span class="req">*</span></label>
									<input type="text" id="razaoSocial" name="razao_social" placeholder="Raz&atilde;o Social da empresa" />
								</div>
							</div>

							<!-- ENDEREÇO -->
							<h2>Endere&ccedil;o</h2>
							<div class="form-row">
								<div class="form-group" style="max-width:200px;">
									<label for="cep">CEP <span class="req">*</span></label>
									<input type="text" id="cep" name="cep" placeholder="Ex: 13606-238" maxlength="9" required />
								</div>
								<div class="form-group" style="flex:2;">
									<label for="logradouro">Logradouro</label>
									<input type="text" id="logradouro" name="logradouro" placeholder="Rua, Avenida..." readonly />
								</div>
								<div class="form-group" style="max-width:120px;">
									<label for="numero">N&uacute;mero <span class="req">*</span></label>
									<input type="text" id="numero" name="numero" placeholder="N&ordm;" required />
								</div>
							</div>
							<div class="form-row">
								<div class="form-group">
									<label for="complemento">Complemento</label>
									<input type="text" id="complemento" name="complemento" placeholder="Apto, Bloco..." />
								</div>
								<div class="form-group">
									<label for="bairro">Bairro</label>
									<input type="text" id="bairro" name="bairro" placeholder="Bairro" readonly />
								</div>
								<div class="form-group">
									<label for="cidade">Cidade</label>
									<input type="text" id="cidade" name="cidade" placeholder="Cidade" readonly />
								</div>
								<div class="form-group" style="max-width:90px;">
									<label for="uf">UF</label>
									<input type="text" id="uf" name="uf" placeholder="UF" readonly maxlength="2" />
								</div>
							</div>

							<!-- CONTATO -->
							<h2>Informa&ccedil;&otilde;es Para Contato</h2>
							<div class="form-row">
								<div class="form-group full">
									<label for="email">Email <span class="req">*</span></label>
									<input type="email" id="email" name="email" placeholder="Ex. ameicosmeticos@gmail.com" required />
								</div>
							</div>
							<div class="form-row">
								<div class="form-group">
									<label for="celular">Celular <span class="req">*</span></label>
									<input type="text" id="celular" name="celular" placeholder="Ex: (99) 9 1234-5678" maxlength="16" required />
								</div>
								<div class="form-group">
									<label for="telefone">Telefone</label>
									<input type="text" id="telefone" name="telefone" placeholder="Ex: (99) 1234-5678" maxlength="15" />
								</div>
							</div>

							<!-- CONTA -->
							<h2>Sua Conta</h2>
							<div class="form-row">
								<div class="form-group full">
									<label for="login">Login <span class="req">*</span></label>
									<input type="text" id="login" name="login" placeholder="Informe seu login" required />
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
										<input type="password" id="senha" name="senha" required />
										<span class="senha-toggle" onclick="toggleSenha('senha', this)">&#128065;</span>
									</div>
								</div>
								<div class="form-group">
									<label for="senhaConfirm">Redigite a senha <span class="req">*</span></label>
									<div class="senha-wrapper">
										<input type="password" id="senhaConfirm" name="senha_confirm" required />
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
					<div class="paragraph" style="text-align:center;"><font size="3">SIGA NOSSOS CANAIS</font></div>
					<div style="text-align:center;">
						<span class="wsite-social wsite-social-default">
							<a class='first-child wsite-social-item wsite-social-facebook' href='https://www.facebook.com/ameicosmeticos' target='_blank' aria-label='Facebook'><span class='wsite-social-item-inner'></span></a>
							<a class='wsite-social-item wsite-social-instagram' href='https://www.instagram.com/oficial_ameicosmeticos/' target='_blank' aria-label='Instagram'><span class='wsite-social-item-inner'></span></a>
							<a class='last-child wsite-social-item wsite-social-youtube' href='https://www.youtube.com/c/AmeiCosm%C3%A9ticosOficial/videos' target='_blank' aria-label='Youtube'><span class='wsite-social-item-inner'></span></a>
						</span>
					</div>
				</td>
				<td class="wsite-multicol-col" style="width:32.3%;padding:0 15px;">
					<div class="paragraph" style="text-align:center;"><font size="3"><strong>ENTRE EM CONTATO</strong><br />Tel: (19) 3132-0626<br />Whatsapp: (19) 98235-5796<br />Av. Roberto Lacerda de Oliveira, 191<br />Araras/SP - CEP 13603-134</font></div>
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
		<li class="wsite-menu-item-wrap"><a href="revendedor.php" class="wsite-menu-item">Quero ser Consultor(a)</a></li>
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
function alternarTipo(radio) {
	document.getElementById('campos-fisica').style.display  = radio.value === 'fisica'   ? 'flex' : 'none';
	document.getElementById('campos-juridica').style.display = radio.value === 'juridica' ? 'flex' : 'none';
}

// ── Busca patrocinador (fixo por enquanto) ──────────────────────────────────
function buscarPatrocinador() {
	var cod = document.getElementById('patrocinador').value.trim();
	if (!cod) { alert('Informe o código do patrocinador.'); return; }
	// Patrocinador fixo para este link de consultor
	document.getElementById('nomePatrocinador').value = 'OSEIAS MILTON NERY DE FREITAS';
}

// ── Máscara CPF ─────────────────────────────────────────────────────────────
document.getElementById('cpf').addEventListener('input', function() {
	var v = this.value.replace(/\D/g,'');
	v = v.replace(/(\d{3})(\d)/,'$1.$2');
	v = v.replace(/(\d{3})(\d)/,'$1.$2');
	v = v.replace(/(\d{3})(\d{1,2})$/,'$1-$2');
	this.value = v;
});

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

// ── Máscara Celular ─────────────────────────────────────────────────────────
document.getElementById('celular').addEventListener('input', function() {
	var v = this.value.replace(/\D/g,'');
	v = v.replace(/^(\d{2})(\d)/,'($1) $2');
	v = v.replace(/(\d{5})(\d)/,'$1-$2');
	this.value = v;
});

// ── Máscara Telefone ────────────────────────────────────────────────────────
document.getElementById('telefone').addEventListener('input', function() {
	var v = this.value.replace(/\D/g,'');
	v = v.replace(/^(\d{2})(\d)/,'($1) $2');
	v = v.replace(/(\d{4})(\d)/,'$1-$2');
	this.value = v;
});

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

	if (!nome)               { e.preventDefault(); mostrarErro(erro, 'Informe o Nome Completo.'); return; }
	if (!email)              { e.preventDefault(); mostrarErro(erro, 'Informe o E-mail.'); return; }
	if (!cel)                { e.preventDefault(); mostrarErro(erro, 'Informe o Celular.'); return; }
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
</body>
</html>
