<?php
// Carrega os dados do JSON
$dataFile = 'db.json';
$data = json_decode(file_get_contents($dataFile), true)['evox'];
?>

<!-- Corpo da Tabela -->
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
