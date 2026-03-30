<?php

namespace test\model;

use model\Carrinho;
use PHPUnit\Framework\TestCase;

class CarrinhoTest extends TestCase
{
    public function testCriarObjeto()
    {
        $carrinho = new Carrinho("ABERTO");
        $this->assertNotNull($carrinho);
        $this->assertEquals("ABERTO", $carrinho->getStatus());
    }

    public function testCriarObjetoComStatusPadrao()
    {
        $carrinho = new Carrinho();
        $this->assertNotNull($carrinho);
        $this->assertEquals("ABERTO", $carrinho->getStatus());
    }
}
