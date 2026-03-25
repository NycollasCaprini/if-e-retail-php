<?php

namespace utils;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;

/**
 * Classe utilitária que fornece o EntityManager do Doctrine.
 *
 * Segue o padrão Singleton: cria uma única instância do EntityManager
 * durante toda a execução da aplicação, reutilizando a mesma conexão.
 *
 * Equivalente ao ApplicationContext do Spring Boot — é o ponto
 * central que gerencia o ciclo de vida das entidades JPA/Doctrine.
 */
class Conexao {

    private static $entityManager;

    public static function getEntityManager(): EntityManager {
        if (self::$entityManager === null) {

            // ORMSetup lê as anotações #[ORM\...] dos arquivos dentro de src/model/
            // Equivalente ao @EnableJpaRepositories + scan de @Entity no Spring
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: [realpath(__DIR__ . '/../model')],
                isDevMode: true,
            );

            // Carrega as variáveis do .env (DB_DRIVER, DB_HOST, etc.)
            // Equivalente ao application.properties do Spring Boot
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->load();

            $connection = DriverManager::getConnection([
                'driver'   => $_ENV['DB_DRIVER'],
                'host'     => $_ENV['DB_HOST'],
                'port'     => $_ENV['DB_PORT'],
                'dbname'   => $_ENV['DB_NAME'],
                'user'     => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
            ], $config);

            self::$entityManager = new EntityManager($connection, $config);
        }

        return self::$entityManager;
    }
}
