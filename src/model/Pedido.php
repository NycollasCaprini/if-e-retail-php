<?php

namespace model;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
#[ORM\Table(name:"tb_pedido")]
class Pedido extends GenericModel
{
    #[ORM\Column(type:'datetime')]
    private $dataPedido;

    #[ORM\Column(type:'datetime')]
    private $dataEntrega;

    #[ORM\Column(type:'boolean')]
    private $status;

    #[ORM\ManyToOne(targetEntity: Cliente::class)]
    #[ORM\JoinColumn(name: "cliente_id")]
    private $cliente;

    #[ORM\ManyToMany(targetEntity: Produto::class)]
    #[ORM\JoinTable(name: "tb_produto_pedido")]
    #[ORM\JoinColumn(name: "pedido_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "produto_id", referencedColumnName: "id")]
    private $itens;

    public function __construct($dataPedido, $dataEntrega, $status)
    {
        $this->dataPedido = $dataPedido;
        $this->dataEntrega = $dataEntrega;
        $this->status = $status;
    }

    public function setDataPedido($dataPedido)
    {
        $this->dataPedido = $dataPedido;
    }

    public function getDataPedido()
    {
        return $this->dataPedido;
    }

    public function setDataEntrega($dataEntrega)
    {
        $this->dataEntrega = $dataEntrega;
    }

    public function getDataEntrega()
    {
        return $this->dataEntrega;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}