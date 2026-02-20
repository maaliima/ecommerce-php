<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Adicionar Produto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="text-gray-800 bg-gray-100">
    <div class="flex min-h-screen">
        <?php
        $current = 'add_product';
        include __DIR__ . '/../partials/admin/sidebar.php';
        ?>

        <div class="flex-1 p-6 space-y-6">
            <?php
            $title = 'Adicionar Produto';
            include __DIR__ . '/../partials/admin/topbar.php';
            ?>

            <div class="w-full max-w-3xl bg-white p-8 md:p-10 rounded-xl card-shadow shadow-lg space-y-6">
                <main>
                    <?php if (!empty($erro)): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-5 rounded" role="alert">
                            <p class="font-bold">Erro</p>
                            <p><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php
                    $mode = 'create';
                    $action = url('admin/adicionar');
                    $submitLabel = 'Salvar Produto';
                    include __DIR__ . '/../partials/admin/product_form.php';
                    ?>
                </main>
            </div>
        </div>
    </div>
</body>
</html>

