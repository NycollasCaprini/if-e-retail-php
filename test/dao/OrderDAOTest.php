<?php

namespace test\dao;

use dao\ClienteDAO;
use dao\OrderDAO;
use model\Cliente;
use model\Order;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

/**
 * Testes de integração para OrderDAO (Pedido).
 * Segue o padrão PedidoRepositoryTest do Spring.
 */
class OrderDAOTest extends TestCase {

    private OrderDAO $dao;
    private ClienteDAO $clienteDAO;

    protected function setUp(): void {
        $this->dao       = new OrderDAO();
        $this->clienteDAO = new ClienteDAO();
    }

    protected function tearDown(): void {
        $em = Conexao::getEntityManager();
        $em->createQuery('DELETE FROM model\Order o')->execute();
        $em->createQuery('DELETE FROM model\Cliente c')->execute();
        $em->clear();
    }

    private function criarCliente(string $nome, string $cpf): Cliente {
        $c = new Cliente();
        $c->setName($nome); $c->setCpf($cpf);
        $c->setSenha('hash'); $c->setTipo('CLIENTE');
        return $this->clienteDAO->salvar($c);
    }

    // ── Inserção simples ──────────────────────────────────────────────────────

    public function testInsert(): void {
        $cliente = $this->criarCliente('Joana Dark', '100.100.100-10');

        $pedido = new Order();
        $pedido->setCliente($cliente);
        $pedido->setDataPedido(new \DateTime());
        $pedido->setStatus(true);
        $pedido = $this->dao->salvar($pedido);

        $pedidoInserido = $this->dao->buscarPorId($pedido->getId());

        $this->assertNotNull($pedidoInserido, 'O pedido não foi inserido.');
        $this->assertNotNull($pedidoInserido->getCliente(),
            'O cliente do pedido está nulo.');
    }

    // ── Inserção com cliente relacionado ──────────────────────────────────────

    public function testInserirComCliente(): void {
        $cliente = $this->criarCliente('Marcos Vinicius', '200.200.200-20');

        $pedido = new Order();
        $pedido->setCliente($cliente);
        $pedido->setDataPedido(new \DateTime());
        $pedido->setDataEntrega((new \DateTime())->modify('+5 days'));
        $pedido->setStatus(true);
        $pedido = $this->dao->salvar($pedido);

        $pedidoInserido = $this->dao->buscarPorId($pedido->getId());

        $this->assertEquals($cliente->getId(), $pedidoInserido->getCliente()->getId(),
            'O cliente associado ao pedido não confere.');
        $this->assertNotNull($pedidoInserido->getDataEntrega(),
            'A data de entrega do pedido está nula.');
    }

    // ── Atualização ───────────────────────────────────────────────────────────

    public function testUpdate(): void {
        $cliente = $this->criarCliente('Silvia Torres', '300.300.300-30');

        $pedido = new Order();
        $pedido->setCliente($cliente);
        $pedido->setDataPedido(new \DateTime());
        $pedido->setStatus(true);
        $pedido = $this->dao->salvar($pedido);

        $pedido->setStatus(false); // cancela o pedido
        $this->dao->salvar($pedido);

        $pedidoAtualizado = $this->dao->buscarPorId($pedido->getId());

        $this->assertFalse($pedidoAtualizado->getStatus(),
            'O status do pedido não foi atualizado para cancelado.');
    }

    // ── Remoção ───────────────────────────────────────────────────────────────

    public function testDelete(): void {
        $cliente = $this->criarCliente('Ricardo Gomes', '400.400.400-40');

        $pedido = new Order();
        $pedido->setCliente($cliente);
        $pedido->setDataPedido(new \DateTime());
        $pedido->setStatus(true);
        $pedido = $this->dao->salvar($pedido);
        $id = $pedido->getId();

        $this->dao->deletar($pedido);

        $pedidoDeletado = $this->dao->buscarPorId($id);
        $this->assertNull($pedidoDeletado, 'O pedido ainda se encontra no banco de dados.');
    }

    // ── Listagem com performance ──────────────────────────────────────────────

    public function testListar(): void {
        $cliente = $this->criarCliente('Listar Teste', '500.500.500-50');

        for ($i = 0; $i < 3; $i++) {
            $p = new Order();
            $p->setCliente($cliente);
            $p->setDataPedido(new \DateTime());
            $p->setStatus(true);
            $this->dao->salvar($p);
        }

        $inicio = microtime(true);
        $pedidos = $this->dao->listarTodos();
        $fim = microtime(true);

        $this->assertNotEmpty($pedidos, 'A listagem não retornou resultados.');
        $this->assertLessThan(0.3, $fim - $inicio, 'A consulta demorou mais de 0,3 segundos.');
    }

    // ── Busca por status ──────────────────────────────────────────────────────

    public function testBuscarPorStatus(): void {
        $cliente = $this->criarCliente('Status Teste', '600.600.600-60');

        $ativo = new Order();
        $ativo->setCliente($cliente); $ativo->setDataPedido(new \DateTime()); $ativo->setStatus(true);
        $this->dao->salvar($ativo);

        $cancelado = new Order();
        $cancelado->setCliente($cliente); $cancelado->setDataPedido(new \DateTime()); $cancelado->setStatus(false);
        $this->dao->salvar($cancelado);

        $ativos    = $this->dao->buscarPorStatus(true);
        $cancelados = $this->dao->buscarPorStatus(false);

        $this->assertNotEmpty($ativos, 'Nenhum pedido ativo encontrado.');
        $this->assertNotEmpty($cancelados, 'Nenhum pedido cancelado encontrado.');
    }

    // ── DQL: pedidos pendentes ────────────────────────────────────────────────

    public function testBuscarPendentesPorCliente(): void {
        $cliente = $this->criarCliente('Pendente Teste', '700.700.700-70');

        $pendente = new Order();
        $pendente->setCliente($cliente);
        $pendente->setDataPedido(new \DateTime());
        $pendente->setStatus(true);
        // dataEntrega = null → pendente
        $this->dao->salvar($pendente);

        $entregue = new Order();
        $entregue->setCliente($cliente);
        $entregue->setDataPedido((new \DateTime())->modify('-3 days'));
        $entregue->setDataEntrega(new \DateTime());
        $entregue->setStatus(true);
        $this->dao->salvar($entregue);

        $pendentes = $this->dao->buscarPendentesPorCliente($cliente->getId());

        $this->assertNotEmpty($pendentes, 'Nenhum pedido pendente encontrado.');
        foreach ($pendentes as $p) {
            $this->assertNull($p->getDataEntrega(),
                'Pedido com data de entrega retornado como pendente.');
        }
    }

    // ── SQL Nativo: contar por status ─────────────────────────────────────────

    public function testContarPorStatus(): void {
        $cliente = $this->criarCliente('Nativo Count', '800.800.800-80');

        $p1 = new Order();
        $p1->setCliente($cliente); $p1->setDataPedido(new \DateTime()); $p1->setStatus(true);
        $this->dao->salvar($p1);

        $p2 = new Order();
        $p2->setCliente($cliente); $p2->setDataPedido(new \DateTime()); $p2->setStatus(false);
        $this->dao->salvar($p2);

        $resultado = $this->dao->contarPorStatus();

        $this->assertNotEmpty($resultado,
            'A query SQL nativa de contagem por status não retornou resultados.');
    }
}
