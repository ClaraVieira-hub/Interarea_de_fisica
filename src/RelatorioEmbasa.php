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
        $cabecalho = fgetcsv($arquivo, 0, ',', '"', '');
        $cabecalho[0] = ltrim($cabecalho[0], "\xEF\xBB\xBF"); // tira o BOM, caractere invisível que alguns editores põem no começo

        $registros = [];

        while (($linha = fgetcsv($arquivo, 0, ',', '"', '')) !== false) {
            if ($linha === [null]) {
                continue; // linha em branco
            }

            $dados = array_combine($cabecalho, $linha);

            // Lacuna 1: converta os campos numéricos. Molde para o pH:
            $ph = $dados['ph'] === '' ? null : (float) $dados['ph'];
            // Faça o mesmo para turbidez, cloro_residual, dureza e temperatura.

            // Lacuna 2: se algum entre pH, turbidez, cloro e dureza for null,
            // o registro é 'Dados incompletos' e NÃO deve ser classificado.
            // Caso contrário, chame $this->qualidadeAgua->classificarAgua(...)
            // com os cinco valores e guarde o resultado.

            $registros[] = [
                'localidade' => $dados['localidade'],
                'fonte' => $dados['fonte'],
                // acrescente aqui os valores lidos e o resultado da lacuna 2
            ];
        }

        fclose($arquivo);

        return $registros;
    }
}