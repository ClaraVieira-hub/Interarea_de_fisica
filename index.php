<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\QualidadeAgua;
use App\RelatorioEmbasa;

$relatorio = new RelatorioEmbasa(new QualidadeAgua());
$registros = $relatorio->carregar(__DIR__ . '/dados/relatorios_embasa.csv');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoramento da Água</title>
    <link rel="stylesheet" href="templates/css/style.css">

</head>

<body>

    <div class="principal">

        <div class="card">

            <h1>Calculadora de pH</h1>

            <p>Digite a concentração de H⁺:</p>

            <input type="number" id="h" placeholder="Ex: 0.001">

            <button onclick="calcularPH()">Calcular pH</button>

            <h2 id="resultadoPH"></h2>

        </div>


        <div class="card">

            <h1>Qualidade da Água</h1>

            <p>Digite o pH da água:</p>

            <input type="number" id="phAgua" placeholder="Ex: 7">

            <p>Turbidez:</p>

            <input type="number" id="turbidez" placeholder="Ex: 5">

            <p>Cloro Residual:</p>

            <input type="number" id="cloroResidual" placeholder="Ex: 2">


            <p>Dureza:</p>

            <input type="number" id="dureza" placeholder="Ex: 500">

            <p>Temperatura:</p>

            <input type="number" id="temperatura" placeholder="Ex: 35°">



            <button onclick="avaliarAgua()">Avaliar</button>

            <h2 id="resultadoAgua"></h2>

        </div>

        <div class="card relatorio">

            <h1>Relatórios da Embasa</h1>

            <?php if ($registros === []): ?>
                <p>Nenhum relatório importado ainda.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Município</th>
                            <th>Sistema</th>
                            <th>Coleta</th>
                            <th>pH</th>
                            <th>Turbidez</th>
                            <th>Cloro</th>
                            <th>Dureza</th>
                            <th>Situação</th>
                            <th>Fonte</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $registro): ?>
                            <tr>
                                <td><?= htmlspecialchars($registro['municipio']) ?></td>
                                <td><?= htmlspecialchars($registro['sistema']) ?></td>
                                <td><?= htmlspecialchars($registro['data_coleta']) ?></td>
                                <td><?= $registro['ph'] ?? '-' ?></td>
                                <td><?= $registro['turbidez'] ?? '-' ?></td>
                                <td><?= $registro['cloro_residual'] ?? '-' ?></td>
                                <td><?= $registro['dureza'] ?? '-' ?></td>
                                <td><?= $relatorio->situacao($registro) ?></td>
                                <td><?= htmlspecialchars($registro['fonte']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        </div>


    </div>


    <script src="templates/js/script.js"></script>

</body>


</html>