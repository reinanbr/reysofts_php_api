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
    <title>Criar Tarefa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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
        input, button {
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
</head>
<body>
    <h1>Criar Nova Tarefa</h1>
    <form id="taskForm">
        <label for="name">Nome da Tarefa:</label>
        <input type="text" id="name" name="name" placeholder="Descrição da tarefa" required>

        <label for="date_end">Data de Término:</label>
        <input type="datetime-local" id="date_end" name="date_end" required>

        <button type="submit">Criar Tarefa</button>
    </form>
    <div id="message" class="message"></div>

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
                messageDiv.textContent = result.message;
                messageDiv.style.color = "green";
                e.target.reset(); // Limpar o formulário
            } else {
                messageDiv.textContent = result.error || "Erro ao criar tarefa.";
                messageDiv.style.color = "red";
            }
        });
    </script>
</body>
</html>

