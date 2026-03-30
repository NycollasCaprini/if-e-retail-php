<?php

namespace test\utils;

use Exception;
use utils\Conexao;
use PHPUnit\Framework\TestCase;

class ConexaoTest extends TestCase
{
    public function testConectarBanco()
    {
        try {
            $conexao = Conexao::getEntityManager();
            self::assertNotNull($conexao);
        } catch (Exception $e) {
            self::fail("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }
}
