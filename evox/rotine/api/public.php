<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Carregar o arquivo JSON
$file = 'db.json';
if (!file_exists($file)) {
    die("O arquivo de banco de dados não foi encontrado.");
}

$data = json_decode(file_get_contents($file), true);
$rotine = $data['Rotine'] ?? [];

// Função para contar tarefas concluídas
function countCompletedTasks($dayData) {
    $completedTasks = 0;
    $totalTasks = 0;

    foreach ($dayData as $key => $value) {
        // Ignorar campos que não são tarefas
        if (!in_array($key, ['date', 'date_key'])) {
            $totalTasks++;
            if ($value == 1) {
                $completedTasks++;
            }
        }
    }
    return [$completedTasks, $totalTasks];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Tarefas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Relatório de Tarefas</h1>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Tarefas Concluídas</th>
                <th>Total de Tarefas</th>
                <th>Porcentagem Concluída</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rotine as $day): ?>
                <?php
                    [$completedTasks, $totalTasks] = countCompletedTasks($day);
                    $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;
                ?>
                <tr>
                    <td><?= htmlspecialchars($day['date']) ?></td>
                    <td><?= $completedTasks ?></td>
                    <td><?= $totalTasks ?></td>
                    <td><?= $percentage ?>%</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

