<?php

// Caminho para o arquivo db.json
$file = 'db.json';

// Função para carregar o JSON
function loadData($file) {
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    }
    return ["Tasks" => []];
}

// Função para salvar o JSON
function saveData($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// Carregar dados para exibição inicial
$data = loadData($file);
$tasks = $data['Tasks'] ?? [];

// Processar atualização via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_updates'])) {
    $task_updates = json_decode($_POST['task_updates'], true);

    // Atualizar as tarefas com os novos status
    foreach ($task_updates as $task_update) {
        foreach ($data['Tasks'] as &$task) {
            if ($task['key'] == $task_update['key']) {
                $task['ok'] = $task_update['ok'];
                break;
            }
        }
    }

    saveData($file, $data);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcar Tarefas como Concluídas</title>
    <script>
        // Função para armazenar as atualizações de status de tarefas
        let taskUpdates = [];

        // Função para alternar o status de uma tarefa
        function toggleCheckbox(taskId) {
            const checkbox = document.getElementById('task-' + taskId);
            const status = checkbox.checked ? true : false;

            // Armazenar o status da tarefa para envio posterior
            taskUpdates = taskUpdates.filter(task => task.key !== taskId); // Remove task if already in array
            taskUpdates.push({ key: taskId, ok: status });

            console.log(taskUpdates); // Debug: Verificar atualizações
        }

        // Função para salvar as alterações no servidor
        function saveTasks() {
            const formData = new FormData();
            formData.append('task_updates', JSON.stringify(taskUpdates));

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert('Alterações salvas com sucesso!');
                location.reload(); // Recarrega a página para mostrar os dados atualizados
            })
            .catch(error => console.error('Erro:', error));
        }
    </script>
</head>
<body>
    <h1>Tarefas</h1>
    <form>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <label>
                        <input 
                            type="checkbox" 
                            id="task-<?= $task['key'] ?>" 
                            <?= $task['ok'] ? 'checked' : '' ?>
                            onclick="toggleCheckbox(<?= $task['key'] ?>)">
                        <?= htmlspecialchars($task['name']) ?> 
                        (Prazo: <?= date('d/m/Y', $task['date_end']) ?>)
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
    </form>

    <h2>Status das Tarefas:</h2>
    <p>
        Tarefas Concluídas: 
        <?= count(array_filter($tasks, fn($task) => $task['ok'])) ?>
    </p>
    <p>
        Tarefas Pendentes: 
        <?= count(array_filter($tasks, fn($task) => !$task['ok'])) ?>
    </p>

    <button type="button" onclick="saveTasks()">Salvar Alterações</button>
</body>
</html>

