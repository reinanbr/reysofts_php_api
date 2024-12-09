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
    return ["Rotine" => []];
}

// Carregar dados para exibição inicial
$data = loadData($file);
$date = date("Y-m-d");
$dateKey = date("Ymd");

// Verificar se existe entrada para o dia
$today = array_filter($data['Rotine'], fn($item) => $item['date_key'] === $dateKey);
if (empty($today)) {
    $example = $data['Rotine'][0] ?? [];
    $keys = array_keys($example);
    $today = ["date" => $date, "date_key" => $dateKey];
    foreach ($keys as $key) {
        if (!in_array($key, ["date", "date_key"])) {
            $today[$key] = 0;
        }
    }
    $data['Rotine'][] = $today;
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
} else {
    $today = array_values($today)[0];
}

// Calcular a porcentagem inicial
$totalActivities = count($today) - 2; // Exclui 'date' e 'date_key'
$completedActivities = array_sum(array_filter($today, fn($value) => $value === 1));
$completionPercentage = ($totalActivities > 0) ? ($completedActivities / $totalActivities) * 100 : 0;
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Site com Navbar e Footer</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Função para atualizar uma atividade via AJAX
        function updateActivity(key, value) {
            const formData = new FormData();
            formData.append('key', key);
            formData.append('value', value);

            fetch('save_update.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.percentage !== undefined) {
                    document.getElementById('progress').textContent = `Progresso: ${data.percentage}%`;
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Erro:', error));
        }

        // Função para desmarcar uma atividade
        function clearActivity(key) {
            const radio = document.querySelector(`input[name="${key}"]`);
            if (radio) {
                radio.checked = false;
                updateActivity(key, 0);
            }
        }
    </script>
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

    <h1>Atividades do Dia: <?= $date ?></h1>
    <form>
        <ul>
            <?php foreach ($today as $key => $value): ?>
                <?php if (!in_array($key, ["date", "date_key"])): ?>
                    <li>
                        <label>
                            <input 
                                type="radio" 
                                name="<?= $key ?>" 
                                <?= $value == 1 ? 'checked' : '' ?> 
                                onchange="updateActivity('<?= $key ?>', this.checked ? 1 : 0)">
                            <?= ucfirst(str_replace('_', ' ', $key)) ?>
                        </label>
                        <button type="button" onclick="clearActivity('<?= $key ?>')">X</button>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </form>
    <h2 id="progress">Progresso: <?= round($completionPercentage, 2) ?>%</h2>
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
</body>
</html>

