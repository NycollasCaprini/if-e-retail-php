<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'tb_cliente')]
class Cliente extends UserModel
{
    #[ORM\OneToOne(targetEntity: Carrinho::class, cascade: ['all'], orphanRemoval: true, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: "carrinho_id")]
    private $carrinho;

    #[ORM\OneToMany(mappedBy: "cliente", targetEntity: Pedido::class, cascade:["all"], orphanRemoval: true,fetch: 'LAZY')]
    private $listaPedidos;
    #[ORM\ManyToMany(targetEntity: Produto::class)]
    #[ORM\JoinTable(name: 'tb_produtos_favoritos')]
    #[ORM\JoinColumn(name: "cliente_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "produto_id", referencedColumnName: "id")]
    private $listaFavoritos;


    public function __construct($name, $cpf, $dataNascimento, $senha, $tipo, $carrinho = null)
    {
        parent::__construct($name, $cpf, $dataNascimento, $senha, $tipo);
        $this->carrinho = $carrinho;
        $this->listaPedidos = new ArrayCollection();
        $this->listaFavoritos = new ArrayCollection();
    }


    public function setCarrinho($carrinho){
        $this->carrinho = $carrinho;
    }

    public function getCarrinho(){
        return $this->carrinho;
    }

    public function setListaPedidos($listaPedidos){
        $this->listaPedidos = $listaPedidos;
    }

    public function getListaPedidos(){
        return $this->listaPedidos;
    }

    public function setListaFavoritos($listaFavoritos){
        $this->listaFavoritos = $listaFavoritos;
    }

    public function getListaFavoritos(){
        return $this->listaFavoritos;
    }

}