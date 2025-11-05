<?php
session_start();
include('includes/conexao.php');

function get_css_version($filepath)
{
  if (file_exists($filepath)) {
    return filemtime($filepath);
  }
  return time();
}

$stmt = $pdo->query("SELECT * FROM produtos");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$usuario_logado = false;
$usuario_nome = '';
$usuario_imagem = '';
$usuario_is_admin = false;

if (isset($_SESSION['usuario_id'])) {
  $usuario_id = $_SESSION['usuario_id'];
  $stmt = $pdo->prepare("SELECT nome, imagem, is_admin FROM usuarios WHERE id = :id");
  $stmt->bindParam(':id', $usuario_id);
  $stmt->execute();
  $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($usuario) {
    $usuario_nome = $usuario['nome'];
    $usuario_imagem = $usuario['imagem'] ? $usuario['imagem'] : 'default-avatar.jpg';
    $usuario_is_admin = ($usuario['is_admin'] == 1);
  }

  $usuario_logado = true;
}

$total_itens_carrinho = 0;
if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
  $total_itens_carrinho = count($_SESSION['carrinho']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" href="assets/images/sistema/carta_fechada.png" type="image/png">
  <title>Um Convite de Casamento</title>

  <link rel="stylesheet" href="assets/css/carrossel.css?v=<?php echo get_css_version('assets/css/carrossel.css'); ?>" />
  <link rel="stylesheet" href="assets/css/style.css?v=<?php echo get_css_version('assets/css/style.css'); ?>" />
  <link rel="stylesheet" href="assets/css/perfil.css?v=<?php echo get_css_version('assets/css/perfil.css'); ?>" />
  <link rel="stylesheet"
    href="assets/css/pagina-container.css?v=<?php echo get_css_version('assets/css/pagina-container.css'); ?>" />
  <link rel="stylesheet" href="assets/css/card.css?v=<?php echo get_css_version('assets/css/card.css'); ?>" />
  <link rel="stylesheet"
    href="assets/css/pagina_inicial.css?v=<?php echo get_css_version('assets/css/pagina_inicial.css'); ?>" />
  <link rel="stylesheet"
    href="assets/css/menu-lateral.css?v=<?php echo get_css_version('assets/css/menu-lateral.css'); ?>" />

  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <header class="header-fixo">
    <div class="header-superior">
      <div class="logo">
        <a href="index.php">
          <img src="assets/images/sistema/logo01.png" alt="Logo da Loja" class="logo-img" />
        </a>
      </div>

      <div class="search-bar-container">
        <input type="text" placeholder="O que você está procurando?" class="search-input">
        <button class="search-button"><i class='bx bx-search'></i></button>
      </div>

      <div class="icones-header-direita">
        <div class="icone-texto-container perfil-menu-container">
          <a href="<?php echo $usuario_logado ? 'public/perfil.php' : 'public/login_registro.php'; ?>"
            class="icone-link" id="perfil-link">
            <i class='bx bx-user'></i>
            <span>
              <?php
              if ($usuario_logado) {
                echo htmlspecialchars($usuario_nome);
              } else {
                echo 'Entrar / Cadastre-se';
              }
              ?>
            </span>
          </a>
          <?php if ($usuario_logado): ?>
            <div class="perfil-dropdown" id="perfil-dropdown">
              <a href="public/perfil.php">Gerenciar Perfil</a>
              <?php if ($usuario_is_admin): ?>
                <a href="admin/painel.php">Painel Admin</a>
              <?php endif; ?>
              <a href="public/logout.php" class="logout-btn">Sair</a>
            </div>
          <?php endif; ?>
        </div>

        <div class="icone-texto-container">
          <a href="public/carrinho.php" class="icone-link sacola-link">
            <i class='bx bx-shopping-bag'></i>
            <span></span>
            <?php if ($total_itens_carrinho > 0): ?>
              <span class="cart-notification"><?php echo $total_itens_carrinho; ?></span>
            <?php endif; ?>
          </a>
        </div>

      </div>
    </div>
  </header>

  <nav class="novo-menu-principal">
    <div class="menu-container-central">
      <a href="index.php" class="menu-link-item">Início</a>
      <a href="#" class="menu-link-item">Quem Somos</a>
      <a href="#" class="menu-link-item">Redes Sociais</a>
      <a href="#" class="menu-link-item">Contato</a>
      <a href="#" class="menu-link-item">Compartilhar</a>
    </div>
  </nav>

  <div class="menu-fundo" id="menuFundo"></div>
  <nav class="menu-lateral" id="menuLateral">
    <?php if ($usuario_logado): ?>
      <?php if ($usuario_is_admin): ?>
        <a href="admin/painel.php">Painel</a>
      <?php endif; ?>
      <a href="public/perfil.php">Gerenciar Perfil</a>
      <a href="public/logout.php">Sair</a>
    <?php else: ?>
      <a href="public/login_registro.php">Login</a>
      <a href="public/login_registro.php?acao=registrar">Registrar-se</a>
    <?php endif; ?>
  </nav>

  <div class="pagina-container">
    <section class="banner">
      <div class="slider">
        <div class="slides">
          <input type="radio" name="radio-btn" id="radio1" checked>
          <input type="radio" name="radio-btn" id="radio2">
          <input type="radio" name="radio-btn" id="radio3">
          <input type="radio" name="radio-btn" id="radio4">
          <input type="radio" name="radio-btn" id="radio5">

          <div class="slide first"><img src="assets/images/sistema/banner1.png" alt="imagem 1"></div>
          <div class="slide"><img src="assets/images/sistema/banner2.png" alt="imagem 2"></div>
          <div class="slide"><img src="assets/images/sistema/banner3.png" alt="imagem 3"></div>
          <div class="slide"><img src="assets/images/sistema/banner4.png" alt="imagem 4"></div>
          <div class="slide"><img src="assets/images/sistema/banner5.png" alt="imagem 5"></div>

          <div class="navigation-auto">
            <div class="auto-btn1"></div>
            <div class="auto-btn2"></div>
            <div class="auto-btn3"></div>
            <div class="auto-btn4"></div>
            <div class="auto-btn5"></div>
          </div>
        </div>

        <div class="manual-navigation">
          <label for="radio1" class="manual-btn"></label>
          <label for="radio2" class="manual-btn"></label>
          <label for="radio3" class="manual-btn"></label>
          <label for="radio4" class="manual-btn"></label>
          <label for="radio5" class="manual-btn"></label>
        </div>
      </div>
    </section>

    <section class="catalogo">
      <div class="catalogo-container">
        <div class="conteudo-produto">
          <?php foreach ($produtos as $produto): ?>
            <div class="produto" onclick="abrirProduto(<?php echo $produto['id']; ?>)">
              <img src="assets/images/produtos/<?php echo $produto['imagem']; ?>"
                alt="<?php echo htmlspecialchars($produto['nome']); ?>" />
              <div class="info-preco-sacola">
                <?php if ($produto['preco'] > 0): ?>
                  <span class="preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
                <?php else: ?>
                  <span class="preco indisponivel">Preço indisponível</span>
                <?php endif; ?>
              </div>
              <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
              <?php if ($produto['preco'] > 0): ?>
                <form action="public/adicionar_ao_carrinho.php" method="POST" onsubmit="pararPropagacao(event)">
                  <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                  <input type="hidden" name="quantidade" value="1">
                  <button type="submit" class="btn-adicionar-sacola">Adicionar à sacola</button>
                </form>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </div>

  <div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>

  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>

  <script src="assets/js/menu-lateral.js"></script>
  <script src="assets/js/carrossel.js"></script>

  <script>
    function abrirProduto(id) {
      window.location.href = 'public/produto.php?id=' + id;
    }

    function pararPropagacao(event) {
      event.stopPropagation();
    }

    const perfilLink = document.getElementById('perfil-link');
    const perfilDropdown = document.getElementById('perfil-dropdown');

    if (perfilLink && perfilDropdown) {
      perfilLink.addEventListener('click', function(event) {
        if ('<?php echo $usuario_logado ? 'true' : 'false'; ?>' === 'true') {
          event.preventDefault();
          perfilDropdown.classList.toggle('show');
        }
      });

      document.addEventListener('click', function(event) {
        if (!perfilLink.contains(event.target) && !perfilDropdown.contains(event.target)) {
          perfilDropdown.classList.remove('show');
        }
      });
    }
  </script>
</body>

</html>