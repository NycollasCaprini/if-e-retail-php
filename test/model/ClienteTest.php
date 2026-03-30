<?php

namespace test\model;

use model\Cliente;
use PHPUnit\Framework\TestCase;

class ClienteTest extends TestCase
{
    public function testCriarObjeto()
    {
        $cliente = new Cliente(
            "Elis Regina",
            "222.333.444-55",
            new \DateTime("1992-06-17"),
            "hash456",
            "cliente"
        );
        $this->assertNotNull($cliente);
        $this->assertEquals("Elis Regina", $cliente->getName());
        $this->assertEquals("222.333.444-55", $cliente->getCpf());
        $this->assertEquals("cliente", $cliente->getTipo());
    }
}
