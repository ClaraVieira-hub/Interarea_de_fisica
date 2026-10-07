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
        // (10 - 2) / 10 * 100 = 80
        $this->assertSame(80.0, $this->biofiltro->calcularEficienciaRemocao(10.0, 2.0));
    }

    public function testSemMudancaDaEficienciaZero(): void
    {
        // (5 - 5) / 5 * 100 = 0
        $this->assertSame(0.0, $this->biofiltro->calcularEficienciaRemocao(5.0, 5.0));
    }

    public function testRemocaoTotalDaCemPorCentoCasoDeBorda(): void
    {
        // (8 - 0) / 8 * 100 = 100
        $this->assertSame(100.0, $this->biofiltro->calcularEficienciaRemocao(8.0, 0.0));
    }

    public function testPioraDoParametroDaEficienciaNegativa(): void
    {
        // (4 - 6) / 4 * 100 = -50
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