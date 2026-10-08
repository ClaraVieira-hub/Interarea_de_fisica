<?php

declare(strict_types=1);

namespace Controller;

use Model\QualidadeAguaModel;

class QualidadeAguaController
{
    public function __construct(private ?QualidadeAguaModel $qualidadeAguaModel = null)
    {
    }

    public function calculatePh(float $concentracaoH): array
    {
        if ($concentracaoH <= 0) {
            return [
                'ph' => null,
                'phRange' => 'A concentração de H+ deve ser maior que zero.'
            ];
        }

        $ph = round(-log10($concentracaoH), 2);

        return [
            'ph' => $ph,
            'phRange' => $this->classifyPh($ph)
        ];
    }

    public function classifyPh(float $ph): string
    {
        return match (true) {
            $ph < 6.0 => 'Fora do padrão',
            $ph <= 9.5 => 'Dentro do padrão',
            default => 'Fora do padrão'
        };
    }

    public function classifyTurbidez(float $turbidez): string
    {
        return $turbidez >= 0 && $turbidez <= 5.0 ? 'Dentro do padrão' : 'Fora do padrão';
    }

    public function classifyCloro(float $cloro): string
    {
        return $cloro >= 0.2 && $cloro <= 5.0 ? 'Dentro do padrão' : 'Fora do padrão';
    }

    public function classifyDureza(float $dureza): string
    {
        return $dureza >= 0 && $dureza <= 300.0 ? 'Dentro do padrão' : 'Fora do padrão';
    }

    public function evaluateQuality(
        float $concentracaoH,
        float $turbidez,
        float $temperatura,
        float $cloroResidual,
        float $dureza
    ): array {
        $error = $this->validateData($concentracaoH, $turbidez, $temperatura, $cloroResidual, $dureza);

        if ($error !== null) {
            return $error;
        }

        $phData = $this->calculatePh($concentracaoH);
        $ph = $phData['ph'];

        $parametros = [
            'ph' => $this->classifyPh($ph),
            'turbidez' => $this->classifyTurbidez($turbidez),
            'cloroResidual' => $this->classifyCloro($cloroResidual),
            'dureza' => $this->classifyDureza($dureza)
        ];

        $problemas = count(array_filter(
            $parametros,
            static fn (string $valor): bool => $valor === 'Fora do padrão'
        ));

        $qualidade = $problemas === 0
            ? 'Água própria para consumo'
            : ($problemas === 1
                ? 'Água com atenção: um parâmetro fora do padrão'
                : 'Água imprópria para consumo');

        return [
            'ph' => $ph,
            'parametros' => $parametros,
            'problemas' => $problemas,
            'qualidade' => $qualidade,
            'temperatura' => $temperatura
        ];
    }

    public function validateData(
        float $concentracaoH,
        float $turbidez,
        float $temperatura,
        float $cloroResidual,
        float $dureza
    ): ?array {
        if ($concentracaoH <= 0) {
            return [
                'ph' => null,
                'qualidade' => 'A concentração de H+ deve ser maior que zero.'
            ];
        }

        if ($turbidez < 0 || $cloroResidual < 0 || $dureza < 0) {
            return [
                'ph' => null,
                'qualidade' => 'Turbidez, cloro residual e dureza não podem ser negativos.'
            ];
        }

        if ($temperatura < 0 || $temperatura > 100) {
            return [
                'ph' => null,
                'qualidade' => 'A temperatura deve estar entre 0 e 100 °C.'
            ];
        }

        return null;
    }

    public function saveAnalise(
        float $concentracaoH,
        float $ph,
        float $turbidez,
        float $temperatura,
        float $cloroResidual,
        float $dureza,
        string $classificacao
    ): bool {
        if ($this->qualidadeAguaModel === null) {
            return false;
        }

        return $this->qualidadeAguaModel->createAnalise(
            $concentracaoH,
            $ph,
            $turbidez,
            $temperatura,
            $cloroResidual,
            $dureza,
            $classificacao
        );
    }
}
