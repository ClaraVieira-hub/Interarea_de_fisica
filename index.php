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
$relatorio = new RelatorioEmbasa($qualidadeAgua);

$registros = $relatorio->carregar(
    __DIR__ . '/dataset/RelatorioEmbasa.csv'
);

$resultado = null;
$erro = '';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['calcularPH'])) {
            $concentracao = (float) $_POST['concentracaoH'];

            $resultado = $qualidadeAgua
                ->calcularPHPorConcentracao($concentracao);
        }

        if (isset($_POST['avaliarAgua'])) {
            $resultado = $qualidadeAgua->classificarAgua(
                (float) $_POST['ph'],
                (float) $_POST['turbidez'],
                (float) $_POST['cloro'],
                (float) $_POST['dureza'],
                (float) $_POST['temperatura']
            );
        }

        if (isset($_POST['simularBiofiltro'])) {
            $resultado = $biofiltro->aplicarEficiencia(
                (float) $_POST['turbidezInicial'],
                (float) $_POST['eficiencia']
            );
        }
    }
} catch (InvalidArgumentException $e) {
    $erro = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratório da Água</title>
    <link rel="stylesheet" href="templates/css/style.css">
</head>
<body>

<h1>Laboratório Digital da Água</h1>

<?php if ($erro !== ''): ?>
    <p><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<section>
    <h2>Calculadora de pH</h2>

    <form method="POST">
        <label>Concentração de H+:</label>
        <input type="number" name="concentracaoH"
               step="any" min="0" required>
        <button type="submit" name="calcularPH">Calcular pH</button>
    </form>

    <?php if (isset($_POST['calcularPH']) && is_float($resultado)): ?>
        <p>pH: <?= $resultado ?></p>
        <p><?= $qualidadeAgua->classificarPH($resultado) ?></p>
    <?php endif; ?>
</section>

<section>
    <h2>Qualidade da Água</h2>

    <form method="POST">
        <label>pH:</label>
        <input type="number" name="ph" step="any" required>

        <label>Turbidez (NTU):</label>
        <input type="number" name="turbidez" step="any" required>

        <label>Cloro residual (mg/L):</label>
        <input type="number" name="cloro" step="any" required>

        <label>Dureza (mg/L):</label>
        <input type="number" name="dureza" step="any" required>

        <label>Temperatura (°C):</label>
        <input type="number" name="temperatura" step="any" required>

        <button type="submit" name="avaliarAgua">Avaliar água</button>
    </form>

    <?php if (isset($_POST['avaliarAgua']) && is_array($resultado)): ?>
        <?php foreach ($resultado as $indicador => $valor): ?>
            <p>
                <?= htmlspecialchars((string) $indicador) ?>:
                <?= is_bool($valor) ? ($valor ? 'Sim' : 'Não') : htmlspecialchars((string) $valor) ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<section>
    <h2>Simulador de Biofiltro</h2>

    <form method="POST">
        <label>Turbidez inicial:</label>
        <input type="number" name="turbidezInicial"
               step="any" min="0" required>

        <label>Eficiência de remoção (%):</label>
        <input type="number" name="eficiencia"
               step="any" min="0" max="100" value="80" required>

        <button type="submit" name="simularBiofiltro">
            Simular filtro
        </button>
    </form>

    <?php if (isset($_POST['simularBiofiltro']) && is_float($resultado)): ?>
        <p>Turbidez após o filtro: <?= $resultado ?> NTU</p>
    <?php endif; ?>
</section>

<section>
    <h2>Relatórios da Embasa</h2>

    <?php foreach ($registros as $registro): ?>
        <p><?= htmlspecialchars(implode(' | ', $registro)) ?></p>
    <?php endforeach; ?>
</section>

</body>
</html>
