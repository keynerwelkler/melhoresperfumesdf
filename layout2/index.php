<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Amei Cosméticos | Os Melhores Perfumes Importados</title>
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
  --accent: #c9a84c;
}

html { scroll-behavior: smooth; }

body {
  font-family: 'Inter', sans-serif;
  background: var(--dark);
  color: var(--white);
  overflow-x: hidden;
}

/* ── NAVBAR ── */
nav {
  position: fixed;
  top: 0; left: 0; right: 0;
  z-index: 1000;
  padding: 20px 60px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  transition: background .3s, backdrop-filter .3s;
}
nav.scrolled {
  background: rgba(13,13,13,0.92);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(201,168,76,0.15);
}
.nav-logo {
  font-family: 'Playfair Display', serif;
  font-size: 22px;
  font-weight: 700;
  letter-spacing: 2px;
  color: var(--gold);
  text-decoration: none;
}
.nav-logo span { color: var(--white); }
.nav-links { display: flex; gap: 36px; list-style: none; }
.nav-links a {
  color: rgba(255,255,255,0.75);
  text-decoration: none;
  font-size: 13px;
  font-weight: 500;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  transition: color .3s;
}
.nav-links a:hover, .nav-links a.active { color: var(--gold); }
.nav-cta {
  background: var(--gold);
  color: var(--dark) !important;
  padding: 10px 24px;
  border-radius: 40px;
  font-weight: 700 !important;
  transition: background .3s, transform .2s !important;
}
.nav-cta:hover { background: var(--gold-light) !important; transform: translateY(-2px); }
.hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; }
.hamburger span { width: 24px; height: 2px; background: var(--white); border-radius: 2px; transition: .3s; }

/* ── HERO ── */
.hero {
  min-height: 100vh;
  position: relative;
  display: flex;
  align-items: center;
  overflow: hidden;
}
.hero-bg {
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at 70% 50%, rgba(201,168,76,0.12) 0%, transparent 60%),
              radial-gradient(ellipse at 20% 80%, rgba(201,168,76,0.06) 0%, transparent 50%),
              linear-gradient(135deg, #0d0d0d 0%, #1a1408 50%, #0d0d0d 100%);
}
.hero-particles {
  position: absolute;
  inset: 0;
  background-image:
    radial-gradient(circle, rgba(201,168,76,0.4) 1px, transparent 1px);
  background-size: 80px 80px;
  opacity: 0.15;
  animation: particlesDrift 20s linear infinite;
}
@keyframes particlesDrift {
  from { transform: translateY(0); }
  to   { transform: translateY(-80px); }
}
.hero-content {
  position: relative;
  z-index: 2;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 60px;
  padding-top: 100px;
}
.hero-tag {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(201,168,76,0.12);
  border: 1px solid rgba(201,168,76,0.3);
  padding: 6px 16px;
  border-radius: 40px;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--gold);
  margin-bottom: 28px;
  animation: fadeInUp .8s ease both;
}
.hero-tag::before {
  content: '';
  width: 6px; height: 6px;
  background: var(--gold);
  border-radius: 50%;
  animation: blink 1.5s ease-in-out infinite;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
.hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(48px, 7vw, 96px);
  font-weight: 900;
  line-height: 1.05;
  margin-bottom: 24px;
  animation: fadeInUp .8s .1s ease both;
}
.hero h1 em {
  font-style: normal;
  background: linear-gradient(135deg, var(--gold), var(--gold-light), var(--gold));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.hero-desc {
  font-size: 18px;
  color: rgba(255,255,255,0.65);
  max-width: 540px;
  line-height: 1.7;
  margin-bottom: 44px;
  font-weight: 300;
  animation: fadeInUp .8s .2s ease both;
}
.hero-actions {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
  animation: fadeInUp .8s .3s ease both;
}
.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: linear-gradient(135deg, var(--gold), var(--gold-light));
  color: var(--dark);
  padding: 16px 36px;
  border-radius: 50px;
  font-weight: 700;
  font-size: 15px;
  text-decoration: none;
  letter-spacing: .5px;
  transition: transform .25s, box-shadow .25s;
  box-shadow: 0 8px 32px rgba(201,168,76,0.35);
}
.btn-primary:hover { transform: translateY(-3px); box-shadow: 0 16px 40px rgba(201,168,76,0.45); }
.btn-secondary {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  border: 1px solid rgba(201,168,76,0.4);
  color: var(--gold);
  padding: 16px 36px;
  border-radius: 50px;
  font-weight: 600;
  font-size: 15px;
  text-decoration: none;
  transition: background .25s, border-color .25s;
}
.btn-secondary:hover { background: rgba(201,168,76,0.1); border-color: var(--gold); }
.hero-stats {
  display: flex;
  gap: 48px;
  margin-top: 64px;
  padding-top: 40px;
  border-top: 1px solid rgba(255,255,255,0.08);
  animation: fadeInUp .8s .4s ease both;
}
.stat-item { text-align: left; }
.stat-num {
  font-family: 'Playfair Display', serif;
  font-size: 36px;
  font-weight: 700;
  color: var(--gold);
  line-height: 1;
}
.stat-label {
  font-size: 12px;
  color: var(--gray);
  letter-spacing: 1px;
  text-transform: uppercase;
  margin-top: 6px;
}
.hero-image-col {
  position: absolute;
  right: 0; top: 0; bottom: 0;
  width: 45%;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}
.hero-circle {
  width: 600px;
  height: 600px;
  border-radius: 50%;
  background: radial-gradient(circle at 40% 40%, rgba(201,168,76,0.18), rgba(201,168,76,0.04) 60%, transparent);
  border: 1px solid rgba(201,168,76,0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  animation: floatCircle 6s ease-in-out infinite;
}
@keyframes floatCircle {
  0%,100% { transform: translateY(0) rotate(0deg); }
  50%      { transform: translateY(-20px) rotate(3deg); }
}
.hero-circle-inner {
  width: 420px;
  height: 420px;
  border-radius: 50%;
  background: radial-gradient(circle at 35% 35%, rgba(201,168,76,0.25), transparent 70%);
  border: 1px solid rgba(201,168,76,0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Playfair Display', serif;
  font-size: 80px;
  text-align: center;
  line-height: 1;
}

/* ── SECTION COMMON ── */
section { padding: 100px 60px; max-width: 1200px; margin: 0 auto; }
.section-tag {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--gold);
  margin-bottom: 14px;
}
.section-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(32px, 4vw, 52px);
  font-weight: 700;
  line-height: 1.15;
  margin-bottom: 20px;
}
.section-title em {
  font-style: normal;
  background: linear-gradient(135deg, var(--gold), var(--gold-light));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.section-sub {
  font-size: 16px;
  color: rgba(255,255,255,0.55);
  max-width: 520px;
  line-height: 1.7;
}

/* ── FEATURES ── */
.features-section { background: var(--dark2); padding: 100px 0; }
.features-inner { max-width: 1200px; margin: 0 auto; padding: 0 60px; }
.features-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  margin-top: 60px;
}
.feature-card {
  background: var(--dark3);
  border: 1px solid rgba(201,168,76,0.1);
  border-radius: 20px;
  padding: 40px 32px;
  transition: border-color .3s, transform .3s;
  position: relative;
  overflow: hidden;
}
.feature-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--gold), transparent);
  opacity: 0;
  transition: opacity .3s;
}
.feature-card:hover { border-color: rgba(201,168,76,0.3); transform: translateY(-6px); }
.feature-card:hover::before { opacity: 1; }
.feature-icon {
  width: 56px; height: 56px;
  background: rgba(201,168,76,0.12);
  border-radius: 16px;
  display: flex; align-items: center; justify-content: center;
  font-size: 24px;
  margin-bottom: 24px;
}
.feature-card h3 {
  font-family: 'Playfair Display', serif;
  font-size: 22px;
  margin-bottom: 12px;
}
.feature-card p { font-size: 14px; color: rgba(255,255,255,0.55); line-height: 1.7; }

/* ── VIDEO SECTION ── */
.video-section { padding: 80px 0; }
.video-inner { max-width: 1200px; margin: 0 auto; padding: 0 60px; }
.video-wrap {
  margin-top: 50px;
  position: relative;
  border-radius: 24px;
  overflow: hidden;
  border: 1px solid rgba(201,168,76,0.2);
  box-shadow: 0 40px 80px rgba(0,0,0,0.6), 0 0 0 1px rgba(201,168,76,0.1);
  background: #000;
}
.video-wrap::before {
  content: '';
  display: block;
  padding-top: 56.25%;
}
.video-wrap iframe {
  position: absolute;
  inset: 0;
  width: 100%; height: 100%;
  border: 0;
}

/* ── HOW IT WORKS ── */
.how-section { background: var(--dark2); padding: 100px 0; }
.how-inner { max-width: 1200px; margin: 0 auto; padding: 0 60px; }
.how-steps {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 0;
  margin-top: 60px;
  position: relative;
}
.how-steps::before {
  content: '';
  position: absolute;
  top: 36px; left: 16.66%; right: 16.66%;
  height: 1px;
  background: linear-gradient(90deg, var(--gold), rgba(201,168,76,0.2), var(--gold));
}
.how-step { text-align: center; padding: 0 24px; }
.step-num {
  width: 72px; height: 72px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--gold), var(--gold-light));
  color: var(--dark);
  font-family: 'Playfair Display', serif;
  font-size: 26px;
  font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 28px;
  position: relative;
  z-index: 1;
  box-shadow: 0 8px 24px rgba(201,168,76,0.4);
}
.how-step h3 {
  font-family: 'Playfair Display', serif;
  font-size: 20px;
  margin-bottom: 12px;
}
.how-step p { font-size: 14px; color: rgba(255,255,255,0.55); line-height: 1.7; }

/* ── CTA BANNER ── */
.cta-section {
  background: linear-gradient(135deg, #1a1408 0%, #0d0d0d 50%, #1a1408 100%);
  border-top: 1px solid rgba(201,168,76,0.15);
  border-bottom: 1px solid rgba(201,168,76,0.15);
  padding: 100px 0;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.cta-section::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at center, rgba(201,168,76,0.08) 0%, transparent 70%);
}
.cta-inner { max-width: 700px; margin: 0 auto; padding: 0 40px; position: relative; z-index: 1; }
.cta-section h2 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(32px, 4vw, 52px);
  font-weight: 700;
  margin-bottom: 16px;
}
.cta-section p { font-size: 17px; color: rgba(255,255,255,0.6); margin-bottom: 40px; line-height: 1.7; }
.cta-badges {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
  margin-bottom: 40px;
}
.badge {
  background: rgba(201,168,76,0.1);
  border: 1px solid rgba(201,168,76,0.25);
  color: var(--gold);
  padding: 8px 18px;
  border-radius: 40px;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 1px;
}

/* ── FOOTER ── */
footer {
  background: #080808;
  border-top: 1px solid rgba(255,255,255,0.06);
  padding: 60px;
}
.footer-inner {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 2fr 1fr 1fr;
  gap: 60px;
}
.footer-brand .nav-logo { display: block; margin-bottom: 16px; font-size: 24px; }
.footer-brand p { font-size: 14px; color: var(--gray); line-height: 1.7; max-width: 300px; }
.footer-col h4 {
  font-size: 11px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--gold);
  margin-bottom: 20px;
}
.footer-col ul { list-style: none; }
.footer-col li { margin-bottom: 12px; }
.footer-col a { color: rgba(255,255,255,0.55); text-decoration: none; font-size: 14px; transition: color .2s; }
.footer-col a:hover { color: var(--white); }
.footer-bottom {
  max-width: 1200px;
  margin: 40px auto 0;
  padding-top: 24px;
  border-top: 1px solid rgba(255,255,255,0.06);
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 12px;
  color: rgba(255,255,255,0.3);
}

/* ── WHATSAPP FLOAT ── */
.whatsapp-float {
  position: fixed;
  bottom: 28px; right: 28px;
  z-index: 9999;
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
}
.wa-bubble {
  background: #fff;
  color: #333;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
  box-shadow: 0 4px 20px rgba(0,0,0,0.2);
  white-space: nowrap;
  animation: waBubblePop .4s ease 1.2s both;
  opacity: 0;
}
.wa-btn {
  width: 62px; height: 62px;
  background: #25D366;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 4px 20px rgba(37,211,102,0.5);
  animation: waPulse 2s ease-in-out infinite;
  position: relative;
  flex-shrink: 0;
}
.wa-btn svg { width: 32px; height: 32px; fill: #fff; }
.wa-badge {
  position: absolute;
  top: -3px; right: -3px;
  background: #e53935;
  color: #fff;
  font-size: 11px; font-weight: 700;
  width: 20px; height: 20px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  border: 2px solid #fff;
  animation: waBadgeBounce 1s ease 2s infinite;
}
@keyframes waPulse {
  0%   { box-shadow: 0 0 0 0 rgba(37,211,102,0.55); }
  70%  { box-shadow: 0 0 0 18px rgba(37,211,102,0); }
  100% { box-shadow: 0 0 0 0 rgba(37,211,102,0); }
}
@keyframes waBubblePop {
  from { opacity:0; transform:scale(.8) translateX(10px); }
  to   { opacity:1; transform:scale(1) translateX(0); }
}
@keyframes waBadgeBounce {
  0%,100%{ transform:scale(1); } 50%{ transform:scale(1.3); }
}
@keyframes fadeInUp {
  from { opacity:0; transform:translateY(30px); }
  to   { opacity:1; transform:translateY(0); }
}

/* ── MOBILE ── */
@media (max-width: 900px) {
  nav { padding: 18px 24px; }
  .nav-links { display: none; }
  .hamburger { display: flex; }
  .hero-content { padding: 0 24px; padding-top: 110px; }
  .hero-image-col { display: none; }
  .hero-stats { gap: 28px; flex-wrap: wrap; }
  section { padding: 70px 24px; }
  .features-inner, .how-inner, .video-inner { padding: 0 24px; }
  .features-grid { grid-template-columns: 1fr; }
  .how-steps { grid-template-columns: 1fr; }
  .how-steps::before { display: none; }
  .footer-inner { grid-template-columns: 1fr; gap: 40px; }
  footer { padding: 48px 24px; }
  .footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
  .wa-bubble { display: none; }
  .whatsapp-float { bottom: 18px; right: 16px; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav id="navbar">
  <a href="index.php" class="nav-logo">Amei <span>Cosméticos</span></a>
  <ul class="nav-links">
    <li><a href="index.php" class="active">Início</a></li>
    <li><a href="revendedor.php">Seja Consultor(a)</a></li>
    <li><a href="cadastro.php" class="nav-cta">Cadastre-se Grátis</a></li>
  </ul>
  <div class="hamburger" onclick="toggleMenu()">
    <span></span><span></span><span></span>
  </div>
</nav>

<!-- HERO -->
<div class="hero">
  <div class="hero-bg"></div>
  <div class="hero-particles"></div>
  <div class="hero-content">
    <div class="hero-tag">Perfumaria Fina Direto da Fábrica</div>
    <h1>Os <em>Melhores<br/>Perfumes</em><br/>Importados</h1>
    <p class="hero-desc">Alta fixação, elegância e qualidade premium. Revenda com até 100% de lucro. Sem consulta ao SPC e Serasa.</p>
    <div class="hero-actions">
      <a href="cadastro.php" class="btn-primary">
        Quero ser Consultor(a)
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
      <a href="revendedor.php" class="btn-secondary">Saiba mais</a>
    </div>
    <div class="hero-stats">
      <div class="stat-item">
        <div class="stat-num">100%</div>
        <div class="stat-label">De lucro ou mais</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">Sem</div>
        <div class="stat-label">Consulta SPC/Serasa</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">+10</div>
        <div class="stat-label">Anos no mercado</div>
      </div>
    </div>
  </div>
  <div class="hero-image-col">
    <div class="hero-circle">
      <div class="hero-circle-inner">🌸</div>
    </div>
  </div>
</div>

<!-- FEATURES -->
<div class="features-section">
  <div class="features-inner">
    <div class="section-tag">Por que a Amei Cosméticos?</div>
    <h2 class="section-title">Tudo que você precisa para<br/><em>começar hoje</em></h2>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon">💎</div>
        <h3>Qualidade Premium</h3>
        <p>Perfumes de alta performance com fixação superior. Fragrâncias inspiradas nos grandes lançamentos internacionais.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">💰</div>
        <h3>Lucro Garantido</h3>
        <p>Compre direto da fábrica e revenda com 100% ou mais de lucro. Seja no atacado ou no varejo.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🚀</div>
        <h3>Comece Imediatamente</h3>
        <p>Cadastro simples, sem burocracia. Sem consulta ao SPC e Serasa. Qualquer pessoa pode começar.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🎯</div>
        <h3>Suporte Completo</h3>
        <p>Acompanhamento e suporte do seu patrocinador para você crescer no negócio com segurança.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">📦</div>
        <h3>Portfólio Completo</h3>
        <p>Perfumes 17ml, 30ml e 100ml. Cosméticos, linha capilar e muito mais para diversificar suas vendas.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🌟</div>
        <h3>Renda Extra Real</h3>
        <p>Trabalhe no seu próprio horário, de onde quiser. Centenas de consultores transformando suas vidas.</p>
      </div>
    </div>
  </div>
</div>

<!-- VIDEO -->
<div class="video-section">
  <div class="video-inner">
    <div class="section-tag">Apresentação</div>
    <h2 class="section-title">Conheça a <em>Amei Cosméticos</em></h2>
    <p class="section-sub">Assista ao vídeo e entenda como funciona o modelo de negócio que já transformou a vida de milhares de brasileiros.</p>
    <div class="video-wrap">
      <iframe src="https://www.youtube.com/embed/WGZjOSScoz0" title="Amei Cosméticos" allowfullscreen loading="lazy"></iframe>
    </div>
  </div>
</div>

<!-- HOW IT WORKS -->
<div class="how-section">
  <div class="how-inner">
    <div class="section-tag">Como funciona</div>
    <h2 class="section-title">3 passos para <em>começar</em></h2>
    <div class="how-steps">
      <div class="how-step">
        <div class="step-num">1</div>
        <h3>Faça seu Cadastro</h3>
        <p>Cadastro rápido e gratuito. Sem SPC e Serasa. Você começa em minutos.</p>
      </div>
      <div class="how-step">
        <div class="step-num">2</div>
        <h3>Receba os Produtos</h3>
        <p>Compre direto da fábrica com os melhores preços e receba onde estiver.</p>
      </div>
      <div class="how-step">
        <div class="step-num">3</div>
        <h3>Venda e Lucre</h3>
        <p>Revenda com 100% ou mais de lucro. Trabalhe no seu ritmo e horário.</p>
      </div>
    </div>
  </div>
</div>

<!-- CTA -->
<div class="cta-section">
  <div class="cta-inner">
    <div class="section-tag" style="text-align:center">Oportunidade única</div>
    <h2>Pronto para <em style="background:linear-gradient(135deg,#c9a84c,#e8c97a);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">transformar</em><br/>sua vida?</h2>
    <p>Junte-se a milhares de consultores que já conquistaram sua independência financeira com a Amei Cosméticos.</p>
    <div class="cta-badges">
      <span class="badge">✓ Sem SPC & Serasa</span>
      <span class="badge">✓ 100%+ de Lucro</span>
      <span class="badge">✓ Suporte completo</span>
      <span class="badge">✓ Cadastro grátis</span>
    </div>
    <a href="cadastro.php" class="btn-primary" style="font-size:16px;padding:18px 48px;">
      Começar agora
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

<!-- WHATSAPP FLOAT -->
<a class="whatsapp-float" href="https://wa.me/5561982290919?text=Ol%C3%A1%2C%20vim%20pelo%20site%20da%20Amei%20Cosm%C3%A9ticos%20e%20quero%20saber%20mais!" target="_blank" rel="noopener" aria-label="WhatsApp">
  <span class="wa-bubble">Olá, como posso ajudar? 👋</span>
  <span class="wa-btn">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M16 0C7.163 0 0 7.163 0 16c0 2.822.736 5.469 2.027 7.77L0 32l8.437-2.007A15.934 15.934 0 0 0 16 32c8.837 0 16-7.163 16-16S24.837 0 16 0zm0 29.333a13.28 13.28 0 0 1-6.771-1.845l-.485-.288-5.007 1.192 1.23-4.882-.317-.502A13.267 13.267 0 0 1 2.667 16C2.667 8.636 8.636 2.667 16 2.667S29.333 8.636 29.333 16 23.364 29.333 16 29.333zm7.27-9.986c-.398-.199-2.354-1.162-2.72-1.294-.365-.133-.631-.199-.897.199-.265.398-1.029 1.294-1.261 1.56-.232.265-.465.298-.863.1-.398-.199-1.681-.62-3.202-1.977-1.183-1.056-1.982-2.361-2.214-2.759-.232-.398-.025-.613.174-.811.179-.179.398-.465.597-.698.199-.232.265-.398.398-.664.133-.265.066-.498-.033-.697-.1-.199-.897-2.162-1.229-2.96-.324-.777-.653-.672-.897-.684l-.764-.013c-.265 0-.697.1-.1062.498-.365.398-1.395 1.362-1.395 3.322s1.428 3.853 1.627 4.119c.199.265 2.811 4.291 6.812 6.021.952.411 1.695.657 2.274.841.955.304 1.825.261 2.513.158.767-.114 2.354-.962 2.686-1.891.332-.93.332-1.727.232-1.891-.099-.165-.365-.265-.763-.464z"/></svg>
    <span class="wa-badge">1</span>
  </span>
</a>

<script>
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 40);
});
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
