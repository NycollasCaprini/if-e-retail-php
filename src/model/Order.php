<?php

namespace model;

class Order extends GenericModel
{
    private $dataPedido;
    private $dataEntrega;
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