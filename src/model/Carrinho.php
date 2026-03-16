<?php

namespace model;

class Carrinho extends GenericModel
{
    private $listaProdutos; // Lista de objetos Produto
    private $status;
    private $valorTotal;

    private $cliente;

    public function __construct($listaProdutos, $status, $valorTotal, $cliente)
    {
        $this->cliente=$cliente;
        $this->listaProdutos = $listaProdutos;
        $this->status = $status;
        $this->valorTotal = $valorTotal;
    }

    public function setListaProdutos($listaProdutos)
    {
        $this->listaProdutos = $listaProdutos;
    }

    public function getListaProdutos()
    {
        return $this->listaProdutos;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setValorTotal($valorTotal)
    {
        $this->valorTotal = $valorTotal;
    }

    public function getValorTotal()
    {
        return $this->valorTotal;
    }
}