<?php

namespace test\dao;

use dao\PedidoDAO;
use model\Cliente;
use dao\ClienteDAO;
use model\Pedido;
use DateTime;
use PHPUnit\Framework\TestCase;


class PedidoDAOTest extends TestCase
{

    private function criarPedido(): Pedido
    {
        return new Pedido(new DateTime(), new DateTime("+7 days"), true);
    }

    private function criarCliente(): Cliente
    {
        return new Cliente(
            "Elis Regina", "222.333.444-55", new \DateTime("1992-06-17"), "hash456", "cliente"
        );
    }

    public function testSalvar()
    {
        $pedido = $this->criarPedido();
        $salvo = PedidoDAO::salvar($pedido);
        $this->assertNotNull($salvo->getID());
    }
    public function testSalvarComCliente()
    {
        $cliente = ClienteDAO::salvar($this->criarCliente());
        $pedido = $this->criarPedido();
        $pedido -> setCliente($cliente);
        $salvo = PedidoDAO::salvar($pedido);
        $localizado = PedidoDAO::buscarPorId($salvo->getID());
        echo $localizado->getCliente()->getName();
        $this->assertNotNull($localizado->getCliente());

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
