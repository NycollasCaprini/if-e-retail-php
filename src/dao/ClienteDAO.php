<?php

namespace dao;

use Exception;
use model\Cliente;
use utils\Conexao;

// Ele já herda todos os métodos do GenericDAO
class ClienteDAO extends GenericDAO
{
    protected static $modelClass = Cliente::class;

    // Forma 1: findBy — busca exata por campo
    public static function buscarPorCpf($cpf)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Cliente::class);
            return $repository->findBy(['cpf' => $cpf]);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar cliente por CPF. " . $ex->getMessage());
        }
    }

    // Forma 2: QueryBuilder — busca parcial com LIKE
    public static function buscarPorNome($name)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Cliente::class);
            $queryBuilder = $repository->createQueryBuilder('c');
            $queryBuilder
                ->where('c.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
            return $queryBuilder->getQuery()->getResult();
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar cliente por nome. " . $ex->getMessage());
        }
    }

    // Forma 3: DQL — query em linguagem Doctrine
    public static function buscarPorNomeDQL($name)
    {
        try {
            $em = Conexao::getEntityManager();
            $query = $em->createQuery("SELECT c FROM model\Cliente c WHERE c.name LIKE :name");
            $query->setParameter('name', '%' . $name . '%');
            return $query->getResult();
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar cliente por nome (DQL). " . $ex->getMessage());
        }
    }
}
