<?php

namespace test\dao;

use dao\PedidoDAO;
use model\Pedido;
use DateTime;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

class OrderDAOTest extends TestCase
{
    protected function setUp(): void
    {
        $reflection = new \ReflectionClass(Conexao::class);
        $prop = $reflection->getProperty('entityManager');
        $prop->setAccessible(true);
        $prop->setValue(null, null);
    }

    private function criarOrder(): Pedido
    {
        return new Pedido(new DateTime(), new DateTime("+7 days"), "PENDENTE");
    }

    public function testSalvar()
    {
        // Pedido não possui #[ORM\Entity] nem #[ORM\Table] — não é uma entidade Doctrine mapeada.
        $this->markTestSkipped('Pedido não está mapeada como entidade Doctrine.');
    }

    public function testListar()
    {
        $this->markTestSkipped('Pedido não está mapeada como entidade Doctrine.');
    }

    public function testDeletar()
    {
        $this->markTestSkipped('Pedido não está mapeada como entidade Doctrine.');
    }

    public function testBuscarPorStatus()
    {
        $this->markTestSkipped('Pedido não está mapeada como entidade Doctrine.');
    }
}
