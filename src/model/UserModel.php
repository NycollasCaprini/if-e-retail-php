<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Superclasse abstrata para Admin e Cliente.
 *
 * #[ORM\MappedSuperclass] → campos desta classe são herdados pelas
 *   subclasses Admin e Cliente, que terão suas próprias tabelas.
 *
 * A herança por MappedSuperclass é mais simples que JOINED/SINGLE_TABLE
 * do JPA e é suficiente quando não precisamos consultar UserModel diretamente.
 */
#[ORM\MappedSuperclass]
abstract class UserModel extends GenericModel {

    #[ORM\Column(type: 'string', nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', unique: true)]
    private $cpf;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $idade;

    #[ORM\Column(type: 'string')]
    private $senha;

    #[ORM\Column(type: 'string', nullable: true)]
    private $tipo;

    public function setName($name): void { $this->name = $name; }
    public function getName() { return $this->name; }

    public function setCpf($cpf): void { $this->cpf = $cpf; }
    public function getCpf() { return $this->cpf; }

    public function setIdade($idade): void { $this->idade = $idade; }
    public function getIdade() { return $this->idade; }

    public function setSenha($senha): void { $this->senha = $senha; }
    public function getSenha() { return $this->senha; }

    public function setTipo($tipo): void { $this->tipo = $tipo; }
    public function getTipo() { return $this->tipo; }
}
