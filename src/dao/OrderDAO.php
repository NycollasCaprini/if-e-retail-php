<?php

namespace dao;

use Exception;
use model\Order;
use utils\Conexao;

class OrderDAO extends GenericDAO
{
    protected static $modelClass = Order::class;

    public static function buscarPorStatus($status)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Order::class);
            return $repository->findBy(['status' => $status]);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar pedidos por status. " . $ex->getMessage());
        }
    }
}
