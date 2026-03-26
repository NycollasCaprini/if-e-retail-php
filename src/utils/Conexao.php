<?php

namespace utils;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;

class Conexao{
    private static $entityManager;
    #captura o EntityManager que controla as entidades (doctrine)

    public static function getEntityManager() {
        // self eh diferente do this. O this eh usado para objetos, self para atributos estaticas
        if (self::$entityManager === null) {
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: [realpath(__DIR__ . '/../model')],
                isDevMode: true,
            );
            //estas duas linhas seguintes servem apenas para ler o .env
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->load();

            //configuramos a conexao com o banco
            $connection = DriverManager::getConnection([
                'driver' => $_ENV['DB_DRIVER'],
                'host' => $_ENV['DB_HOST'],
                'port' => $_ENV['DB_PORT'],
                'dbname' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
            ], $config);
            //instancia e coloca os parametros que configuramos
            self::$entityManager = new EntityManager($connection, $config);
        }
        return self::$entityManager;
    }

}



?>