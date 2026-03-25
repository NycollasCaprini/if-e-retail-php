<?php

namespace test\dao;

use dao\ClienteDAO;
use model\Carrinho;
use model\Cliente;
use model\Contato;
use model\Endereco;
use model\Order;
use model\Produto;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

/**
 * Testes de integração para ClienteDAO.
 *
 * Segue o padrão do ClienteRepositoryTest do exemplo Spring do professor:
 *   testInsert, testUpdate, testDelete, testListar,
 *   testInserirComContatos, testInserirComEndereco,
 *   testBuscarPorNomeLike, testBuscarPorNomeLikeComLimite (SQL nativo).
 */
class ClienteDAOTest extends TestCase {

    private ClienteDAO $dao;

    protected function setUp(): void {
        $this->dao = new ClienteDAO();
    }

    protected function tearDown(): void {
        $em = Conexao::getEntityManager();
        // Remove na ordem correta para respeitar as FKs
        $em->createQuery('DELETE FROM model\Order o')->execute();
        $em->createQuery('DELETE FROM model\Contato c')->execute();
        $em->createQuery('DELETE FROM model\Carrinho c')->execute();
        $em->createQuery('DELETE FROM model\Cliente c')->execute();
        $em->createQuery('DELETE FROM model\Endereco e')->execute();
        $em->createQuery('DELETE FROM model\Produto p')->execute();
        $em->clear();
    }

    // ── Inserção simples ──────────────────────────────────────────────────────

    public function testInsert(): void {
        $cliente = new Cliente();
        $cliente->setName('João da Silva');
        $cliente->setCpf('123.456.789-00');
        $cliente->setSenha('hash_senha');
        $cliente->setTipo('CLIENTE');
        $cliente = $this->dao->salvar($cliente);

        $clienteInserido = $this->dao->buscarPorId($cliente->getId());

        $this->assertNotNull($clienteInserido, 'O cliente não foi inserido.');
        $this->assertEquals('João da Silva', $clienteInserido->getName(),
            'O nome do cliente não confere.');
    }

    // ── Atualização ───────────────────────────────────────────────────────────

    public function testUpdate(): void {
        $cliente = new Cliente();
        $cliente->setName('Maria Antunes');
        $cliente->setCpf('987.654.321-00');
        $cliente->setSenha('hash');
        $cliente->setTipo('CLIENTE');
        $cliente = $this->dao->salvar($cliente);

        $cliente->setName('Maria Antunes Silva');
        $this->dao->salvar($cliente);

        $clienteAtualizado = $this->dao->buscarPorId($cliente->getId());

        $this->assertEquals('Maria Antunes Silva', $clienteAtualizado->getName(),
            'O nome do cliente não foi atualizado.');
    }

    // ── Remoção ───────────────────────────────────────────────────────────────

    public function testDelete(): void {
        $cliente = new Cliente();
        $cliente->setName('Pedro Oliveira');
        $cliente->setCpf('111.111.111-11');
        $cliente->setSenha('hash');
        $cliente->setTipo('CLIENTE');
        $cliente = $this->dao->salvar($cliente);
        $id = $cliente->getId();

        $this->dao->deletar($cliente);

        $clienteDeletado = $this->dao->buscarPorId($id);
        $this->assertNull($clienteDeletado, 'O cliente ainda se encontra no banco de dados.');
    }

    // ── Listagem com teste de performance ─────────────────────────────────────

    public function testListar(): void {
        $cliente = new Cliente();
        $cliente->setName('Carlos Souza');
        $cliente->setCpf('222.222.222-22');
        $cliente->setSenha('hash');
        $cliente->setTipo('CLIENTE');
        $this->dao->salvar($cliente);

        $inicio = microtime(true);
        $clientes = $this->dao->listarTodos();
        $fim = microtime(true);

        $this->assertNotEmpty($clientes, 'A listagem não retornou resultados.');
        $this->assertLessThan(0.3, $fim - $inicio, 'A consulta demorou mais de 0,3 segundos.');
    }

    // ── Busca por CPF ─────────────────────────────────────────────────────────

    public function testBuscarPorCpf(): void {
        $cliente = new Cliente();
        $cliente->setName('Ana Pereira');
        $cliente->setCpf('333.333.333-33');
        $cliente->setSenha('hash');
        $cliente->setTipo('CLIENTE');
        $this->dao->salvar($cliente);

        $encontrado = $this->dao->buscarPorCpf('333.333.333-33');

        $this->assertNotNull($encontrado, 'Cliente não encontrado pelo CPF.');
        $this->assertEquals('Ana Pereira', $encontrado->getName(),
            'O nome do cliente buscado por CPF não confere.');
    }

    // ── Inserção com relacionamentos ──────────────────────────────────────────

    /**
     * Equivalente ao testInserirComContatos do Spring.
     * cascade: ['all'] no OneToMany garante que os contatos
     * sejam persistidos junto com o cliente.
     */
    public function testInserirComContatos(): void {
        $cliente = new Cliente();
        $cliente->setName('Leticia Laumann');
        $cliente->setCpf('555.666.777-88');
        $cliente->setSenha('hash');
        $cliente->setTipo('CLIENTE');

        $contato1 = new Contato();
        $contato1->setEmail('leticia@gmail.com');
        $contato1->setTelefone('+55(46)99999-9999');
        $contato1->setWhatsapp('+55(46)99999-9999');
        $contato1->setCliente($cliente);

        $contato2 = new Contato();
        $contato2->setEmail('leticia2@hotmail.com');
        $contato2->setTelefone('+55(46)88888-8888');
        $contato2->setCliente($cliente);

        $cliente->getContatos()->add($contato1);
        $cliente->getContatos()->add($contato2);

        $cliente = $this->dao->salvar($cliente);

        $clienteInserido = $this->dao->buscarPorId($cliente->getId());

        $this->assertNotNull($clienteInserido->getContatos(),
            'A coleção de contatos está nula.');
        $this->assertCount(2, $clienteInserido->getContatos(),
            'Os contatos do cliente não foram inseridos corretamente (cascade all).');
    }

    /**
     * Equivalente ao testInserirComEndereco do Spring.
     */
    public function testInserirComEndereco(): void {
        $cliente = new Cliente();
        $cliente->setName('Gabriela Fogaça');
        $cliente->setCpf('666.777.888-99');
        $cliente->setSenha('hash');
        $cliente->setTipo('CLIENTE');

        // Endereço independente (sem FK direta em Cliente — salvo separado)
        $endereco = new Endereco();
        $endereco->setRua('Rua das Flores');
        $endereco->setNumero('123');
        $endereco->setCep('85555-000');
        $endereco->setCidade('Palmas');
        $endereco->setEstado('PR');
        $endereco->setBairro('Centro');

        $em = Conexao::getEntityManager();
        $em->persist($endereco);
        $em->flush();

        $cliente = $this->dao->salvar($cliente);

        // Verifica cliente e endereço foram persistidos
        $this->assertNotNull($cliente->getId(), 'O cliente não foi inserido.');
        $this->assertNotNull($endereco->getId(), 'O endereço não foi inserido.');
        $this->assertEquals('Palmas', $endereco->getCidade(),
            'A cidade do endereço não confere.');
    }

    /**
     * Equivalente ao testInserirComFavoritos do Spring (ManyToMany).
     */
    public function testInserirComFavoritos(): void {
        $p1 = new \model\Produto();
        $p1->setDescricao('Notebook'); $p1->setPrecoUnitario(3000.00);
        $p1->setQuantidade(5); $p1->setStatus(true);

        $p2 = new \model\Produto();
        $p2->setDescricao('Fone'); $p2->setPrecoUnitario(200.00);
        $p2->setQuantidade(20); $p2->setStatus(true);

        $cliente = new Cliente();
        $cliente->setName('Bruno Favoritos');
        $cliente->setCpf('777.888.999-00');
        $cliente->setSenha('hash');
        $cliente->setTipo('CLIENTE');
        $cliente->getListaFavoritos()->add($p1);
        $cliente->getListaFavoritos()->add($p2);

        $cliente = $this->dao->salvar($cliente);
        $clienteInserido = $this->dao->buscarPorId($cliente->getId());

        $this->assertCount(2, $clienteInserido->getListaFavoritos(),
            'Os produtos favoritos não foram associados corretamente (ManyToMany).');
    }

    /**
     * Equivalente ao testInserirComPedido do Spring (OneToMany cascade).
     */
    public function testInserirComPedido(): void {
        $cliente = new Cliente();
        $cliente->setName('Roberta Pedido');
        $cliente->setCpf('888.999.000-11');
        $cliente->setSenha('hash');
        $cliente->setTipo('CLIENTE');
        $cliente = $this->dao->salvar($cliente);

        $pedido = new Order();
        $pedido->setCliente($cliente);
        $pedido->setDataPedido(new \DateTime());
        $pedido->setStatus(true);
        $cliente->getListaPedidos()->add($pedido);
        $this->dao->salvar($cliente);

        $clienteComPedido = $this->dao->buscarPorId($cliente->getId());

        $this->assertNotEmpty($clienteComPedido->getListaPedidos(),
            'O pedido do cliente não foi inserido corretamente (cascade all).');
    }

    // ── DQL: busca por nome LIKE ──────────────────────────────────────────────

    /**
     * Equivalente ao testGetAllByNomeLike do exemplo do professor.
     */
    public function testBuscarPorNomeLike(): void {
        $cliente = new Cliente();
        $cliente->setName('Eduardo Alvarenga');
        $cliente->setCpf('100.200.300-40');
        $cliente->setSenha('hash');
        $cliente->setTipo('CLIENTE');
        $this->dao->salvar($cliente);

        $resultado = $this->dao->buscarPorNomeLike('Edu');

        $this->assertNotEmpty($resultado,
            'Nenhum cliente encontrado com o nome especificado (DQL LIKE).');
    }

    // ── SQL Nativo: busca com limite ──────────────────────────────────────────

    /**
     * Equivalente ao testGetAllByNomeLikeLimit do exemplo do professor.
     */
    public function testBuscarPorNomeLikeComLimite(): void {
        for ($i = 0; $i < 20; $i++) {
            $c = new Cliente();
            $c->setName('Luiz ' . $i);
            $c->setCpf('111.222.333-' . str_pad($i, 2, '0', STR_PAD_LEFT));
            $c->setSenha('hash');
            $c->setTipo('CLIENTE');
            $this->dao->salvar($c);
        }

        $resultado = $this->dao->buscarPorNomeLikeComLimite('Luiz', 10);

        $this->assertNotEmpty($resultado,
            'Nenhum cliente encontrado com o nome especificado (SQL nativo LIMIT).');
        $this->assertCount(10, $resultado,
            'O número de clientes retornados não corresponde ao limite definido.');
    }

    // ── SQL Nativo: contar pedidos por cliente ────────────────────────────────

    public function testContarPedidosPorCliente(): void {
        $cliente = new Cliente();
        $cliente->setName('Luiz Contagem');
        $cliente->setCpf('500.600.700-80');
        $cliente->setSenha('hash');
        $cliente->setTipo('CLIENTE');
        $cliente = $this->dao->salvar($cliente);

        $p1 = new Order();
        $p1->setCliente($cliente); $p1->setDataPedido(new \DateTime()); $p1->setStatus(true);
        $p2 = new Order();
        $p2->setCliente($cliente); $p2->setDataPedido(new \DateTime()); $p2->setStatus(true);
        $cliente->getListaPedidos()->add($p1);
        $cliente->getListaPedidos()->add($p2);
        $this->dao->salvar($cliente);

        $resultado = $this->dao->contarPedidosPorCliente();

        $this->assertNotEmpty($resultado,
            'A query SQL nativa de contagem de pedidos não retornou resultados.');
    }
}
