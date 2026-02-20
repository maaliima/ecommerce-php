<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="<?php echo asset_url('js/validacoes.js'); ?>"></script>
    <title>Editar Perfil</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Rubik', sans-serif; background: linear-gradient(to bottom, #fff0f5, #ffe4e1); color: #333; min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px; }
        .pagina-container { background: #fff; padding: 40px 30px; border-radius: 15px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1); max-width: 700px; width: 100%; position: relative; }
        header { text-align: center; margin-bottom: 30px; position: relative; }
        header h1 { font-size: 2em; color: #e75480; font-weight: 600; }
        .voltar { position: absolute; top: 0; left: 0; }
        .voltar img { width: 30px; height: auto; }
        form { display: flex; flex-direction: column; }
        form label { font-weight: 600; margin-bottom: 6px; color: #b33951; }
        form input[type="text"], form input[type="password"], form input[type="file"] { padding: 10px; border: 1px solid #ccc; border-radius: 10px; font-size: 1em; margin-bottom: 12px; background-color: #fffafa; }
        form button { margin-top: 15px; background-color: pink; color: #fff; padding: 14px 0; font-size: 1.1em; border: none; border-radius: 30px; cursor: pointer; }
        .foto-perfil-container { position: relative; width: 160px; margin: 0 auto 20px; }
        .foto-perfil-container img { width: 160px; height: 160px; object-fit: cover; border-radius: 50%; border: 3px solid #e75480; }
        .botao-editar-foto { position: absolute; bottom: 0; right: 0; background-color: transparent; border: none; padding: 0; cursor: pointer; }
        .botao-editar-foto img { width: 30px; height: 30px; object-fit: contain; }
        .mensagem-alerta { padding: 15px 20px; border-radius: 10px; font-weight: 500; text-align: center; max-width: 500px; margin: 10px auto 20px auto; font-size: 1em; }
        .mensagem-alerta.sucesso { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .mensagem-alerta.erro { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="pagina-container">
        <?php if (isset($_SESSION['sucesso']) || isset($_SESSION['erro'])): ?>
            <div class="mensagem-alerta <?php echo isset($_SESSION['sucesso']) ? 'sucesso' : 'erro'; ?>">
                <?php echo htmlspecialchars($_SESSION['sucesso'] ?? $_SESSION['erro'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <?php unset($_SESSION['sucesso'], $_SESSION['erro']); ?>
        <?php endif; ?>

        <header>
            <h1>Editar Perfil</h1>
            <div class="voltar"><a href="<?php echo url(''); ?>" aria-label="Voltar ao Catálogo"><img src="<?php echo asset_url('images/sistema/back.png'); ?>" alt="Voltar ao Catálogo" /></a></div>
        </header>

        <main>
            <section>
                <h3>Dados do Usuário</h3>
                <form action="<?php echo url('perfil'); ?>" method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario();">
                    <?php echo csrf_field(); ?>
                    <div class="foto-perfil-container">
                        <img id="previewFoto" src="<?php echo upload_url((string) ($usuario['imagem'] ?? 'default.png')); ?>" alt="Foto de Perfil" />
                        <label for="foto" class="botao-editar-foto" title="Editar foto"><img src="<?php echo asset_url('images/sistema/pencil.png'); ?>" alt="Editar"></label>
                        <input type="file" id="foto" name="foto" accept="image/jpeg, image/png" style="display: none;" onchange="mostrarPreview(event)">
                    </div>

                    <label for="nome">Novo nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8'); ?>" />
                    <span id="msg_nome"></span>

                    <label for="senha">Nova Senha:</label>
                    <input type="password" id="senha" name="senha" />

                    <label for="confirmar_senha">Confirmar Senha:</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" onkeyup="validarSenhas()" />
                    <span id="msg_senha"></span>

                    <button type="submit">Atualizar</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>





