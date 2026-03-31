<?php

namespace dao;

use Exception;
use model\Pedido;
use utils\Conexao;

class PedidoDAO extends GenericDAO
{
    protected static $modelClass = Pedido::class;

    public static function buscarPorStatus($status){
        try{
            $em = Conexao::getEntityManager();
            $query = $em->createQuery("SELECT p FROM model\Produto p WHERE p.status = :status");
            $query->setParameter("status", $status);
            return $query->getResult();
        }catch(Exception $e){
            throw new Exception("Falha ao buscar produto por status. " .  $e->getMessage());
        }
    }
}
