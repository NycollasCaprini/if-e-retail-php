<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
#[ORM\Table(name:'tb_admin')]
class Admin extends UserModel
{

    #[ORM\Column(type:'string')]
    private $matricula;
    #[ORM\Column(type:'string')]
    private $setor;
    #[ORM\Column(type:'string')]
    private $cargo;
    #[ORM\Column(type:'string')]
    private $dataAdmissao;
    #[ORM\Column(type:'date')]
    private $status;




    public function __construct($name, $cpf, $endereco, $contato, $idade, $senha, $matricula, $setor, $cargo, $dataAdmissao, $status, $tipo){

        parent::__construct($name, $cpf, $endereco, $contato, $idade, $senha, $tipo); #com esse parent acessamos o construtor da classe pai e obrigamos a criar um admin completo, ou seja, com os atributos herdados.

        $this->matricula = $matricula;
        $this->setor = $setor;
        $this->cargo = $cargo;
        $this->dataAdmissao = $dataAdmissao;
        $this->status = $status;
    }

    public function setMatricula($matricula){
        $this->matricula = $matricula;
    }
    public function getMatricula(){
        return $this->matricula;
    }

    public function setSetor($setor){
        $this->setor = $setor;
    }

    public function getSetor(){
        return $this->setor;
    }

    public function setCargo($cargo){
        $this->cargo = $cargo;
    }
    public function getCargo(){
        return $this->cargo;
    }

    public function setDataAdmissao($dataAdmissao){
        $this->dataAdmissao = $dataAdmissao;
    }

    public function getDataAdmissao(){
        return $this->dataAdmissao;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function getStatus(){
        return $this->status;
    }


}