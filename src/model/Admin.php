<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Entidade Admin — subclasse de UserModel.
 *
 * Assim como Cliente, herda os campos de UserModel por @MappedSuperclass:
 * todos os campos (name, cpf, senha, tipo, etc.) ficam na tabela tb_admin.
 */
#[ORM\Entity]
#[ORM\Table(name: 'tb_admin')]
class Admin extends UserModel {

    #[ORM\Column(type: 'string', unique: true)]
    private $matricula;

    #[ORM\Column(type: 'string', nullable: true)]
    private $setor;

    #[ORM\Column(type: 'string', nullable: true)]
    private $cargo;

    #[ORM\Column(type: 'date', nullable: true)]
    private $dataAdmissao;

    #[ORM\Column(type: 'boolean')]
    private $status = true;

    // Contatos do admin
    #[ORM\OneToMany(targetEntity: Contato::class, mappedBy: 'admin', cascade: ['all'], orphanRemoval: true)]
    private Collection $contatos;

    public function __construct() {
        $this->contatos = new ArrayCollection();
    }

    public function setMatricula($matricula): void { $this->matricula = $matricula; }
    public function getMatricula() { return $this->matricula; }

    public function setSetor($setor): void { $this->setor = $setor; }
    public function getSetor() { return $this->setor; }

    public function setCargo($cargo): void { $this->cargo = $cargo; }
    public function getCargo() { return $this->cargo; }

    public function setDataAdmissao($dataAdmissao): void { $this->dataAdmissao = $dataAdmissao; }
    public function getDataAdmissao() { return $this->dataAdmissao; }

    public function setStatus($status): void { $this->status = $status; }
    public function getStatus() { return $this->status; }

    public function setContatos($contatos): void { $this->contatos = $contatos; }
    public function getContatos(): Collection { return $this->contatos; }
}
