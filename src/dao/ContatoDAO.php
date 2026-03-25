<?php

namespace dao;

use model\Contato;
use utils\Conexao;

/**
 * DAO para a entidade Contato.
 * Equivalente ao ContatoRepository do Spring Boot.
 */
class ContatoDAO {

    private $entityManager;

    public function __construct() {
        $this->entityManager = Conexao::getEntityManager();
    }

    public function salvar(Contato $contato): Contato {
        $this->entityManager->persist($contato);
        $this->entityManager->flush();
        return $contato;
    }

    public function deletar(Contato $contato): void {
        $this->entityManager->remove($contato);
        $this->entityManager->flush();
    }

    public function listarTodos(): array {
        return $this->entityManager->getRepository(Contato::class)->findAll();
    }

    public function buscarPorId(int $id): ?Contato {
        return $this->entityManager->find(Contato::class, $id);
    }

    public function buscarPorEmail(string $email): ?Contato {
        return $this->entityManager
            ->getRepository(Contato::class)
            ->findOneBy(['email' => $email]);
    }

    // ── DQL ───────────────────────────────────────────────────────────────────

    public function buscarContatosComWhatsapp(int $clienteId): array {
        return $this->entityManager
            ->createQuery(
                'SELECT c FROM model\Contato c
                 WHERE c.cliente = :clienteId
                 AND c.whatsapp IS NOT NULL'
            )
            ->setParameter('clienteId', $clienteId)
            ->getResult();
    }
}
