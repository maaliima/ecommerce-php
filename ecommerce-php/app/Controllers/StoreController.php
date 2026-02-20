<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use PDO;

class StoreController extends Controller
{
    private Product $productModel;
    private User $userModel;
    private Cart $cartModel;

    public function __construct(private PDO $pdo)
    {
        $this->productModel = new Product($pdo);
        $this->userModel = new User($pdo);
        $this->cartModel = new Cart($pdo);
    }

    public function home(): void
    {
        $this->view('store.home', [
            'produtos' => $this->productModel->all(),
            'usuario' => $this->currentUser(),
            'total_itens_carrinho' => $this->cartModel->countItems(),
        ]);
    }

    public function productDetail(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id <= 0) {
            $this->redirect('');
        }

        $produto = $this->productModel->find($id);
        if (!$produto) {
            $this->redirect('');
        }

        $this->view('store.product', [
            'produto' => $produto,
            'relacionados' => $this->productModel->related((string) ($produto['categoria'] ?? ''), (int) $produto['id']),
            'usuario' => $this->currentUser(),
            'total_itens_carrinho' => $this->cartModel->countItems(),
        ]);
    }

    private function currentUser(): array
    {
        $default = [
            'logado' => false,
            'nome' => '',
            'imagem' => '',
            'is_admin' => false,
        ];

        if (!isset($_SESSION['usuario_id'])) {
            return $default;
        }

        $dbUser = $this->userModel->findById((int) $_SESSION['usuario_id']);
        if (!$dbUser) {
            return $default;
        }

        return [
            'logado' => true,
            'nome' => $dbUser['nome'] ?? '',
            'imagem' => $dbUser['imagem'] ?? '',
            'is_admin' => ((int) ($dbUser['is_admin'] ?? 0)) === 1,
        ];
    }
}
