<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use PDO;

class ProfileController extends Controller
{
    private const MAX_IMAGE_SIZE = 3_145_728;

    private User $userModel;

    public function __construct(private PDO $pdo)
    {
        $this->userModel = new User($pdo);
    }

    public function edit(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('login');
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $usuario = $this->userModel->findById($usuarioId);

        if (!$usuario) {
            $this->redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf($_POST['_csrf'] ?? null);

            $dadosAtualizacao = [];
            $novoNome = trim((string) ($_POST['nome'] ?? ''));
            $novaSenha = (string) ($_POST['senha'] ?? '');

            if ($novoNome !== '' && $novoNome !== (string) $usuario['nome']) {
                $dadosAtualizacao['nome'] = $novoNome;
            }

            if ($novaSenha !== '') {
                if (strlen($novaSenha) < 6) {
                    $_SESSION['erro'] = 'A senha deve ter ao menos 6 caracteres.';
                    $this->redirect('perfil');
                }

                $dadosAtualizacao['senha'] = password_hash($novaSenha, PASSWORD_DEFAULT);
            }

            if (isset($_FILES['foto']) && ($_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                $foto = $_FILES['foto'];

                if (($foto['size'] ?? 0) > self::MAX_IMAGE_SIZE) {
                    $_SESSION['erro'] = 'A imagem deve ter no máximo 3MB.';
                    $this->redirect('perfil');
                }

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = $finfo ? finfo_file($finfo, (string) $foto['tmp_name']) : false;
                if ($finfo) {
                    finfo_close($finfo);
                }

                $allowed = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                ];

                if (!is_string($mime) || !isset($allowed[$mime])) {
                    $_SESSION['erro'] = 'Formato de imagem inválido. Use JPG ou PNG.';
                    $this->redirect('perfil');
                }

                $nomeUnico = uniqid('', true) . '-perfil.' . $allowed[$mime];
                $destino = __DIR__ . '/../../uploads/' . $nomeUnico;

                if (move_uploaded_file((string) $foto['tmp_name'], $destino)) {
                    $dadosAtualizacao['imagem'] = $nomeUnico;
                }
            }

            if (!empty($dadosAtualizacao) && $this->userModel->updateProfile($usuarioId, $dadosAtualizacao)) {
                if (isset($dadosAtualizacao['nome'])) {
                    $_SESSION['nome'] = $dadosAtualizacao['nome'];
                }

                $_SESSION['sucesso'] = 'Dados atualizados com sucesso!';
                $this->redirect('perfil');
            }

            if (empty($dadosAtualizacao)) {
                $_SESSION['erro'] = 'Nenhuma alteração foi enviada.';
            } else {
                $_SESSION['erro'] = 'Erro ao atualizar os dados.';
            }
        }

        $this->view('profile.edit', [
            'usuario' => $usuario,
        ]);
    }
}
