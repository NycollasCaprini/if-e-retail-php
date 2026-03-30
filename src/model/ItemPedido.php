<?php
namespace model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tb_item_pedido")]
class ItemPedido extends GenericModel {

    #[ORM\ManyToOne(targetEntity: Pedido::class)]
    #[ORM\JoinColumn(name: "pedido_id")]
    private $pedido;
    #[ORM\ManyToOne(targetEntity: Produto::class)]
    #[ORM\JoinColumn(name: "produto_id")]
    private $produto;

    #[ORM\Column(type: "integer")]
    private $quantidade;

    #[ORM\Column(type: "decimal", precision:10, scale: 2)]
    private $preco;

    public function __construct($pedido, $produto, $quantidade, $preco)
    {
        $this->pedido = $pedido;
        $this->produto = $produto;
        $this->quantidade = $quantidade;
        $this->preco = $preco;
    }

    /**
     * @return mixed
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     * @param mixed $pedido
     */
    public function setPedido($pedido): void
    {
        $this->pedido = $pedido;
    }

    /**
     * @return mixed
     */
    public function getProduto()
    {
        return $this->produto;
    }

    /**
     * @param mixed $produto
     */
    public function setProduto($produto): void
    {
        $this->produto = $produto;
    }

    /**
     * @return mixed
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * @param mixed $quantidade
     */
    public function setQuantidade($quantidade): void
    {
        $this->quantidade = $quantidade;
    }

    /**
     * @return mixed
     */
    public function getPreco()
    {
        return $this->preco;
    }

    /**
     * @param mixed $preco
     */
    public function setPreco($preco): void
    {
        $this->preco = $preco;
    }



}