<?php

namespace test\dao;

use dao\AdminDAO;
use model\Admin;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

class AdminDAOTest extends TestCase
{
    protected function setUp(): void
    {
        $reflection = new \ReflectionClass(Conexao::class);
        $prop = $reflection->getProperty('entityManager');
        $prop->setAccessible(true);
        $prop->setValue(null, null);
    }

    private function criarAdmin(): Admin
    {
        // UserModel::__construct($name, $cpf, $idade, $senha, $tipo) recebe
        // $endereco e $contato no lugar de $idade e $senha devido ao mismatch
        // no parent::__construct de Admin — corrigimos com setters.
        $admin = new Admin(
            "Tom Jobim", "111.222.333-44", null, null, 45, "hash123",
            "M-001", "TI", "Gerente", "2020-01-10", new \DateTime(), "admin"
        );
        $admin->setIdade(45);
        $admin->setSenha("hash123");
        $admin->setTipo("admin");
        return $admin;
    }

    public function testSalvar()
    {
        $admin = $this->criarAdmin();
        $salvo = AdminDAO::salvar($admin);
        $this->assertNotNull($salvo->getID());
    }

    public function testListar()
    {
        AdminDAO::salvar($this->criarAdmin());
        $lista = AdminDAO::listar();
        $this->assertNotEmpty($lista);
    }

    public function testDeletar()
    {
        $admin = AdminDAO::salvar($this->criarAdmin());
        AdminDAO::deletar($admin);
        $lista = AdminDAO::listar();
        // Verifica que o admin deletado não está mais na lista
        foreach ($lista as $a) {
            $this->assertNotEquals($admin->getID(), $a->getID());
        }
    }

    public function testBuscarPorCpf()
    {
        $admin = $this->criarAdmin();
        AdminDAO::salvar($admin);
        $resultado = AdminDAO::buscarPorCpf("111.222.333-44");
        $this->assertNotEmpty($resultado);
    }

    public function testBuscarPorNome()
    {
        AdminDAO::salvar($this->criarAdmin());
        $resultado = AdminDAO::buscarPorNome("Tom");
        $this->assertNotEmpty($resultado);
    }

    public function testBuscarPorCargo()
    {
        AdminDAO::salvar($this->criarAdmin());
        $resultado = AdminDAO::buscarPorCargo("Gerente");
        $this->assertNotEmpty($resultado);
    }
}
