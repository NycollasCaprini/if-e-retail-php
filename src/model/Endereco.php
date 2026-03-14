<?php

namespace model;


class Endereco extends GenericModel
{
    private $rua;
    private $numero;
    private $complemento;
    private $bairro;
    private $cidade;
    private $estado;
    private $cep;
    private $pais;

    public function __construct($rua, $numero, $complemento, $bairro, $cidade, $estado, $cep, $pais ){
        $this->rua = $rua;
        $this->numero = $numero;
        $this->complemento = $complemento;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->estado = $estado;
        $this->cep = $cep;
        $this->pais = $pais;

    }

    public function setRua($rua){
        $this->rua=$rua;
    }
    public function getRua(){
        return $this->rua;
    }

    public function setNumero($numero){
        $this->numero=$numero;
    }
    public function getNumero(){
        return $this->numero;
    }

    public function setComplemento($complemento){
        $this->complemento=$complemento;
    }

    public function getComplemento(){
        return $this->complemento;
    }

    public function setBairro($bairro){
        $this->bairro=$bairro;
    }

    public function getBairro(){
        return $this->bairro;
    }

    public function setCidade($cidade){
        $this->cidade=$cidade;
    }

    public function getCidade(){
        return $this->cidade;
    }
    public function setEstado($estado){
        $this->estado=$estado;
    }

    public function getEstado(){
        return $this->estado;
    }

    public function setCep($cep){
        $this->cep=$cep;

    }

    public function getCep(){
        return $this->cep;
    }

}