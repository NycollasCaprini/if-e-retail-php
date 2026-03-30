<?php

namespace model;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
#[ORM\Entity]
#[ORM\Table(name:'tb_carrinho')]
class Carrinho extends GenericModel
{
    #[ORM\OneToMany(mappedBy: "carrinho", targetEntity: ItemPedido::class, cascade: ["all"], orphanRemoval: true)]
    private $itens;

    #[ORM\Column(type: "string")]
    private $status;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private $valorTotal = 0;

    public function __construct($status = 'ABERTO') {
        $this->status = $status;
        $this->itens = new ArrayCollection();
    }


    public function getValorTotal()
    {
        return $this->valorTotal;
    }

    public function setValorTotal(int $valorTotal)
    {
        $this->valorTotal = $valorTotal;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(mixed $status)
    {
        $this->status = $status;
    }

    public function getItens()
    {
        return $this->itens;
    }

    public function setItens(Collection $itens)
    {
        $this->itens = $itens;
    }


}