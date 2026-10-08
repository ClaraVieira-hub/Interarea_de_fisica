<?php

declare(strict_types=1);

namespace Tests;

use App\QualidadeAgua;
use PHPUnit\Framework\TestCase;

class QualidadeAguaTest extends TestCase
{
    private QualidadeAgua $qualidadeAgua;

    protected function setUp(): void
    {
        $this->qualidadeAgua = new QualidadeAgua();
    }

  

    public function testPhDentroDoPadraoCasoFeliz(): void
    {
        $resultado = $this->qualidadeAgua->classificarPH(7.0);
        $this->assertSame('Dentro do padrão', $resultado);
    }

    public function testClassificacaoCompletaAguaPotavelCasoFeliz(): void
    {
        $resultado = $this->qualidadeAgua->classificarAgua(
            ph: 7.0,
            turbidez: 0.5,
            cloroResidual: 2.0,
            dureza: 150.0,
            temperatura: 25.0
        );

        $this->assertTrue($resultado['potavel']);
        $this->assertSame('Dentro do padrão', $resultado['ph']);
        $this->assertSame('Dentro do padrão', $resultado['turbidez']);
        $this->assertSame('Dentro do padrão', $resultado['cloroResidual']);
        $this->assertSame('Dentro do padrão', $resultado['dureza']);
    }

    public function testCalculoPhPorConcentracaoAguaNeutraCasoFeliz(): void
    {
        $ph = $this->qualidadeAgua->calcularPHPorConcentracao(0.0000001);
        $this->assertEqualsWithDelta(7.0, $ph, 0.0001);
    }

  

    public function testPhNoLimiteInferiorCasoDeBorda(): void
    {
        $resultado = $this->qualidadeAgua->classificarPH(6.0);
        $this->assertSame('Dentro do padrão', $resultado);
    }

    public function testPhNoLimiteSuperiorCasoDeBorda(): void
    {
        $resultado = $this->qualidadeAgua->classificarPH(9.5);
        $this->assertSame('Dentro do padrão', $resultado);
    }

    public function testDurezaExatamenteNoLimiteCasoDeBorda(): void
    {
        $resultado = $this->qualidadeAgua->classificarDureza(300.0);
        $this->assertSame('Dentro do padrão', $resultado);
    }

    public function testDurezaAcimaDoLimiteCasoDeBorda(): void
{
    $resultado = $this->qualidadeAgua->classificarDureza(300.1);
    $this->assertSame('Fora do padrão', $resultado);
}
public function testTurbidezExatamenteNoLimiteCasoDeBorda(): void
{
    $resultado = $this->qualidadeAgua->classificarTurbidez(5.0);
    $this->assertSame('Dentro do padrão', $resultado);
}

public function testTurbidezAcimaDoLimiteCasoDeBorda(): void
{
    $resultado = $this->qualidadeAgua->classificarTurbidez(5.1);
    $this->assertSame('Fora do padrão', $resultado);
}

public function testCloroAbaixoDoMinimoCasoDeBorda(): void
{
    $resultado = $this->qualidadeAgua->classificarCloroResidual(0.19);
    $this->assertSame('Fora do padrão', $resultado);
}

public function testCloroExatamenteNoMinimoCasoDeBorda(): void
{
    $resultado = $this->qualidadeAgua->classificarCloroResidual(0.2);
    $this->assertSame('Dentro do padrão', $resultado);
}

public function testCloroExatamenteNoMaximoCasoDeBorda(): void
{
    $resultado = $this->qualidadeAgua->classificarCloroResidual(5.0);
    $this->assertSame('Dentro do padrão', $resultado);
}

public function testCloroAcimaDoMaximoCasoDeBorda(): void
{
    $resultado = $this->qualidadeAgua->classificarCloroResidual(5.1);
    $this->assertSame('Fora do padrão', $resultado);
}
  

    public function testPhForaDoPadraoAbaixoCasoDeErro(): void
    {
        $resultado = $this->qualidadeAgua->classificarPH(5.5);
        $this->assertSame('Fora do padrão', $resultado);
    }

    public function testPhForaDoPadraoAcimaCasoDeErro(): void
    {
        $resultado = $this->qualidadeAgua->classificarPH(10.0);
        $this->assertSame('Fora do padrão', $resultado);
    }

    public function testClassificacaoCompletaAguaNaoPotavelCasoDeErro(): void
    {
        $resultado = $this->qualidadeAgua->classificarAgua(
            ph: 5.0,           
            turbidez: 0.5,
            cloroResidual: 2.0,
            dureza: 500.0,
            temperatura: 25.0
        );

        $this->assertFalse($resultado['potavel']);
    }

    public function testConcentracaoHInvalidaLancaExcecaoCasoDeErro(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->qualidadeAgua->calcularPHPorConcentracao(0.0);
    }

    public function testConcentracaoHNegativaLancaExcecaoCasoDeErro(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->qualidadeAgua->calcularPHPorConcentracao(-0.0000001);
    }
}