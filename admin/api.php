<?php
require_once 'config.php';
header('Content-Type: application/json');
requerLogin();

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

switch ($acao) {

    // ── Atualizar status do lead ───────────────────────────────────────────
    case 'status':
        $id     = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $validos = ['novo', 'contato', 'fechado', 'perdido'];
        if (!$id || !in_array($status, $validos)) {
            echo json_encode(['ok' => false, 'msg' => 'Dados inválidos']);
            break;
        }
        $pdo->prepare("UPDATE leads SET status = ? WHERE id = ?")->execute([$status, $id]);
        echo json_encode(['ok' => true]);
        break;

    // ── Salvar observação ─────────────────────────────────────────────────
    case 'obs':
        $id  = (int)($_POST['id'] ?? 0);
        $obs = trim($_POST['obs'] ?? '');Plano de Negócios, Ética e Conduta, Manual do Ponto de Apoio
        if (!$id) {
            echo json_encode(['ok' => false, 'msg' => 'ID inválido']);
            break;
        }
        $pdo->prepare("UPDATE leads SET observacao = ? WHERE id = ?")->execute([$obs, $id]);
        echo json_encode(['ok' => true]);
        break;

    // ── Excluir lead ──────────────────────────────────────────────────────
    case 'excluir':
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['ok' => false, 'msg' => 'ID inválido']);
            break;
        }
        $pdo->prepare("DELETE FROM leads WHERE id = ?")->execute([$id]);
        echo json_encode(['ok' => true]);
        break;

    // ── Buscar detalhes de um lead ────────────────────────────────────────
    case 'detalhe':
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            echo json_encode(['ok' => false, 'msg' => 'ID inválido']);
            break;
        }
        $stmt = $pdo->prepare("SELECT * FROM leads WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $lead = $stmt->fetch();
        if (!$lead) {
            echo json_encode(['ok' => false, 'msg' => 'Lead não encontrado']);
            break;
        }
        unset($lead['senha_hash']); // nunca expor o hash
        echo json_encode(['ok' => true, 'lead' => $lead]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['ok' => false, 'msg' => 'Ação desconhecida']);
}
