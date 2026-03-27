<?php

namespace dao;

use Exception;
use model\Admin;
use utils\Conexao;

class AdminDAO extends GenericDAO
{
    protected static $modelClass = Admin::class;

    public static function buscarPorCpf($cpf)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Admin::class);
            return $repository->findBy(['cpf' => $cpf]);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar admin por CPF. " . $ex->getMessage());
        }
    }

    public static function buscarPorNome($name)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Admin::class);
            $queryBuilder = $repository->createQueryBuilder('a');
            $queryBuilder
                ->where('a.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
            return $queryBuilder->getQuery()->getResult();
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar admin por nome. " . $ex->getMessage());
        }
    }

    public static function buscarPorCargo($cargo)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Admin::class);
            return $repository->findBy(['cargo' => $cargo]);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar admin por cargo. " . $ex->getMessage());
        }
    }
}
