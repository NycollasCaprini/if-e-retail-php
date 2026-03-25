<?php

namespace dao;

use model\Carrinho;
use utils\Conexao;

/**
 * DAO para a entidade Carrinho.
 * Equivalente ao CarrinhoRepository do Spring Boot.
 */
class CarrinhoDAO {

    private $entityManager;

    public function __construct() {
        $this->entityManager = Conexao::getEntityManager();
    }

    public function salvar(Carrinho $carrinho): Carrinho {
        $this->entityManager->persist($carrinho);
        $this->entityManager->flush();
        return $carrinho;
    }

    public function deletar(Carrinho $carrinho): void {
        $this->entityManager->remove($carrinho);
        $this->entityManager->flush();
    }

    public function listarTodos(): array {
        return $this->entityManager->getRepository(Carrinho::class)->findAll();
    }

    public function buscarPorId(int $id): ?Carrinho {
        return $this->entityManager->find(Carrinho::class, $id);
    }

    // ── DQL ───────────────────────────────────────────────────────────────────

    /**
     * Carrega carrinho com seus produtos via JOIN FETCH (evita N+1).
     * Equivalente ao findByIdWithItens do Spring com JOIN FETCH.
     */
    public function buscarComProdutos(int $id): ?Carrinho {
        return $this->entityManager
            ->createQuery(
                'SELECT c, p FROM model\Carrinho c
                 LEFT JOIN FETCH c.listaProdutos p
                 WHERE c.id = :id'
            )
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    // ── SQL Nativo ────────────────────────────────────────────────────────────

    /**
     * Conta o total de produtos em um carrinho.
     * Equivalente ao countTotalItens do Spring (SQL nativo).
     */
    public function contarProdutos(int $carrinhoId): int {
        return (int) $this->entityManager
            ->getConnection()
            ->fetchOne(
                'SELECT COUNT(*) FROM tb_carrinho_produto WHERE carrinho_id = :id',
                ['id' => $carrinhoId]
            );
    }
}
