<?php

namespace test\model;

use model\Produto;
use PHPUnit\Framework\TestCase;

/**
 * Testes unitários para o model Produto (sem banco de dados).
 * Segue o padrão do ClienteTest.php do professor (Aula 06):
 * verifica criação de objeto e getters/setters.
 */
class ProdutoTest extends TestCase {

    public function testCriarObjeto(): void {
        $produto = new Produto();
        $produto->setDescricao('Notebook Dell');
        $produto->setPrecoUnitario(3500.00);
        $produto->setQuantidade(10);
        $produto->setStatus(true);

        $this->assertNotNull($produto);
        $this->assertEquals('Notebook Dell', $produto->getDescricao());
        $this->assertEquals(3500.00, $produto->getPrecoUnitario());
        $this->assertTrue($produto->getStatus());
    }
}
