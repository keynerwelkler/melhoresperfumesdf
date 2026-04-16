<?php
require_once 'config.php';
requerLogin();

// ── Totais para os cards ──────────────────────────────────────────────────────
$totais = $pdo->query("
    SELECT
        COUNT(*) AS total,
        SUM(status='novo')    AS novo,
        SUM(status='contato') AS contato,
        SUM(status='fechado') AS fechado,
        SUM(status='perdido') AS perdido
    FROM leads
")->fetch();

// ── Buscar todos os leads ─────────────────────────────────────────────────────
$busca  = trim($_GET['busca']  ?? '');
$status = trim($_GET['status'] ?? '');
$ordem  = trim($_GET['ordem']  ?? 'recente');

$where  = [];
$params = [];

if ($busca) {
    $where[]  = "(nome LIKE ? OR email LIKE ? OR celular LIKE ? OR login LIKE ?)";
    $like     = "%$busca%";
    $params   = array_merge($params, [$like, $like, $like, $like]);
}
if ($status) {
    $where[]  = "status = ?";
    $params[] = $status;
}

$sql = "SELECT * FROM leads";
if ($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= match($ordem) {
    'antigo' => " ORDER BY criado_em ASC",
    'nome'   => " ORDER BY nome ASC",
    default  => " ORDER BY criado_em DESC",
};

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$leads = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Painel de Leads — Amei Cosméticos</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
        --laranja: #ff8345; --laranja-esc: #e06a2a;
        --azul-esc: #1a1a2e; --azul-med: #16213e;
        --sidebar-w: 240px;
    }
    body { font-family: 'Inter', sans-serif; background: #f0f2f7; color: #333; min-height: 100vh; display: flex; }

    /* SIDEBAR */
    .sidebar {
        width: var(--sidebar-w); background: var(--azul-esc);
        min-height: 100vh; display: flex; flex-direction: column;
        position: fixed; top: 0; left: 0; bottom: 0; z-index: 100;
    }
    .sidebar-logo { padding: 22px 20px 18px; border-bottom: 1px solid rgba(255,255,255,.08); text-align: center; }
    .sidebar-logo img { max-width: 120px; }
    .sidebar-logo .versao { font-size: 10px; color: rgba(255,255,255,.35); margin-top: 6px; letter-spacing: 1px; text-transform: uppercase; }
    .sidebar-nav { flex: 1; padding: 16px 0; }
    .nav-section { font-size: 10px; font-weight: 600; color: rgba(255,255,255,.3); letter-spacing: 1.5px; text-transform: uppercase; padding: 14px 20px 6px; }
    .nav-item {
        display: flex; align-items: center; gap: 12px;
        padding: 11px 20px; color: rgba(255,255,255,.65);
        font-size: 14px; font-weight: 500; text-decoration: none;
        border-left: 3px solid transparent; transition: all .18s; cursor: pointer;
    }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,.06); }
    .nav-item.active { color: #fff; background: rgba(255,131,69,.18); border-left-color: var(--laranja); }
    .nav-item .ico { font-size: 17px; min-width: 20px; text-align: center; }
    .badge-count { margin-left: auto; background: var(--laranja); color: #fff; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; }
    .sidebar-footer { padding: 16px 20px; border-top: 1px solid rgba(255,255,255,.08); }
    .btn-sair { display: flex; align-items: center; gap: 10px; color: rgba(255,255,255,.5); font-size: 13px; font-weight: 500; text-decoration: none; transition: color .18s; }
    .btn-sair:hover { color: #ff6b6b; }

    /* MAIN */
    .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
    .header {
        height: 64px; background: #fff; border-bottom: 1px solid #e8eaf0;
        display: flex; align-items: center; padding: 0 28px; gap: 16px;
        position: sticky; top: 0; z-index: 90; box-shadow: 0 1px 6px rgba(0,0,0,.06);
    }
    .header-titulo { font-size: 18px; font-weight: 700; color: #1a1a2e; flex: 1; }
    .header-titulo span { color: var(--laranja); }
    .header-acoes { display: flex; align-items: center; gap: 12px; }
    .btn-header {
        display: flex; align-items: center; gap: 7px;
        padding: 8px 16px; border-radius: 7px; font-size: 13px; font-weight: 600;
        font-family: 'Inter', sans-serif; cursor: pointer; border: none; transition: all .18s; text-decoration: none;
    }
    .btn-header.laranja { background: var(--laranja); color: #fff; box-shadow: 0 3px 10px rgba(255,131,69,.3); }
    .btn-header.laranja:hover { background: var(--laranja-esc); }
    .btn-header.ghost { background: #f4f5f9; color: #555; border: 1px solid #e0e0e0; }
    .btn-header.ghost:hover { background: #eaecf4; }
    .avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--laranja), #ff6c00); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; }

    /* CONTEÚDO */
    .content { padding: 28px; }
    .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 18px; margin-bottom: 28px; }
    .card-stat { background: #fff; border-radius: 12px; padding: 22px 24px; box-shadow: 0 1px 6px rgba(0,0,0,.07); display: flex; align-items: center; gap: 16px; border-left: 4px solid transparent; }
    .card-stat.c1 { border-left-color: var(--laranja); }
    .card-stat.c2 { border-left-color: #2ecc71; }
    .card-stat.c3 { border-left-color: #3498db; }
    .card-stat.c4 { border-left-color: #9b59b6; }
    .card-stat.c5 { border-left-color: #e74c3c; }
    .card-ico { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
    .c1 .card-ico { background: #fff4ee; }
    .c2 .card-ico { background: #eafaf1; }
    .c3 .card-ico { background: #eaf4fb; }
    .c4 .card-ico { background: #f5eef8; }
    .c5 .card-ico { background: #fdedec; }
    .card-info .num { font-size: 30px; font-weight: 700; color: #1a1a2e; line-height: 1; }
    .card-info .label { font-size: 12px; color: #888; font-weight: 500; margin-top: 4px; }

    /* PAINEL TABELA */
    .panel { background: #fff; border-radius: 14px; box-shadow: 0 1px 6px rgba(0,0,0,.07); overflow: hidden; }
    .panel-header { padding: 18px 24px; border-bottom: 1px solid #f0f2f7; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .panel-titulo { font-size: 16px; font-weight: 700; color: #1a1a2e; flex: 1; display: flex; align-items: center; gap: 8px; }
    .cnt { background: #f0f2f7; color: #666; font-size: 12px; font-weight: 600; padding: 2px 10px; border-radius: 12px; }
    .busca-wrap { position: relative; }
    .busca-wrap .ico-lupa { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #bbb; font-size: 14px; }
    .busca-wrap input { padding: 9px 14px 9px 34px; border: 1.5px solid #e0e0e0; border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif; color: #333; width: 220px; transition: border-color .2s; background: #fafafa; }
    .busca-wrap input:focus { outline: none; border-color: var(--laranja); background: #fff; }
    .busca-wrap input::placeholder { color: #bbb; }
    select.filtro { padding: 9px 12px; border: 1.5px solid #e0e0e0; border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif; color: #555; background: #fafafa; cursor: pointer; }
    select.filtro:focus { outline: none; border-color: var(--laranja); }

    /* TABELA */
    .tabela-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    thead th { background: #f8f9fc; color: #888; font-size: 11px; font-weight: 600; letter-spacing: .8px; text-transform: uppercase; padding: 12px 14px; text-align: left; white-space: nowrap; border-bottom: 1px solid #eee; }
    tbody tr { border-bottom: 1px solid #f4f5f9; transition: background .15s; }
    tbody tr:hover { background: #fafbff; }
    tbody tr:last-child { border-bottom: none; }
    td { padding: 13px 14px; vertical-align: middle; }
    .td-nome { font-weight: 600; color: #1a1a2e; }
    .td-nome .sub { font-size: 11px; color: #aaa; font-weight: 400; margin-top: 2px; }

    .badge-status { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px; white-space: nowrap; }
    .badge-status::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
    .s-novo    { background: #fff4ee; color: #ff6c00; } .s-novo::before    { background: #ff6c00; }
    .s-contato { background: #eaf4fb; color: #2980b9; } .s-contato::before { background: #2980b9; }
    .s-fechado { background: #eafaf1; color: #27ae60; } .s-fechado::before { background: #27ae60; }
    .s-perdido { background: #fdedec; color: #e74c3c; } .s-perdido::before { background: #e74c3c; }

    select.sel-status {
        border: 1.5px solid #e0e0e0; border-radius: 6px; padding: 5px 8px;
        font-size: 12px; font-family: 'Inter', sans-serif; background: #fafafa;
        cursor: pointer; font-weight: 600;
    }
    select.sel-status:focus { outline: none; border-color: var(--laranja); }

    .btn-zap { display: inline-flex; align-items: center; gap: 6px; padding: 7px 13px; background: #25d366; color: #fff; border: none; border-radius: 7px; font-size: 12px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; text-decoration: none; transition: background .18s, transform .12s; white-space: nowrap; }
    .btn-zap:hover { background: #1da851; transform: scale(1.04); }
    .acoes { display: flex; gap: 6px; align-items: center; }
    .btn-acao { width: 32px; height: 32px; border-radius: 7px; border: 1.5px solid #e0e0e0; background: #fff; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; transition: all .15s; text-decoration: none; }
    .btn-acao:hover { border-color: var(--laranja); background: #fff4ee; }
    .btn-acao.del:hover { border-color: #e74c3c; background: #fdedec; }

    /* INFO REGISTROS */
    .info-registros { padding: 14px 24px; border-top: 1px solid #f0f2f7; font-size: 13px; color: #888; }

    /* MODAL */
    .overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.52); z-index: 200; align-items: center; justify-content: center; padding: 20px; }
    .overlay.show { display: flex; }
    .modal { background: #fff; border-radius: 16px; width: 100%; max-width: 580px; box-shadow: 0 20px 60px rgba(0,0,0,.3); overflow: hidden; max-height: 90vh; display: flex; flex-direction: column; }
    .modal-header { background: linear-gradient(135deg, var(--azul-esc), var(--azul-med)); padding: 22px 28px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
    .modal-header h3 { color: #fff; font-size: 17px; font-weight: 700; }
    .btn-fechar { background: rgba(255,255,255,.15); border: none; color: #fff; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center; transition: background .15s; }
    .btn-fechar:hover { background: rgba(255,255,255,.3); }
    .modal-body { padding: 24px 28px; overflow-y: auto; flex: 1; }
    .modal-secao { font-size: 11px; font-weight: 700; color: var(--laranja); letter-spacing: 1px; text-transform: uppercase; margin: 18px 0 10px; border-bottom: 1px solid #f0f0f0; padding-bottom: 6px; }
    .modal-secao:first-child { margin-top: 0; }
    .modal-campo { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; gap: 12px; }
    .mc-label { color: #888; font-size: 12px; font-weight: 600; min-width: 130px; flex-shrink: 0; }
    .mc-valor { color: #1a1a2e; font-weight: 500; font-size: 13px; flex: 1; }
    .btn-copiar {
        background: #f0f2f7; border: 1px solid #e0e0e0; border-radius: 6px;
        padding: 4px 10px; font-size: 11px; font-weight: 600; color: #555;
        cursor: pointer; font-family: 'Inter', sans-serif; white-space: nowrap;
        transition: all .15s; flex-shrink: 0;
    }
    .btn-copiar:hover { background: var(--laranja); color: #fff; border-color: var(--laranja); }
    .btn-copiar.copiado { background: #2ecc71; color: #fff; border-color: #2ecc71; }

    /* Observação no modal */
    .obs-area { width: 100%; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 10px 12px; font-size: 13px; font-family: 'Inter', sans-serif; color: #333; resize: vertical; min-height: 80px; transition: border-color .2s; }
    .obs-area:focus { outline: none; border-color: var(--laranja); }
    .btn-salvar-obs { margin-top: 8px; padding: 8px 18px; background: var(--laranja); color: #fff; border: none; border-radius: 7px; font-size: 13px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; transition: background .18s; }
    .btn-salvar-obs:hover { background: var(--laranja-esc); }

    .modal-footer { padding: 0 28px 24px; display: flex; gap: 10px; flex-wrap: wrap; flex-shrink: 0; }
    .btn-modal { flex: 1; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; border: none; text-align: center; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: opacity .18s; }
    .btn-modal.zap { background: #25d366; color: #fff; }
    .btn-modal.zap:hover { opacity: .88; }
    .btn-modal.cinza { background: #f0f2f7; color: #555; }
    .btn-modal.cinza:hover { background: #e5e8f0; }

    /* Confirmação exclusão */
    .confirm-box { background: #fdedec; border: 1px solid #f5c6cb; border-radius: 8px; padding: 14px 18px; margin: 0 28px 20px; font-size: 13px; color: #c0392b; display: none; }
    .confirm-box button { margin-left: 10px; padding: 5px 14px; border-radius: 6px; border: none; font-family: 'Inter', sans-serif; font-weight: 600; cursor: pointer; font-size: 12px; }
    .confirm-box .sim { background: #e74c3c; color: #fff; }
    .confirm-box .nao { background: #eee; color: #333; }

    .empty-state { text-align: center; padding: 60px 20px; color: #bbb; }
    .empty-state .ico { font-size: 48px; margin-bottom: 12px; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="../uploads/1/2/9/1/12910257/logo-amei-2025-png_1.png" alt="Amei"/>
        <div class="versao">Painel Admin</div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Principal</div>
        <a class="nav-item active" href="index.php">
            <span class="ico">📊</span> Dashboard
            <span class="badge-count"><?= (int)$totais['total'] ?></span>
        </a>
        <a class="nav-item" href="index.php">
            <span class="ico">👤</span> Leads
        </a>
        <div class="nav-section">Relatórios</div>
        <a class="nav-item" href="exportar.php">
            <span class="ico">📋</span> Exportar CSV
        </a>
        <div class="nav-section">Configurações</div>
        <a class="nav-item" href="../index.php" target="_blank">
            <span class="ico">🌐</span> Ver Site
        </a>
    </nav>
    <div class="sidebar-footer">
        <a class="btn-sair" href="logout.php">
            <span>🚪</span> Sair — <?= h($_SESSION['admin_nome']) ?>
        </a>
    </div>
</aside>

<!-- MAIN -->
<div class="main">
    <header class="header">
        <div class="header-titulo">Painel de <span>Leads</span></div>
        <div class="header-acoes">
            <a href="exportar.php" class="btn-header ghost">📋 Exportar CSV</a>
            <div class="avatar"><?= strtoupper(substr($_SESSION['admin_nome'], 0, 1)) ?></div>
        </div>
    </header>

    <div class="content">

        <!-- CARDS -->
        <div class="cards-grid">
            <div class="card-stat c1">
                <div class="card-ico">👥</div>
                <div class="card-info">
                    <div class="num"><?= (int)$totais['total'] ?></div>
                    <div class="label">Total de Leads</div>
                </div>
            </div>
            <div class="card-stat c4">
                <div class="card-ico">🆕</div>
                <div class="card-info">
                    <div class="num"><?= (int)$totais['novo'] ?></div>
                    <div class="label">Novos</div>
                </div>
            </div>
            <div class="card-stat c3">
                <div class="card-ico">📞</div>
                <div class="card-info">
                    <div class="num"><?= (int)$totais['contato'] ?></div>
                    <div class="label">Em Contato</div>
                </div>
            </div>
            <div class="card-stat c2">
                <div class="card-ico">✅</div>
                <div class="card-info">
                    <div class="num"><?= (int)$totais['fechado'] ?></div>
                    <div class="label">Fechados</div>
                </div>
            </div>
            <div class="card-stat c5">
                <div class="card-ico">❌</div>
                <div class="card-info">
                    <div class="num"><?= (int)$totais['perdido'] ?></div>
                    <div class="label">Perdidos</div>
                </div>
            </div>
        </div>

        <!-- TABELA -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-titulo">
                    Todos os Leads
                    <span class="cnt"><?= count($leads) ?></span>
                </div>
                <form method="GET" action="index.php" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                    <div class="busca-wrap">
                        <span class="ico-lupa">🔍</span>
                        <input type="text" name="busca" placeholder="Buscar nome, e-mail..."
                               value="<?= h($busca) ?>" oninput="this.form.submit()"/>
                    </div>
                    <select class="filtro" name="status" onchange="this.form.submit()">
                        <option value="">Todos os status</option>
                        <option value="novo"    <?= $status==='novo'    ? 'selected' : '' ?>>Novo</option>
                        <option value="contato" <?= $status==='contato' ? 'selected' : '' ?>>Em Contato</option>
                        <option value="fechado" <?= $status==='fechado' ? 'selected' : '' ?>>Fechado</option>
                        <option value="perdido" <?= $status==='perdido' ? 'selected' : '' ?>>Perdido</option>
                    </select>
                    <select class="filtro" name="ordem" onchange="this.form.submit()">
                        <option value="recente" <?= $ordem==='recente' ? 'selected' : '' ?>>Mais Recentes</option>
                        <option value="antigo"  <?= $ordem==='antigo'  ? 'selected' : '' ?>>Mais Antigos</option>
                        <option value="nome"    <?= $ordem==='nome'    ? 'selected' : '' ?>>Nome A-Z</option>
                    </select>
                    <?php if ($busca || $status): ?>
                        <a href="index.php" class="btn-header ghost" style="padding:8px 12px;font-size:12px;">✕ Limpar</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="tabela-wrap">
                <?php if (empty($leads)): ?>
                    <div class="empty-state">
                        <div class="ico">👤</div>
                        <p>Nenhum lead encontrado.</p>
                    </div>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome / Login</th>
                            <th>Contato</th>
                            <th>Cidade / UF</th>
                            <th>Cadastro</th>
                            <th>Status</th>
                            <th>WhatsApp</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($leads as $l):
                        $fone   = preg_replace('/\D/', '', $l['celular']);
                        $nome1  = explode(' ', $l['nome'])[0];
                        $msg    = urlencode("Olá {$nome1}! Vi que você se cadastrou na Amei Cosméticos. Podemos conversar sobre as oportunidades?");
                        $zapUrl = "https://wa.me/55{$fone}?text={$msg}";
                        $bClass = ['novo'=>'s-novo','contato'=>'s-contato','fechado'=>'s-fechado','perdido'=>'s-perdido'][$l['status']] ?? 's-novo';
                        $bLabel = ['novo'=>'Novo','contato'=>'Em Contato','fechado'=>'Fechado','perdido'=>'Perdido'][$l['status']] ?? 'Novo';
                        $data   = date('d/m/Y H:i', strtotime($l['criado_em']));
                    ?>
                        <tr>
                            <td style="color:#bbb;font-size:12px;">#<?= $l['id'] ?></td>
                            <td>
                                <div class="td-nome"><?= h($l['nome']) ?>
                                    <div class="sub">@<?= h($l['login']) ?></div>
                                </div>
                            </td>
                            <td>
                                <?= h($l['email']) ?><br>
                                <small style="color:#888;"><?= h($l['celular']) ?></small>
                            </td>
                            <td><?= h($l['cidade']) ?> / <?= h($l['uf']) ?></td>
                            <td style="white-space:nowrap;color:#888;font-size:12px;"><?= $data ?></td>
                            <td>
                                <select class="sel-status" data-id="<?= $l['id'] ?>" onchange="atualizarStatus(this)">
                                    <option value="novo"    <?= $l['status']==='novo'    ? 'selected' : '' ?>>🆕 Novo</option>
                                    <option value="contato" <?= $l['status']==='contato' ? 'selected' : '' ?>>📞 Em Contato</option>
                                    <option value="fechado" <?= $l['status']==='fechado' ? 'selected' : '' ?>>✅ Fechado</option>
                                    <option value="perdido" <?= $l['status']==='perdido' ? 'selected' : '' ?>>❌ Perdido</option>
                                </select>
                            </td>
                            <td>
                                <a class="btn-zap" href="<?= $zapUrl ?>" target="_blank">💬 WhatsApp</a>
                            </td>
                            <td>
                                <div class="acoes">
                                    <button class="btn-acao" title="Ver detalhes" onclick="abrirModal(<?= $l['id'] ?>)">👁</button>
                                    <button class="btn-acao del" title="Excluir lead" onclick="confirmarExclusao(<?= $l['id'] ?>, '<?= addslashes(h($l['nome'])) ?>')">🗑</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
            <div class="info-registros"><?= count($leads) ?> registro(s) encontrado(s)</div>
        </div>

    </div><!-- /content -->
</div><!-- /main -->

<!-- MODAL DETALHES -->
<div class="overlay" id="overlay" onclick="fecharModal(event)">
    <div class="modal" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="modalTitulo">👤 Detalhes do Lead</h3>
            <button class="btn-fechar" onclick="fecharModal()">✕</button>
        </div>
        <div class="modal-body" id="modalBody">
            <div style="text-align:center;padding:30px;color:#bbb;">Carregando...</div>
        </div>
        <div class="confirm-box" id="confirmBox">
            Tem certeza que deseja excluir este lead?
            <button class="sim" onclick="excluirConfirmado()">Sim, excluir</button>
            <button class="nao" onclick="document.getElementById('confirmBox').style.display='none'">Cancelar</button>
        </div>
        <div class="modal-footer" id="modalFooter"></div>
    </div>
</div>

<script>
var leadParaExcluir = null;

// ── Copiar para clipboard ─────────────────────────────────────────────────────
function copiar(texto, btn) {
    navigator.clipboard.writeText(texto).then(function() {
        btn.textContent = '✔ Copiado!';
        btn.classList.add('copiado');
        setTimeout(function() { btn.textContent = '📋 Copiar'; btn.classList.remove('copiado'); }, 1800);
    });
}

// ── Atualizar status via AJAX ─────────────────────────────────────────────────
function atualizarStatus(sel) {
    var id = sel.dataset.id;
    var status = sel.value;
    fetch('api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'acao=status&id=' + id + '&status=' + status
    });
}

// ── Abrir modal com dados reais ───────────────────────────────────────────────
function abrirModal(id) {
    document.getElementById('overlay').classList.add('show');
    document.getElementById('modalBody').innerHTML = '<div style="text-align:center;padding:30px;color:#bbb;">Carregando...</div>';
    document.getElementById('modalFooter').innerHTML = '';
    document.getElementById('confirmBox').style.display = 'none';

    fetch('api.php?acao=detalhe&id=' + id)
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (!d.ok) { alert('Erro ao carregar dados.'); return; }
            var l = d.lead;
            document.getElementById('modalTitulo').textContent = '👤 ' + l.nome;
            leadParaExcluir = id;

            var fone   = l.celular.replace(/\D/g,'');
            var nome1  = l.nome.split(' ')[0];
            var msg    = encodeURIComponent('Olá ' + nome1 + '! Vi que você se cadastrou na Amei Cosméticos. Podemos conversar sobre as oportunidades?');
            var zapUrl = 'https://wa.me/55' + fone + '?text=' + msg;
            var data   = l.criado_em;

            function linha(label, valor) {
                if (!valor || valor === 'null') return '';
                return '<div class="modal-campo">' +
                    '<span class="mc-label">' + label + '</span>' +
                    '<span class="mc-valor">' + valor + '</span>' +
                    '<button class="btn-copiar" onclick="copiar(\'' + valor.replace(/'/g, "\\'") + '\', this)">📋 Copiar</button>' +
                '</div>';
            }

            document.getElementById('modalBody').innerHTML =
                '<div class="modal-secao">📋 Dados Pessoais</div>' +
                linha('Nome Completo', l.nome) +
                linha('Login', '@' + l.login) +
                linha('Sexo', l.sexo) +
                linha('CPF', l.cpf) +
                linha('RG', l.rg) +
                linha('Nascimento', l.data_nascimento) +
                linha('CNPJ', l.cnpj) +
                linha('Razão Social', l.razao_social) +

                '<div class="modal-secao">📍 Endereço</div>' +
                linha('CEP', l.cep) +
                linha('Logradouro', l.logradouro) +
                linha('Número', l.numero) +
                linha('Complemento', l.complemento) +
                linha('Bairro', l.bairro) +
                linha('Cidade / UF', l.cidade + ' / ' + l.uf) +

                '<div class="modal-secao">📞 Contato</div>' +
                linha('E-mail', l.email) +
                linha('Celular', l.celular) +
                linha('Telefone', l.telefone) +

                '<div class="modal-secao">📝 Observação</div>' +
                '<textarea class="obs-area" id="obsArea" placeholder="Adicione uma observação sobre este lead...">' + (l.observacao || '') + '</textarea>' +
                '<button class="btn-salvar-obs" onclick="salvarObs(' + id + ')">💾 Salvar Observação</button>' +

                '<div class="modal-secao">🕒 Registro</div>' +
                linha('Cadastrado em', data) +
                linha('Patrocinador', l.patrocinador);

            document.getElementById('modalFooter').innerHTML =
                '<a class="btn-modal zap" href="' + zapUrl + '" target="_blank">💬 Chamar no WhatsApp</a>' +
                '<button class="btn-modal cinza" onclick="fecharModal()">Fechar</button>';
        });
}

function fecharModal(e) {
    if (!e || e.target === document.getElementById('overlay')) {
        document.getElementById('overlay').classList.remove('show');
    }
}

// ── Salvar observação ─────────────────────────────────────────────────────────
function salvarObs(id) {
    var obs = document.getElementById('obsArea').value;
    fetch('api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'acao=obs&id=' + id + '&obs=' + encodeURIComponent(obs)
    }).then(function(r) { return r.json(); })
      .then(function(d) {
        var btn = document.querySelector('.btn-salvar-obs');
        btn.textContent = '✔ Salvo!';
        btn.style.background = '#2ecc71';
        setTimeout(function() { btn.textContent = '💾 Salvar Observação'; btn.style.background = ''; }, 2000);
    });
}

// ── Excluir lead ──────────────────────────────────────────────────────────────
function confirmarExclusao(id, nome) {
    leadParaExcluir = id;
    document.getElementById('overlay').classList.add('show');
    document.getElementById('modalTitulo').textContent = '🗑 Excluir Lead';
    document.getElementById('modalBody').innerHTML = '<p style="padding:20px 0;color:#555;">Excluir o lead <strong>' + nome + '</strong>? Esta ação não pode ser desfeita.</p>';
    document.getElementById('confirmBox').style.display = 'block';
    document.getElementById('modalFooter').innerHTML = '';
}

function excluirConfirmado() {
    if (!leadParaExcluir) return;
    fetch('api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'acao=excluir&id=' + leadParaExcluir
    }).then(function() { window.location.reload(); });
}
</script>
</body>
</html>
