<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

class Biofiltro
{
    public function calcularEficienciaRemocao(float $antes, float $depois): float
    {
        if ($antes < 0 || $depois < 0) {
            throw new InvalidArgumentException('Os valores não podem ser negativos.');
        }

        if ($antes === 0.0) {
            throw new InvalidArgumentException('O valor de antes não pode ser zero (divisão por zero).');
        }

        return round((($antes - $depois) / $antes) * 100, 2);
    }

    public function aplicarEficiencia(float $antes, float $eficienciaPercentual): float
    {
        if ($antes < 0) {
            throw new InvalidArgumentException('O valor de antes não pode ser negativo.');
        }

        if ($eficienciaPercentual < 0 || $eficienciaPercentual > 100) {
            throw new InvalidArgumentException('A eficiência deve estar entre 0 e 100.');
        }

        return round($antes * (1 - $eficienciaPercentual / 100), 2);
    }

    public function aplicarCamadas(float $antes, array $eficiencias): float
    {
        $valor = $antes;

        foreach ($eficiencias as $eficiencia) {
            $valor = $this->aplicarEficiencia($valor, $eficiencia);
        }

        return $valor;
    }
}