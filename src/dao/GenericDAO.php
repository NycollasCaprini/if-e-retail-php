<?php

namespace dao;

use Exception;
use model\GenericModel;
use utils\Conexao;

abstract class GenericDAO
{
    // Armazenamos a CLASSE do model que será implementada
    // Ele é protegido pra permitir acesso das outras classes filhas
    protected static $modelClass;

    // Método estático — não precisa de instanciação
    public static function salvar(GenericModel $model)
    {
        try {
            $em = Conexao::getEntityManager(); // captura a instancia do EntityManager que controla o nosso banco pelo Doctrine.
            $em->beginTransaction(); // Inicia explicitamente uma transação.
            $em->persist($model); // O doctrine registra que o objeto (Model) deve ser persistido, mas não vai para o banco ainda.
            $em->flush(); // compara as entidades e gera os comandos SQL para INSERT/UPDATE
            $em->commit(); // Torna a transação permanente no banco
            return $model; // retorna o model com o ID já preenchido. O doctrine salva diretamente no model enviado
        } catch (Exception $ex) {
            $em->rollback(); // Se acontecer algum erro, a transação é desfeita
            throw new Exception("Falha ao salvar os dados." . $ex->getMessage());
        }
    }

    public static function listar()
    {
        try {
            $em = Conexao::getEntityManager(); // captura a instancia do EntityManager que controla o nosso banco pelo Doctrine.
            $repository = $em->getRepository(static::$modelClass); // Obtém o repositório específico da classe/entidade alvo
            return $repository->findAll(); // Executa um 'SELECT * FROM ...' e salva tudo em um array. Retorna este array no final
        } catch (Exception $ex) {
            throw new Exception("Falha ao listar os dados." . $ex->getMessage());
        }
    }

    public static function deletar(GenericModel $model)
    {
        try {
            $em = Conexao::getEntityManager();
            $em->beginTransaction();
            $em->remove($model);
            $em->flush();
            $em->commit();
        } catch (Exception $ex) {
            $em->rollback();
            throw new Exception("Falha ao deletar os dados." . $ex->getMessage());
        }
    }
}
