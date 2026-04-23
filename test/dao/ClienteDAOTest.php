<?php

namespace test\dao;

use dao\ClienteDAO;
use model\Cliente;
use model\Contato;
use model\Endereco;
use PHPUnit\Framework\TestCase;


class ClienteDAOTest extends TestCase
{


    private function criarCliente(): Cliente
    {
        return new Cliente(
            "Elis Regina", "222.333.444-55", new \DateTime("1992-06-17"), "hash456", "cliente"
        );
    }

    private function criarEndereco(): Endereco{
        return new Endereco(
            "Palestra Italia", "1996", "ap01", "Centro",
            "Sao Paulo", "Sao Paulo", "86543-000", "Brasil"
        );
    }

    public function testSalvar()
    {
        $cliente = $this->criarCliente();
        $salvo = ClienteDAO::salvar($cliente);
        $this->assertNotNull($salvo->getID());
    }

    public function testSalvarComContatos(){
        $cliente = $this->criarCliente();

        $contato1 = new Contato("999222-222", "luiz@gmail.com");
        $contato1->setUsuario($cliente);

        $contato2 = new Contato("666444-111", "francisco@gmail.com");
        $contato2->setUsuario($cliente);

        $contatos[] = $contato1;
        $contatos[] = $contato2;

        $cliente->setContato($contatos);

        $clienteInserido = ClienteDAO::salvar($cliente);

        $this->assertNotNull($clienteInserido);
    }

    public function testSalvarComEndreco(){
        $cliente = $this->criarCliente();
        $endereco = $this->criarEndereco();

        $cliente->setEndereco($endereco);

        $clienteInserido = ClienteDAO::salvar($cliente);

        $enderecoDoCliente = $cliente->getEndereco();
        echo $enderecoDoCliente->getRua();
        $this->assertNotNull($clienteInserido);
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

    // Teste de atualização: verifica que alterações em um cliente persistido são salvas corretamente
    public function testAtualizar()
    {
        $cliente = ClienteDAO::salvar($this->criarCliente());
        $cliente->setName("Clara Nunes");
        $cliente->setCpf("999.888.777-66");
        $atualizado = ClienteDAO::salvar($cliente);
        $this->assertEquals("Clara Nunes", $atualizado->getName());
        $this->assertEquals("999.888.777-66", $atualizado->getCpf());
    }
}
