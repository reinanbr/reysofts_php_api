<?php
session_start();

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

// Processar atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['key']) && isset($_POST['value'])) {
    $key = $_POST['key'];
    $value = intval($_POST['value']);

    $data = loadData($file);
    $dateKey = date("Ymd");

    // Atualizar o valor no arquivo JSON
    foreach ($data['Rotine'] as &$entry) {
        if ($entry['date_key'] === $dateKey) {
            $entry[$key] = $value;
        }
    }
    saveData($file, $data);

    // Calcular a porcentagem de progresso
    $today = array_filter($data['Rotine'], fn($item) => $item['date_key'] === $dateKey);
    if (!empty($today)) {
        $today = array_values($today)[0];
        $totalActivities = count($today) - 2; // Exclui 'date' e 'date_key'
        $completedActivities = array_sum(array_filter($today, fn($v) => $v === 1));
        $completionPercentage = ($totalActivities > 0) ? ($completedActivities / $totalActivities) * 100 : 0;

        // Responder com a nova porcentagem
        echo json_encode(['percentage' => round($completionPercentage, 2)]);
        exit;
    }
}

// Responder com erro se algo deu errado
echo json_encode(['error' => 'Requisição inválida']);
exit;

