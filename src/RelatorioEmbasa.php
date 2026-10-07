<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

class RelatorioEmbasa
{
    public function __construct(private QualidadeAgua $qualidadeAgua)
    {
    }

    public function carregar(string $caminho): array
    {
        if (!is_file($caminho)) {
            throw new InvalidArgumentException('Arquivo de dados não encontrado.');
        }

        $arquivo = fopen($caminho, 'r');
        if ($arquivo === false) {
            throw new InvalidArgumentException('Não foi possível abrir o arquivo de dados.');
        }

        $cabecalho = fgetcsv($arquivo, 0, ',', '"', '');
        $cabecalho[0] = ltrim($cabecalho[0], "\xEF\xBB\xBF"); 
        $registros = [];

        while (($linha = fgetcsv($arquivo, 0, ',', '"', '')) !== false) {
            if ($linha === [null]) {
                continue; 
            }

           
            if (count($linha) !== count($cabecalho)) {
                continue;
            }

            $dados = array_combine($cabecalho, $linha);

          
            $ph = $dados['ph'] === '' ? null : (float) $dados['ph'];
            $turbidez = $dados['turbidez'] === '' ? null : (float) $dados['turbidez'];
            $cloroResidual = $dados['cloro_residual'] === '' ? null : (float) $dados['cloro_residual'];
            $dureza = $dados['dureza'] === '' ? null : (float) $dados['dureza'];
            $temperatura = $dados['temperatura'] === '' ? null : (float) $dados['temperatura'];


            if ($ph === null || $turbidez === null || $cloroResidual === null || $dureza === null) {
                $classificacao = null;
            } else {
                
                $classificacao = $this->qualidadeAgua->classificarAgua(
                    $ph,
                    $turbidez,
                    $cloroResidual,
                    $dureza,
                    $temperatura ?? 0.0
                );
            }

            $registros[] = [
                'localidade' => $dados['localidade'],
                'ph' => $ph,
                'turbidez' => $turbidez,
                'cloro_residual' => $cloroResidual,
                'dureza' => $dureza,
                'temperatura' => $temperatura,
                'classificacao' => $classificacao,
                'fonte' => $dados['fonte'],
            ];
        }

        fclose($arquivo);

        return $registros;
    }

   
    public function situacao(array $registro): string
    {
        if ($registro['classificacao'] === null) {
            return 'Dados incompletos';
        }

        return $registro['classificacao']['potavel'] ? 'Potável' : 'Fora do padrão';
    }
}