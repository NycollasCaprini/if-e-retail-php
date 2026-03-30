<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tb_produto")]
class Produto extends GenericModel
{
    #[ORM\Column(type: "string")]
    private $descricao;

    #[ORM\Column(type: "integer")]
    private $quantidade;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private $precoUnitario;

    #[ORM\Column(type: "string")]
    private $status;

    public function __construct($descricao, $quantidade, $precoUnitario, $status)
    {
        $this->descricao = $descricao;
        $this->quantidade = $quantidade;
        $this->precoUnitario = $precoUnitario;
        $this->status = $status;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function setPrecoUnitario($precoUnitario)
    {
        $this->precoUnitario = $precoUnitario;
    }

    public function getPrecoUnitario()
    {
        return $this->precoUnitario;
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