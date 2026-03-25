<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Superclasse abstrata de todos os models do domínio.
 *
 * #[ORM\MappedSuperclass] → equivalente ao @MappedSuperclass do JPA:
 *   esta classe não gera tabela própria, mas seus campos são herdados
 *   pelas subclasses que forem entidades (#[ORM\Entity]).
 *
 * #[ORM\Id] + #[ORM\GeneratedValue] → equivalente ao
 *   @Id @GeneratedValue do Spring — o banco gera o ID automaticamente.
 */
#[ORM\MappedSuperclass]
abstract class GenericModel {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    public function setId($id): void {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }
}
