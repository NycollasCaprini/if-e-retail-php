<?php

namespace dao;

use model\Produto;
use utils\Conexao;

/**
 * DAO (Data Access Object) para a entidade Produto.
 *
 * No Spring usávamos JpaRepository<Produto, Long> que já fornecia
 * save(), findById(), findAll(), delete() automaticamente.
 * Aqui implementamos manualmente os mesmos métodos usando o
 * EntityManager do Doctrine — equivalente direto ao JpaRepository.
 *
 * Padrão de uso do EntityManager (Doctrine) vs Spring Data:
 *   Spring: produtoRepository.save(produto)
 *   Doctrine: $em->persist($produto); $em->flush();
 *
 *   Spring: produtoRepository.findById(id)
 *   Doctrine: $em->find(Produto::class, $id)
 *
 *   Spring: produtoRepository.findAll()
 *   Doctrine: $em->getRepository(Produto::class)->findAll()
 */
class ProdutoDAO {

    private $entityManager;

    public function __construct() {
        $this->entityManager = Conexao::getEntityManager();
    }

    // ── Inserção / Atualização ────────────────────────────────────────────────

    /**
     * Salva ou atualiza um Produto no banco.
     * persist() → marca a entidade como gerenciada (INSERT se nova).
     * flush()   → executa o SQL no banco (commit da operação).
     * Equivalente ao produtoRepository.save(produto) do Spring.
     */
    public function salvar(Produto $produto): Produto {
        $this->entityManager->persist($produto);
        $this->entityManager->flush();
        return $produto;
    }

    // ── Remoção ───────────────────────────────────────────────────────────────

    /**
     * Remove um Produto do banco pelo objeto.
     * Equivalente ao produtoRepository.delete(produto) do Spring.
     */
    public function deletar(Produto $produto): void {
        $this->entityManager->remove($produto);
        $this->entityManager->flush();
    }

    // ── Listagem ──────────────────────────────────────────────────────────────

    /**
     * Retorna todos os produtos.
     * Equivalente ao produtoRepository.findAll() do Spring.
     */
    public function listarTodos(): array {
        return $this->entityManager
            ->getRepository(Produto::class)
            ->findAll();
    }

    // ── Buscas simples ────────────────────────────────────────────────────────

    /**
     * Busca produto por ID.
     * Equivalente ao produtoRepository.findById(id).get() do Spring.
     */
    public function buscarPorId(int $id): ?Produto {
        return $this->entityManager->find(Produto::class, $id);
    }

    /**
     * Busca produtos pelo status (ativo/inativo).
     * Equivalente ao produtoRepository.findByStatus(status) do Spring.
     */
    public function buscarPorStatus(bool $status): array {
        return $this->entityManager
            ->getRepository(Produto::class)
            ->findBy(['status' => $status]);
    }

    // ── DQL (Doctrine Query Language) ────────────────────────────────────────
    // DQL é equivalente ao JPQL do Spring — orientado a objetos,
    // usa nomes de classes PHP (não nomes de tabelas SQL).

    /**
     * Retorna produtos ativos ordenados por preço crescente.
     * Equivalente à @Query JPQL do Spring:
     *   "SELECT p FROM Produto p WHERE p.status = true ORDER BY p.precoUnitario ASC"
     */
    public function buscarAtivosOrdenadosPorPreco(): array {
        return $this->entityManager
            ->createQuery('SELECT p FROM model\Produto p WHERE p.status = true ORDER BY p.precoUnitario ASC')
            ->getResult();
    }

    /**
     * Busca produtos com estoque zerado.
     * Equivalente: "SELECT p FROM Produto p WHERE p.quantidadeEmEstoque = 0"
     */
    public function buscarSemEstoque(): array {
        return $this->entityManager
            ->createQuery('SELECT p FROM model\Produto p WHERE p.quantidade = 0')
            ->getResult();
    }

    /**
     * Busca produtos pela descrição (LIKE, case-insensitive).
     * Usa LOWER() no DQL — equivalente ao findByDescricaoContainingIgnoreCase do Spring.
     */
    public function buscarPorDescricao(string $descricao): array {
        return $this->entityManager
            ->createQuery('SELECT p FROM model\Produto p WHERE LOWER(p.descricao) LIKE LOWER(:desc)')
            ->setParameter('desc', '%' . $descricao . '%')
            ->getResult();
    }

    // ── SQL Nativo ────────────────────────────────────────────────────────────
    // Equivalente às @Query(nativeQuery = true) do Spring.

    /**
     * Atualiza o estoque de um produto diretamente via SQL nativo.
     * Equivalente ao @Modifying @Query nativeQuery do Spring.
     */
    public function atualizarEstoque(int $id, int $novaQuantidade): int {
        return $this->entityManager
            ->getConnection()
            ->executeStatement(
                'UPDATE tb_produto SET quantidade = :qtd WHERE id = :id',
                ['qtd' => $novaQuantidade, 'id' => $id]
            );
    }

    /**
     * Busca produtos dentro de uma faixa de preço via SQL nativo.
     * Equivalente ao findByPrecoUnitarioBetween do Spring.
     */
    public function buscarPorFaixaDePreco(float $min, float $max): array {
        $conn = $this->entityManager->getConnection();
        $rows = $conn->fetchAllAssociative(
            'SELECT * FROM tb_produto WHERE preco_unitario BETWEEN :min AND :max',
            ['min' => $min, 'max' => $max]
        );
        return $rows;
    }
}
