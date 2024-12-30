<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>

  <html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Site com Navbar e Footer</title>
  <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0px;
        }
        .task {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .completed {
            background-color: #d4edda;
        }
        .pending {
            background-color: #f8d7da;
        }
        .time-left {
            font-size: 0.9em;
            color: #555;
        }
        
        }
        form {
            max-width: 400px;
            margin: auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, .button_task {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
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

    <h1>Criar Nova Tarefa</h1>
    <form id="taskForm">
        <label for="name">Nome da Tarefa:</label>
        <input type="text" id="name" name="name" placeholder="Descrição da tarefa" required>

        <label for="date_end">Data de Término:</label>
        <input type="datetime-local" id="date_end" name="date_end" required>

        <button class='button_task' type="submit">Criar Tarefa</button>
    </form>
    <div id="message" class="message"></div>


    <hr>
        <h1>Lista de Tarefas</h1>

    <h2>Tarefas Pendentes</h2>
    <div id="pendingTasks"></div>

    <h2>Tarefas Concluídas</h2>
    <div id="completedTasks"></div>
  <!-- Footer -->
  <br><br>  <br><br>  <br><br>  <br><br>  <br><br>
  
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

    <script>
        async function loadTasks() {
            try {
                // Buscar tarefas do servidor
                const response = await fetch('get_tasks.php');
                const data = await response.json();
                const tasks = data.Tasks;

                const pendingDiv = document.getElementById('pendingTasks');
                const completedDiv = document.getElementById('completedTasks');
                pendingDiv.innerHTML = ''
                completedTasks.innerHTML = ''

                tasks.forEach(task => {
                    // Calcular tempo restante
                    const now = Math.floor(Date.now() / 1000);
                    const timeLeft = task.date_end - now;

                    const timeLeftText = timeLeft > 0
                        ? `${Math.floor(timeLeft / 86400)}d ${Math.floor((timeLeft % 86400) / 3600)}h ${Math.floor((timeLeft % 3600) / 60)}m restantes`
                        : "Atrasada!";

                    // Criar elemento para a tarefa
                    const taskDiv = document.createElement('div');
                    taskDiv.className = `task ${task.ok ? 'completed' : 'pending'}`;
                    taskDiv.innerHTML = `
                        <strong>${task.name}</strong>
                        <div class="time-left">${task.ok ? 'Concluída!' : timeLeftText}</div>
                    `;

                    // Adicionar à lista correspondente
                    if (task.ok) {
                        completedDiv.appendChild(taskDiv);
                    } else {
                        pendingDiv.appendChild(taskDiv);
                    }
                });
            } catch (error) {
                console.error('Erro ao carregar tarefas:', error);
            }
        }

        // Carregar tarefas ao iniciar
        loadTasks();
    </script>
       <script>
        document.getElementById('taskForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            

            const formData = new FormData(e.target);
            const response = await fetch('create_task.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            const messageDiv = document.getElementById('message');

            if (result.success) {
                const pendingDiv = document.getElementById('pendingTasks');
                const completedDiv = document.getElementById('completedTasks');
                messageDiv.style.color = "green";
                e.target.reset(); // Limpar o formulário
                await loadTasks();
            } else {
                messageDiv.textContent = result.error || "Erro ao criar tarefa.";
                messageDiv.style.color = "red";
            }
        });
    </script>
 <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

