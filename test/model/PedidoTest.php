<?php

use model\Cliente;
use model\Pedido;
use model\Produto;
use PHPUnit\Framework\TestCase;

class PedidoTest extends TestCase{

    public function testCriaPedido(){
        $agora = new DateTimeImmutable();
        $depois = $agora->modify('+7 days');

        $pedido = new Pedido($agora, $depois, "entregue");

        $cliente = new Cliente("Elis Regina", "222.333.444-55", new \DateTime("1992-06-17"), "hash456", "cliente");
        $produto = new Produto("Notebook Dell", 10, 3500.00, "ativo");

        $pedido->setCliente($cliente);
        $pedido->setItens($produto);

        $this->assertNotNull($pedido);


    }
}