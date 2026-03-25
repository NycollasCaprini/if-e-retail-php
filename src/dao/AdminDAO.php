<?php

namespace dao;

use model\Admin;
use utils\Conexao;

/**
 * DAO para a entidade Admin.
 * Equivalente ao AdminRepository do Spring Boot.
 */
class AdminDAO {

    private $entityManager;

    public function __construct() {
        $this->entityManager = Conexao::getEntityManager();
    }

    public function salvar(Admin $admin): Admin {
        $this->entityManager->persist($admin);
        $this->entityManager->flush();
        return $admin;
    }

    public function deletar(Admin $admin): void {
        $this->entityManager->remove($admin);
        $this->entityManager->flush();
    }

    public function listarTodos(): array {
        return $this->entityManager->getRepository(Admin::class)->findAll();
    }

    public function buscarPorId(int $id): ?Admin {
        return $this->entityManager->find(Admin::class, $id);
    }

    public function buscarPorMatricula(string $matricula): ?Admin {
        return $this->entityManager
            ->getRepository(Admin::class)
            ->findOneBy(['matricula' => $matricula]);
    }

    public function buscarPorSetor(string $setor): array {
        return $this->entityManager
            ->getRepository(Admin::class)
            ->findBy(['setor' => $setor]);
    }

    // ── DQL ───────────────────────────────────────────────────────────────────

    /**
     * Retorna admins ativos de um determinado setor.
     * Equivalente ao findAdminsAtivosPorSetor do Spring.
     */
    public function buscarAtivosPorSetor(string $setor): array {
        return $this->entityManager
            ->createQuery('SELECT a FROM model\Admin a WHERE a.status = true AND a.setor = :setor')
            ->setParameter('setor', $setor)
            ->getResult();
    }

    // ── SQL Nativo ────────────────────────────────────────────────────────────

    /**
     * Lista matrículas de admins ativos via SQL nativo.
     * Equivalente ao findMatriculasAdminsAtivos do Spring.
     */
    public function listarMatriculasAtivos(): array {
        return $this->entityManager
            ->getConnection()
            ->fetchFirstColumn(
                'SELECT matricula FROM tb_admin WHERE status = true'
            );
    }
}
