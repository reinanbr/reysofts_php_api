<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Caminho para o arquivo db.json
$file = 'db.json';

// Função para carregar o JSON
function loadData($file) {
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    }
    return ["Diary" => []];
}

// Função para salvar o JSON
function saveData($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// Processar envio do diário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content'] ?? '');
    if ($content !== '') {
        $data = loadData($file);
        $diaryEntry = [
            'author' => $_SESSION['username'], // Nome do usuário da sessão
            'timestamp' => date('Y-m-d H:i:s'),
            'content' => $content
        ];
        $data['Diary'][] = $diaryEntry;
        saveData($file, $data);

        header('Location: read_diary.php'); // Redirecionar após salvar
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Diário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <!-- Substitua 'logo.png' pelo caminho da sua imagem -->
        <img src="https://via.placeholder.com/40" alt="Logo" class="d-inline-block align-text-top" style="width: 40px; height: 40px; border-radius: 50%;">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="/evox/rotine/api">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/evox/rotine/api/rotine.php">Rotina</a>
          
          <li class="nav-item">
            <a class="nav-link" href="/evox/rotine/api/read_diary.php">Diário</a>
	  </li>
	 <li class="nav-item">
            <a class="nav-link" href="/evox/rotine/api/create_tasks.php">Tarefas</a>
          </li>

        </ul>
      </div>
    </div>
  </nav>

<div class="container mt-5">
    <h1 class="text-center">Publicar Diário</h1>
    <form method="POST">
        <div class="form-group">
            <label for="content" class="form-label">Escreva sua resenha (Markdown permitido):</label>
            <textarea class="form-control" id="content" name="content" rows="10" placeholder="Digite sua resenha aqui..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Publicar</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Footer -->
  <footer class="bg-primary text-white mt-5 py-3">
    <div class="container text-center">
      <p class="mb-0">&copy; 2024 SeuSite. Todos os direitos reservados.</p>
      <div class="mt-2">
        <a href="#" class="text-white me-3">Termos</a>
        <a href="#" class="text-white me-3">Privacidade</a>
        <a href="#" class="text-white">Contato</a>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

