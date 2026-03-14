<?php
namespace model;
abstract class UserModel extends GenericModel{
    private $name;
    private $cpf;
    private $endereco;
    private $contato;
    private $idade;
    private $senha;
    private $tipo;


    public function __contstruct($name, $cpf, $endereco, $contato, $idade, $senha, $tipo){
        $this->name = $name;
        $this->cpf = $cpf;
        $this->contato = $contato;
        $this->endereco = $endereco;
        $this->idade = $idade;
        $this->senha = $senha;
        $this->tipo = $tipo;
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