<?php
namespace controller;

use Exception;
use dao\PedidoDAO;

class PedidoController{

    public function listar(){
        try{
            $pedidos = PedidoDAO::listar();
        }catch(Exception $ex){
            echo "erro ao listar pedidos" . $ex->getMessage();
        } finally {
            require __DIR__ . "/../view/lista-pedidos.php";
        }
    }

    public function buscar(array $params){
        try{
            $id = $params['id'];
            $pedido = PedidoDAO::buscarPorId($id);
            if(empty($pedido)){
                echo "pedido nao encontrado";
            }
        }catch(Exception $ex){
            echo "erro ao buscar pedido" . $ex->getMessage();
        } finally {
            require __DIR__ . "/../view/lista-pedidos.php";
        }
    }

    public function remover(array $params){
        try{
            $id = $params['id'];
            $pedido = PedidoDAO::buscarPorId($id);
            if(empty($pedido)){
                echo "pedido nao encontrado";
            }
            PedidoDAO::deletar($pedido);
        }catch(Exception $ex){
            echo "erro ao remover pedido" . $ex->getMessage();
        } finally {
            header('Location: ' . BASE_URL . '/pedidos');
            exit;
        }
    }

}

