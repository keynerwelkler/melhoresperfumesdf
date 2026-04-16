<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Seja um Consultor(a) | Amei Cosméticos</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<style>
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
:root {
  --gold: #c9a84c;
  --gold-light: #e8c97a;
  --dark: #0d0d0d;
  --dark2: #161616;
  --dark3: #1f1f1f;
  --white: #ffffff;
  --gray: #888;
}
html { scroll-behavior: smooth; }
body { font-family: 'Inter', sans-serif; background: var(--dark); color: var(--white); overflow-x: hidden; }

/* NAV */
nav {
  position: fixed;
  top: 0; left: 0; right: 0;
  z-index: 1000;
  padding: 20px 60px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: rgba(13,13,13,0.92);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(201,168,76,0.15);
}
.nav-logo {
  font-family: 'Playfair Display', serif;
  font-size: 22px; font-weight: 700;
  letter-spacing: 2px;
  color: var(--gold);
  text-decoration: none;
}
.nav-logo span { color: var(--white); }
.nav-links { display: flex; gap: 36px; list-style: none; }
.nav-links a {
  color: rgba(255,255,255,0.75);
  text-decoration: none;
  font-size: 13px; font-weight: 500;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  transition: color .3s;
}
.nav-links a:hover, .nav-links a.active { color: var(--gold); }
.nav-cta {
  background: var(--gold) !important;
  color: var(--dark) !important;
  padding: 10px 24px;
  border-radius: 40px;
  font-weight: 700 !important;
  transition: background .3s !important;
}
.nav-cta:hover { background: var(--gold-light) !important; }
.hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; }
.hamburger span { width: 24px; height: 2px; background: var(--white); border-radius: 2px; }

/* HERO PAGE */
.page-hero {
  min-height: 60vh;
  display: flex;
  align-items: center;
  position: relative;
  overflow: hidden;
  padding: 140px 60px 80px;
}
.page-hero-bg {
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at 30% 50%, rgba(201,168,76,0.1) 0%, transparent 60%),
              linear-gradient(180deg, #1a1408 0%, #0d0d0d 100%);
}
.page-hero-content { position: relative; z-index: 2; max-width: 1200px; margin: 0 auto; width: 100%; }
.breadcrumb {
  display: flex; align-items: center; gap: 8px;
  font-size: 12px; color: var(--gray);
  margin-bottom: 24px;
  letter-spacing: 1px;
}
.breadcrumb a { color: var(--gold); text-decoration: none; }
.breadcrumb span { color: var(--gray); }
.page-tag {
  display: inline-flex;
  align-items: center; gap: 8px;
  background: rgba(201,168,76,0.12);
  border: 1px solid rgba(201,168,76,0.3);
  padding: 6px 16px; border-radius: 40px;
  font-size: 11px; font-weight: 600;
  letter-spacing: 2px; text-transform: uppercase;
  color: var(--gold);
  margin-bottom: 24px;
}
.page-hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(40px, 6vw, 72px);
  font-weight: 900;
  line-height: 1.1;
  margin-bottom: 20px;
}
.page-hero h1 em {
  font-style: normal;
  background: linear-gradient(135deg, var(--gold), var(--gold-light));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.page-hero p {
  font-size: 18px; color: rgba(255,255,255,0.6);
  max-width: 580px; line-height: 1.7; font-weight: 300;
  margin-bottom: 40px;
}

/* BUTTONS */
.btn-primary {
  display: inline-flex; align-items: center; gap: 10px;
  background: linear-gradient(135deg, var(--gold), var(--gold-light));
  color: var(--dark);
  padding: 16px 36px; border-radius: 50px;
  font-weight: 700; font-size: 15px;
  text-decoration: none;
  transition: transform .25s, box-shadow .25s;
  box-shadow: 0 8px 32px rgba(201,168,76,0.35);
}
.btn-primary:hover { transform: translateY(-3px); box-shadow: 0 16px 40px rgba(201,168,76,0.45); }
.btn-secondary {
  display: inline-flex; align-items: center; gap: 10px;
  border: 1px solid rgba(201,168,76,0.4);
  color: var(--gold);
  padding: 16px 36px; border-radius: 50px;
  font-weight: 600; font-size: 15px;
  text-decoration: none;
  transition: background .25s;
}
.btn-secondary:hover { background: rgba(201,168,76,0.1); }

/* VIDEO */
.section-wrap { max-width: 1200px; margin: 0 auto; padding: 80px 60px; }
.section-tag { font-size: 11px; font-weight: 600; letter-spacing: 3px; text-transform: uppercase; color: var(--gold); margin-bottom: 14px; }
.section-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(28px, 4vw, 46px);
  font-weight: 700; line-height: 1.15; margin-bottom: 16px;
}
.section-title em {
  font-style: normal;
  background: linear-gradient(135deg, var(--gold), var(--gold-light));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.section-sub { font-size: 16px; color: rgba(255,255,255,0.55); line-height: 1.7; max-width: 560px; }
.video-wrap {
  margin-top: 50px;
  position: relative;
  border-radius: 24px;
  overflow: hidden;
  border: 1px solid rgba(201,168,76,0.2);
  box-shadow: 0 40px 80px rgba(0,0,0,0.6);
  background: #000;
}
.video-wrap::before { content:''; display:block; padding-top:56.25%; }
.video-wrap iframe { position:absolute; inset:0; width:100%; height:100%; border:0; }

/* BENEFITS */
.benefits-section { background: var(--dark2); }
.benefits-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
  margin-top: 50px;
}
.benefit-card {
  display: flex;
  gap: 20px;
  align-items: flex-start;
  background: var(--dark3);
  border: 1px solid rgba(201,168,76,0.1);
  border-radius: 20px;
  padding: 28px;
  transition: border-color .3s, transform .3s;
}
.benefit-card:hover { border-color: rgba(201,168,76,0.3); transform: translateY(-4px); }
.benefit-icon {
  width: 52px; height: 52px;
  background: rgba(201,168,76,0.12);
  border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  font-size: 22px; flex-shrink: 0;
}
.benefit-text h3 { font-family: 'Playfair Display', serif; font-size: 18px; margin-bottom: 8px; }
.benefit-text p { font-size: 14px; color: rgba(255,255,255,0.55); line-height: 1.6; }

/* TESTIMONIAL / HIGHLIGHT */
.highlight-section {
  background: linear-gradient(135deg, #1a1408, #0d0d0d);
  border-top: 1px solid rgba(201,168,76,0.12);
  border-bottom: 1px solid rgba(201,168,76,0.12);
}
.highlight-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
  margin-top: 0;
}
.highlight-numbers { display: flex; flex-direction: column; gap: 32px; }
.hl-num {
  display: flex; align-items: center; gap: 20px;
  padding: 24px 28px;
  background: rgba(201,168,76,0.06);
  border: 1px solid rgba(201,168,76,0.15);
  border-radius: 16px;
}
.hl-val {
  font-family: 'Playfair Display', serif;
  font-size: 42px; font-weight: 700;
  color: var(--gold);
  line-height: 1;
  min-width: 120px;
}
.hl-desc { font-size: 14px; color: rgba(255,255,255,0.65); line-height: 1.6; }
.highlight-text { padding: 20px 0; }

/* CTA */
.cta-section {
  background: radial-gradient(ellipse at center, rgba(201,168,76,0.08) 0%, transparent 70%),
              var(--dark);
  text-align: center;
  padding: 100px 40px;
  border-top: 1px solid rgba(201,168,76,0.1);
}
.cta-inner { max-width: 700px; margin: 0 auto; }
.cta-section h2 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(32px, 4vw, 52px);
  font-weight: 700; margin-bottom: 16px;
}
.cta-section p { font-size: 17px; color: rgba(255,255,255,0.6); margin-bottom: 40px; line-height: 1.7; }
.cta-badges { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-bottom: 40px; }
.badge {
  background: rgba(201,168,76,0.1);
  border: 1px solid rgba(201,168,76,0.25);
  color: var(--gold);
  padding: 8px 18px; border-radius: 40px;
  font-size: 12px; font-weight: 600; letter-spacing: 1px;
}

/* FOOTER */
footer { background: #080808; border-top: 1px solid rgba(255,255,255,0.06); padding: 60px; }
.footer-inner {
  max-width: 1200px; margin: 0 auto;
  display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 60px;
}
.footer-brand .nav-logo { display: block; margin-bottom: 16px; font-size: 24px; }
.footer-brand p { font-size: 14px; color: var(--gray); line-height: 1.7; max-width: 300px; }
.footer-col h4 { font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: var(--gold); margin-bottom: 20px; }
.footer-col ul { list-style: none; }
.footer-col li { margin-bottom: 12px; }
.footer-col a { color: rgba(255,255,255,0.55); text-decoration: none; font-size: 14px; transition: color .2s; }
.footer-col a:hover { color: var(--white); }
.footer-bottom {
  max-width: 1200px; margin: 40px auto 0;
  padding-top: 24px;
  border-top: 1px solid rgba(255,255,255,0.06);
  display: flex; justify-content: space-between; align-items: center;
  font-size: 12px; color: rgba(255,255,255,0.3);
}

/* WA FLOAT */
.whatsapp-float { position:fixed; bottom:28px; right:28px; z-index:9999; display:flex; align-items:center; gap:10px; text-decoration:none; }
.wa-bubble { background:#fff; color:#333; padding:8px 16px; border-radius:20px; font-size:13px; font-weight:600; box-shadow:0 4px 20px rgba(0,0,0,0.2); white-space:nowrap; animation:waBubblePop .4s ease 1.2s both; opacity:0; }
.wa-btn { width:62px; height:62px; background:#25D366; border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 20px rgba(37,211,102,0.5); animation:waPulse 2s ease-in-out infinite; position:relative; flex-shrink:0; }
.wa-btn svg { width:32px; height:32px; fill:#fff; }
.wa-badge { position:absolute; top:-3px; right:-3px; background:#e53935; color:#fff; font-size:11px; font-weight:700; width:20px; height:20px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid #fff; animation:waBadgeBounce 1s ease 2s infinite; }
@keyframes waPulse { 0%{box-shadow:0 0 0 0 rgba(37,211,102,.55)} 70%{box-shadow:0 0 0 18px rgba(37,211,102,0)} 100%{box-shadow:0 0 0 0 rgba(37,211,102,0)} }
@keyframes waBubblePop { from{opacity:0;transform:scale(.8) translateX(10px)} to{opacity:1;transform:scale(1) translateX(0)} }
@keyframes waBadgeBounce { 0%,100%{transform:scale(1)} 50%{transform:scale(1.3)} }

@media (max-width: 900px) {
  nav { padding: 18px 24px; }
  .nav-links { display: none; }
  .hamburger { display: flex; }
  .page-hero { padding: 120px 24px 60px; }
  .section-wrap { padding: 60px 24px; }
  .benefits-grid { grid-template-columns: 1fr; }
  .highlight-grid { grid-template-columns: 1fr; }
  .footer-inner { grid-template-columns: 1fr; gap: 40px; }
  footer { padding: 48px 24px; }
  .footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
  .wa-bubble { display: none; }
  .whatsapp-float { bottom: 18px; right: 16px; }
}
</style>
</head>
<body>

<nav>
  <a href="index.php" class="nav-logo">Amei <span>Cosméticos</span></a>
  <ul class="nav-links">
    <li><a href="index.php">Início</a></li>
    <li><a href="revendedor.php" class="active">Seja Consultor(a)</a></li>
    <li><a href="cadastro.php" class="nav-cta">Cadastre-se Grátis</a></li>
  </ul>
  <div class="hamburger" onclick="toggleMenu()">
    <span></span><span></span><span></span>
  </div>
</nav>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="page-hero-bg"></div>
  <div class="page-hero-content">
    <div class="breadcrumb">
      <a href="index.php">Início</a>
      <span>›</span>
      <span>Seja Consultor(a)</span>
    </div>
    <div class="page-tag">Oportunidade de negócio</div>
    <h1>Ganhe dinheiro<br/>revendendo os<br/><em>melhores perfumes</em></h1>
    <p>Trabalhe no seu horário, de onde quiser. Sem SPC e Serasa. Lucro real de 100% ou mais direto da fábrica.</p>
    <div style="display:flex;gap:16px;flex-wrap:wrap;">
      <a href="cadastro.php" class="btn-primary">
        Quero me cadastrar
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
      <a href="#video" class="btn-secondary">Ver apresentação</a>
    </div>
  </div>
</div>

<!-- VIDEO -->
<div id="video">
  <div class="section-wrap">
    <div class="section-tag">Apresentação oficial</div>
    <h2 class="section-title">Assista e se apaixone pela<br/><em>oportunidade</em></h2>
    <p class="section-sub">Entenda como funciona o modelo de negócio e por que a Amei Cosméticos é a escolha certa para você.</p>
    <div class="video-wrap">
      <iframe src="https://www.youtube.com/embed/WGZjOSScoz0" title="Seja Consultor Amei Cosméticos" allowfullscreen loading="lazy"></iframe>
    </div>
  </div>
</div>

<!-- BENEFITS -->
<div class="benefits-section">
  <div class="section-wrap">
    <div class="section-tag">Vantagens exclusivas</div>
    <h2 class="section-title">Por que escolher a<br/><em>Amei Cosméticos</em>?</h2>
    <div class="benefits-grid">
      <div class="benefit-card">
        <div class="benefit-icon">💰</div>
        <div class="benefit-text">
          <h3>100% de Lucro ou Mais</h3>
          <p>Compre direto da fábrica pelo menor preço do mercado e revenda com margem altíssima.</p>
        </div>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">🚫</div>
        <div class="benefit-text">
          <h3>Sem SPC e Serasa</h3>
          <p>Não consultamos o seu CPF. Qualquer pessoa pode começar, independente do histórico financeiro.</p>
        </div>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">🏭</div>
        <div class="benefit-text">
          <h3>Direto da Fábrica</h3>
          <p>Sem intermediários. Produtos saem direto da nossa fábrica para você, com qualidade garantida.</p>
        </div>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">🕐</div>
        <div class="benefit-text">
          <h3>Trabalhe no Seu Ritmo</h3>
          <p>Seja seu próprio chefe. Defina seus horários e trabalhe de onde quiser, como quiser.</p>
        </div>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">💎</div>
        <div class="benefit-text">
          <h3>Produtos Premium</h3>
          <p>Perfumes de alta fixação, cosméticos de qualidade. Produtos que se vendem sozinhos.</p>
        </div>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">🎓</div>
        <div class="benefit-text">
          <h3>Suporte e Treinamento</h3>
          <p>Você não estará sozinho. Seu patrocinador e a equipe Amei estão sempre prontos para ajudar.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- NUMBERS -->
<div class="highlight-section">
  <div class="section-wrap" style="padding-top:80px;padding-bottom:80px;">
    <div class="highlight-grid">
      <div class="highlight-text">
        <div class="section-tag">Números reais</div>
        <h2 class="section-title">Resultados que<br/><em>falam por si</em></h2>
        <p class="section-sub" style="margin-bottom:32px;">A Amei Cosméticos já transformou a vida de milhares de brasileiros que decidiram apostar nessa oportunidade única.</p>
        <a href="cadastro.php" class="btn-primary">
          Quero fazer parte
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
      <div class="highlight-numbers">
        <div class="hl-num">
          <div class="hl-val">100%</div>
          <div class="hl-desc">De lucro ou mais em cada produto revendido</div>
        </div>
        <div class="hl-num">
          <div class="hl-val">+10</div>
          <div class="hl-desc">Anos no mercado de perfumaria fina nacional</div>
        </div>
        <div class="hl-num">
          <div class="hl-val">0</div>
          <div class="hl-desc">Consultas ao SPC e Serasa. Totalmente acessível</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- CTA -->
<div class="cta-section">
  <div class="cta-inner">
    <div class="section-tag" style="margin-bottom:14px;">Comece agora</div>
    <h2>Sua <em style="background:linear-gradient(135deg,#c9a84c,#e8c97a);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">independência</em><br/>financeira começa aqui</h2>
    <p>Cadastro gratuito, simples e rápido. Em minutos você já pode começar a revender e lucrar.</p>
    <div class="cta-badges">
      <span class="badge">✓ Cadastro grátis</span>
      <span class="badge">✓ Sem SPC & Serasa</span>
      <span class="badge">✓ 100%+ de Lucro</span>
    </div>
    <a href="cadastro.php" class="btn-primary" style="font-size:16px;padding:18px 48px;">
      Fazer meu cadastro agora
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
  </div>
</div>

<!-- FOOTER -->
<footer>
  <div class="footer-inner">
    <div class="footer-brand">
      <a href="index.php" class="nav-logo">Amei <span>Cosméticos</span></a>
      <p>Fábrica de perfumaria fina e cosméticos de alta performance. Direto ao revendedor, sem intermediários.</p>
    </div>
    <div class="footer-col">
      <h4>Navegação</h4>
      <ul>
        <li><a href="index.php">Início</a></li>
        <li><a href="revendedor.php">Seja Consultor(a)</a></li>
        <li><a href="cadastro.php">Cadastro</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Contato</h4>
      <ul>
        <li><a href="https://wa.me/5561982290919" target="_blank" rel="noopener">WhatsApp: (61) 98229-0919</a></li>
        <li><a href="mailto:oseiascw@gmail.com">oseiascw@gmail.com</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© 2024 Amei Cosméticos. Todos os direitos reservados.</span>
    <span>Perfumaria Fina Direto da Fábrica</span>
  </div>
</footer>

<!-- WA FLOAT -->
<a class="whatsapp-float" href="https://wa.me/5561982290919?text=Ol%C3%A1%2C%20vim%20pelo%20site%20da%20Amei%20Cosm%C3%A9ticos%20e%20quero%20saber%20mais!" target="_blank" rel="noopener" aria-label="WhatsApp">
  <span class="wa-bubble">Olá, como posso ajudar? 👋</span>
  <span class="wa-btn">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M16 0C7.163 0 0 7.163 0 16c0 2.822.736 5.469 2.027 7.77L0 32l8.437-2.007A15.934 15.934 0 0 0 16 32c8.837 0 16-7.163 16-16S24.837 0 16 0zm0 29.333a13.28 13.28 0 0 1-6.771-1.845l-.485-.288-5.007 1.192 1.23-4.882-.317-.502A13.267 13.267 0 0 1 2.667 16C2.667 8.636 8.636 2.667 16 2.667S29.333 8.636 29.333 16 23.364 29.333 16 29.333zm7.27-9.986c-.398-.199-2.354-1.162-2.72-1.294-.365-.133-.631-.199-.897.199-.265.398-1.029 1.294-1.261 1.56-.232.265-.465.298-.863.1-.398-.199-1.681-.62-3.202-1.977-1.183-1.056-1.982-2.361-2.214-2.759-.232-.398-.025-.613.174-.811.179-.179.398-.465.597-.698.199-.232.265-.398.398-.664.133-.265.066-.498-.033-.697-.1-.199-.897-2.162-1.229-2.96-.324-.777-.653-.672-.897-.684l-.764-.013c-.265 0-.697.1-.1062.498-.365.398-1.395 1.362-1.395 3.322s1.428 3.853 1.627 4.119c.199.265 2.811 4.291 6.812 6.021.952.411 1.695.657 2.274.841.955.304 1.825.261 2.513.158.767-.114 2.354-.962 2.686-1.891.332-.93.332-1.727.232-1.891-.099-.165-.365-.265-.763-.464z"/></svg>
    <span class="wa-badge">1</span>
  </span>
</a>

<script>
function toggleMenu() {
  const links = document.querySelector('.nav-links');
  links.style.display = links.style.display === 'flex' ? 'none' : 'flex';
  links.style.flexDirection = 'column';
  links.style.position = 'fixed';
  links.style.top = '70px';
  links.style.right = '0';
  links.style.background = 'rgba(13,13,13,0.97)';
  links.style.padding = '24px 32px';
  links.style.backdropFilter = 'blur(20px)';
  links.style.border = '1px solid rgba(201,168,76,0.15)';
  links.style.borderRadius = '0 0 0 16px';
}
</script>
</body>
</html>
