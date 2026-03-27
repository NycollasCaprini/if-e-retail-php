<?php

namespace dao;

use Exception;
use model\ItemCarrinho;
use utils\Conexao;

class ItemCarrinhoDAO extends GenericDAO
{
    protected static $modelClass = ItemCarrinho::class;

    public static function buscarPorCarrinho($carrinho)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(ItemCarrinho::class);
            return $repository->findBy(['carrinho' => $carrinho]);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar itens por carrinho. " . $ex->getMessage());
        }
    }

    public static function buscarPorProduto($produto)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(ItemCarrinho::class);
            return $repository->findBy(['produto' => $produto]);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar itens por produto. " . $ex->getMessage());
        }
    }
}
