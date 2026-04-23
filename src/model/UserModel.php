<?php
namespace model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
#[ORM\Entity]
#[ORM\Table(name: "tb_user")]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "discr", type: "string")]
#[ORM\DiscriminatorMap(["admin" => Admin::class, "cliente" => Cliente::class])]
abstract class UserModel extends GenericModel{
    #[ORM\Column(type:'string')]
    private $name;
    #[ORM\Column(type:'string')]
    private $cpf;
    #[ORM\OneToOne(targetEntity: Endereco::class, cascade: ['all'], orphanRemoval: true, fetch: 'LAZY')]
    #[ORM\JoinColumn(name:"endereco_id")]
    protected $endereco = null;
    // Correção: mappedBy deve referenciar o atributo '$usuario' existente em Contato, não '$user'
    #[ORM\OneToMany(mappedBy: "usuario", targetEntity: Contato::class, cascade: ["all"], orphanRemoval: true, fetch: 'LAZY')]
    protected $contatos;
    #[ORM\Column(type:'date')]
    private $dataNascimento;
    #[ORM\Column(type:'string')]
    private $senha;
    #[ORM\Column(type:'string')]
    private $tipo;



    public function __construct($name, $cpf, $dataNascimento, $senha, $tipo) {
        $this->name = $name;
        $this->cpf = $cpf;
        $this->dataNascimento = $dataNascimento;
        $this->senha = $senha;
        $this->tipo = $tipo;

        $this->endereco = null;
        $this->contatos = new ArrayCollection();
    }
    public function setName($name){
        $this->name=$name;
    }

    public function getName(){
        return $this->name;
    }

    public function setCpf($cpf){
        $this->cpf=$cpf;
    }

    public function getCpf(){
        return $this->cpf;
    }

    public function setEndereco($endereco){
        $this->endereco=$endereco;
    }

    public function getEndereco(){
        return $this->endereco;
    }

    public function setContato($contato){
        $this->contato=$contato;
    }

    public function getContato(){
        return $this->contato;
    }

    public function setDataNascimento($dataNascimento){
        $this->dataNascimento=$dataNascimento;
    }

    public function getDataNascimento(){
        return $this->dataNascimento;
    }

    public function setSenha($senha){
        $this->senha=$senha;
    }

    public function getSenha(){
        return $this->senha;
    }

    public function setTipo($tipo){
        $this->tipo=$tipo;
    }

    public function getTipo(){
        return $this->tipo;
    }

}

?>