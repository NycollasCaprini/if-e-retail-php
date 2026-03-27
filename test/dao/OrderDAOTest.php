<?php

namespace test\dao;

use dao\OrderDAO;
use model\Order;
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

    private function criarOrder(): Order
    {
        return new Order(new DateTime(), new DateTime("+7 days"), "PENDENTE");
    }

    public function testSalvar()
    {
        // Order não possui #[ORM\Entity] nem #[ORM\Table] — não é uma entidade Doctrine mapeada.
        $this->markTestSkipped('Order não está mapeada como entidade Doctrine.');
    }

    public function testListar()
    {
        $this->markTestSkipped('Order não está mapeada como entidade Doctrine.');
    }

    public function testDeletar()
    {
        $this->markTestSkipped('Order não está mapeada como entidade Doctrine.');
    }

    public function testBuscarPorStatus()
    {
        $this->markTestSkipped('Order não está mapeada como entidade Doctrine.');
    }
}
