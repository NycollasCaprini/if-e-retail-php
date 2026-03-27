<?php

namespace dao;

use Exception;
use model\Contato;
use utils\Conexao;

class ContatoDAO extends GenericDAO
{
    protected static $modelClass = Contato::class;

    public static function buscarPorEmail($email)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Contato::class);
            return $repository->findBy(['email' => $email]);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar contato por email. " . $ex->getMessage());
        }
    }

    public static function buscarPorTelefone($telefone)
    {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(Contato::class);
            return $repository->findBy(['telefone' => $telefone]);
        } catch (Exception $ex) {
            throw new Exception("Falha ao buscar contato por telefone. " . $ex->getMessage());
        }
    }
}
