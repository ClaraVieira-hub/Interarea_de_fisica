<?php

declare(strict_types=1);

require_once __DIR__ . '/src/QualidadeAgua.php';
require_once __DIR__ . '/src/RelatorioEmbasa.php';
require_once __DIR__ . '/src/Biofiltro.php';

use App\QualidadeAgua;
use App\RelatorioEmbasa;
use App\Biofiltro;

$qualidadeAgua = new QualidadeAgua();
$biofiltro = new Biofiltro();

$turbidezFinal = null;
$erroBiofiltro = '';
$turbidezInicial = null;
$eficiencia = 80.0;

$relatorio = new RelatorioEmbasa($qualidadeAgua);
$registros = $relatorio->carregar(__DIR__ . '/dataset/RelatorioEmbasa.csv');

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['simularBiofiltro'])
) {
    try {
        if (
            !isset($_POST['turbidezInicial'], $_POST['eficienciaBiofiltro'])
            || $_POST['turbidezInicial'] === ''
            || $_POST['eficienciaBiofiltro'] === ''
            || !is_numeric($_POST['turbidezInicial'])
            || !is_numeric($_POST['eficienciaBiofiltro'])
        ) {
            throw new InvalidArgumentException('Preencha os campos corretamente.');
        }

        $turbidezInicial = (float) $_POST['turbidezInicial'];
        $eficiencia = (float) $_POST['eficienciaBiofiltro'];

        $turbidezFinal = $biofiltro->aplicarEficiencia(
            $turbidezInicial,
            $eficiencia
        );
    } catch (InvalidArgumentException $e) {
        $erroBiofiltro = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoramento da Qualidade da Água</title>
    <link rel="stylesheet" href="templates/css/style.css">
</head>

<body>
    <main class="container">

        <section class="card">
            <h1>Calculadora de pH</h1>
            <p>Digite a concentração de H⁺:</p>
            <input type="number" id="h" step="any" min="0" placeholder="Ex.: 0.001">
            <button type="button" onclick="calcularPH()">Calcular pH</button>
            <div id="resultadoPH" class="resultado"></div>
        </section>

        <section class="card">
            <h1>Qualidade da Água</h1>

            <label for="phAgua">pH da água</label>
            <input type="number" id="phAgua" step="any" min="0" placeholder="Ex.: 7">

            <label for="turbidez">Turbidez</label>
            <input type="number" id="turbidez" step="any" min="0" placeholder="Ex.: 5">

            <label for="cloroResidual">Cloro residual</label>
            <input type="number" id="cloroResidual" step="any" min="0" placeholder="Ex.: 2">

            <label for="dureza">Dureza</label>
            <input type="number" id="dureza" step="any" min="0" placeholder="Ex.: 150">

            <label for="temperatura">Temperatura (°C)</label>
            <input type="number" id="temperatura" step="any" min="0" max="100" placeholder="Ex.: 25">

            <button type="button" onclick="avaliarAgua()">Avaliar água</button>
            <div id="resultadoAgua" class="resultado"></div>
        </section>

        <section class="card">
            <h1>Simulador de Biofiltro</h1>
            <p>Compare a turbidez antes e depois da filtragem.</p>

            <form method="POST">
                <label for="turbidezInicial">Turbidez inicial (NTU)</label>
                <input
                    type="number"
                    name="turbidezInicial"
                    id="turbidezInicial"
                    min="0"
                    step="any"
                    value="<?= htmlspecialchars((string) ($turbidezInicial ?? '')) ?>"
                    required
                >

                <label for="eficienciaBiofiltro">Eficiência do biofiltro (%)</label>
                <input
                    type="number"
                    name="eficienciaBiofiltro"
                    id="eficienciaBiofiltro"
                    min="0"
                    max="100"
                    step="any"
                    value="<?= htmlspecialchars((string) $eficiencia) ?>"
                    required
                >

                <button type="submit" name="simularBiofiltro">
                    Simular filtragem
                </button>
            </form>

            <?php if ($erroBiofiltro !== ''): ?>
                <div class="resultado">
                    <?= htmlspecialchars($erroBiofiltro) ?>
                </div>
            <?php endif; ?>

            <?php if ($turbidezFinal !== null): ?>
                <div class="resultado">
                    <p>
                        Turbidez inicial:
                        <?= number_format($turbidezInicial, 2, ',', '.') ?> NTU
                    </p>
                    <p>
                        Turbidez após filtragem:
                        <?= number_format($turbidezFinal, 2, ',', '.') ?> NTU
                    </p>
                    <p>
                        Eficiência aplicada:
                        <?= number_format($eficiencia, 2, ',', '.') ?>%
                    </p>
                </div>
            <?php endif; ?>
        </section>

        <section class="card relatorio">
            <h1>Relatórios da Embasa</h1>

            <?php if ($registros === []): ?>
                <p>Nenhum relatório importado.</p>
            <?php else: ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Localidade</th>
                                <th>pH</th>
                                <th>Turbidez</th>
                                <th>Cloro</th>
                                <th>Dureza</th>
                                <th>Temperatura</th>
                                <th>Situação</th>
                                <th>Fonte</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registros as $registro): ?>
                                <tr>
                                    <td><?= htmlspecialchars($registro['localidade']) ?></td>
                                    <td><?= $registro['ph'] !== null ? htmlspecialchars((string) $registro['ph']) : '-' ?></td>
                                    <td><?= $registro['turbidez'] !== null ? htmlspecialchars((string) $registro['turbidez']) : '-' ?></td>
                                    <td><?= $registro['cloro_residual'] !== null ? htmlspecialchars((string) $registro['cloro_residual']) : '-' ?></td>
                                    <td><?= $registro['dureza'] !== null ? htmlspecialchars((string) $registro['dureza']) : '-' ?></td>
                                    <td><?= $registro['temperatura'] !== null ? htmlspecialchars((string) $registro['temperatura']) . ' °C' : '-' ?></td>
                                    <td><?= htmlspecialchars($relatorio->situacao($registro)) ?></td>
                                    <td><?= htmlspecialchars($registro['fonte']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

    </main>

    <script>
        function calcularPH() {
            const valor = Number(document.getElementById('h').value);
            const resultado = document.getElementById('resultadoPH');

            if (!Number.isFinite(valor) || valor <= 0) {
                resultado.textContent = 'Digite uma concentração de H+ maior que zero.';
                return;
            }

            const ph = -Math.log10(valor);
            resultado.textContent = `pH = ${ph.toFixed(2)}`;
        }

        function avaliarAgua() {
            const ph = Number(document.getElementById('phAgua').value);
            const turbidez = Number(document.getElementById('turbidez').value);
            const cloro = Number(document.getElementById('cloroResidual').value);
            const dureza = Number(document.getElementById('dureza').value);
            const temperatura = Number(document.getElementById('temperatura').value);
            const resultado = document.getElementById('resultadoAgua');

            if (![ph, turbidez, cloro, dureza, temperatura].every(Number.isFinite)) {
                resultado.textContent = 'Preencha todos os campos.';
                return;
            }

            if (turbidez < 0 || cloro < 0 || dureza < 0 || temperatura < 0 || temperatura > 100) {
                resultado.textContent = 'Verifique os valores informados.';
                return;
            }

            const parametros = {
                'pH': ph >= 6 && ph <= 9.5,
                'Turbidez': turbidez <= 5,
                'Cloro residual': cloro >= 0.2 && cloro <= 5,
                'Dureza': dureza <= 300
            };

            const fora = Object.entries(parametros)
                .filter(([, dentro]) => !dentro)
                .map(([nome]) => nome);

            if (fora.length === 0) {
                resultado.innerHTML = '<strong>Água dentro dos padrões.</strong>';
            } else {
                resultado.innerHTML = `<strong>Fora do padrão:</strong> ${fora.join(', ')}.`;
            }
        }
    </script>
</body>

</html>
