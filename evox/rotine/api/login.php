<?php
session_start();

// Caminho para o arquivo de usuários
$file = 'users.json';

// Função para carregar os usuários
function loadUsers($file) {
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    }
    return ["users" => []];
}

$users = loadUsers($file);

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validar as credenciais
    foreach ($users['users'] as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        }
    }

    // Credenciais inválidas
    $error = "Usuário ou senha incorretos.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="username">Usuário:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Senha:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>

