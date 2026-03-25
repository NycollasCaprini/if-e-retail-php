<?php
require_once __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use utils\Conexao;

// Executar: php doctrine.php orm:schema-tool:create
ConsoleRunner::run(
    new SingleManagerProvider(Conexao::getEntityManager())
);
