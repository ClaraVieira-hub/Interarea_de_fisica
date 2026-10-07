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


    </div>


    <script src="templates/js/script.js"></script>

</body>


</html>