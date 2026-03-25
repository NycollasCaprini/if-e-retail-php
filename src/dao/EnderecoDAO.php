<?php

namespace dao;

use model\Endereco;
use utils\Conexao;

/**
 * DAO para a entidade Endereco.
 * Equivalente ao EnderecoRepository do Spring Boot.
 */
class EnderecoDAO {

    private $entityManager;

    public function __construct() {
        $this->entityManager = Conexao::getEntityManager();
    }

    public function salvar(Endereco $endereco): Endereco {
        $this->entityManager->persist($endereco);
        $this->entityManager->flush();
        return $endereco;
    }

    public function deletar(Endereco $endereco): void {
        $this->entityManager->remove($endereco);
        $this->entityManager->flush();
    }

    public function listarTodos(): array {
        return $this->entityManager->getRepository(Endereco::class)->findAll();
    }

    public function buscarPorId(int $id): ?Endereco {
        return $this->entityManager->find(Endereco::class, $id);
    }

    public function buscarPorCidade(string $cidade): array {
        return $this->entityManager
            ->getRepository(Endereco::class)
            ->findBy(['cidade' => $cidade]);
    }

    public function buscarPorCep(string $cep): array {
        return $this->entityManager
            ->getRepository(Endereco::class)
            ->findBy(['cep' => $cep]);
    }

    // ── SQL Nativo ────────────────────────────────────────────────────────────

    public function listarTodasCidades(): array {
        return $this->entityManager
            ->getConnection()
            ->fetchFirstColumn(
                'SELECT DISTINCT cidade FROM tb_endereco ORDER BY cidade'
            );
    }
}
