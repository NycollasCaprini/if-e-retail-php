<?php

namespace model;

class UserFactory
{
    public static function create(TipoUsuario $tipo, array $d): UserModel {
        $user = match($tipo) {
            TipoUsuario::CLIENTE => new Cliente($d['nome'], $d['cpf'], $d['idade'], $d['senha'], $tipo->value),
            TipoUsuario::ADMIN   => new Admin($d['nome'], $d['cpf'], $d['idade'], $d['senha'], $tipo->value),
        };

        if ($user instanceof Cliente) {
            $carrinho = new Carrinho($user, 'ABERTO');
            $user->setCarrinho($carrinho);
        }

        return $user;
    }
}