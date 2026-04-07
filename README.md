# IF E-Retail — Sistema de Gestão de Varejo

> Sistema de gerenciamento de varejo desenvolvido como projeto acadêmico para o curso de **Sistemas de Informação** do **IFPR Palmas**. Implementa CRUD completo para as entidades principais do domínio de varejo, seguindo o padrão arquitetural MVC com ORM via Doctrine.

---

## 📋 Índice

- [Sobre o Projeto](#sobre-o-projeto)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Arquitetura](#arquitetura)
- [Estrutura de Pastas](#estrutura-de-pastas)
- [Modelo de Dados](#modelo-de-dados)
- [Camada Model](#camada-model)
- [Camada DAO](#camada-dao)
- [Utilitários](#utilitários)
- [Testes](#testes)
- [Como Executar](#como-executar)
- [Configuração do Banco de Dados](#configuração-do-banco-de-dados)
- [Comandos Úteis](#comandos-úteis)
- [Licença](#licença)

---

## Sobre o Projeto

O **IF E-Retail** é um sistema back-end de gestão de varejo que modela as operações essenciais de uma loja: cadastro de clientes e administradores, gerenciamento de produtos, carrinho de compras e pedidos. O projeto foi desenvolvido com foco em boas práticas de orientação a objetos, separação de responsabilidades e cobertura de testes automatizados.

**Principais funcionalidades:**

- Cadastro e gerenciamento de **Clientes** e **Administradores** com herança de `UserModel`
- Gerenciamento de **Produtos** com controle de estoque e status
- Criação e acompanhamento de **Pedidos** com associação de itens e clientes
- **Carrinho de Compras** vinculado ao ciclo de vida do cliente
- Gerenciamento de **Endereços** e **Contatos** por usuário
- Abstração de acesso a dados via **GenericDAO** com suporte a três formas de consulta (Repository, QueryBuilder e DQL)

---

## Tecnologias Utilizadas

| Tecnologia | Versão | Finalidade |
|---|---|---|
| PHP | 8.2.x | Linguagem principal |
| Doctrine ORM | ^3.6 | Mapeamento objeto-relacional |
| MySQL | — | Banco de dados relacional |
| PHPUnit | ^9.6 | Testes automatizados |
| vlucas/phpdotenv | ^5.6 | Gerenciamento de variáveis de ambiente |
| Symfony Console | ^7.0 | Comandos CLI do Doctrine |
| Symfony Cache | ^7.0 | Cache para o ORM |
| Symfony Var Exporter | ^7.0 | Suporte interno ao Doctrine |
| Composer | — | Gerenciador de dependências |

---

## Arquitetura

O projeto segue o padrão **MVC (Model-View-Controller)**, com ênfase nas camadas Model e DAO, dado o escopo back-end do sistema.

```
┌──────────────────────────────────────────────────────┐
│                    Aplicação                         │
│                                                      │
│  ┌─────────────┐    ┌─────────────┐                  │
│  │  Controller │───▶│    Model    │                  │
│  │  (futuro)   │    │  (Entidades)│                  │
│  └─────────────┘    └──────┬──────┘                  │
│                            │                         │
│                     ┌──────▼──────┐                  │
│                     │     DAO     │                  │
│                     │  (Acesso)   │                  │
│                     └──────┬──────┘                  │
│                            │                         │
│                     ┌──────▼──────┐                  │
│                     │   Conexao   │ ◀── .env          │
│                     │  (Doctrine) │                  │
│                     └──────┬──────┘                  │
│                            │                         │
│                     ┌──────▼──────┐                  │
│                     │    MySQL    │                  │
│                     └─────────────┘                  │
└──────────────────────────────────────────────────────┘
```

**Princípios arquiteturais aplicados:**

- **Herança com Single Table Inheritance (STI):** `Admin` e `Cliente` herdam de `UserModel`, que é mapeada em uma única tabela (`tb_user`) com discriminador `discr`.
- **DAO Genérico:** `GenericDAO` centraliza as operações CRUD (salvar, listar, buscar por ID, deletar), eliminando duplicação de código entre os DAOs específicos.
- **Singleton do EntityManager:** A classe `Conexao` implementa o padrão Singleton para garantir uma única instância do EntityManager durante o ciclo de vida da aplicação.
- **Factory Pattern:** `UserFactory` encapsula a lógica de criação de `Admin` ou `Cliente`, incluindo a inicialização automática do carrinho para clientes.

---

## Estrutura de Pastas

```
if-e-retail-php/
│
├── src/
│   ├── dao/                    # Camada de acesso ao banco de dados
│   │   ├── GenericDAO.php      # DAO abstrato com CRUD genérico
│   │   ├── AdminDAO.php        # DAO específico para Admin
│   │   ├── ClienteDAO.php      # DAO específico para Cliente
│   │   ├── PedidoDAO.php       # DAO específico para Pedido
│   │   └── ProdutoDAO.php      # DAO específico para Produto
│   │
│   ├── model/                  # Entidades de domínio (mapeadas pelo Doctrine)
│   │   ├── GenericModel.php    # Superclasse abstrata com ID gerado automaticamente
│   │   ├── UserModel.php       # Modelo base de usuário (STI)
│   │   ├── Admin.php           # Entidade Administrador
│   │   ├── Cliente.php         # Entidade Cliente
│   │   ├── Produto.php         # Entidade Produto
│   │   ├── Pedido.php          # Entidade Pedido
│   │   ├── ItemPedido.php      # Item individual de um pedido
│   │   ├── Carrinho.php        # Carrinho de compras do cliente
│   │   ├── Endereco.php        # Endereço vinculado a um usuário
│   │   ├── Contato.php         # Contato (telefone/email) de um usuário
│   │   ├── TipoUsuario.php     # Enum: CLIENTE | ADMIN
│   │   └── UserFactory.php     # Factory para criação de usuários
│   │
│   └── utils/
│       └── Conexao.php         # Singleton do EntityManager (Doctrine)
│
├── test/
│   ├── dao/                    # Testes de integração dos DAOs
│   │   ├── AdminDAOTest.php
│   │   ├── ClienteDAOTest.php
│   │   ├── PedidoDAOTest.php
│   │   └── ProdutoDAOTest.php
│   │
│   ├── model/                  # Testes unitários dos modelos
│   │   ├── AdminTest.php
│   │   ├── CarrinhoTest.php
│   │   ├── ClienteTest.php
│   │   ├── PedidoTest.php
│   │   └── ProdutoTest.php
│   │
│   └── utils/
│       └── ConexaoTest.php     # Teste de conectividade com o banco
│
├── doctrine.php                # Entry-point para CLI do Doctrine
├── composer.json               # Manifesto de dependências
├── composer.lock               # Versões travadas das dependências
├── .gitignore
└── LICENSE
```

---

## Modelo de Dados

O diagrama abaixo representa as relações entre as entidades do sistema:

```
┌──────────────┐          ┌──────────────┐
│  GenericModel│          │  TipoUsuario │
│  (superclass)│          │    (Enum)    │
│──────────────│          │──────────────│
│ + id: int    │          │ CLIENTE      │
└──────┬───────┘          │ ADMIN        │
       │ herança          └──────────────┘
       │
┌──────▼──────────────────────────────────────────┐
│                   UserModel                      │
│               [tb_user — STI]                    │
│─────────────────────────────────────────────────│
│ name: string                                     │
│ cpf: string                                      │
│ dataNascimento: date                             │
│ senha: string                                    │
│ tipo: string                                     │
│ endereco_id ──────────────────▶ Endereco         │
│ contatos[] ───────────────────▶ Contato[]        │
└──────────┬──────────────────────┬───────────────┘
           │ herança               │ herança
    ┌──────▼──────┐        ┌──────▼──────────┐
    │    Admin    │        │     Cliente      │
    │  [tb_admin] │        │  [tb_cliente]    │
    │─────────────│        │──────────────────│
    │ matricula   │        │ carrinho ────────┼──▶ Carrinho
    │ setor       │        │ listaPedidos[]───┼──▶ Pedido[]
    │ cargo       │        │ listaFavoritos───┼──▶ Produto[]
    │ dataAdmissao│        └──────────────────┘
    │ status      │
    └─────────────┘

┌──────────────┐    ┌──────────────────┐    ┌──────────────┐
│   Carrinho   │    │    ItemPedido    │    │    Pedido    │
│ [tb_carrinho]│    │[tb_item_pedido]  │    │  [tb_pedido] │
│──────────────│    │──────────────────│    │──────────────│
│ status       │    │ pedido_id ───────┼───▶│ dataPedido   │
│ valorTotal   │    │ produto_id       │    │ dataEntrega  │
│ itens[]      │    │ quantidade       │    │ status       │
└──────────────┘    │ preco            │    │ cliente_id   │
                    └──────────────────┘    │ itens[]      │
                                            └──────────────┘

┌──────────────┐    ┌──────────────┐
│   Produto    │    │   Endereco   │
│ [tb_produto] │    │[tb_endereco] │
│──────────────│    │──────────────│
│ descricao    │    │ rua          │
│ quantidade   │    │ numero       │
│ precoUnitario│    │ complemento  │
│ status       │    │ bairro       │
└──────────────┘    │ cidade       │
                    │ estado       │
                    │ cep          │
                    │ pais         │
                    └──────────────┘
```

---

## Camada Model

### `GenericModel` — Superclasse Abstrata

Classe base mapeada como `@MappedSuperclass`, responsável por declarar e gerenciar o campo `id` em todas as entidades. Toda entidade do sistema herda desta classe, garantindo um identificador auto-gerado pelo banco de dados.

```php
// Toda entidade herda o ID automático
abstract class GenericModel {
    protected $id; // gerado pelo banco (auto-increment)
    
    public function getID(): int { ... }
    public function setID(int $id): void { ... }
}
```

**Por que existe:** evita repetição do campo `id` em todas as entidades e centraliza a configuração do Doctrine para geração automática de chave primária.

---

### `UserModel` — Modelo Base de Usuário (Single Table Inheritance)

Entidade abstrata que centraliza os atributos comuns a `Admin` e `Cliente`. Utiliza **Single Table Inheritance (STI)** do Doctrine: ambas as subclasses são armazenadas em `tb_user`, diferenciadas pela coluna discriminadora `discr`.

**Atributos:**

| Campo | Tipo | Descrição |
|---|---|---|
| `name` | string | Nome completo do usuário |
| `cpf` | string | CPF do usuário |
| `dataNascimento` | date | Data de nascimento |
| `senha` | string | Senha (hash recomendado) |
| `tipo` | string | `"admin"` ou `"cliente"` |
| `endereco` | Endereco | Endereço (OneToOne, lazy) |
| `contatos` | Contato[] | Lista de contatos (OneToMany) |

**Por que STI:** como Admin e Cliente compartilham a maioria dos campos, uma única tabela reduz joins e simplifica as consultas de autenticação.

---

### `Admin` — Administrador do Sistema

Estende `UserModel`, mapeada para `tb_admin`. Armazena dados funcionais do administrador.

**Atributos específicos:**

| Campo | Tipo | Descrição |
|---|---|---|
| `matricula` | string | Matrícula funcional |
| `setor` | string | Setor de atuação |
| `cargo` | string | Cargo/função |
| `dataAdmissao` | string | Data de admissão na empresa |
| `status` | date | Status do vínculo empregatício |

---

### `Cliente` — Cliente da Loja

Estende `UserModel`, mapeada para `tb_cliente`. Possui carrinho de compras, histórico de pedidos e lista de produtos favoritos.

**Atributos específicos:**

| Campo | Tipo | Descrição |
|---|---|---|
| `carrinho` | Carrinho | Carrinho ativo (OneToOne, cascade all) |
| `listaPedidos` | Pedido[] | Histórico de pedidos (OneToMany) |
| `listaFavoritos` | Produto[] | Produtos favoritados (ManyToMany) |

**Observação:** o carrinho é criado automaticamente pela `UserFactory` no momento de instanciação do cliente.

---

### `Produto` — Produto do Catálogo

Entidade que representa um item vendável, mapeada para `tb_produto`.

**Atributos:**

| Campo | Tipo | Descrição |
|---|---|---|
| `descricao` | string | Nome/descrição do produto |
| `quantidade` | integer | Quantidade em estoque |
| `precoUnitario` | decimal(10,2) | Preço por unidade |
| `status` | string | Status do produto (`"disponivel"`, `"inativo"`, etc.) |

---

### `Pedido` — Pedido de Compra

Representa uma transação de compra, mapeada para `tb_pedido`. Vinculada a um `Cliente` e a múltiplos `Produto` via tabela associativa `tb_produto_pedido`.

**Atributos:**

| Campo | Tipo | Descrição |
|---|---|---|
| `dataPedido` | datetime | Data de criação do pedido |
| `dataEntrega` | datetime | Data prevista de entrega |
| `status` | boolean | Se o pedido está ativo/concluído |
| `cliente` | Cliente | Cliente que realizou o pedido (ManyToOne) |
| `itens` | Produto[] | Produtos do pedido (ManyToMany) |

---

### `ItemPedido` — Item de Pedido

Tabela associativa enriquecida que detalha cada produto dentro de um pedido, incluindo quantidade e preço no momento da compra. Mapeada para `tb_item_pedido`.

**Atributos:**

| Campo | Tipo | Descrição |
|---|---|---|
| `pedido` | Pedido | Referência ao pedido pai (ManyToOne) |
| `produto` | Produto | Produto associado (ManyToOne) |
| `quantidade` | integer | Quantidade adquirida |
| `preco` | decimal(10,2) | Preço no momento da compra (snapshot) |

**Por que existe:** armazenar o preço no `ItemPedido` garante que alterações futuras no preço do produto não modifiquem pedidos históricos.

---

### `Carrinho` — Carrinho de Compras

Representa o carrinho ativo do cliente, mapeado para `tb_carrinho`. Gerencia itens antes de se tornarem um pedido confirmado.

**Atributos:**

| Campo | Tipo | Descrição |
|---|---|---|
| `status` | string | `"ABERTO"` (padrão) ou `"FECHADO"` |
| `valorTotal` | decimal(10,2) | Soma dos itens adicionados |
| `itens` | ItemPedido[] | Itens no carrinho (OneToMany, cascade all) |

---

### `Endereco` — Endereço do Usuário

Entidade de valor associada a um `UserModel` via relacionamento OneToOne, mapeada para `tb_endereco`.

**Atributos:** `rua`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `cep`, `pais`.

---

### `Contato` — Contato do Usuário

Permite múltiplos contatos (telefone/email) por usuário, via relacionamento ManyToOne com `UserModel`, mapeada para `tb_contato`.

**Atributos:**

| Campo | Tipo | Descrição |
|---|---|---|
| `telefone` | string | Número de telefone |
| `email` | string | Endereço de e-mail |
| `usuario` | UserModel | Dono do contato (ManyToOne) |

---

### `TipoUsuario` — Enum de Tipo de Usuário

Enum backed (PHP 8.1+) que define os dois tipos de usuário suportados.

```php
enum TipoUsuario: string {
    case CLIENTE = 'cliente';
    case ADMIN   = 'admin';
}
```

**Por que usar Enum:** tipagem forte evita strings mágicas no código e garante que apenas valores válidos sejam aceitos pelo compilador.

---

### `UserFactory` — Fábrica de Usuários

Implementa o padrão **Factory Method** para encapsular a criação de usuários. Garante que a inicialização do carrinho de `Cliente` seja sempre feita de forma consistente.

```php
$cliente = UserFactory::create(TipoUsuario::CLIENTE, [
    'nome'  => 'Maria',
    'cpf'   => '123.456.789-00',
    'idade' => new DateTime('1990-05-10'),
    'senha' => 'hash_seguro',
]);
// O carrinho já está inicializado automaticamente
```

**Por que existe:** centralizar a criação evita que o código consumidor esqueça de inicializar o carrinho ou outros objetos dependentes.

---

## Camada DAO

### `GenericDAO` — DAO Abstrato e Genérico

Classe abstrata que implementa as quatro operações CRUD básicas para qualquer entidade que extenda `GenericModel`. As subclasses apenas precisam declarar `protected static $modelClass` apontando para a entidade alvo.

**Métodos disponíveis:**

| Método | Descrição |
|---|---|
| `salvar(GenericModel $model)` | Persiste ou atualiza a entidade no banco. Usa transação explícita com rollback automático em caso de falha. |
| `listar()` | Retorna todas as instâncias da entidade (`SELECT * FROM ...`). |
| `buscarPorId($id)` | Retorna uma entidade pelo seu ID primário. |
| `deletar(GenericModel $model)` | Remove a entidade do banco. Usa transação com rollback. |

**Exemplo de uso:**

```php
$produto = new Produto("Notebook Dell", 10, 3500.00, "disponivel");
$salvo = ProdutoDAO::salvar($produto);
echo $salvo->getID(); // ID gerado automaticamente pelo banco

$todos = ProdutoDAO::listar();
$um = ProdutoDAO::buscarPorId(1);
ProdutoDAO::deletar($um);
```

**Por que transações explícitas:** o `beginTransaction()` + `commit()` + `rollback()` garante atomicidade mesmo em cenários onde o Doctrine possa ter auto-commit desabilitado em configurações específicas.

---

### `AdminDAO` — DAO do Administrador

Estende `GenericDAO` com buscas específicas para a entidade `Admin`.

**Métodos adicionais:**

| Método | Estratégia | Descrição |
|---|---|---|
| `buscarPorCpf($cpf)` | `findBy` (Repository) | Busca exata por CPF |
| `buscarPorNome($name)` | `QueryBuilder` + `LIKE` | Busca parcial por nome |
| `buscarPorCargo($cargo)` | `findBy` (Repository) | Busca exata por cargo |

---

### `ClienteDAO` — DAO do Cliente

Estende `GenericDAO` e demonstra as **três formas de consulta** suportadas pelo Doctrine:

**Métodos adicionais:**

| Método | Estratégia | Descrição |
|---|---|---|
| `buscarPorCpf($cpf)` | `findBy` — busca exata por campo | Retorna clientes com CPF exato |
| `buscarPorNome($name)` | `QueryBuilder` com `LIKE` | Busca parcial por nome (mais flexível) |
| `buscarPorNomeDQL($name)` | DQL — Doctrine Query Language | Busca via query Doctrine nativa |

As três formas coexistem intencionalmente como referência didática:

- **`findBy`**: ideal para filtragem simples por igualdade de campo.
- **`QueryBuilder`**: recomendado para queries dinâmicas e condicionais complexas.
- **`DQL`**: útil quando a expressividade da query se assemelha ao SQL e o desenvolvedor tem domínio da linguagem Doctrine.

---

### `ProdutoDAO` — DAO do Produto

Estende `GenericDAO` com buscas orientadas ao gerenciamento de catálogo e estoque.

**Métodos adicionais:**

| Método | Descrição |
|---|---|
| `buscarPorDescricao($descricao)` | Busca parcial por descrição do produto (QueryBuilder + LIKE) |
| `buscarSemEstoque()` | Retorna produtos com `quantidade = 0` |
| `buscarPorStatus($status)` | Filtra produtos por status (`"disponivel"`, `"inativo"`, etc.) |

---

### `PedidoDAO` — DAO do Pedido

Estende `GenericDAO` com busca por status de pedido.

**Métodos adicionais:**

| Método | Descrição |
|---|---|
| `buscarPorStatus($status)` | Busca pedidos por status via DQL |

> **Nota:** o método `buscarPorStatus` referencia `model\Produto` na query DQL — isso é um ponto de atenção que pode indicar um bug ou cópia incorreta do `ProdutoDAO`. Recomenda-se revisar para `model\Pedido`.

---

## Utilitários

### `Conexao` — Singleton do EntityManager

Gerencia a conexão com o banco de dados via Doctrine ORM. Implementa o padrão **Singleton** para garantir que apenas uma instância do `EntityManager` exista por requisição.

**Funcionamento:**

1. Carrega as variáveis de ambiente via `vlucas/phpdotenv` a partir do arquivo `.env` na raiz do projeto.
2. Configura o Doctrine com `ORMSetup::createAttributeMetadataConfiguration`, apontando para o diretório `src/model/` onde estão as anotações PHP 8 (`#[ORM\...]`).
3. Cria a conexão com `DriverManager::getConnection()` usando os parâmetros do `.env`.
4. Instancia e retorna o `EntityManager`.

**Variáveis de ambiente necessárias (`.env`):**

```dotenv
DB_DRIVER=pdo_mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=nome_do_banco
DB_USER=usuario
DB_PASSWORD=senha
```

---

## Testes

O projeto conta com cobertura de testes via **PHPUnit 9.6**, divididos em testes unitários (model) e testes de integração (dao).

### Testes de Model (Unitários)

Verificam a criação e consistência dos objetos sem necessidade de banco de dados.

| Arquivo | Casos de Teste |
|---|---|
| `AdminTest.php` | Criação de objeto com todos os atributos obrigatórios |
| `ClienteTest.php` | Criação de objeto e verificação de atributos herdados |
| `CarrinhoTest.php` | Criação com status explícito e com status padrão `"ABERTO"` |
| `ProdutoTest.php` | Criação de produto e verificação de todos os getters |
| `PedidoTest.php` | Criação de pedido com cliente e produto associados |

### Testes de DAO (Integração)

Requerem conexão ativa com o banco de dados. Testam o ciclo completo de persistência.

| Arquivo | Casos de Teste |
|---|---|
| `AdminDAOTest.php` | salvar, listar, deletar, buscarPorCpf, buscarPorNome, buscarPorCargo |
| `ClienteDAOTest.php` | salvar, salvarComContatos, salvarComEndereco, listar, deletar, buscarPorCpf, buscarPorNome, buscarPorNomeDQL |
| `PedidoDAOTest.php` | salvar, salvarComCliente, salvarComProdutos, listar, deletar, buscarPorStatus |
| `ProdutoDAOTest.php` | salvar, listar, deletar, buscarPorDescricao, buscarSemEstoque, buscarPorStatus |
| `ConexaoTest.php` | Verifica se o EntityManager é instanciado corretamente |

---

## Como Executar

### Pré-requisitos

- PHP 8.2 ou superior
- Composer instalado globalmente
- MySQL 5.7+ ou MariaDB 10.4+

### 1. Clonar o repositório

```bash
git clone https://github.com/NycollasCaprini/if-e-retail-php.git
cd if-e-retail-php
```

### 2. Instalar as dependências

```bash
composer install
```

### 3. Configurar o ambiente

Crie o arquivo `.env` na raiz do projeto com base no seguinte modelo:

```dotenv
DB_DRIVER=pdo_mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=if_e_retail
DB_USER=root
DB_PASSWORD=sua_senha_aqui
```

### 4. Criar o banco de dados

Crie o banco no MySQL:

```sql
CREATE DATABASE if_e_retail CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Gerar as tabelas via Doctrine

```bash
php doctrine.php orm:schema-tool:create
```

Para atualizar o schema após mudanças nos models:

```bash
php doctrine.php orm:schema-tool:update --force
```

### 6. Executar os testes

```bash
# Todos os testes
./vendor/bin/phpunit

# Apenas testes de model (sem banco de dados)
./vendor/bin/phpunit test/model/

# Apenas testes de integração (requer banco configurado)
./vendor/bin/phpunit test/dao/
```

---

## Configuração do Banco de Dados

O projeto utiliza o arquivo `.env` na raiz para configuração da conexão. Este arquivo **não deve ser versionado** (já está no `.gitignore`).

**Drivers suportados pelo Doctrine DBAL:**

| Driver | Uso |
|---|---|
| `pdo_mysql` | MySQL / MariaDB (padrão do projeto) |
| `pdo_pgsql` | PostgreSQL |
| `pdo_sqlite` | SQLite (útil para testes) |

---

## Comandos Úteis

```bash
# Instalar dependências
composer install

# Ver schema atual das entidades
php doctrine.php orm:schema-tool:create --dump-sql

# Atualizar tabelas sem perder dados
php doctrine.php orm:schema-tool:update --force

# Validar mapeamento das entidades
php doctrine.php orm:validate-schema

# Rodar todos os testes com saída detalhada
./vendor/bin/phpunit --testdox

# Rodar testes de uma classe específica
./vendor/bin/phpunit test/dao/ProdutoDAOTest.php
```

---

## Licença

Este projeto está licenciado sob a licença **CC0 1.0 Universal** — consulte o arquivo [LICENSE](LICENSE) para mais detalhes.

---

*Projeto acadêmico desenvolvido para o curso de Sistemas de Informação — IFPR Palmas.*
