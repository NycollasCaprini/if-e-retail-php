<?php

namespace test\dao;

use dao\ClienteDAO;
use dao\ContatoDAO;
use model\Cliente;
use model\Contato;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

/**
 * Testes de integração para ContatoDAO.
 */
class ContatoDAOTest extends TestCase {

    private ContatoDAO $dao;
    private ClienteDAO $clienteDAO;

    protected function setUp(): void {
        $this->dao       = new ContatoDAO();
        $this->clienteDAO = new ClienteDAO();
    }

    protected function tearDown(): void {
        $em = Conexao::getEntityManager();
        $em->createQuery('DELETE FROM model\Contato c')->execute();
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
        $cliente = $this->criarCliente('Contato Teste', '010.020.030-40');

        $contato = new Contato();
        $contato->setCliente($cliente);
        $contato->setTelefone('(46) 3025-1234');
        $contato->setEmail('contato@teste.com');
        $contato->setWhatsapp('(46) 99999-1234');
        $contato = $this->dao->salvar($contato);

        $contatoInserido = $this->dao->buscarPorId($contato->getId());

        $this->assertNotNull($contatoInserido, 'O contato não foi inserido.');
        $this->assertEquals('contato@teste.com', $contatoInserido->getEmail(),
            'O e-mail do contato não confere.');
    }

    // ── Inserção com cliente relacionado ──────────────────────────────────────

    public function testInserirComCliente(): void {
        $cliente = $this->criarCliente('Relação Contato', '050.060.070-80');

        $contato = new Contato();
        $contato->setCliente($cliente);
        $contato->setTelefone('(46) 3333-4444');
        $contato->setEmail('relacao@teste.com');
        $contato = $this->dao->salvar($contato);

        $contatoInserido = $this->dao->buscarPorId($contato->getId());

        $this->assertNotNull($contatoInserido->getCliente(),
            'O cliente associado ao contato está nulo.');
        $this->assertEquals($cliente->getId(), $contatoInserido->getCliente()->getId(),
            'O cliente associado ao contato não confere.');
    }

    // ── Atualização ───────────────────────────────────────────────────────────

    public function testUpdate(): void {
        $cliente = $this->criarCliente('Update Contato', '090.080.070-60');
        $contato = new Contato();
        $contato->setCliente($cliente);
        $contato->setTelefone('(46) 1111-2222');
        $contato->setEmail('antes@email.com');
        $contato = $this->dao->salvar($contato);

        $contato->setEmail('depois@email.com');
        $this->dao->salvar($contato);

        $contatoAtualizado = $this->dao->buscarPorId($contato->getId());

        $this->assertEquals('depois@email.com', $contatoAtualizado->getEmail(),
            'O e-mail do contato não foi atualizado.');
    }

    // ── Remoção ───────────────────────────────────────────────────────────────

    public function testDelete(): void {
        $cliente = $this->criarCliente('Delete Contato', '110.110.110-11');
        $contato = new Contato();
        $contato->setCliente($cliente);
        $contato->setTelefone('(46) 9999-8888');
        $contato->setEmail('deletar@email.com');
        $contato = $this->dao->salvar($contato);
        $id = $contato->getId();

        $this->dao->deletar($contato);

        $this->assertNull($this->dao->buscarPorId($id),
            'O contato ainda se encontra no banco de dados.');
    }

    // ── Listagem com performance ──────────────────────────────────────────────

    public function testListar(): void {
        $cliente = $this->criarCliente('Listar Contato', '220.220.220-22');
        for ($i = 1; $i <= 3; $i++) {
            $c = new Contato();
            $c->setCliente($cliente);
            $c->setTelefone('(46) 9999-000' . $i);
            $c->setEmail('email' . $i . '@teste.com');
            $this->dao->salvar($c);
        }

        $inicio = microtime(true);
        $contatos = $this->dao->listarTodos();
        $fim = microtime(true);

        $this->assertNotEmpty($contatos, 'A listagem não retornou resultados.');
        $this->assertLessThan(0.3, $fim - $inicio, 'A consulta demorou mais de 0,3 segundos.');
    }

    // ── Busca por e-mail ──────────────────────────────────────────────────────

    public function testBuscarPorEmail(): void {
        $cliente = $this->criarCliente('Email Find', '440.440.440-44');
        $contato = new Contato();
        $contato->setCliente($cliente);
        $contato->setTelefone('(46) 5555-4444');
        $contato->setEmail('unico@email.com');
        $this->dao->salvar($contato);

        $resultado = $this->dao->buscarPorEmail('unico@email.com');

        $this->assertNotNull($resultado, 'Contato não encontrado pelo e-mail.');
    }

    // ── DQL: contatos com WhatsApp ────────────────────────────────────────────

    public function testBuscarContatosComWhatsapp(): void {
        $cliente = $this->criarCliente('WhatsApp Teste', '660.660.660-66');

        $comWhats = new Contato();
        $comWhats->setCliente($cliente);
        $comWhats->setTelefone('(46) 9900-0011');
        $comWhats->setEmail('whats@email.com');
        $comWhats->setWhatsapp('(46) 99900-0011');
        $this->dao->salvar($comWhats);

        $semWhats = new Contato();
        $semWhats->setCliente($cliente);
        $semWhats->setTelefone('(46) 3300-0011');
        $semWhats->setEmail('semwhats@email.com');
        $this->dao->salvar($semWhats);

        $resultado = $this->dao->buscarContatosComWhatsapp($cliente->getId());

        $this->assertNotEmpty($resultado, 'Nenhum contato com WhatsApp encontrado.');
        foreach ($resultado as $c) {
            $this->assertNotNull($c->getWhatsapp(),
                'Contato sem WhatsApp retornado na busca específica.');
        }
    }
}
