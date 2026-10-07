<?php

declare(strict_types=1);

namespace Tests;

use App\Biofiltro;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BiofiltroTest extends TestCase
{
    private Biofiltro $biofiltro;

    protected function setUp(): void
    {
        $this->biofiltro = new Biofiltro();
    }

    public function testEficienciaDeOitentaPorCentoCasoFeliz(): void
    {
        
        $this->assertSame(80.0, $this->biofiltro->calcularEficienciaRemocao(10.0, 2.0));
    }

    public function testSemMudancaDaEficienciaZero(): void
    {
        
        $this->assertSame(0.0, $this->biofiltro->calcularEficienciaRemocao(5.0, 5.0));
    }

    public function testRemocaoTotalDaCemPorCentoCasoDeBorda(): void
    {
        
        $this->assertSame(100.0, $this->biofiltro->calcularEficienciaRemocao(8.0, 0.0));
    }

    public function testPioraDoParametroDaEficienciaNegativa(): void
    {
        
        $this->assertSame(-50.0, $this->biofiltro->calcularEficienciaRemocao(4.0, 6.0));
    }

    public function testAntesZeroLancaExcecaoCasoDeErro(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->biofiltro->calcularEficienciaRemocao(0.0, 2.0);
    }

    public function testAntesNegativoLancaExcecaoCasoDeErro(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->biofiltro->calcularEficienciaRemocao(-1.0, 2.0);
    }

    public function testDepoisNegativoLancaExcecaoCasoDeErro(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->biofiltro->calcularEficienciaRemocao(10.0, -1.0);
    }
}