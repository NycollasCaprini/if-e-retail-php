<?php

namespace model;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
#[ORM\Table(name:"tb_order")]
class Order extends GenericModel
{
    #[ORM\Column(type:'datetime')]
    private $dataPedido;

    #[ORM\Column(type:'datetime')]
    private $dataEntrega;

    #[ORM\Column(type:'boolean')]
    private $status;

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