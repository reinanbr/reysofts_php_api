<?php
// Caminho para o arquivo db.json
$file = 'db.json';

// Função para carregar o JSON
function loadData($file) {
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    }
    return ["Rotine" => []];
}

// Função para salvar o JSON
function saveData($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// Processar atualização via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['key'])) {
    $data = loadData($file);

    // Obter a data atual
    $dateKey = date("Ymd");
    foreach ($data['Rotine'] as &$entry) {
        if ($entry['date_key'] === $dateKey) {
            $entry[$_POST['key']] = intval($_POST['value']);
        }
    }
    saveData($file, $data);

    // Calcular porcentagem de progresso
    $today = array_filter($data['Rotine'], fn($item) => $item['date_key'] === $dateKey);
    if (!empty($today)) {
        $today = array_values($today)[0];
        $totalActivities = count($today) - 2; // Exclui 'date' e 'date_key'
        $completedActivities = array_sum(array_filter($today, fn($value) => $value === 1));
        $completionPercentage = ($totalActivities > 0) ? ($completedActivities / $totalActivities) * 100 : 0;

        // Responder com JSON
        echo json_encode(['percentage' => round($completionPercentage, 2)]);
        exit;
    }
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
    saveData($file, $data);
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
    <title>Rotina do Dia</title>
    <script>
        // Função para atualizar uma atividade via AJAX
        function updateActivity(key, value) {
            const formData = new FormData();
            formData.append('key', key);
            formData.append('value', value);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.percentage !== undefined) {
                    document.getElementById('progress').textContent = `Progresso: ${data.percentage}%`;
                }
            })
            .catch(error => console.error('Erro:', error));
        }

        // Função para desmarcar um rádio ao clicar nele
        function toggleRadio(radio) {
            if (radio.checked) {
                radio.checked = false;
                updateActivity(radio.name, 0); // Envia o valor 0 para o servidor
            } else {
                radio.checked = true;
                updateActivity(radio.name, 1); // Envia o valor 1 para o servidor
            }
        }
    </script>
</head>
<body>
    <h1>Atividades do Dia: <?= $date ?></h1>
    <form>
        <ul>
            <?php foreach ($today as $key => $value): ?>
                <?php if (!in_array($key, ["date", "date_key"])): ?>
                    <li>
                        <label>
                            <input type="radio" name="<?= $key ?>" value="1" <?= $value == 1 ? 'checked' : '' ?> onclick="toggleRadio(this)">
                            <?= ucfirst(str_replace('_', ' ', $key)) ?>
                        </label>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </form>
    <h2 id="progress">Progresso: <?= round($completionPercentage, 2) ?>%</h2>
</body>
</html>

