<?php

namespace dao;

use Exception;
use model\Endereco;
use utils\Conexao;

class EnderecoDAO extends GenericDAO
{
    protected static $modelClass = Endereco::class;

    public static function buscarPorCidade($cidade)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Endereco::class);
            return $repository->findBy(['cidade' => $cidade]);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar endereço por cidade. " . $ex->getMessage());
        }
    }

    public static function buscarPorCep($cep)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Endereco::class);
            return $repository->findBy(['cep' => $cep]);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar endereço por CEP. " . $ex->getMessage());
        }
    }

    public static function buscarPorCidadeParecida($cidade)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Endereco::class);
            $queryBuilder = $repository->createQueryBuilder('e');
            $queryBuilder
                ->where('e.cidade LIKE :cidade')
                ->setParameter('cidade', '%' . $cidade . '%');
            return $queryBuilder->getQuery()->getResult();
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar endereço por cidade (parcial). " . $ex->getMessage());
        }
    }
}
