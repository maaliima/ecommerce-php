<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Painel Admin - Um Convite de Casamento</title>
  <link rel="stylesheet" href="../admin/css/painel.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="text-gray-800">
  <div class="flex min-h-screen">
    <?php
    $current = 'dashboard';
    include __DIR__ . '/../partials/admin/sidebar.php';
    ?>

    <div class="flex-1 p-6 space-y-6">
      <?php
      $title = 'Dashboard';
      include __DIR__ . '/../partials/admin/topbar.php';
      ?>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded p-4 card-shadow"><div class="text-sm text-gray-500">Produtos</div><div class="text-2xl font-semibold text-pink-600"><?php echo (int) $produtos_total; ?></div></div>
        <div class="bg-white rounded p-4 card-shadow"><div class="text-sm text-gray-500">Pedidos</div><div class="text-2xl font-semibold text-pink-600"><?php echo (int) $pedidos_total; ?></div></div>
        <div class="bg-white rounded p-4 card-shadow"><div class="text-sm text-gray-500">Clientes</div><div class="text-2xl font-semibold text-pink-600"><?php echo (int) $clientes_total; ?></div></div>
        <div class="bg-white rounded p-4 card-shadow"><div class="text-sm text-gray-500">Faturamento</div><div class="text-2xl font-semibold text-pink-600">R$ <?php echo number_format((float) $faturamento, 2, ',', '.'); ?></div></div>
      </div>

      <div class="bg-white rounded p-4 card-shadow">
        <div class="overflow-x-auto">
          <table class="table w-full text-base">
            <thead class="bg-pink-50 text-pink-600"><tr><th>Nome</th><th>Preço</th><th>Estoque</th><th>Ações</th></tr></thead>
            <tbody>
              <?php if (!empty($produtos)): ?>
                <?php foreach ($produtos as $produto): ?>
                  <tr class="border-b">
                    <td><?php echo htmlspecialchars($produto['nome'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>R$ <?php echo number_format((float) ($produto['preco'] ?? 0), 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars((string) ($produto['estoque'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="flex gap-2 items-center">
                      <a href="<?php echo url('admin/editar'); ?>?id=<?php echo (int) $produto['id']; ?>" class="px-3 py-1.5 bg-yellow-500 text-white rounded text-sm hover:bg-yellow-600">Editar</a>
                      <form id="remove-form-<?php echo (int) $produto['id']; ?>" action="<?php echo url('admin/remover'); ?>" method="POST" class="m-0">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo (int) $produto['id']; ?>">
                        <button type="button" onclick="confirmarRemocao(<?php echo (int) $produto['id']; ?>)" class="px-3 py-1.5 bg-red-600 text-white rounded text-sm hover:bg-red-700">Remover</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="4" class="text-center text-gray-500 p-4">Nenhum produto encontrado</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    function confirmarRemocao(id) {
      Swal.fire({
        title: 'Remover produto?',
        text: 'Deseja realmente remover?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#e11d63'
      }).then(result => {
        if (result.isConfirmed) {
          const form = document.getElementById('remove-form-' + id);
          if (form) form.submit();
        }
      });
    }
  </script>
</body>
</html>

