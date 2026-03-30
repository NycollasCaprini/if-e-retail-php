<?php

namespace test\dao;

use dao\ClienteDAO;
use model\Cliente;
use PHPUnit\Framework\TestCase;


class ClienteDAOTest extends TestCase
{


    private function criarCliente(): Cliente
    {
        return new Cliente(
            "Elis Regina", "222.333.444-55", new \DateTime("1992-06-17"), "hash456", "cliente"
        );
    }

    public function testSalvar()
    {
        $cliente = $this->criarCliente();
        $salvo = ClienteDAO::salvar($cliente);
        $this->assertNotNull($salvo->getID());
    }

    public function testListar()
    {
        ClienteDAO::salvar($this->criarCliente());
        $lista = ClienteDAO::listar();
        $this->assertNotEmpty($lista);
    }

    public function testDeletar()
    {
        $cliente = ClienteDAO::salvar($this->criarCliente());
        ClienteDAO::deletar($cliente);
        $lista = ClienteDAO::listar();
        foreach ($lista as $c) {
            $this->assertNotEquals($cliente->getID(), $c->getID());
        }
    }

    public function testBuscarPorCpf()
    {
        ClienteDAO::salvar($this->criarCliente());
        $resultado = ClienteDAO::buscarPorCpf("222.333.444-55");
        $this->assertNotEmpty($resultado);
    }

    public function testBuscarPorNome()
    {
        ClienteDAO::salvar($this->criarCliente());
        $resultado = ClienteDAO::buscarPorNome("Elis");
        $this->assertNotEmpty($resultado);
    }

    public function testBuscarPorNomeDQL()
    {
        ClienteDAO::salvar($this->criarCliente());
        $resultado = ClienteDAO::buscarPorNomeDQL("Elis");
        $this->assertNotEmpty($resultado);
    }
}
