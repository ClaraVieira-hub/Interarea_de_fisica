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
}