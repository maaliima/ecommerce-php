<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cart;
use App\Models\User;
use PDO;

class AuthController extends Controller
{
    private User $userModel;
    private Cart $cartModel;

    public function __construct(private PDO $pdo)
    {
        $this->userModel = new User($pdo);
        $this->cartModel = new Cart($pdo);
    }

    public function loginRegister(): void
    {
        $erroRegistro = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'login') {
            require_csrf($_POST['_csrf'] ?? null);

            $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
            $senha = (string) ($_POST['senha'] ?? '');

            if (!$email || $senha === '') {
                $_SESSION['erro_login'] = 'Informe e-mail e senha válidos.';
                $this->redirect('login');
            }

            $usuario = $this->userModel->findByEmail((string) $email);
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['is_admin'] = $usuario['is_admin'];
                $_SESSION['imagem'] = $usuario['imagem'];

                $this->cartModel->syncSessionToDatabase();

                if ((int) $usuario['is_admin'] === 1) {
                    $this->redirect('admin');
                }

                $this->redirect('');
            }

            $_SESSION['erro_login'] = 'E-mail ou senha incorretos.';
            $this->redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'registrar') {
            require_csrf($_POST['_csrf'] ?? null);

            $nome = trim((string) ($_POST['nome'] ?? ''));
            $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
            $senha = (string) ($_POST['senha'] ?? '');

            if ($nome === '' || !$email || strlen($senha) < 6) {
                $erroRegistro = 'Preencha os campos corretamente. A senha deve ter ao menos 6 caracteres.';
            } else {
                $resultado = $this->userModel->createCustomer($nome, (string) $email, $senha);
                if ($resultado === true) {
                    $this->redirect('login');
                }

                $erroRegistro = is_string($resultado) ? $resultado : 'Não foi possível concluir o cadastro.';
            }
        }

        $this->view('auth.login_register', [
            'erroRegistro' => $erroRegistro,
        ]);
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        $this->redirect('');
    }
}
