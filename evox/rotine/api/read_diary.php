<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Caminho para o arquivo db.json
$file = 'db.json';

// Função para carregar o JSON
function loadData($file) {
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    }
    return ["Diary" => []];
}

// Retornar os dados como JSON via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch'])) {
    $data = loadData($file);
    echo json_encode($data['Diary'] ?? []);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Diário</h1>
    <div id="diary-container">
        <p class="text-center">Carregando resenhas...</p>
    </div>
</div>
<script>
    // Função para carregar e renderizar os dados do diário
    async function loadDiary() {
        try {
            const response = await fetch('read_diary.php?fetch=1');
            const data = await response.json();

            const container = document.getElementById('diary-container');
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = '<p class="text-center">Nenhuma resenha publicada ainda.</p>';
                return;
            }

            data.reverse().forEach(entry => {
                const card = document.createElement('div');
                card.className = 'card my-3';

                const header = document.createElement('div');
                header.className = 'card-header d-flex justify-content-between';
                header.innerHTML = `
                    <span><strong>${entry.author}</strong></span>
                    <span>${new Date(entry.timestamp).toLocaleString()}</span>
                `;

                const body = document.createElement('div');
                body.className = 'card-body';
                body.innerText = entry.content; // Exibe o conteúdo como texto puro

                card.appendChild(header);
                card.appendChild(body);
                container.appendChild(card);
            });
        } catch (error) {
            console.error('Erro ao carregar as resenhas:', error);
            document.getElementById('diary-container').innerHTML = '<p class="text-center text-danger">Erro ao carregar as resenhas.</p>';
        }
    }

    // Carregar os dados ao iniciar a página
    document.addEventListener('DOMContentLoaded', loadDiary);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

