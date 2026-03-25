<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entidade Produto — representa um item do catálogo da loja.
 *
 * #[ORM\Entity] → diz ao Doctrine que esta classe é mapeada para uma tabela.
 * #[ORM\Table] → define o nome da tabela no banco.
 * Equivalente a @Entity + @Table do JPA/Spring.
 */
#[ORM\Entity]
#[ORM\Table(name: 'tb_produto')]
class Produto extends GenericModel {

    #[ORM\Column(type: 'string')]
    private $descricao;

    #[ORM\Column(type: 'integer')]
    private $quantidade;

    #[ORM\Column(type: 'float')]
    private $precoUnitario;

    #[ORM\Column(type: 'boolean')]
    private $status;

    public function setDescricao($descricao): void { $this->descricao = $descricao; }
    public function getDescricao() { return $this->descricao; }

    public function setQuantidade($quantidade): void { $this->quantidade = $quantidade; }
    public function getQuantidade() { return $this->quantidade; }

    public function setPrecoUnitario($precoUnitario): void { $this->precoUnitario = $precoUnitario; }
    public function getPrecoUnitario() { return $this->precoUnitario; }

    public function setStatus($status): void { $this->status = $status; }
    public function getStatus() { return $this->status; }
}
