<?php

namespace test\dao;

use dao\ProdutoDAO;
use model\Produto;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

/**
 * Testes de integração para ProdutoDAO.
 *
 * Padrão do professor (Aula 06 / Exemplo Spring):
 *   - Estende TestCase do PHPUnit
 *   - Usa o banco real via EntityManager (Doctrine)
 *   - setUp() e tearDown() garantem limpeza entre testes
 *     (equivalente ao @Transactional do Spring que reverte ao final)
 *   - Cada teste segue o padrão: arrange → act → assert
 *
 * Para rodar: ./vendor/bin/phpunit test/dao/ProdutoDAOTest.php
 */
class ProdutoDAOTest extends TestCase {

    private ProdutoDAO $dao;

    // setUp() roda antes de cada teste — equivalente ao @BeforeEach do JUnit 5
    protected function setUp(): void {
        $this->dao = new ProdutoDAO();
    }

    // tearDown() roda após cada teste — limpa os dados inseridos
    // Equivalente ao @Transactional do Spring (que reverte automaticamente)
    protected function tearDown(): void {
        $em = Conexao::getEntityManager();
        $em->createQuery('DELETE FROM model\Produto p')->execute();
        $em->clear();
    }

    // ── Inserção simples ──────────────────────────────────────────────────────

    public function testInsert(): void {
        $produto = new Produto();
        $produto->setDescricao('Notebook Dell Inspiron');
        $produto->setPrecoUnitario(3500.00);
        $produto->setQuantidade(10);
        $produto->setStatus(true);
        $produto = $this->dao->salvar($produto);

        $produtoInserido = $this->dao->buscarPorId($produto->getId());

        $this->assertNotNull($produtoInserido, 'O produto não foi inserido.');
        $this->assertEquals('Notebook Dell Inspiron', $produtoInserido->getDescricao(),
            'A descrição do produto não confere.');
    }

    // ── Atualização ───────────────────────────────────────────────────────────

    public function testUpdate(): void {
        $produto = new Produto();
        $produto->setDescricao('Mouse sem fio');
        $produto->setPrecoUnitario(80.00);
        $produto->setQuantidade(50);
        $produto->setStatus(true);
        $produto = $this->dao->salvar($produto);

        $produto->setPrecoUnitario(65.00);
        $this->dao->salvar($produto);

        $produtoAtualizado = $this->dao->buscarPorId($produto->getId());

        $this->assertEquals(65.00, $produtoAtualizado->getPrecoUnitario(),
            'O preço do produto não foi atualizado.');
    }

    // ── Remoção ───────────────────────────────────────────────────────────────

    public function testDelete(): void {
        $produto = new Produto();
        $produto->setDescricao('Teclado Mecânico');
        $produto->setPrecoUnitario(250.00);
        $produto->setQuantidade(15);
        $produto->setStatus(true);
        $produto = $this->dao->salvar($produto);
        $id = $produto->getId();

        $this->dao->deletar($produto);

        $produtoDeletado = $this->dao->buscarPorId($id);
        $this->assertNull($produtoDeletado, 'O produto ainda se encontra no banco de dados.');
    }

    // ── Listagem com teste de performance ─────────────────────────────────────

    public function testListar(): void {
        for ($i = 1; $i <= 5; $i++) {
            $p = new Produto();
            $p->setDescricao('Produto ' . $i);
            $p->setPrecoUnitario($i * 10.0);
            $p->setQuantidade($i * 5);
            $p->setStatus(true);
            $this->dao->salvar($p);
        }

        $inicio = microtime(true);
        $produtos = $this->dao->listarTodos();
        $fim = microtime(true);

        $this->assertNotEmpty($produtos, 'A listagem não retornou resultados.');
        // Equivalente ao teste de performance < 300ms do professor
        $this->assertLessThan(0.3, $fim - $inicio, 'A consulta demorou mais de 0,3 segundos.');
    }

    // ── Busca por status ──────────────────────────────────────────────────────

    public function testBuscarPorStatus(): void {
        $ativo = new Produto();
        $ativo->setDescricao('Produto Ativo');
        $ativo->setPrecoUnitario(100.00);
        $ativo->setQuantidade(10);
        $ativo->setStatus(true);
        $this->dao->salvar($ativo);

        $inativo = new Produto();
        $inativo->setDescricao('Produto Inativo');
        $inativo->setPrecoUnitario(100.00);
        $inativo->setQuantidade(0);
        $inativo->setStatus(false);
        $this->dao->salvar($inativo);

        $ativos = $this->dao->buscarPorStatus(true);

        $this->assertNotEmpty($ativos, 'Nenhum produto ativo encontrado.');
        foreach ($ativos as $p) {
            $this->assertTrue($p->getStatus(), 'Produto inativo retornado na busca por ativos.');
        }
    }

    // ── DQL: ativos ordenados por preço ──────────────────────────────────────

    public function testBuscarAtivosOrdenadosPorPreco(): void {
        $p1 = new Produto();
        $p1->setDescricao('Caro'); $p1->setPrecoUnitario(300.00);
        $p1->setQuantidade(1); $p1->setStatus(true);
        $this->dao->salvar($p1);

        $p2 = new Produto();
        $p2->setDescricao('Barato'); $p2->setPrecoUnitario(100.00);
        $p2->setQuantidade(1); $p2->setStatus(true);
        $this->dao->salvar($p2);

        $p3 = new Produto();
        $p3->setDescricao('Inativo'); $p3->setPrecoUnitario(50.00);
        $p3->setQuantidade(1); $p3->setStatus(false); // não deve aparecer
        $this->dao->salvar($p3);

        $resultado = $this->dao->buscarAtivosOrdenadosPorPreco();

        $this->assertNotEmpty($resultado, 'A DQL não retornou resultados.');
        // Verifica ordenação crescente
        for ($i = 0; $i < count($resultado) - 1; $i++) {
            $this->assertLessThanOrEqual(
                $resultado[$i + 1]->getPrecoUnitario(),
                $resultado[$i]->getPrecoUnitario(),
                'Os produtos não estão ordenados por preço crescente.'
            );
        }
    }

    // ── DQL: busca por descrição (LIKE) ───────────────────────────────────────

    public function testBuscarPorDescricao(): void {
        $produto = new Produto();
        $produto->setDescricao('Monitor Ultrawide 34"');
        $produto->setPrecoUnitario(1200.00);
        $produto->setQuantidade(5);
        $produto->setStatus(true);
        $this->dao->salvar($produto);

        $resultado = $this->dao->buscarPorDescricao('monitor');

        $this->assertNotEmpty($resultado, 'Nenhum produto encontrado pela busca de descrição.');
    }

    // ── SQL Nativo: atualizar estoque ─────────────────────────────────────────

    public function testAtualizarEstoqueNativo(): void {
        $produto = new Produto();
        $produto->setDescricao('Webcam HD');
        $produto->setPrecoUnitario(300.00);
        $produto->setQuantidade(5);
        $produto->setStatus(true);
        $produto = $this->dao->salvar($produto);

        $linhasAfetadas = $this->dao->atualizarEstoque($produto->getId(), 30);

        $this->assertEquals(1, $linhasAfetadas,
            'A query SQL nativa deveria ter atualizado exatamente 1 linha.');
    }

    // ── SQL Nativo: busca por faixa de preço ──────────────────────────────────

    public function testBuscarPorFaixaDePreco(): void {
        $barato = new Produto();
        $barato->setDescricao('Barato'); $barato->setPrecoUnitario(50.00);
        $barato->setQuantidade(10); $barato->setStatus(true);
        $this->dao->salvar($barato);

        $medio = new Produto();
        $medio->setDescricao('Médio'); $medio->setPrecoUnitario(300.00);
        $medio->setQuantidade(10); $medio->setStatus(true);
        $this->dao->salvar($medio);

        $caro = new Produto();
        $caro->setDescricao('Caro'); $caro->setPrecoUnitario(2000.00);
        $caro->setQuantidade(10); $caro->setStatus(true);
        $this->dao->salvar($caro);

        $resultado = $this->dao->buscarPorFaixaDePreco(100.00, 500.00);

        $this->assertNotEmpty($resultado, 'Nenhum produto encontrado na faixa de preço.');
        foreach ($resultado as $row) {
            $this->assertGreaterThanOrEqual(100.00, $row['preco_unitario'],
                'Produto abaixo do mínimo retornado.');
            $this->assertLessThanOrEqual(500.00, $row['preco_unitario'],
                'Produto acima do máximo retornado.');
        }
    }
}
