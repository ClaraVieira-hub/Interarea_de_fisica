<?php

namespace Controller;

use Model\Ph;

class PhController
{
    public function __construct(private Ph $qualidadeAguaModel)
    {
    }

    
    public function calculatePh(float $concentracaoH): array
    {
        if ($concentracaoH <= 0) {
            return [
                "ph" => null,
                "phRange" => "A concentração de H+ deve ser maior que zero."
            ];
        }

        $ph = round(-log10($concentracaoH), 2);

        return [
            "ph" => $ph,
            "phRange" => $this->classifyPh($ph)
        ];
    }

   
    public function classifyPh(float $ph): string
    {
        return match (true) {
            $ph < 6.0 => "Ácida",
            $ph <= 9.0 => "Adequada para consumo",
            default => "Alcalina"
        };
    }

    public function classifyTurbidez(float $turbidez): string
    {
        return $turbidez <= 5 ? "Adequada" : "Acima do limite";
    }

    public function classifyCloro(float $cloro): string
    {
        return match (true) {
            $cloro < 0.2 => "Abaixo do mínimo",
            $cloro <= 5.0 => "Adequado",
            default => "Acima do limite"
        };
    }

    public function classifyDureza(float $dureza): string
    {
        return match (true) {
            $dureza <= 60 => "Água mole",
            $dureza <= 120 => "Dureza moderada",
            $dureza <= 180 => "Água dura",
            default => "Água muito dura"
        };
    }

   
    public function evaluateQuality(float $concentracaoH,float $turbidez,float $temperatura,float $cloroResidual,float $dureza): array {
        $error = $this->validateData($concentracaoH, $turbidez, $temperatura, $cloroResidual, $dureza);
        if ($error !== null) {
            return $error;
        }

        $phData = $this->calculatePh($concentracaoH);
        $ph = $phData["ph"];

        $parametros = [
            "ph" => $this->classifyPh($ph),
            "turbidez" => $this->classifyTurbidez($turbidez),
            "cloroResidual" => $this->classifyCloro($cloroResidual),
            "dureza" => $this->classifyDureza($dureza),
        ];

       
        $problemas = 0;
        if ($ph < 6.0 || $ph > 9.0) {
            $problemas++;
        }
        if ($turbidez > 5) {
            $problemas++;
        }
        if ($cloroResidual < 0.2 || $cloroResidual > 5.0) {
            $problemas++;
        }
        if ($dureza > 300) {
            $problemas++;
        }

        $qualidade = match (true) {
            $problemas === 0 => "Água própria para consumo",
            $problemas === 1 => "Água com atenção: um parâmetro fora do padrão",
            default => "Água imprópria para consumo"
        };

        return [
            "ph" => $ph,
            "parametros" => $parametros,
            "problemas" => $problemas,
            "qualidade" => $qualidade
        ];
    }

   
    public function validateData( float $concentracaoH, float $turbidez, float $temperatura, float $cloroResidual, float $dureza): ?array {
        if ($concentracaoH <= 0) {
            return ["ph" => null, "qualidade" => "A concentração de H+ deve ser maior que zero."];
        }

        if ($turbidez < 0 || $cloroResidual < 0 || $dureza < 0) {
            return ["ph" => null, "qualidade" => "Turbidez, cloro residual e dureza não podem ser negativos."];
        }

        if ($temperatura < 0 || $temperatura > 100) {
            return ["ph" => null, "qualidade" => "A temperatura deve estar entre 0 e 100 °C."];
        }

        return null;
    }

    
    public function saveAnalise( float $concentracaoH,float $ph,float $turbidez,float $temperatura,float $cloroResidual,float $dureza,string $classificacao): bool {
        return $this->qualidadeAguaModel->createAnalise($concentracaoH, $ph, $turbidez, $temperatura,$cloroResidual, $dureza, $classificacao);
    }

}