<?php

namespace test\dao;

use dao\AdminDAO;
use model\Admin;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

/**
 * Testes de integração para AdminDAO.
 * Segue o mesmo padrão do ClienteDAOTest e do exemplo do professor.
 */
class AdminDAOTest extends TestCase {

    private AdminDAO $dao;

    protected function setUp(): void {
        $this->dao = new AdminDAO();
    }

    protected function tearDown(): void {
        $em = Conexao::getEntityManager();
        $em->createQuery('DELETE FROM model\Contato c WHERE c.admin IS NOT NULL')->execute();
        $em->createQuery('DELETE FROM model\Admin a')->execute();
        $em->clear();
    }

    // ── Inserção simples ──────────────────────────────────────────────────────

    public function testInsert(): void {
        $admin = new Admin();
        $admin->setName('Carlos Alberto');
        $admin->setCpf('111.222.333-44');
        $admin->setSenha('hash_senha');
        $admin->setTipo('ADMIN');
        $admin->setMatricula('MAT001');
        $admin->setSetor('TI');
        $admin->setCargo('Desenvolvedor');
        $admin->setStatus(true);
        $admin = $this->dao->salvar($admin);

        $adminInserido = $this->dao->buscarPorId($admin->getId());

        $this->assertNotNull($adminInserido, 'O admin não foi inserido.');
        $this->assertEquals('MAT001', $adminInserido->getMatricula(),
            'A matrícula do admin não confere.');
    }

    // ── Atualização ───────────────────────────────────────────────────────────

    public function testUpdate(): void {
        $admin = new Admin();
        $admin->setName('Fernanda Lima');
        $admin->setCpf('222.333.444-55');
        $admin->setSenha('hash');
        $admin->setTipo('ADMIN');
        $admin->setMatricula('MAT002');
        $admin->setSetor('RH');
        $admin->setCargo('Recrutadora');
        $admin->setStatus(true);
        $admin = $this->dao->salvar($admin);

        $admin->setCargo('Gerente de RH');
        $this->dao->salvar($admin);

        $adminAtualizado = $this->dao->buscarPorId($admin->getId());

        $this->assertEquals('Gerente de RH', $adminAtualizado->getCargo(),
            'O cargo do admin não foi atualizado.');
    }

    // ── Remoção ───────────────────────────────────────────────────────────────

    public function testDelete(): void {
        $admin = new Admin();
        $admin->setName('Rodrigo Melo');
        $admin->setCpf('333.444.555-66');
        $admin->setSenha('hash');
        $admin->setTipo('ADMIN');
        $admin->setMatricula('MAT003');
        $admin->setSetor('Financeiro');
        $admin->setCargo('Contador');
        $admin->setStatus(true);
        $admin = $this->dao->salvar($admin);
        $id = $admin->getId();

        $this->dao->deletar($admin);

        $adminDeletado = $this->dao->buscarPorId($id);
        $this->assertNull($adminDeletado, 'O admin ainda se encontra no banco de dados.');
    }

    // ── Listagem com teste de performance ─────────────────────────────────────

    public function testListar(): void {
        for ($i = 1; $i <= 3; $i++) {
            $a = new Admin();
            $a->setName('Admin ' . $i);
            $a->setCpf('00' . $i . '.000.000-0' . $i);
            $a->setSenha('hash');
            $a->setTipo('ADMIN');
            $a->setMatricula('MAT10' . $i);
            $a->setSetor('TI');
            $a->setCargo('Dev');
            $a->setStatus(true);
            $this->dao->salvar($a);
        }

        $inicio = microtime(true);
        $admins = $this->dao->listarTodos();
        $fim = microtime(true);

        $this->assertNotEmpty($admins, 'A listagem não retornou resultados.');
        $this->assertLessThan(0.3, $fim - $inicio, 'A consulta demorou mais de 0,3 segundos.');
    }

    // ── Busca por matrícula ───────────────────────────────────────────────────

    public function testBuscarPorMatricula(): void {
        $admin = new Admin();
        $admin->setName('Paulo Salave\'a');
        $admin->setCpf('444.555.666-77');
        $admin->setSenha('hash');
        $admin->setTipo('ADMIN');
        $admin->setMatricula('MAT999');
        $admin->setSetor('Vendas');
        $admin->setCargo('Supervisor');
        $admin->setStatus(true);
        $this->dao->salvar($admin);

        $encontrado = $this->dao->buscarPorMatricula('MAT999');

        $this->assertNotNull($encontrado, 'Admin não encontrado pela matrícula.');
        $this->assertEquals('Vendas', $encontrado->getSetor(),
            'O setor do admin não confere.');
    }

    // ── Busca por setor ───────────────────────────────────────────────────────

    public function testBuscarPorSetor(): void {
        for ($i = 1; $i <= 3; $i++) {
            $a = new Admin();
            $a->setName('TI Dev ' . $i);
            $a->setCpf('55' . $i . '.000.000-0' . $i);
            $a->setSenha('hash');
            $a->setTipo('ADMIN');
            $a->setMatricula('TI00' . $i);
            $a->setSetor('TI');
            $a->setCargo('Dev');
            $a->setStatus(true);
            $this->dao->salvar($a);
        }

        $resultado = $this->dao->buscarPorSetor('TI');

        $this->assertNotEmpty($resultado, 'Nenhum admin encontrado no setor TI.');
    }

    // ── DQL: ativos por setor ─────────────────────────────────────────────────

    public function testBuscarAtivosPorSetor(): void {
        $ativo = new Admin();
        $ativo->setName('Ativo TI'); $ativo->setCpf('600.000.000-01');
        $ativo->setSenha('hash'); $ativo->setTipo('ADMIN');
        $ativo->setMatricula('AT001'); $ativo->setSetor('TI');
        $ativo->setCargo('Dev'); $ativo->setStatus(true);
        $this->dao->salvar($ativo);

        $inativo = new Admin();
        $inativo->setName('Inativo TI'); $inativo->setCpf('600.000.000-02');
        $inativo->setSenha('hash'); $inativo->setTipo('ADMIN');
        $inativo->setMatricula('AT002'); $inativo->setSetor('TI');
        $inativo->setCargo('Dev'); $inativo->setStatus(false);
        $this->dao->salvar($inativo);

        $resultado = $this->dao->buscarAtivosPorSetor('TI');

        $this->assertNotEmpty($resultado, 'Nenhum admin ativo encontrado no setor TI.');
        foreach ($resultado as $a) {
            $this->assertTrue($a->getStatus(),
                'Admin inativo retornado na busca por ativos.');
        }
    }

    // ── SQL Nativo: matrículas de ativos ──────────────────────────────────────

    public function testListarMatriculasAtivos(): void {
        $admin = new Admin();
        $admin->setName('Nativo Teste'); $admin->setCpf('700.000.000-01');
        $admin->setSenha('hash'); $admin->setTipo('ADMIN');
        $admin->setMatricula('NAT001'); $admin->setSetor('Ops');
        $admin->setCargo('Analista'); $admin->setStatus(true);
        $this->dao->salvar($admin);

        $matriculas = $this->dao->listarMatriculasAtivos();

        $this->assertNotEmpty($matriculas,
            'A query SQL nativa não retornou matrículas de admins ativos.');
        $this->assertContains('NAT001', $matriculas,
            'A matrícula NAT001 deveria estar na lista.');
    }
}
