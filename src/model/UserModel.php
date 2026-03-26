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
    #[ORM\ManyToMany(targetEntity: Endereco::class, cascade: ["persist"])]
    #[ORM\JoinTable(name: "user_enderecos")]
    protected Collection $enderecos;
    #[ORM\ManyToMany(targetEntity: Contato::class, cascade: ["persist"])]
    #[ORM\JoinTable(name: "user_contatos")]
    protected Collection $contatos;
    #[ORM\Column(type:'integer')]
    private $idade;
    #[ORM\Column(type:'string')]
    private $senha;
    #[ORM\Column(type:'string')]
    private $tipo;



    public function __construct($name, $cpf, $idade, $senha, $tipo) {
        $this->name = $name;
        $this->cpf = $cpf;
        $this->idade = $idade;
        $this->senha = $senha;
        $this->tipo = $tipo;

        $this->enderecos = new ArrayCollection();
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

    public function setIdade($idade){
        $this->idade=$idade;
    }

    public function getIdade(){
        return $this->idade;
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