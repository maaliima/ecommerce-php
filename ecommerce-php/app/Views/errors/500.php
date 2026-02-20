<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro interno</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; display: grid; place-items: center; min-height: 100vh; background: #f8fafc; color: #1f2937; }
        .box { text-align: center; max-width: 560px; padding: 24px; }
        .debug { margin-top: 16px; font-size: 14px; color: #b91c1c; }
    </style>
</head>
<body>
<div class="box">
    <h1>500</h1>
    <p>Ocorreu um erro ao processar sua requisição.</p>
    <p><a href="<?php echo url(''); ?>">Voltar para a loja</a></p>
    <?php if (!empty($message)): ?>
        <div class="debug"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
</div>
</body>
</html>

