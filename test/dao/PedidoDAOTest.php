<?php

namespace test\dao;

use dao\PedidoDAO;
use model\Cliente;
use dao\ClienteDAO;
use model\ItemPedido;
use model\Pedido;
use DateTime;
use model\Produto;
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

    public function testSalvarComProdutos(){
        $pedido = $this->criarPedido();

        $produto1 = new Produto("Notebook dell", "20", "3400.00", "diponivel");

        $itemPedido = new ItemPedido();
        $itemPedido->setPedido($pedido);
        $itemPedido->setProduto($produto1);
        $itemPedido->setQuantidade(2);
        $itemPedido->setPreco("6800.00");

        $itens[] = $itemPedido;

        $pedido->setItens($itens);

        $this->assertNotNull($pedido->getItens());

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
        $resultado = PedidoDAO::buscarPorStatus("true");
        $this->assertNotTrue($resultado);
    }

    // Teste de atualização: verifica que alterações em um pedido persistido são salvas corretamente
    public function testAtualizar()
    {
        $pedido = PedidoDAO::salvar($this->criarPedido());
        $novaDataEntrega = new DateTime("+14 days");
        $pedido->setDataEntrega($novaDataEntrega);
        $pedido->setStatus(false);
        $atualizado = PedidoDAO::salvar($pedido);
        $this->assertEquals(false, $atualizado->getStatus());
        $this->assertEquals($novaDataEntrega, $atualizado->getDataEntrega());
    }
}
