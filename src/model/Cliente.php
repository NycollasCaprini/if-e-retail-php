<?php

namespace model;

class Cliente extends UserModel
{
    private $carrinho;
    private $ListaPedidos;
    private $ListaFavoritos;

    public function __construct($carrinho, $ListaPedidos, $ListaFavoritos, $name, $cpf, $endereco, $contato, $idade, $senha, $tipo){
        parent::__contstruct($name, $cpf, $endereco, $contato, $idade, $senha, $tipo);
        $this->carrinho = $carrinho;
        $this->ListaPedidos = $ListaPedidos;
        $this->ListaFavoritos = $ListaFavoritos;
    }

    public function setCarrinho($carrinho){
        $this->carrinho = $carrinho;
    }

    public function getCarrinho(){
        return $this->carrinho;
    }

    public function setListaPedidos($ListaPedidos){
        $this->ListaPedidos = $ListaPedidos;
    }

    public function getListaPedidos(){
        return $this->ListaPedidos;
    }

    public function setListaFavoritos($ListaFavoritos){
        $this->ListaFavoritos = $ListaFavoritos;
    }

    public function getListaFavoritos(){
        return $this->ListaFavoritos;
    }

}