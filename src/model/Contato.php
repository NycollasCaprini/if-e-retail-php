<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entidade Contato (telefone + e-mail) associada a um Admin ou Cliente.
 *
 * No Spring era @ManyToOne com User via user_id.
 * Aqui mantemos a mesma semântica: cada Contato pertence a um único
 * usuário, mas um usuário pode ter vários Contatos.
 *
 * Como UserModel é @MappedSuperclass (não é @Entity), não podemos
 * criar FK diretamente para ele. Criamos FKs separadas para Admin e
 * Cliente conforme o tipo de usuário.
 */
#[ORM\Entity]
#[ORM\Table(name: 'tb_contato')]
class Contato extends GenericModel {

    #[ORM\Column(type: 'string')]
    private $telefone;

    #[ORM\Column(type: 'string')]
    private $email;

    #[ORM\Column(type: 'string', nullable: true)]
    private $whatsapp;

    // FK para Cliente — nullable para permitir contatos de Admin também
    #[ORM\ManyToOne(targetEntity: Cliente::class, inversedBy: 'contatos')]
    #[ORM\JoinColumn(name: 'cliente_id', nullable: true)]
    private $cliente;

    // FK para Admin
    #[ORM\ManyToOne(targetEntity: Admin::class, inversedBy: 'contatos')]
    #[ORM\JoinColumn(name: 'admin_id', nullable: true)]
    private $admin;

    public function setTelefone($telefone): void { $this->telefone = $telefone; }
    public function getTelefone() { return $this->telefone; }

    public function setEmail($email): void { $this->email = $email; }
    public function getEmail() { return $this->email; }

    public function setWhatsapp($whatsapp): void { $this->whatsapp = $whatsapp; }
    public function getWhatsapp() { return $this->whatsapp; }

    public function setCliente($cliente): void { $this->cliente = $cliente; }
    public function getCliente() { return $this->cliente; }

    public function setAdmin($admin): void { $this->admin = $admin; }
    public function getAdmin() { return $this->admin; }
}
