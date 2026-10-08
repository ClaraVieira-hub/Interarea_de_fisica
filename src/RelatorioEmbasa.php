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

        $cabecalho = fgetcsv($arquivo, 0, ',', '"', '\\');

        if ($cabecalho === false) {
            fclose($arquivo);
            return [];
        }

        $cabecalho[0] = preg_replace('/^ï»¿/', '', $cabecalho[0]);
        $registros = [];

        while (($linha = fgetcsv($arquivo, 0, ',', '"', '\\')) !== false) {
            if ($linha === [null] || count(array_filter($linha, static fn ($valor) => $valor !== null && trim((string) $valor) !== '')) === 0) {
                continue;
            }

            if (count($linha) !== count($cabecalho)) {
                continue;
            }

            $dados = array_combine($cabecalho, $linha);

            if ($dados === false) {
                continue;
            }

            $ph = $this->numeroOuNull($dados['ph'] ?? null);
            $turbidez = $this->numeroOuNull($dados['turbidez'] ?? null);
            $cloroResidual = $this->numeroOuNull($dados['cloro_residual'] ?? null);
            $dureza = $this->numeroOuNull($dados['dureza'] ?? null);
            $temperatura = $this->numeroOuNull($dados['temperatura'] ?? null);

            $classificacao = null;

            if ($ph !== null && $turbidez !== null && $cloroResidual !== null && $dureza !== null) {
                $classificacao = $this->qualidadeAgua->classificarAgua(
                    $ph,
                    $turbidez,
                    $cloroResidual,
                    $dureza,
                    $temperatura ?? 0.0
                );
            }

            $registros[] = [
                'id' => $dados['id'] ?? '',
                'localidade' => $dados['localidade'] ?? '',
                'concentracao_h' => $this->numeroOuNull($dados['concentracao_h'] ?? null),
                'ph' => $ph,
                'turbidez' => $turbidez,
                'temperatura' => $temperatura,
                'cloro_residual' => $cloroResidual,
                'dureza' => $dureza,
                'classificacao' => $classificacao,
                'fonte' => $dados['fonte'] ?? ''
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

    private function numeroOuNull(?string $valor): ?float
    {
        if ($valor === null || trim($valor) === '') {
            return null;
        }

        return is_numeric($valor) ? (float) $valor : null;
    }
}
