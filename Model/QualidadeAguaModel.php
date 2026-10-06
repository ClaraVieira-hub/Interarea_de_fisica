<?php

namespace Model;

use PDO;
use PDOException;

class Ph
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    
    public function createAnalise(float $concentracaoH,float $ph,float $turbidez,float $temperatura,float $cloroResidual,float $dureza,string $classificacao): bool {
        try {
            $sql = "INSERT INTO analises_agua
                        (concentracao_h, ph, turbidez, temperatura, cloro_residual, dureza, classificacao)
                    VALUES
                        (:concentracaoH, :ph, :turbidez, :temperatura, :cloroResidual, :dureza, :classificacao)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":concentracaoH", $concentracaoH, PDO::PARAM_STR);
            $stmt->bindValue(":ph", $ph, PDO::PARAM_STR);
            $stmt->bindValue(":turbidez", $turbidez, PDO::PARAM_STR);
            $stmt->bindValue(":temperatura", $temperatura, PDO::PARAM_STR);
            $stmt->bindValue(":cloroResidual", $cloroResidual, PDO::PARAM_STR);
            $stmt->bindValue(":dureza", $dureza, PDO::PARAM_STR);
            $stmt->bindValue(":classificacao", $classificacao, PDO::PARAM_STR);
        

            return $stmt->execute();
        } catch (PDOException $error) {
            error_log("Erro ao salvar análise da água: " . $error->getMessage());
            return false;
        }
    }

}