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

    public function testEficienciaDeOitentaPorCento(): void
    {
        $this->assertSame(
            80.0,
            $this->biofiltro->calcularEficienciaRemocao(10.0, 2.0)
        );
    }

    public function testSemRemocao(): void
    {
        $this->assertSame(
            0.0,
            $this->biofiltro->calcularEficienciaRemocao(5.0, 5.0)
        );
    }

    public function testRemocaoTotal(): void
    {
        $this->assertSame(
            100.0,
            $this->biofiltro->calcularEficienciaRemocao(8.0, 0.0)
        );
    }

    public function testParametroPodePiorar(): void
    {
        $this->assertSame(
            -50.0,
            $this->biofiltro->calcularEficienciaRemocao(4.0, 6.0)
        );
    }

    public function testValorInicialZeroLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->biofiltro->calcularEficienciaRemocao(0.0, 2.0);
    }

    public function testValorInicialNegativoLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->biofiltro->calcularEficienciaRemocao(-1.0, 2.0);
    }

    public function testValorFinalNegativoLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->biofiltro->calcularEficienciaRemocao(10.0, -1.0);
    }

    public function testAplicaEficienciaDeOitentaPorCento(): void
    {
        $this->assertSame(
            2.0,
            $this->biofiltro->aplicarEficiencia(10.0, 80.0)
        );
    }

    public function testEficienciaZeroMantemValorInicial(): void
    {
        $this->assertSame(
            10.0,
            $this->biofiltro->aplicarEficiencia(10.0, 0.0)
        );
    }

    public function testEficienciaCemZeraOParametro(): void
    {
        $this->assertSame(
            0.0,
            $this->biofiltro->aplicarEficiencia(10.0, 100.0)
        );
    }

    public function testValorInicialNegativoEmAplicarEficienciaLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->biofiltro->aplicarEficiencia(-1.0, 50.0);
    }

    public function testEficienciaNegativaLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->biofiltro->aplicarEficiencia(10.0, -1.0);
    }

    public function testEficienciaAcimaDeCemLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->biofiltro->aplicarEficiencia(10.0, 101.0);
    }

    public function testAplicaDuasCamadasDeFiltragem(): void
    {
        $this->assertSame(
            40.0,
            $this->biofiltro->aplicarCamadas(100.0, [20.0, 50.0])
        );
    }

    public function testListaVaziaMantemValorInicial(): void
    {
        $this->assertSame(
            10.0,
            $this->biofiltro->aplicarCamadas(10.0, [])
        );
    }

    public function testValorInicialNegativoEmCamadasLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->biofiltro->aplicarCamadas(-1.0, [20.0]);
    }

    public function testCamadaComEficienciaInvalidaLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->biofiltro->aplicarCamadas(10.0, [20.0, 150.0]);
    }
}
