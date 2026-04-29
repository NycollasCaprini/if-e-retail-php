<?php

namespace controller;

use dao\ClienteDAO;
use Exception;

class ClienteController{

    public function listar(){
        try{
            $clientes = ClienteDAO::listar();
        }catch(Exception $ex){
            echo "Falha ao listar os clientes" . $ex->getMessage();
        } finally {
            require __DIR__ . "/../view/lista-clientes.php";
        }
    }

    public function buscar(array $params){
        try{
            $id = $params['id'];
            $cliente = ClienteDAO::buscarPorId($id);
            if(empty($cliente)){
                throw new Exception("Cliente nao encontrado");
            }
        }catch(Exception $ex){
            echo "Falha ao buscar cliente" . $ex->getMessage();
        }finally{
            require __DIR__ . "/../view/visualizar-cliente.php";
        }
    }

    public function remover(array $params){
        try{
            $id = $params['id'];
            $cliente = ClienteDAO::buscarPorId($id);
            if(empty($cliente)){
                throw new Exception("Cliente nao encontrado");
            }
            ClienteDAO::deletar($cliente);
        }catch (Exception $ex){
            echo "Falha ao remover cliente" . $ex->getMessage();
        }finally{
            header('Location: ' . BASE_URL . '/clientes');
            exit;
        }
    }
}