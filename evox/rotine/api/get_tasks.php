<?php
header('Content-Type: application/json');

// Caminho para o arquivo JSON
$file = 'db.json';

// Verificar se o arquivo existe
if (file_exists($file)) {
    echo file_get_contents($file);
} else {
    echo json_encode(["Tasks" => []]);
}
?>

