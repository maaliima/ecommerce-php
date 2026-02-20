<?php
$current = $current ?? '';
?>
<aside class="w-64 bg-white border-r border-gray-200 flex flex-col p-4 space-y-4">
  <a href="#" class="flex items-center gap-2">
    <div class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center text-pink-600 font-bold">UC</div>
    <span class="text-pink-600 font-semibold text-base">Um Convite de Casamento</span>
  </a>
  <nav class="flex flex-col gap-1 text-sm">
    <a href="<?php echo url('admin'); ?>" class="px-2 py-1 rounded <?php echo $current === 'dashboard' ? 'bg-pink-50 text-pink-600 font-medium' : 'hover:bg-pink-50 hover:text-pink-600'; ?>">Dashboard</a>
    <a href="<?php echo url('admin/adicionar'); ?>" class="px-2 py-1 rounded <?php echo $current === 'add_product' ? 'bg-pink-50 text-pink-600 font-medium' : 'hover:bg-pink-50 hover:text-pink-600'; ?>">Adicionar Produto</a>
    <a href="<?php echo url(''); ?>" class="px-2 py-1 rounded hover:bg-pink-50 hover:text-pink-600">Ver loja</a>
    <a href="<?php echo url('logout'); ?>" class="px-2 py-1 rounded text-red-600 hover:bg-red-50">Sair</a>
  </nav>
</aside>

