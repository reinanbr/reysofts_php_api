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
    <title>Tarefas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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
    </style>
</head>
<body>
    <h1>Lista de Tarefas</h1>

    <h2>Tarefas Pendentes</h2>
    <div id="pendingTasks"></div>

    <h2>Tarefas Concluídas</h2>
    <div id="completedTasks"></div>

    <script>
        async function loadTasks() {
            try {
                // Buscar tarefas do servidor
                const response = await fetch('get_tasks.php');
                const data = await response.json();
                const tasks = data.Tasks;

                const pendingDiv = document.getElementById('pendingTasks');
                const completedDiv = document.getElementById('completedTasks');

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
</body>
</html>

