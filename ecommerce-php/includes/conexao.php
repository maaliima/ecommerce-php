<?php
$host = 'sql313.infinityfree.com';
$user = 'if0_39028034';
$pass = 'SUA_SENHA_AQUI'; // coloque exatamente a senha do painel InfinityFree
$db   = 'if0_39028034_XXX';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    echo "Conectado com sucesso!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>