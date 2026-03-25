<?php

namespace dao;

use model\Cliente;
use utils\Conexao;

/**
 * DAO para a entidade Cliente.
 * Equivalente ao ClienteRepository do Spring Boot.
 */
class ClienteDAO {

    private $entityManager;

    public function __construct() {
        $this->entityManager = Conexao::getEntityManager();
    }

    public function salvar(Cliente $cliente): Cliente {
        $this->entityManager->persist($cliente);
        $this->entityManager->flush();
        return $cliente;
    }

    public function deletar(Cliente $cliente): void {
        $this->entityManager->remove($cliente);
        $this->entityManager->flush();
    }

    public function listarTodos(): array {
        return $this->entityManager->getRepository(Cliente::class)->findAll();
    }

    public function buscarPorId(int $id): ?Cliente {
        return $this->entityManager->find(Cliente::class, $id);
    }

    public function buscarPorCpf(string $cpf): ?Cliente {
        return $this->entityManager
            ->getRepository(Cliente::class)
            ->findOneBy(['cpf' => $cpf]);
    }

    public function buscarPorNome(string $nome): array {
        return $this->entityManager
            ->getRepository(Cliente::class)
            ->findBy(['name' => $nome]);
    }

    // ── DQL ───────────────────────────────────────────────────────────────────

    /**
     * Busca cliente pelo CPF via DQL.
     * Equivalente ao @Query JPQL findByCpf do Spring.
     */
    public function buscarPorCpfDQL(string $cpf): ?Cliente {
        return $this->entityManager
            ->createQuery('SELECT c FROM model\Cliente c WHERE c.cpf = :cpf')
            ->setParameter('cpf', $cpf)
            ->getOneOrNullResult();
    }

    /**
     * Busca clientes cujo nome contenha a string informada.
     * Equivalente ao getAllByNomeLike do exemplo do professor.
     */
    public function buscarPorNomeLike(string $nome): array {
        return $this->entityManager
            ->createQuery('SELECT c FROM model\Cliente c WHERE c.name LIKE :nome')
            ->setParameter('nome', '%' . $nome . '%')
            ->getResult();
    }

    // ── SQL Nativo ────────────────────────────────────────────────────────────

    /**
     * Busca clientes com limite de resultados via SQL nativo.
     * Equivalente ao getAllByNomeLikeLimit do exemplo do professor.
     */
    public function buscarPorNomeLikeComLimite(string $nome, int $limite): array {
        return $this->entityManager
            ->getConnection()
            ->fetchAllAssociative(
                'SELECT * FROM tb_cliente WHERE name LIKE :nome LIMIT :limite',
                ['nome' => '%' . $nome . '%', 'limite' => $limite]
            );
    }

    /**
     * Conta pedidos por cliente via SQL nativo.
     * Equivalente ao countPedidosPorCliente do Spring.
     */
    public function contarPedidosPorCliente(): array {
        return $this->entityManager
            ->getConnection()
            ->fetchAllAssociative(
                'SELECT c.id, COUNT(o.id) AS total_pedidos
                 FROM tb_cliente c
                 LEFT JOIN tb_order o ON o.cliente_id = c.id
                 GROUP BY c.id
                 ORDER BY total_pedidos DESC'
            );
    }
}
