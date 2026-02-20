<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\User;
use Exception;
use PDO;

class AdminController extends Controller
{
    private const MAX_IMAGE_SIZE = 5_242_880;

    private Product $productModel;
    private User $userModel;

    public function __construct(private PDO $pdo)
    {
        $this->productModel = new Product($pdo);
        $this->userModel = new User($pdo);
    }

    public function login(): void
    {
        $this->redirect('login');
    }

    public function dashboard(): void
    {
        $this->guardAdmin();

        $usuario = $this->userModel->findById((int) $_SESSION['usuario_id']);

        $pedidosTotal = 0;
        $faturamento = 0.0;

        try {
            $q = $this->pdo->query('SELECT COUNT(*) AS total FROM pedidos');
            $pedidosTotal = (int) $q->fetch()['total'];
        } catch (Exception) {
            $pedidosTotal = 0;
        }

        try {
            $q = $this->pdo->query("SELECT SUM(valor_total) AS faturamento FROM pedidos WHERE status <> 'cancelado'");
            $faturamento = (float) ($q->fetch()['faturamento'] ?? 0);
        } catch (Exception) {
            $faturamento = 0;
        }

        $this->view('admin.dashboard', [
            'usuario' => $usuario,
            'produtos' => $this->productModel->all(),
            'produtos_total' => $this->productModel->countAll(),
            'pedidos_total' => $pedidosTotal,
            'clientes_total' => $this->userModel->countCustomers(),
            'faturamento' => $faturamento,
        ]);
    }

    public function addProduct(): void
    {
        $this->guardAdmin();
        $erro = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf($_POST['_csrf'] ?? null);

            $nome = trim((string) ($_POST['nome'] ?? ''));
            $descricao = trim((string) ($_POST['descricao'] ?? ''));
            $precoInput = str_replace(',', '.', (string) ($_POST['preco'] ?? ''));
            $preco = filter_var($precoInput, FILTER_VALIDATE_FLOAT);
            $categoria = trim((string) ($_POST['categoria'] ?? ''));

            if ($nome === '' || $descricao === '' || $categoria === '' || $preco === false || $preco <= 0) {
                $erro = 'Preencha todos os campos corretamente.';
            }

            $nomeImagem = null;
            if (!$erro) {
                $nomeImagem = $this->handleProductImageUpload($_FILES['foto'] ?? null, true, $erro);
            }

            if (!$erro && $nomeImagem && $this->productModel->create($nome, $descricao, (float) $preco, $categoria, $nomeImagem)) {
                $this->redirect('admin?sucesso=produto-adicionado');
            }

            if (!$erro) {
                $erro = 'Erro ao salvar produto no banco.';
            }
        }

        $this->view('admin.add_product', ['erro' => $erro]);
    }

    public function editProduct(): void
    {
        $this->guardAdmin();

        $produtoId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($produtoId <= 0) {
            $this->redirect('admin');
        }

        $produto = $this->productModel->find($produtoId);
        if (!$produto) {
            $this->redirect('admin?error=Produto não encontrado');
        }

        $erro = '';
        $sucesso = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf($_POST['_csrf'] ?? null);

            $precoInput = str_replace(',', '.', (string) ($_POST['preco'] ?? ''));
            $preco = filter_var($precoInput, FILTER_VALIDATE_FLOAT);

            $data = [
                'nome' => trim((string) ($_POST['nome'] ?? '')),
                'descricao' => trim((string) ($_POST['descricao'] ?? '')),
                'preco' => $preco,
                'categoria' => trim((string) ($_POST['categoria'] ?? '')),
                'imagem' => $produto['imagem'],
            ];

            if ($data['nome'] === '' || $data['descricao'] === '' || $data['categoria'] === '' || $preco === false || $preco <= 0) {
                $erro = 'Preencha todos os campos corretamente.';
            }

            if (!$erro && isset($_FILES['foto']) && ($_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                $novaImagem = $this->handleProductImageUpload($_FILES['foto'], false, $erro);
                if ($novaImagem) {
                    $imagemAntiga = __DIR__ . '/../../assets/images/produtos/' . $produto['imagem'];
                    if (!empty($produto['imagem']) && file_exists($imagemAntiga)) {
                        @unlink($imagemAntiga);
                    }
                    $data['imagem'] = $novaImagem;
                }
            }

            if (!$erro && $this->productModel->update($produtoId, $data)) {
                $sucesso = 'Produto atualizado com sucesso.';
                $produto = $this->productModel->find($produtoId) ?? $produto;
            }

            if (!$erro && !$sucesso) {
                $erro = 'Erro ao atualizar o produto.';
            }
        }

        $this->view('admin.edit_product', [
            'produto' => $produto,
            'produto_id' => $produtoId,
            'erro' => $erro,
            'sucesso' => $sucesso,
        ]);
    }

    public function removeProduct(): void
    {
        $this->guardAdmin();

        require_csrf($_POST['_csrf'] ?? null);

        $produtoId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($produtoId <= 0) {
            $this->redirect('admin');
        }

        $produto = $this->productModel->find($produtoId);
        if (!$produto) {
            $this->redirect('admin?error=Produto não encontrado');
        }

        if ($this->productModel->delete($produtoId)) {
            $caminhoImagem = __DIR__ . '/../../assets/images/produtos/' . $produto['imagem'];
            if (!empty($produto['imagem']) && file_exists($caminhoImagem)) {
                @unlink($caminhoImagem);
            }

            $this->redirect('admin?msg=Produto removido com sucesso');
        }

        $this->redirect('admin?error=Erro ao remover produto');
    }

    private function guardAdmin(): void
    {
        if (!isset($_SESSION['usuario_id']) || (int) ($_SESSION['is_admin'] ?? 0) !== 1) {
            $this->redirect('login');
        }
    }

    private function handleProductImageUpload(?array $file, bool $required, ?string &$erro = null): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            if ($required) {
                $erro = 'Imagem do produto é obrigatória.';
            }
            return null;
        }

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $erro = 'Erro no upload da imagem.';
            return null;
        }

        if (($file['size'] ?? 0) > self::MAX_IMAGE_SIZE) {
            $erro = 'Imagem muito grande. Limite de 5MB.';
            return null;
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo ? finfo_file($finfo, $tmpName) : false;
        if ($finfo) {
            finfo_close($finfo);
        }

        $allowedMimes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
        ];

        if (!is_string($mime) || !isset($allowedMimes[$mime])) {
            $erro = 'Formato de imagem inválido. Use JPG ou PNG.';
            return null;
        }

        $newName = uniqid('', true) . '.' . $allowedMimes[$mime];
        $destinationDir = __DIR__ . '/../../assets/images/produtos/';

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        if (!move_uploaded_file($tmpName, $destinationDir . $newName)) {
            $erro = 'Falha ao salvar a imagem.';
            return null;
        }

        return $newName;
    }
}
