<?php

namespace dao;

use Exception;
use model\Carrinho;
use utils\Conexao;

class CarrinhoDAO extends GenericDAO
{
    protected static $modelClass = Carrinho::class;

    public static function buscarPorCliente($cliente)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Carrinho::class);
            return $repository->findBy(['cliente' => $cliente]);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar carrinho por cliente. " . $ex->getMessage());
        }
    }

    public static function buscarAbertos()
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Carrinho::class);
            return $repository->findBy(['status' => 'ABERTO']);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar carrinhos abertos. " . $ex->getMessage());
        }
    }
}
