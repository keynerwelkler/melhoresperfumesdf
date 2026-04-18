<?php
require_once 'config.php';
requerLogin();

$leads = $pdo->query("SELECT * FROM leads ORDER BY criado_em DESC")->fetchAll();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="leads_amei_' . date('Y-m-d') . '.csv"');

$out = fopen('php://output', 'w');
fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

fputcsv($out, ['ID','Nome','Login','Email','Celular/WhatsApp','CPF','RG','Nascimento','Tipo','CEP','Logradouro','Número','Bairro','Cidade','UF','Status','Cadastrado em'], ';');

foreach ($leads as $l) {
    fputcsv($out, [
        $l['id'], $l['nome'], $l['login'], $l['email'],
        $l['celular'], $l['cpf'], $l['rg'],
        $l['data_nascimento'], $l['tipo_pessoa'],
        $l['cep'], $l['logradouro'], $l['numero'],
        $l['bairro'], $l['cidade'], $l['uf'],
        $l['status'], $l['criado_em']
    ], ';');
}
fclose($out);
