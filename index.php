<?php

declare(strict_types=1);

require_once __DIR__ . '/src/QualidadeAgua.php';
require_once __DIR__ . '/src/RelatorioEmbasa.php';
require_once __DIR__ . '/src/Biofiltro.php';

use App\QualidadeAgua;
use App\RelatorioEmbasa;
use App\Biofiltro;

$agua = new QualidadeAgua();
$filtro = new Biofiltro();
$relatorio = new RelatorioEmbasa($agua);
$registros = $relatorio->carregar(__DIR__ . '/dataset/RelatorioEmbasa.csv');

$resultado = null;
$erro = '';

try {
    if (isset($_POST['calcularPH'])) {
        $resultado = $agua->calcularPHPorConcentracao((float) $_POST['concentracaoH']);
    } elseif (isset($_POST['avaliarAgua'])) {
        $resultado = $agua->classificarAgua(
            (float) $_POST['ph'],
            (float) $_POST['turbidez'],
            (float) $_POST['cloro'],
            (float) $_POST['dureza'],
            (float) $_POST['temperatura']
        );
    } elseif (isset($_POST['simularBiofiltro'])) {
        $resultado = $filtro->aplicarEficiencia(
            (float) $_POST['turbidezInicial'],
            (float) $_POST['eficiencia']
        );
    }
} catch (InvalidArgumentException $e) {
    $erro = $e->getMessage();
}

function escapar(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratório Digital da Água</title>
    <link rel="stylesheet" href="templates/css/style.css">
</head>

<body>

    <header class="cabecalho">
        <h1>Laboratório Digital da Água</h1>
        <p>Analise a qualidade da água e simule um biofiltro.</p>
    </header>

    <?php if ($erro !== ''): ?>
        <p class="erro"><?= escapar($erro) ?></p>
    <?php endif; ?>

    <main class="container">

        <section class="card">
            <h2>Calculadora de pH</h2>
            <form method="POST">
                <label>Concentração de H+:</label>
                <input type="number" name="concentracaoH" step="any" min="0" required>
                <button name="calcularPH">Calcular pH</button>
            </form>
            <?php if (isset($_POST['calcularPH']) && is_float($resultado)): ?>
                <div class="resultado">
                    <p>pH: <?= escapar((string) $resultado) ?></p>
                    <p><?= escapar($agua->classificarPH($resultado)) ?></p>
                </div>
            <?php endif; ?>
        </section>

        <section class="card">
            <h2>Qualidade da Água</h2>
            <form method="POST">
                <?php
                $campos = [
                    'ph' => 'pH',
                    'turbidez' => 'Turbidez (NTU)',
                    'cloro' => 'Cloro residual (mg/L)',
                    'dureza' => 'Dureza (mg/L)',
                    'temperatura' => 'Temperatura (°C)'
                ];
                foreach ($campos as $nome => $rotulo):
                ?>
                    <label><?= escapar($rotulo) ?>:</label>
                    <input type="number" name="<?= escapar($nome) ?>" step="any" required>
                <?php endforeach; ?>
                <button name="avaliarAgua">Avaliar água</button>
            </form>
            <?php if (isset($_POST['avaliarAgua']) && is_array($resultado)): ?>
                <div class="resultado">
                    <?php foreach ($resultado as $nome => $valor): ?>
                        <p><strong><?= escapar(ucfirst((string) $nome)) ?>:</strong>
                            <?= escapar(is_bool($valor) ? ($valor ? 'Sim' : 'Não') : (string) $valor) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="card">
            <h2>Simulador de Biofiltro</h2>

            <form method="POST">
                <label>Turbidez inicial (NTU):</label>
                <input type="number" name="turbidezInicial" min="0" step="any" required>

                <label>Eficiência de remoção (%):</label>
                <input type="number" name="eficiencia" min="0" max="100" step="any" value="80" required>

                <button name="simularBiofiltro">Simular filtro</button>
            </form>

            <?php if (isset($_POST['simularBiofiltro']) && is_float($resultado)): ?>
                <div class="resultado">
                    <h3>Resultado da filtragem</h3>

                    <p>
                        <strong>Antes do filtro:</strong>
                        <?= escapar((string) (float) $_POST['turbidezInicial']) ?> NTU
                    </p>

                    <p>
                        <strong>Eficiência de remoção:</strong>
                        <?= escapar((string) (float) $_POST['eficiencia']) ?>%
                    </p>

                    <p>
                        <strong>Depois do filtro:</strong>
                        <?= escapar((string) $resultado) ?> NTU
                    </p>
                </div>
            <?php endif; ?>
        </section>
        <section class="card relatorio">
            <h2>Relatórios da Embasa</h2>
            <?php foreach ($registros as $registro): ?>
                <p class="registro">
                    <?php
                    if (is_array($registro)) {
                        $dados = array_map(
                            fn($valor) => is_scalar($valor) ? (string) $valor : '',
                            $registro
                        );
                        echo escapar(implode(' | ', $dados));
                    } else {
                        echo escapar((string) $registro);
                    }
                    ?>
                </p>
            <?php endforeach; ?>
        </section>

    </main>
    <footer class="rodape">Laboratório Digital da Água — Projeto educacional</footer>

</body>

</html>