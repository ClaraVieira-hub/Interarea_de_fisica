<?php

declare(strict_types=1);

namespace Tests;

use App\QualidadeAgua;
use App\RelatorioEmbasa;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RelatorioEmbasaTest extends TestCase
{
    private RelatorioEmbasa $relatorio;
    private string $arquivo;

    protected function setUp(): void
    {
        $this->relatorio = new RelatorioEmbasa(new QualidadeAgua());
        $this->arquivo = tempnam(sys_get_temp_dir(), 'embasa');
    }

    protected function tearDown(): void
    {
        if (is_file($this->arquivo)) {
            unlink($this->arquivo);
        }
    }

    private function escreverCsv(string $linhas): void
    {
        $cabecalho = "id,localidade,concentracao_h,ph,turbidez,temperatura,cloro_residual,dureza,fonte\n";
        file_put_contents($this->arquivo, $cabecalho . $linhas);
    }

    public function testRegistroCompletoEPotavelCasoFeliz(): void
    {
        $this->escreverCsv("1,ABOBORA,3.6e-07,6.44,0.86,26.5,2.57,101.91,Embasa\n");

        $registros = $this->relatorio->carregar($this->arquivo);

        $this->assertCount(1, $registros);
        $this->assertSame(6.44, $registros[0]['ph']);
        $this->assertSame('Potável', $this->relatorio->situacao($registros[0]));
    }

    public function testRegistroComCampoVazioFicaIncompletoCasoDeBorda(): void
    {
        $this->escreverCsv("2,ALMAS,,,0.7,,1.73,,Embasa\n");

        $registros = $this->relatorio->carregar($this->arquivo);

        $this->assertNull($registros[0]['ph']);
        $this->assertNull($registros[0]['classificacao']);
        $this->assertSame('Dados incompletos', $this->relatorio->situacao($registros[0]));
    }

    public function testRegistroForaDoPadraoCasoDeBorda(): void
    {
        $this->escreverCsv("3,TESTE,1e-07,7.0,9.0,25,2.0,100,Embasa\n");

        $registros = $this->relatorio->carregar($this->arquivo);

        $this->assertSame('Fora do padrão', $this->relatorio->situacao($registros[0]));
    }

    public function testLinhaEmBrancoEIgnoradaCasoDeBorda(): void
    {
        $this->escreverCsv("\n1,ABOBORA,3.6e-07,6.44,0.86,26.5,2.57,101.91,Embasa\n");

        $this->assertCount(1, $this->relatorio->carregar($this->arquivo));
    }

    public function testArquivoInexistenteLancaExcecaoCasoDeErro(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->relatorio->carregar('/caminho/que/nao/existe.csv');
    }
}
