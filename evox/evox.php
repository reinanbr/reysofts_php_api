<?php
// Carrega os dados do JSON
$dataFile = 'db.json';
$data = json_decode(file_get_contents($dataFile), true)['evox'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Nutricional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Painel Nutricional</h1>

    <!-- Formulário -->
    <form id="nutritionForm" class="my-4">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="mass" class="form-label">Massa (kg)</label>
                <input type="number" step="0.1" class="form-control" name="mass" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="protein" class="form-label">Proteínas (g)</label>
                <input type="number" step="0.1" class="form-control" name="protein" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="carbs" class="form-label">Carboidratos (g)</label>
                <input type="number" step="0.1" class="form-control" name="carbs" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="calories" class="form-label">Quilocalorias (kcal)</label>
                <input type="number" step="0.1" class="form-control" name="calories" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="fat" class="form-label">Gordura (g)</label>
                <input type="number" step="0.1" class="form-control" name="fat" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="water" class="form-label">Água (litros)</label>
                <input type="number" step="0.1" class="form-control" name="water" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>

    <!-- Tabela -->
    <h2 class="mt-5">Histórico</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Data</th>
            <th>Massa (kg)</th>
            <th>Proteínas (g)</th>
            <th>Carboidratos (g)</th>
            <th>Quilocalorias (kcal)</th>
            <th>Gordura (g)</th>
            <th>Água (litros)</th>
            <th>Última Atualização</th>
        </tr>
        </thead>
        <tbody id="dataTable">
        <?php foreach ($data as $entry): ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($entry['date_key'])) ?></td>
                <td><?= $entry['mass'] ?></td>
                <td><?= $entry['protein'] ?></td>
                <td><?= $entry['carbs'] ?></td>
                <td><?= $entry['calories'] ?></td>
                <td><?= $entry['fat'] ?></td>
                <td><?= $entry['water'] ?></td>
                <td><?= date('d/m/Y H:i:s', $entry['last_update']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
// Envia os dados do formulário via AJAX
$('#nutritionForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: 'process.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            alert(response);
            location.reload(); // Recarrega os dados
        },
        error: function() {
            alert('Erro ao salvar os dados.');
        }
    });
});
</script>
</body>
</html>
