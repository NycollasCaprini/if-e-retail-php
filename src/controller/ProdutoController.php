<?php

namespace controller;

use dao\ProdutoDAO;
use Exception;
class ProdutoController{

    public function listar(){
        try{
            $produtos = ProdutoDAO::listar();
        }catch(Exception $ex){
            echo "Falha ao listar produtos " . $ex->getMessage();
        } finally {
            require __DIR__ . "/../view/lista-produtos.php";
        }
    }

    public function buscar(array $params){
        try{
            $id = $params['id'];
            $produto = ProdutoDAO::buscarPorId($id);
            if(empty($produto)){
                echo "Produto nao foi encontrado!";
            }
        }catch (Exception $ex){
            echo "Falha ao buscar produto" . $ex->getMessage();
        } finally {
            require __DIR__ . "/../view/lista-produtos.php";
        }
    }

    public function remover(array $params){
        try{
            $id = $params['id'];
            $produto = ProdutoDAO::buscarPorId($id);
            if(empty($produto)){
                echo "Produto nao foi encontrado!";
            }
            ProdutoDAO::deletar($produto);
        }catch (Exception $ex){
            echo "Falha ao remover produto" . $ex->getMessage();
        } finally {
            header('Location: ', BASE_URL . '/produtos');
            exit;
        }
    }
}