<?php

namespace test\dao;

use dao\EnderecoDAO;
use model\Endereco;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

/**
 * Testes de integração para EnderecoDAO.
 */
class EnderecoDAOTest extends TestCase {

    private EnderecoDAO $dao;

    protected function setUp(): void {
        $this->dao = new EnderecoDAO();
    }

    protected function tearDown(): void {
        $em = Conexao::getEntityManager();
        $em->createQuery('DELETE FROM model\Endereco e')->execute();
        $em->clear();
    }

    private function novoEndereco(string $rua, string $cidade, string $estado, string $cep): Endereco {
        $e = new Endereco();
        $e->setRua($rua); $e->setNumero('100');
        $e->setBairro('Centro'); $e->setCidade($cidade);
        $e->setEstado($estado); $e->setCep($cep);
        $e->setPais('Brasil');
        return $e;
    }

    public function testInsert(): void {
        $endereco = $this->novoEndereco('Rua das Acácias', 'Palmas', 'PR', '85555-000');
        $endereco = $this->dao->salvar($endereco);

        $enderecoInserido = $this->dao->buscarPorId($endereco->getId());

        $this->assertNotNull($enderecoInserido, 'O endereço não foi inserido.');
        $this->assertEquals('Palmas', $enderecoInserido->getCidade(), 'A cidade não confere.');
    }

    public function testUpdate(): void {
        $endereco = $this->novoEndereco('Rua Antiga', 'Curitiba', 'PR', '80000-000');
        $endereco = $this->dao->salvar($endereco);

        $endereco->setRua('Rua Nova');
        $this->dao->salvar($endereco);

        $enderecoAtualizado = $this->dao->buscarPorId($endereco->getId());

        $this->assertEquals('Rua Nova', $enderecoAtualizado->getRua(),
            'A rua do endereço não foi atualizada.');
    }

    public function testDelete(): void {
        $endereco = $this->novoEndereco('Rua Deletar', 'Maringá', 'PR', '87000-000');
        $endereco = $this->dao->salvar($endereco);
        $id = $endereco->getId();

        $this->dao->deletar($endereco);

        $this->assertNull($this->dao->buscarPorId($id),
            'O endereço ainda se encontra no banco de dados.');
    }

    public function testListar(): void {
        $this->dao->salvar($this->novoEndereco('Rua A', 'Palmas', 'PR', '85555-001'));
        $this->dao->salvar($this->novoEndereco('Rua B', 'Palmas', 'PR', '85555-002'));

        $inicio = microtime(true);
        $enderecos = $this->dao->listarTodos();
        $fim = microtime(true);

        $this->assertNotEmpty($enderecos, 'A listagem não retornou resultados.');
        $this->assertLessThan(0.3, $fim - $inicio, 'A consulta demorou mais de 0,3 segundos.');
    }

    public function testBuscarPorCidade(): void {
        $this->dao->salvar($this->novoEndereco('Rua PR1', 'Francisco Beltrão', 'PR', '85601-000'));
        $this->dao->salvar($this->novoEndereco('Rua PR2', 'Pato Branco', 'PR', '85501-000'));

        $resultado = $this->dao->buscarPorCidade('Francisco Beltrão');

        $this->assertNotEmpty($resultado, 'Nenhum endereço encontrado pela cidade.');
        foreach ($resultado as $e) {
            $this->assertEquals('Francisco Beltrão', $e->getCidade(),
                'Endereço de cidade diferente retornado.');
        }
    }

    public function testBuscarPorCep(): void {
        $this->dao->salvar($this->novoEndereco('Rua do CEP', 'Palmas', 'PR', '85555-999'));

        $resultado = $this->dao->buscarPorCep('85555-999');

        $this->assertNotEmpty($resultado, 'Nenhum endereço encontrado pelo CEP.');
    }

    // ── SQL Nativo: listar cidades distintas ──────────────────────────────────

    public function testListarTodasCidades(): void {
        $this->dao->salvar($this->novoEndereco('Rua X', 'Guarapuava', 'PR', '85010-000'));
        $this->dao->salvar($this->novoEndereco('Rua Y', 'Londrina', 'PR', '86010-000'));

        $cidades = $this->dao->listarTodasCidades();

        $this->assertNotEmpty($cidades,
            'A query SQL nativa de cidades não retornou resultados.');
        $this->assertContains('Guarapuava', $cidades,
            'Guarapuava deveria estar na lista de cidades.');
        $this->assertContains('Londrina', $cidades,
            'Londrina deveria estar na lista de cidades.');
    }
}
