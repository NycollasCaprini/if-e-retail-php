<?php

namespace test\dao;

use dao\CarrinhoDAO;
use dao\ClienteDAO;
use model\Carrinho;
use model\Cliente;
use model\Produto;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

/**
 * Testes de integração para CarrinhoDAO.
 */
class CarrinhoDAOTest extends TestCase {

    private CarrinhoDAO $dao;
    private ClienteDAO $clienteDAO;

    protected function setUp(): void {
        $this->dao       = new CarrinhoDAO();
        $this->clienteDAO = new ClienteDAO();
    }

    protected function tearDown(): void {
        $em = Conexao::getEntityManager();
        $em->getConnection()->executeStatement('DELETE FROM tb_carrinho_produto');
        $em->createQuery('DELETE FROM model\Carrinho c')->execute();
        $em->createQuery('DELETE FROM model\Cliente c')->execute();
        $em->createQuery('DELETE FROM model\Produto p')->execute();
        $em->clear();
    }

    // ── Inserção simples ──────────────────────────────────────────────────────

    public function testInsert(): void {
        $carrinho = new Carrinho();
        $carrinho->setStatus(true);
        $carrinho->setValorTotal(0.0);
        $carrinho = $this->dao->salvar($carrinho);

        $carrinhoInserido = $this->dao->buscarPorId($carrinho->getId());

        $this->assertNotNull($carrinhoInserido, 'O carrinho não foi inserido.');
    }

    // ── Inserção com produtos relacionados (ManyToMany) ───────────────────────

    public function testInserirComProdutos(): void {
        $em = Conexao::getEntityManager();

        $p1 = new Produto(); $p1->setDescricao('Teclado'); $p1->setPrecoUnitario(150.00);
        $p1->setQuantidade(20); $p1->setStatus(true);
        $em->persist($p1);

        $p2 = new Produto(); $p2->setDescricao('Mouse'); $p2->setPrecoUnitario(80.00);
        $p2->setQuantidade(50); $p2->setStatus(true);
        $em->persist($p2);
        $em->flush();

        $carrinho = new Carrinho();
        $carrinho->setStatus(true);
        $carrinho->setValorTotal(230.0);
        $carrinho->adicionarProduto($p1);
        $carrinho->adicionarProduto($p2);
        $carrinho = $this->dao->salvar($carrinho);

        $carrinhoComProdutos = $this->dao->buscarComProdutos($carrinho->getId());

        $this->assertNotNull($carrinhoComProdutos, 'Carrinho não encontrado via DQL JOIN FETCH.');
        $this->assertCount(2, $carrinhoComProdutos->getListaProdutos(),
            'Os produtos não foram associados corretamente ao carrinho (ManyToMany).');
    }

    // ── Atualização ───────────────────────────────────────────────────────────

    public function testUpdate(): void {
        $carrinho = new Carrinho();
        $carrinho->setStatus(true); $carrinho->setValorTotal(100.0);
        $carrinho = $this->dao->salvar($carrinho);

        $carrinho->setValorTotal(250.0);
        $this->dao->salvar($carrinho);

        $carrinhoAtualizado = $this->dao->buscarPorId($carrinho->getId());

        $this->assertEquals(250.0, $carrinhoAtualizado->getValorTotal(),
            'O valor total do carrinho não foi atualizado.');
    }

    // ── Remoção ───────────────────────────────────────────────────────────────

    public function testDelete(): void {
        $carrinho = new Carrinho();
        $carrinho->setStatus(true); $carrinho->setValorTotal(0.0);
        $carrinho = $this->dao->salvar($carrinho);
        $id = $carrinho->getId();

        $this->dao->deletar($carrinho);

        $carrinhoRemovido = $this->dao->buscarPorId($id);
        $this->assertNull($carrinhoRemovido, 'O carrinho ainda se encontra no banco de dados.');
    }

    // ── Listagem ──────────────────────────────────────────────────────────────

    public function testListar(): void {
        $c1 = new Carrinho(); $c1->setStatus(true); $c1->setValorTotal(0.0);
        $c2 = new Carrinho(); $c2->setStatus(true); $c2->setValorTotal(0.0);
        $this->dao->salvar($c1);
        $this->dao->salvar($c2);

        $inicio = microtime(true);
        $carrinhos = $this->dao->listarTodos();
        $fim = microtime(true);

        $this->assertNotEmpty($carrinhos, 'A listagem não retornou resultados.');
        $this->assertLessThan(0.3, $fim - $inicio, 'A consulta demorou mais de 0,3 segundos.');
    }

    // ── SQL Nativo: contar produtos ───────────────────────────────────────────

    public function testContarProdutos(): void {
        $em = Conexao::getEntityManager();
        $p = new Produto(); $p->setDescricao('SSD'); $p->setPrecoUnitario(400.00);
        $p->setQuantidade(10); $p->setStatus(true);
        $em->persist($p); $em->flush();

        $carrinho = new Carrinho();
        $carrinho->setStatus(true); $carrinho->setValorTotal(400.0);
        $carrinho->adicionarProduto($p);
        $carrinho = $this->dao->salvar($carrinho);

        $total = $this->dao->contarProdutos($carrinho->getId());

        $this->assertEquals(1, $total,
            'A query SQL nativa de contagem de produtos não retornou o valor correto.');
    }
}
