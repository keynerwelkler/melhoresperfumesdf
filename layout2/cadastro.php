<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Cadastro Gratuito | Amei Cosméticos</title>
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
  --red: #ef4444;
  --green: #22c55e;
  --border: rgba(255,255,255,0.1);
}
html { scroll-behavior: smooth; }
body { font-family: 'Inter', sans-serif; background: var(--dark); color: var(--white); overflow-x: hidden; }

/* NAV */
nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
  padding: 20px 60px;
  display: flex; align-items: center; justify-content: space-between;
  background: rgba(13,13,13,0.92);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(201,168,76,0.15);
}
.nav-logo { font-family:'Playfair Display',serif; font-size:22px; font-weight:700; letter-spacing:2px; color:var(--gold); text-decoration:none; }
.nav-logo span { color:var(--white); }
.nav-links { display:flex; gap:36px; list-style:none; }
.nav-links a { color:rgba(255,255,255,0.75); text-decoration:none; font-size:13px; font-weight:500; letter-spacing:1.5px; text-transform:uppercase; transition:color .3s; }
.nav-links a:hover, .nav-links a.active { color:var(--gold); }
.nav-cta { background:var(--gold) !important; color:var(--dark) !important; padding:10px 24px; border-radius:40px; font-weight:700 !important; }
.hamburger { display:none; flex-direction:column; gap:5px; cursor:pointer; }
.hamburger span { width:24px; height:2px; background:var(--white); border-radius:2px; }

/* LAYOUT */
.cadastro-layout {
  min-height: 100vh;
  display: grid;
  grid-template-columns: 1fr 1fr;
}

/* LEFT PANEL */
.cadastro-left {
  background: linear-gradient(160deg, #1a1408 0%, #0d0d0d 40%, #1a1408 100%);
  border-right: 1px solid rgba(201,168,76,0.12);
  padding: 140px 60px 80px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: relative;
  overflow: hidden;
}
.cadastro-left::before {
  content: '';
  position: absolute;
  top: -100px; right: -100px;
  width: 500px; height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(201,168,76,0.08) 0%, transparent 70%);
}
.left-tag {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(201,168,76,0.12);
  border: 1px solid rgba(201,168,76,0.3);
  padding: 6px 16px; border-radius: 40px;
  font-size: 11px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;
  color: var(--gold); margin-bottom: 28px;
  width: fit-content;
}
.cadastro-left h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(36px, 3.5vw, 52px);
  font-weight: 900; line-height: 1.1; margin-bottom: 20px;
}
.cadastro-left h1 em {
  font-style: normal;
  background: linear-gradient(135deg, var(--gold), var(--gold-light));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.cadastro-left p { font-size: 16px; color: rgba(255,255,255,0.6); line-height: 1.7; font-weight: 300; margin-bottom: 40px; }
.perks { display: flex; flex-direction: column; gap: 16px; }
.perk {
  display: flex; align-items: center; gap: 14px;
  background: rgba(201,168,76,0.06);
  border: 1px solid rgba(201,168,76,0.12);
  border-radius: 12px;
  padding: 14px 18px;
}
.perk-icon { font-size: 20px; flex-shrink: 0; }
.perk-text { font-size: 14px; color: rgba(255,255,255,0.8); font-weight: 500; }

/* RIGHT PANEL — FORM */
.cadastro-right {
  background: var(--dark2);
  padding: 120px 60px 80px;
  overflow-y: auto;
}
.form-header { margin-bottom: 36px; }
.form-header h2 {
  font-family: 'Playfair Display', serif;
  font-size: 28px; font-weight: 700; margin-bottom: 8px;
}
.form-header p { font-size: 14px; color: rgba(255,255,255,0.5); }
.form-step-label {
  font-size: 11px; letter-spacing: 2px; text-transform: uppercase;
  color: var(--gold); margin-bottom: 8px;
}

/* FORM FIELDS */
.form-group { margin-bottom: 20px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 0; }
label {
  display: block;
  font-size: 11px; font-weight: 600; letter-spacing: 1px;
  text-transform: uppercase; color: rgba(255,255,255,0.5);
  margin-bottom: 8px;
}
input, select {
  width: 100%;
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 12px;
  padding: 13px 16px;
  color: var(--white);
  font-family: 'Inter', sans-serif;
  font-size: 14px;
  transition: border-color .2s, background .2s;
  outline: none;
}
input:focus, select:focus {
  border-color: var(--gold);
  background: rgba(201,168,76,0.05);
}
input::placeholder { color: rgba(255,255,255,0.25); }
select option { background: var(--dark3); }
input[readonly] {
  color: var(--gold);
  background: rgba(201,168,76,0.06);
  border-color: rgba(201,168,76,0.2);
  cursor: not-allowed;
}

.section-divider {
  display: flex; align-items: center; gap: 12px;
  margin: 28px 0 20px;
}
.section-divider span {
  font-size: 11px; font-weight: 600; letter-spacing: 2px;
  text-transform: uppercase; color: var(--gold);
  white-space: nowrap;
}
.section-divider::before, .section-divider::after {
  content: ''; flex: 1; height: 1px;
  background: rgba(201,168,76,0.15);
}

.btn-submit {
  width: 100%;
  background: linear-gradient(135deg, var(--gold), var(--gold-light));
  color: var(--dark);
  border: none;
  padding: 16px;
  border-radius: 12px;
  font-family: 'Inter', sans-serif;
  font-size: 15px; font-weight: 700;
  letter-spacing: .5px;
  cursor: pointer;
  transition: transform .25s, box-shadow .25s;
  box-shadow: 0 8px 24px rgba(201,168,76,0.3);
  margin-top: 24px;
  display: flex; align-items: center; justify-content: center; gap: 10px;
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 16px 36px rgba(201,168,76,0.4); }

.form-terms {
  margin-top: 16px;
  font-size: 12px; color: rgba(255,255,255,0.35);
  text-align: center; line-height: 1.6;
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

/* FOOTER */
.mini-footer {
  background: #080808;
  border-top: 1px solid rgba(255,255,255,0.06);
  padding: 24px 60px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 12px;
  color: rgba(255,255,255,0.3);
}
.mini-footer a { color: rgba(201,168,76,0.7); text-decoration: none; }

@media (max-width: 900px) {
  nav { padding: 18px 24px; }
  .nav-links { display: none; }
  .hamburger { display: flex; }
  .cadastro-layout { grid-template-columns: 1fr; }
  .cadastro-left { padding: 100px 24px 50px; }
  .cadastro-right { padding: 40px 24px 60px; }
  .form-row { grid-template-columns: 1fr; }
  .mini-footer { flex-direction: column; gap: 8px; text-align: center; padding: 20px 24px; }
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
    <li><a href="revendedor.php">Seja Consultor(a)</a></li>
    <li><a href="cadastro.php" class="active nav-cta">Cadastro</a></li>
  </ul>
  <div class="hamburger" onclick="toggleMenu()">
    <span></span><span></span><span></span>
  </div>
</nav>

<div class="cadastro-layout">

  <!-- LEFT -->
  <div class="cadastro-left">
    <div class="left-tag">Cadastro gratuito</div>
    <h1>Comece sua<br/>jornada para a<br/><em>liberdade</em><br/>financeira</h1>
    <p>Preencha o formulário ao lado e faça parte da maior rede de revendedores de perfumaria fina do Brasil.</p>
    <div class="perks">
      <div class="perk">
        <span class="perk-icon">💎</span>
        <span class="perk-text">Perfumes importados premium</span>
      </div>
      <div class="perk">
        <span class="perk-icon">💰</span>
        <span class="perk-text">Lucro real de 100% ou mais</span>
      </div>
      <div class="perk">
        <span class="perk-icon">🚫</span>
        <span class="perk-text">Sem consulta SPC e Serasa</span>
      </div>
      <div class="perk">
        <span class="perk-icon">🕐</span>
        <span class="perk-text">Trabalhe no seu horário</span>
      </div>
      <div class="perk">
        <span class="perk-icon">🚀</span>
        <span class="perk-text">Suporte completo do patrocinador</span>
      </div>
    </div>
  </div>

  <!-- RIGHT -->
  <div class="cadastro-right">
    <div class="form-header">
      <div class="form-step-label">Formulário de cadastro</div>
      <h2>Criar minha conta</h2>
      <p>Preencha todos os campos para realizar seu cadastro</p>
    </div>

    <form>
      <!-- Patrocinador -->
      <div class="form-group">
        <label>Patrocinador</label>
        <input type="text" value="oseiaswgi" readonly/>
      </div>

      <!-- Tipo de pessoa -->
      <div class="form-group">
        <label>Tipo de pessoa</label>
        <select>
          <option value="">Selecione...</option>
          <option>Pessoa Física</option>
          <option>Pessoa Jurídica</option>
        </select>
      </div>

      <div class="section-divider"><span>Dados pessoais</span></div>

      <div class="form-group">
        <label>Nome completo</label>
        <input type="text" placeholder="Seu nome completo"/>
      </div>

      <div class="form-row form-group">
        <div>
          <label>Sexo</label>
          <select>
            <option value="">Selecione...</option>
            <option>Masculino</option>
            <option>Feminino</option>
            <option>Outro</option>
          </select>
        </div>
        <div>
          <label>Data de nascimento</label>
          <input type="text" placeholder="DD/MM/AAAA"/>
        </div>
      </div>

      <div class="form-row form-group">
        <div>
          <label>CPF</label>
          <input type="text" placeholder="000.000.000-00"/>
        </div>
        <div>
          <label>RG</label>
          <input type="text" placeholder="00.000.000-0"/>
        </div>
      </div>

      <div class="section-divider"><span>Endereço</span></div>

      <div class="form-row form-group">
        <div>
          <label>CEP</label>
          <input type="text" placeholder="00000-000"/>
        </div>
        <div>
          <label>UF</label>
          <input type="text" placeholder="SP"/>
        </div>
      </div>

      <div class="form-group">
        <label>Logradouro</label>
        <input type="text" placeholder="Rua, Avenida, etc."/>
      </div>

      <div class="form-row form-group">
        <div>
          <label>Número</label>
          <input type="text" placeholder="123"/>
        </div>
        <div>
          <label>Complemento</label>
          <input type="text" placeholder="Apto, Sala..."/>
        </div>
      </div>

      <div class="form-row form-group">
        <div>
          <label>Bairro</label>
          <input type="text" placeholder="Seu bairro"/>
        </div>
        <div>
          <label>Cidade</label>
          <input type="text" placeholder="Sua cidade"/>
        </div>
      </div>

      <div class="section-divider"><span>Contato</span></div>

      <div class="form-group">
        <label>E-mail</label>
        <input type="email" placeholder="seuemail@exemplo.com"/>
      </div>

      <div class="form-row form-group">
        <div>
          <label>Celular</label>
          <input type="text" placeholder="(00) 90000-0000"/>
        </div>
        <div>
          <label>Telefone</label>
          <input type="text" placeholder="(00) 0000-0000"/>
        </div>
      </div>

      <div class="section-divider"><span>Acesso</span></div>

      <div class="form-group">
        <label>Login (usuário)</label>
        <input type="text" placeholder="Escolha um nome de usuário"/>
      </div>

      <div class="form-row form-group">
        <div>
          <label>Senha</label>
          <input type="password" placeholder="Mínimo 6 caracteres"/>
        </div>
        <div>
          <label>Confirmar senha</label>
          <input type="password" placeholder="Repita a senha"/>
        </div>
      </div>

      <button type="submit" class="btn-submit">
        Finalizar meu cadastro
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </button>

      <p class="form-terms">Ao se cadastrar, você concorda com nossos termos de uso e política de privacidade.</p>
    </form>
  </div>
</div>

<!-- MINI FOOTER -->
<div class="mini-footer">
  <span>© 2024 Amei Cosméticos. Todos os direitos reservados.</span>
  <span>
    <a href="https://wa.me/5561982290919" target="_blank" rel="noopener">WhatsApp: (61) 98229-0919</a>
    &nbsp;·&nbsp;
    <a href="mailto:oseiascw@gmail.com">oseiascw@gmail.com</a>
  </span>
</div>

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
