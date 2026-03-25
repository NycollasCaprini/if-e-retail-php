<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entidade Order (Pedido) associada a um Cliente.
 *
 * Equivalente à entidade Pedido do Spring:
 *   @ManyToOne com Cliente, dataDoPedido, dataDeEntregaDoPedido, status.
 *
 * Usamos 'Order' como nome da classe pois é o nome original do projeto,
 * mas a tabela é 'tb_order' para evitar conflito com a palavra reservada
 * ORDER do SQL (o Doctrine faz o escape automaticamente, mas é boa prática).
 */
#[ORM\Entity]
#[ORM\Table(name: 'tb_order')]
class Order extends GenericModel {

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dataPedido;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dataEntrega;

    #[ORM\Column(type: 'boolean')]
    private $status = true;

    // Muitos pedidos pertencem a um Cliente
    #[ORM\ManyToOne(targetEntity: Cliente::class, inversedBy: 'listaPedidos')]
    #[ORM\JoinColumn(name: 'cliente_id', nullable: false)]
    private $cliente;

    public function setDataPedido($dataPedido): void { $this->dataPedido = $dataPedido; }
    public function getDataPedido() { return $this->dataPedido; }

    public function setDataEntrega($dataEntrega): void { $this->dataEntrega = $dataEntrega; }
    public function getDataEntrega() { return $this->dataEntrega; }

    public function setStatus($status): void { $this->status = $status; }
    public function getStatus() { return $this->status; }

    public function setCliente($cliente): void { $this->cliente = $cliente; }
    public function getCliente() { return $this->cliente; }
}
