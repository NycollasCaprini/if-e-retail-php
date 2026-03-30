<?php

namespace dao;

use Exception;
use model\Pedido;
use utils\Conexao;

class PedidoDAO extends GenericDAO
{
    protected static $modelClass = Pedido::class;

    public static function buscarPorStatus($status)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Pedido::class);
            return $repository->findBy(['status' => $status]);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar pedidos por status. " . $ex->getMessage());
        }
    }
}
