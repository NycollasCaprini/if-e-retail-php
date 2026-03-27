<?php

namespace dao;

use Exception;
use model\Produto;
use utils\Conexao;

class ProdutoDAO extends GenericDAO
{
    protected static $modelClass = Produto::class;

    public static function buscarPorDescricao($descricao)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Produto::class);
            $queryBuilder = $repository->createQueryBuilder('p');
            $queryBuilder
                ->where('p.descricao LIKE :descricao')
                ->setParameter('descricao', '%' . $descricao . '%');
            return $queryBuilder->getQuery()->getResult();
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar produto por descrição. " . $ex->getMessage());
        }
    }

    public static function buscarSemEstoque()
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Produto::class);
            $queryBuilder = $repository->createQueryBuilder('p');
            $queryBuilder->where('p.quantidade = 0');
            return $queryBuilder->getQuery()->getResult();
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar produtos sem estoque. " . $ex->getMessage());
        }
    }

    public static function buscarPorStatus($status)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Produto::class);
            return $repository->findBy(['status' => $status]);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar produto por status. " . $ex->getMessage());
        }
    }
}
