<?php

namespace test\dao;

use dao\CarrinhoDAO;
use model\Carrinho;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

class CarrinhoDAOTest extends TestCase
{
    protected function setUp(): void
    {
        $reflection = new \ReflectionClass(Conexao::class);
        $prop = $reflection->getProperty('entityManager');
        $prop->setAccessible(true);
        $prop->setValue(null, null);
    }

    public function testSalvar()
    {
        $carrinho = new Carrinho("ABERTO");
        $salvo = CarrinhoDAO::salvar($carrinho);
        $this->assertNotNull($salvo->getID());
    }

    public function testListar()
    {
        CarrinhoDAO::salvar(new Carrinho("ABERTO"));
        $lista = CarrinhoDAO::listar();
        $this->assertNotEmpty($lista);
    }

    public function testDeletar()
    {
        $carrinho = CarrinhoDAO::salvar(new Carrinho("ABERTO"));
        CarrinhoDAO::deletar($carrinho);
        $lista = CarrinhoDAO::listar();
        foreach ($lista as $c) {
            $this->assertNotEquals($carrinho->getID(), $c->getID());
        }
    }

    public function testBuscarAbertos()
    {
        CarrinhoDAO::salvar(new Carrinho("ABERTO"));
        $resultado = CarrinhoDAO::buscarAbertos();
        $this->assertNotEmpty($resultado);
    }
}
