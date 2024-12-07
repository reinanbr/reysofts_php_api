<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Carregar o arquivo JSON
    $file = 'db.json';
    if (!file_exists($file)) {
        file_put_contents($file, json_encode(["Tasks" => []], JSON_PRETTY_PRINT));
    }

    $data = json_decode(file_get_contents($file), true);

    // Obter os dados do formulário
    $key = uniqid(); // Gera um identificador único para a tarefa
    $name = $_POST['name'] ?? '';
    $date_create = time();
    $date_end = strtotime($_POST['date_end'] ?? '');

    // Validar entrada
    if (empty($name) || empty($date_end)) {
        echo json_encode(["error" => "Nome e data de término são obrigatórios."]);
        exit;
    }

    // Adicionar nova tarefa ao array
    $data['Tasks'][] = [
        "key" => $key,
        "name" => $name,
        "date_create" => $date_create,
        "date_end" => $date_end
    ];

    // Salvar no arquivo
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

    // Resposta JSON
    header("Content-Type: application/json");
    echo json_encode(["success" => true, "message" => "Tarefa criada com sucesso."]);
    exit;
}

// Responder erro se o método não for POST
header("Content-Type: application/json");
echo json_encode(["error" => "Método não permitido. Use POST."]);
exit;
?>

