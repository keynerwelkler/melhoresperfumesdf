<?php
$nome = htmlspecialchars(trim($_GET['nome'] ?? 'Consultor(a)'));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Cadastro Realizado! | Melhores Perfumes DF</title>
<meta name="robots" content="noindex, nofollow"/>
<link href='https://fonts.googleapis.com/css?family=Work+Sans:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'/>
<link href='https://fonts.googleapis.com/css?family=Architects+Daughter&subset=latin,latin-ext' rel='stylesheet' type='text/css'/>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Work Sans', sans-serif;
    background: linear-gradient(135deg, #fff8f4 0%, #fff3e8 100%);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px 20px;
    color: #333;
}

.card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 40px rgba(255,131,69,.18);
    max-width: 540px;
    width: 100%;
    padding: 50px 48px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 5px;
    background: linear-gradient(90deg, #ff8345, #ffb347, #ff8345);
    background-size: 200% 100%;
    animation: shimmer 2s linear infinite;
}
@keyframes shimmer {
    0%   { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

.logo { margin-bottom: 28px; }
.logo img { max-width: 160px; }

.check-circle {
    width: 82px;
    height: 82px;
    background: linear-gradient(135deg, #ff8345, #e06000);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 28px;
    box-shadow: 0 6px 24px rgba(255,131,69,.45);
    animation: popIn .5s cubic-bezier(.17,.67,.3,1.3) both;
}
@keyframes popIn {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.check-circle svg {
    width: 40px; height: 40px; fill: none;
    stroke: #fff; stroke-width: 3;
    stroke-linecap: round; stroke-linejoin: round;
}

h1 {
    font-family: 'Architects Daughter', cursive;
    color: #ff7800;
    font-size: 28px;
    margin-bottom: 12px;
    line-height: 1.3;
}
.subtitulo {
    color: #555;
    font-size: 16px;
    line-height: 1.7;
    margin-bottom: 32px;
}
.subtitulo strong { color: #333; }

.proximos-passos {
    background: #fff8f2;
    border: 1px solid #ffe0c8;
    border-radius: 10px;
    padding: 22px 24px;
    margin-bottom: 32px;
    text-align: left;
}
.proximos-passos h3 {
    font-size: 13px;
    font-weight: 700;
    color: #ff7800;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 14px;
}
.proximos-passos ul {
    list-style: none;
    padding: 0;
}
.proximos-passos ul li {
    font-size: 14px;
    color: #444;
    padding: 5px 0;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    line-height: 1.5;
}
.proximos-passos ul li::before {
    content: '✓';
    color: #ff8345;
    font-weight: 700;
    flex-shrink: 0;
    margin-top: 1px;
}

.btn-wpp {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: #25D366;
    color: #fff;
    text-decoration: none;
    padding: 15px 32px;
    border-radius: 50px;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: .5px;
    box-shadow: 0 6px 20px rgba(37,211,102,.4);
    transition: transform .2s, box-shadow .2s;
    margin-bottom: 18px;
    width: 100%;
}
.btn-wpp:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(37,211,102,.5); }
.btn-wpp svg { width: 22px; height: 22px; fill: #fff; flex-shrink: 0; }

.btn-voltar {
    display: inline-block;
    color: #999;
    font-size: 13px;
    text-decoration: none;
    transition: color .2s;
}
.btn-voltar:hover { color: #ff8345; }

.rodape {
    margin-top: 40px;
    font-size: 12px;
    color: #bbb;
}

@media (max-width: 480px) {
    .card { padding: 36px 24px; }
    h1 { font-size: 23px; }
}
</style>
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
<body>

<div class="card">
    <div class="logo">
        <img src="uploads/1/2/9/1/12910257/logo-amei-2025-png_1.png" alt="Amei Cosméticos"/>
    </div>

    <div class="check-circle">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
    </div>

    <h1>Parabéns, <?= $nome ?>!</h1>
    <p class="subtitulo">
        Seu cadastro foi realizado com sucesso!<br>
        <strong>Em breve nossa equipe entrará em contato</strong> para te ajudar a começar a revender os melhores perfumes importados.
    </p>

    <div class="proximos-passos">
        <h3>Próximos passos</h3>
        <ul>
            <li>Aguarde o contato da nossa equipe pelo WhatsApp</li>
            <li>Prepare-se para conhecer o nosso catálogo exclusivo</li>
            <li>Comece a divulgar e lucrar até 150%!</li>
        </ul>
    </div>

    <p style="font-size:16px;font-weight:700;color:#333;margin-bottom:12px;">👇 Quer tirar dúvidas agora? Fale com a gente no WhatsApp!</p>
    <a class="btn-wpp"
       href="https://wa.me/5561982290919?text=Ol%C3%A1%2C%20acabei%20de%20me%20cadastrar%20como%20consultora%20Amei%20Cosm%C3%A9ticos%20e%20gostaria%20de%20saber%20mais!"
       target="_blank" rel="noopener">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M16 0C7.163 0 0 7.163 0 16c0 2.822.736 5.469 2.027 7.77L0 32l8.437-2.007A15.934 15.934 0 0 0 16 32c8.837 0 16-7.163 16-16S24.837 0 16 0zm0 29.333a13.28 13.28 0 0 1-6.771-1.845l-.485-.288-5.007 1.192 1.23-4.882-.317-.502A13.267 13.267 0 0 1 2.667 16C2.667 8.636 8.636 2.667 16 2.667S29.333 8.636 29.333 16 23.364 29.333 16 29.333zm7.27-9.986c-.398-.199-2.354-1.162-2.72-1.294-.365-.133-.631-.199-.897.199-.265.398-1.029 1.294-1.261 1.56-.232.265-.465.298-.863.1-.398-.199-1.681-.62-3.202-1.977-1.183-1.056-1.982-2.361-2.214-2.759-.232-.398-.025-.613.174-.811.179-.179.398-.465.597-.698.199-.232.265-.398.398-.664.133-.265.066-.498-.033-.697-.1-.199-.897-2.162-1.229-2.96-.324-.777-.653-.672-.897-.684l-.764-.013c-.265 0-.697.1-.1062.498-.365.398-1.395 1.362-1.395 3.322s1.428 3.853 1.627 4.119c.199.265 2.811 4.291 6.812 6.021.952.411 1.695.657 2.274.841.955.304 1.825.261 2.513.158.767-.114 2.354-.962 2.686-1.891.332-.93.332-1.727.232-1.891-.099-.165-.365-.265-.763-.464z"/></svg>
        Chamar no WhatsApp
    </a>

    <a class="btn-voltar" href="index.php">← Voltar para o site</a>
</div>

<div class="rodape">
    &copy; <?= date('Y') ?> Amei Cosméticos — Todos os direitos reservados.
</div>

</body>
</html>
