<?php

namespace test\model;

use model\Cliente;
use model\Carrinho;
use PHPUnit\Framework\TestCase;

/**
 * Testes unitários para o model Cliente (sem banco de dados).
 * Equivalente ao ClienteTest.php do professor (Aula 06).
 */
class ClienteTest extends TestCase {

    public function testCriarObjeto(): void {
        $cliente = new Cliente();
        $cliente->setName('Eduardo Luiz Alba');
        $cliente->setCpf('123.456.789-00');
        $cliente->setSenha('hash_senha');
        $cliente->setTipo('CLIENTE');

        $this->assertNotNull($cliente);
        $this->assertEquals('Eduardo Luiz Alba', $cliente->getName());
        $this->assertEquals('123.456.789-00', $cliente->getCpf());
    }

    public function testAtribuirCarrinho(): void {
        $cliente  = new Cliente();
        $carrinho = new Carrinho();
        $carrinho->setStatus(true);
        $carrinho->setValorTotal(0.0);

        $cliente->setCarrinho($carrinho);

        $this->assertNotNull($cliente->getCarrinho());
        $this->assertTrue($cliente->getCarrinho()->getStatus());
    }

    public function testListaFavoritosIniciaVazia(): void {
        $cliente = new Cliente();
        $this->assertEmpty($cliente->getListaFavoritos());
    }
}
