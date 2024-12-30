<?php
$dataFile = 'db.json';
$data = json_decode(file_get_contents($dataFile), true);

// Obtém a data atual no formato "Y-m-d"
$currentDateKey = date('Y-m-d');

// Verifica se a requisição é para buscar dados
if (isset($_POST['action']) && $_POST['action'] === 'fetch_by_date') {
    $dateKey = $_POST['date_key'];
    foreach ($data['evox'] as $entry) {
        if ($entry['date_key'] === $dateKey) {
            echo json_encode($entry);
            exit();
        }
    }
    echo json_encode(["error" => "Nenhum dado encontrado para a data especificada."]);
    exit();
}

// Atualiza ou adiciona os dados para a data atual
$found = false;
foreach ($data['evox'] as &$entry) {
    if ($entry['date_key'] === $currentDateKey) {
        // Atualiza os valores existentes
        $entry['mass'] = $_POST['mass'];
        $entry['protein'] += $_POST['protein'];
        $entry['carbs'] += $_POST['carbs'];
        $entry['calories'] += $_POST['calories'];
        $entry['fat'] += $_POST['fat'];
        $entry['water'] += $_POST['water'];
        $entry['last_update'] = time();
        $found = true;
        break;
    }
}

// Se não encontrou a data, cria uma nova entrada
if (!$found) {
    $data['evox'][] = [
        "date_key" => $currentDateKey,
        "mass" => $_POST['mass'],
        "protein" => $_POST['protein'],
        "carbs" => $_POST['carbs'],
        "calories" => $_POST['calories'],
        "fat" => $_POST['fat'],
        "water" => $_POST['water'],
        "last_update" => time()
    ];
}

// Salva os dados no arquivo JSON
file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
echo "Dados salvos com sucesso!";
exit();
