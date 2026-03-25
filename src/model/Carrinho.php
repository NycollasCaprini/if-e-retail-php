<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Entidade Carrinho de compras associada a um Cliente (OneToOne inverso).
 *
 * No Spring: @OneToOne(mappedBy = "carrinho") — o lado dono é Cliente.
 * Aqui: o Cliente possui a FK carrinho_id, então Carrinho é o lado inverso.
 *
 * #[ORM\OneToMany] para itens (produtos do carrinho) com cascade: ['all']
 * e orphanRemoval = true — equivalente ao CascadeType.ALL + orphanRemoval
 * do Spring, garantindo que itens removidos da lista sejam deletados.
 */
#[ORM\Entity]
#[ORM\Table(name: 'tb_carrinho')]
class Carrinho extends GenericModel {

    // Lado inverso do OneToOne com Cliente
    #[ORM\OneToOne(targetEntity: Cliente::class, mappedBy: 'carrinho')]
    private $cliente;

    #[ORM\Column(type: 'boolean')]
    private $status = true;

    #[ORM\Column(type: 'float', nullable: true)]
    private $valorTotal = 0.0;

    // Relação ManyToMany com Produto — tabela de junção tb_carrinho_produto
    #[ORM\ManyToMany(targetEntity: Produto::class, cascade: ['persist'])]
    #[ORM\JoinTable(name: 'tb_carrinho_produto')]
    private Collection $listaProdutos;

    public function __construct() {
        $this->listaProdutos = new ArrayCollection();
    }

    public function setCliente($cliente): void { $this->cliente = $cliente; }
    public function getCliente() { return $this->cliente; }

    public function setStatus($status): void { $this->status = $status; }
    public function getStatus() { return $this->status; }

    public function setValorTotal($valorTotal): void { $this->valorTotal = $valorTotal; }
    public function getValorTotal() { return $this->valorTotal; }

    public function setListaProdutos($listaProdutos): void { $this->listaProdutos = $listaProdutos; }
    public function getListaProdutos(): Collection { return $this->listaProdutos; }

    public function adicionarProduto(Produto $produto): void {
        if (!$this->listaProdutos->contains($produto)) {
            $this->listaProdutos->add($produto);
        }
    }
}
