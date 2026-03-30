<?php

namespace test\model;

use model\Produto;
use PHPUnit\Framework\TestCase;

class ProdutoTest extends TestCase
{
    public function testCriarObjeto()
    {
        $produto = new Produto("Notebook Dell", 10, 3500.00, "ativo");
        $this->assertNotNull($produto);
        $this->assertEquals("Notebook Dell", $produto->getDescricao());
        $this->assertEquals(10, $produto->getQuantidade());
        $this->assertEquals(3500.00, $produto->getPrecoUnitario());
        $this->assertEquals("ativo", $produto->getStatus());
    }
}
