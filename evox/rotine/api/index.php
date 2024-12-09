<?php

session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
	header('Location: /evox/rotine/api/login.php');
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Site com Navbar e Footer</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <a class="nav-link" href="#home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/evox/rotine/api/rotine.php">Rotina</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/evox/rotine/api/create_task.php">Criar Tarefas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/evox/rotine/api/diary,php">Diário</a>
	  </li>
	 <li class="nav-item">
            <a class="nav-link" href="/evox/rotine/api/diary,php">Diário</a>
          </li>

        </ul>
      </div>
    </div>
  </nav>

  <!-- Conteúdo -->
  <section id="home" class="p-5">
    <div class="container">
      <h1 class="text-primary">Bem-vindo ao nosso site!</h1>
      <p class="mt-3 text-muted">Este é um exemplo de site com navbar e footer responsivos usando Bootstrap.</p>
    </div>
  </section>

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

