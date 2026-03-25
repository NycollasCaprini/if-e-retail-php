<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entidade Endereco associada a um UserModel (Admin ou Cliente).
 *
 * No Spring o relacionamento era @ManyToOne com User.
 * Aqui o Endereco é embutido diretamente na entidade proprietária
 * via #[ORM\Embedded] ou referenciado via FK — optamos por entidade
 * independente para permitir múltiplos endereços por usuário.
 */
#[ORM\Entity]
#[ORM\Table(name: 'tb_endereco')]
class Endereco extends GenericModel {

    #[ORM\Column(type: 'string')]
    private $rua;

    #[ORM\Column(type: 'string')]
    private $numero;

    #[ORM\Column(type: 'string', nullable: true)]
    private $complemento;

    #[ORM\Column(type: 'string')]
    private $bairro;

    #[ORM\Column(type: 'string')]
    private $cidade;

    #[ORM\Column(type: 'string')]
    private $estado;

    #[ORM\Column(type: 'string', length: 9)]
    private $cep;

    #[ORM\Column(type: 'string', nullable: true)]
    private $pais;

    public function setRua($rua): void { $this->rua = $rua; }
    public function getRua() { return $this->rua; }

    public function setNumero($numero): void { $this->numero = $numero; }
    public function getNumero() { return $this->numero; }

    public function setComplemento($complemento): void { $this->complemento = $complemento; }
    public function getComplemento() { return $this->complemento; }

    public function setBairro($bairro): void { $this->bairro = $bairro; }
    public function getBairro() { return $this->bairro; }

    public function setCidade($cidade): void { $this->cidade = $cidade; }
    public function getCidade() { return $this->cidade; }

    public function setEstado($estado): void { $this->estado = $estado; }
    public function getEstado() { return $this->estado; }

    public function setCep($cep): void { $this->cep = $cep; }
    public function getCep() { return $this->cep; }

    public function setPais($pais): void { $this->pais = $pais; }
    public function getPais() { return $this->pais; }
}
