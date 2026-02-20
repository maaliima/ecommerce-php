<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Compra Finalizada - Um Convite de Casamento</title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Open+Sans&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset_url('css/carrinho.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset_url('css/finalizar-compra.css'); ?>">
</head>
<body>
    <div class="carrinho-container">
        <header>
            <div class="voltar">
                <a href="<?php echo url('carrinho'); ?>" title="Voltar ao Catálogo"><img src="<?php echo asset_url('images/sistema/back.png'); ?>" alt="Voltar"></a>
            </div>
            <div class="perfil-admin">
                <?php if ($usuario['logado']): ?>
                    <?php if (!empty($usuario['imagem']) && file_exists(__DIR__ . '/../../../uploads/' . $usuario['imagem'])): ?>
                        <img src="<?php echo upload_url((string) $usuario['imagem']); ?>" alt="Foto de perfil" />
                    <?php else: ?>
                        <img src="<?php echo upload_url('default.png'); ?>" alt="Foto padrão" />
                    <?php endif; ?>
                    <span>Olá, <strong><?php echo htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8'); ?></strong></span>
                    <nav>
                        <a href="<?php echo url('perfil'); ?>">Perfil</a>
                        <a href="<?php echo url('logout'); ?>">Sair</a>
                    </nav>
                <?php else: ?>
                    <span>Faça login para acessar seu perfil.</span>
                <?php endif; ?>
            </div>
        </header>

        <center><h1>SUA COMPRA FOI FINALIZADA COM SUCESSO!</h1></center>
    </div>
</body>
</html>

