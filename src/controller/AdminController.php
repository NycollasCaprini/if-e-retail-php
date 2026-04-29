<?php

namespace controller;

use Exception;
use dao\AdminDAO;

class AdminController{

    public function listar(){
        try{
            $admins = AdminDAO::listar();
        }catch(Exception $ex){
            echo "Falha ao listar funcionarios" . $ex->getMessage();
        }finally{
            require __DIR__ . "/../view/lista-funcionarios.php";
        }
    }

    public function buscar(array $params){
        try{
            $id = $params['id'];
            $admin = AdminDAO::buscarPorId($id);
            if(empty($admin)){
                echo "funcionario nao encontrado!";
            }
        }catch(Exception $ex){
            echo "Falha ao buscar funcionario " . $ex->getMessage();
        }finally{
            require __DIR__ . "/../view/lista-funcinarios.php";
        }
    }

    public function remover(array $params){
        try{
            $id = $params['id'];
            $admin = AdminDAO::buscarPorId($id);
            if(empty($admin)){
                echo "funcionario nao encontrado!";
            }
            AdminDAO::deletar($admin);
        }catch(Exception $ex){
            echo "Falha ao remover " . $ex->getMessage();
        }finally{
            header('Location: ' . BASE_URL . "/admin");
            exit;
        }
    }

}