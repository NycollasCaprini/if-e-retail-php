<?php

namespace test\dao;

use dao\ProdutoDAO;
use model\Produto;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

class ProdutoDAOTest extends TestCase
{

    private function criarProduto(string $descricao = "Notebook Dell",
                                   int $qtd = 10,
                                   float $preco = 3500.00,
                                   // Correção: status padronizado para "ativo", alinhado com o filtro usado em buscarPorStatus()
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

    // Teste de atualização: verifica que alterações em um produto persistido são salvas corretamente
    public function testAtualizar()
    {
        $produto = ProdutoDAO::salvar($this->criarProduto("Teclado Mecânico", 5, 250.00));
        $produto->setDescricao("Teclado Mecânico RGB");
        $produto->setPrecoUnitario(299.90);
        $atualizado = ProdutoDAO::salvar($produto);
        $this->assertEquals("Teclado Mecânico RGB", $atualizado->getDescricao());
        $this->assertEquals(299.90, $atualizado->getPrecoUnitario());
    }
}
