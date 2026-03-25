<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Entidade Cliente — subclasse de UserModel.
 *
 * No Spring usávamos InheritanceType.JOINED com @PrimaryKeyJoinColumn.
 * Aqui, como UserModel é @MappedSuperclass, os campos de UserModel
 * são incluídos diretamente na tabela tb_cliente (estratégia mais simples,
 * equivalente ao InheritanceType.SINGLE_TABLE sem discriminador).
 *
 * Relacionamentos:
 *   - OneToOne com Carrinho (cliente é o lado dono — tem a FK)
 *   - OneToMany com Order (um cliente tem vários pedidos)
 *   - OneToMany com Contato (cascade: all, orphanRemoval)
 *   - ManyToMany com Produto (lista de favoritos)
 */
#[ORM\Entity]
#[ORM\Table(name: 'tb_cliente')]
class Cliente extends UserModel {

    // Cliente é o lado dono do OneToOne — tem a coluna carrinho_id
    #[ORM\OneToOne(targetEntity: Carrinho::class, cascade: ['all'], orphanRemoval: true)]
    #[ORM\JoinColumn(name: 'carrinho_id', nullable: true)]
    private $carrinho;

    // Um cliente tem vários pedidos — mappedBy aponta para o campo 'cliente' em Order
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'cliente', cascade: ['all'], orphanRemoval: true)]
    private Collection $listaPedidos;

    // Contatos do cliente (e-mail, telefone, whatsapp)
    #[ORM\OneToMany(targetEntity: Contato::class, mappedBy: 'cliente', cascade: ['all'], orphanRemoval: true)]
    private Collection $contatos;

    // Produtos favoritos — tabela de junção tb_cliente_favorito
    #[ORM\ManyToMany(targetEntity: Produto::class, cascade: ['persist'])]
    #[ORM\JoinTable(name: 'tb_cliente_favorito')]
    private Collection $listaFavoritos;

    public function __construct() {
        $this->listaPedidos  = new ArrayCollection();
        $this->contatos      = new ArrayCollection();
        $this->listaFavoritos = new ArrayCollection();
    }

    public function setCarrinho($carrinho): void { $this->carrinho = $carrinho; }
    public function getCarrinho() { return $this->carrinho; }

    public function setListaPedidos($listaPedidos): void { $this->listaPedidos = $listaPedidos; }
    public function getListaPedidos(): Collection { return $this->listaPedidos; }

    public function setContatos($contatos): void { $this->contatos = $contatos; }
    public function getContatos(): Collection { return $this->contatos; }

    public function setListaFavoritos($listaFavoritos): void { $this->listaFavoritos = $listaFavoritos; }
    public function getListaFavoritos(): Collection { return $this->listaFavoritos; }
}
