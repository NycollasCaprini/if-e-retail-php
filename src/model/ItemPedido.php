<?php
namespace model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tb_item_carrinho")]
class ItemPedido extends GenericModel {
    #[ORM\ManyToOne(targetEntity: Produto::class)]
    #[ORM\JoinColumn(name: "produto_id", nullable: false)]
    private $produto;

    #[ORM\ManyToOne(targetEntity: Carrinho::class, inversedBy: "itens")]
    #[ORM\JoinColumn(name: "carrinho_id", nullable: false)]
    private  $carrinho;

    #[ORM\Column(type: "integer")]
    private $quantidade;

    #[ORM\Column(type: "decimal", precision:10, scale: 2)]
    private $preco;

    public function __construct($produto, $carrinho, $quantidade) {
        $this->produtos = $produto;
        $this->carrinho = $carrinho;
        $this->quantidade = $quantidade;
        $this->preco = $produto->getPrecoUnitario();
    }

    public function getProduto()
    {
        return $this->produtos;
    }

    public function setProduto(Produto $produto)
    {
        $this->produtos = $produto;
    }

    public function getCarrinho()
    {
        return $this->carrinho;
    }

    public function setCarrinho(Carrinho $carrinho)
    {
        $this->carrinho = $carrinho;
    }


    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function getPrecoNoMomento()
    {
        return $this->precoNoMomento;
    }

    public function setPreco($preco)
    {
        $this->preco = $preco;
    }

}