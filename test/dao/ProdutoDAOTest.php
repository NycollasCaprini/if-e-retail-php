<?php

namespace test\dao;

use dao\ProdutoDAO;
use model\Produto;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

class ProdutoDAOTest extends TestCase
{
    protected function setUp(): void
    {
        $reflection = new \ReflectionClass(Conexao::class);
        $prop = $reflection->getProperty('entityManager');
        $prop->setAccessible(true);
        $prop->setValue(null, null);
    }

    private function criarProduto(string $descricao = "Notebook Dell",
                                   int $qtd = 10,
                                   float $preco = 3500.00,
                                   string $status = "ativo"): Produto
    {
        return new Produto($descricao, $qtd, $preco, $status);
    }

    public function testSalvar()
    {
        $produto = $this->criarProduto();
        $salvo = ProdutoDAO::salvar($produto);
        $this->assertNotNull($salvo->getID());
    }

    public function testListar()
    {
        ProdutoDAO::salvar($this->criarProduto());
        $lista = ProdutoDAO::listar();
        $this->assertNotEmpty($lista);
    }

    public function testDeletar()
    {
        $produto = ProdutoDAO::salvar($this->criarProduto());
        ProdutoDAO::deletar($produto);
        $lista = ProdutoDAO::listar();
        foreach ($lista as $p) {
            $this->assertNotEquals($produto->getID(), $p->getID());
        }
    }

    public function testBuscarPorDescricao()
    {
        ProdutoDAO::salvar($this->criarProduto("Mouse Gamer"));
        $resultado = ProdutoDAO::buscarPorDescricao("Mouse");
        $this->assertNotEmpty($resultado);
    }

    public function testBuscarSemEstoque()
    {
        ProdutoDAO::salvar($this->criarProduto("Produto Zerado", 0));
        $resultado = ProdutoDAO::buscarSemEstoque();
        $this->assertNotEmpty($resultado);
    }

    public function testBuscarPorStatus()
    {
        ProdutoDAO::salvar($this->criarProduto());
        $resultado = ProdutoDAO::buscarPorStatus("ativo");
        $this->assertNotEmpty($resultado);
    }
}
