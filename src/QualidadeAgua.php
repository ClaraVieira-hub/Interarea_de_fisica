<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

class QualidadeAgua
{
    private const PH_MIN = 6.0;
    private const PH_MAX = 9.5;
    private const TURBIDEZ_MAX = 5.0;
    private const CLORO_MIN = 0.2;
    private const CLORO_MAX = 5.0;
    private const DUREZA_MAX = 300.0;

    public function classificarPH(float $ph): string
    {
        return $ph >= self::PH_MIN && $ph <= self::PH_MAX
            ? 'Dentro do padrão'
            : 'Fora do padrão';
    }

    public function calcularPHPorConcentracao(float $concentracaoH): float
    {
        if ($concentracaoH <= 0) {
            throw new InvalidArgumentException('Concentração de H+ deve ser maior que zero.');
        }

        return round(-log10($concentracaoH), 2);
    }

    public function classificarTurbidez(float $turbidez): string
    {
        return $turbidez >= 0 && $turbidez <= self::TURBIDEZ_MAX
            ? 'Dentro do padrão'
            : 'Fora do padrão';
    }

    public function classificarCloroResidual(float $cloro): string
    {
        return $cloro >= self::CLORO_MIN && $cloro <= self::CLORO_MAX
            ? 'Dentro do padrão'
            : 'Fora do padrão';
    }

    public function classificarDureza(float $dureza): string
    {
        return $dureza >= 0 && $dureza <= self::DUREZA_MAX
            ? 'Dentro do padrão'
            : 'Fora do padrão';
    }

    public function classificarAgua(
        float $ph,
        float $turbidez,
        float $cloroResidual,
        float $dureza,
        float $temperatura
    ): array {
        $resultado = [
            'ph' => $this->classificarPH($ph),
            'turbidez' => $this->classificarTurbidez($turbidez),
            'cloroResidual' => $this->classificarCloroResidual($cloroResidual),
            'dureza' => $this->classificarDureza($dureza),
            'temperatura' => $temperatura
        ];

        $resultado['potavel'] =
            $resultado['ph'] === 'Dentro do padrão' &&
            $resultado['turbidez'] === 'Dentro do padrão' &&
            $resultado['cloroResidual'] === 'Dentro do padrão' &&
            $resultado['dureza'] === 'Dentro do padrão';

        return $resultado;
    }
}
