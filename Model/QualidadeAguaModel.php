<?php

declare(strict_types=1);

namespace Model;

use PDO;

class QualidadeAguaModel
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Connection::getInstance();
    }

    public function createAnalise(
        float $concentracaoH,
        float $ph,
        float $turbidez,
        float $temperatura,
        float $cloroResidual,
        float $dureza,
        string $classificacao
    ): bool {
        $sql = 'INSERT INTO analises_agua
            (concentracao_h, ph, turbidez, temperatura, cloro_residual, dureza, classificacao)
            VALUES
            (:concentracaoH, :ph, :turbidez, :temperatura, :cloroResidual, :dureza, :classificacao)';

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':concentracaoH' => $concentracaoH,
            ':ph' => $ph,
            ':turbidez' => $turbidez,
            ':temperatura' => $temperatura,
            ':cloroResidual' => $cloroResidual,
            ':dureza' => $dureza,
            ':classificacao' => $classificacao
        ]);
    }
}
