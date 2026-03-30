<?php

namespace model;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name:"tb_endereco")]
class Endereco extends GenericModel
{
    #[ORM\Column(type: "string")]
    private $rua;
    #[ORM\Column(type: "string")]
    private $numero;
    #[ORM\Column(type: "string")]
    private $complemento;
    #[ORM\Column(type: "string")]
    private $bairro;
    #[ORM\Column(type: "string")]
    private $cidade;
    #[ORM\Column(type: "string")]
    private $estado;
    #[ORM\Column(type: "string")]
    private $cep;
    #[ORM\Column(type: "string")]
    private $pais;
    #[ORM\Column(type: "string")]

    #[ORM\OneToMany(targetEntity: UserModel::class, mappedBy: "enderecos")]
    private Collection $usuarios;

    public function __construct($rua, $numero, $complemento, $bairro, $cidade, $estado, $cep, $pais ){
        $this->rua = $rua;
        $this->numero = $numero;
        $this->complemento = $complemento;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->estado = $estado;
        $this->cep = $cep;
        $this->pais = $pais;

        $this->usuarios = new ArrayCollection();
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