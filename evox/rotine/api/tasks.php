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

</body>
</html>

