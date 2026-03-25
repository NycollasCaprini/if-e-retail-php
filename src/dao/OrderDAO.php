<?php

namespace dao;

use model\Order;
use utils\Conexao;

/**
 * DAO para a entidade Order (Pedido).
 * Equivalente ao PedidoRepository do Spring Boot.
 */
class OrderDAO {

    private $entityManager;

    public function __construct() {
        $this->entityManager = Conexao::getEntityManager();
    }

    public function salvar(Order $order): Order {
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        return $order;
    }

    public function deletar(Order $order): void {
        $this->entityManager->remove($order);
        $this->entityManager->flush();
    }

    public function listarTodos(): array {
        return $this->entityManager->getRepository(Order::class)->findAll();
    }

    public function buscarPorId(int $id): ?Order {
        return $this->entityManager->find(Order::class, $id);
    }

    public function buscarPorClienteId(int $clienteId): array {
        return $this->entityManager
            ->getRepository(Order::class)
            ->findBy(['cliente' => $clienteId]);
    }

    public function buscarPorStatus(bool $status): array {
        return $this->entityManager
            ->getRepository(Order::class)
            ->findBy(['status' => $status]);
    }

    // ── DQL ───────────────────────────────────────────────────────────────────

    /**
     * Busca pedidos sem data de entrega (pendentes) de um cliente.
     * Equivalente ao findPedidosPendentesPorCliente do Spring.
     */
    public function buscarPendentesPorCliente(int $clienteId): array {
        return $this->entityManager
            ->createQuery(
                'SELECT o FROM model\Order o
                 WHERE o.cliente = :clienteId
                 AND o.dataEntrega IS NULL'
            )
            ->setParameter('clienteId', $clienteId)
            ->getResult();
    }

    // ── SQL Nativo ────────────────────────────────────────────────────────────

    /**
     * Conta pedidos agrupados por status.
     * Equivalente ao countPedidosPorStatus do Spring.
     */
    public function contarPorStatus(): array {
        return $this->entityManager
            ->getConnection()
            ->fetchAllAssociative(
                'SELECT status, COUNT(*) as total FROM tb_order GROUP BY status'
            );
    }
}
