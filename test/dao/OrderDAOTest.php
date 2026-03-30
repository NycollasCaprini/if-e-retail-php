<?php

namespace test\dao;

use dao\PedidoDAO;
use model\Pedido;
use DateTime;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

class OrderDAOTest extends TestCase
{
    protected function setUp(): void
    {
        $reflection = new \ReflectionClass(Conexao::class);
        $prop = $reflection->getProperty('entityManager');
        $prop->setAccessible(true);
        $prop->setValue(null, null);
    }

    private function criarPedido(): Pedido
    {
        return new Pedido(new DateTime(), new DateTime("+7 days"), true);
    }

    public function testSalvar()
    {
        $pedido = $this->criarPedido();
        $salvo = PedidoDAO::salvar($pedido);
        $this->assertNotNull($salvo->getID());
    }

    public function testListar()
    {
        PedidoDAO::salvar($this->criarPedido());
        $lista = PedidoDAO::listar();
        $this->assertNotEmpty($lista);
    }

    public function testDeletar()
    {
        $pedido = PedidoDAO::salvar($this->criarPedido());
        PedidoDAO::deletar($pedido);
        $lista = PedidoDAO::listar();
        foreach ($lista as $p) {
            $this->assertNotEquals($pedido->getID(), $p->getID());
        }
    }

    public function testBuscarPorStatus()
    {
        PedidoDAO::salvar($this->criarPedido());
        $resultado = PedidoDAO::buscarPorStatus(true);
        $this->assertNotEmpty($resultado);
    }
}
