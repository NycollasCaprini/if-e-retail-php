<?php

namespace model;

enum TipoUsuario: string {
    case CLIENTE = 'cliente';
    case ADMIN = 'admin';
}