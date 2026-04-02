# IF E-RETAIL

Sistema de gestão de varejo desenvolvido para o curso de Sistemas de Informação - IFPR Palmas.

## 🛠️ Tecnologias e Dependências

* **Linguagem:** PHP 8.x
* **Gerenciador de Dependências:** Composer
* **Banco de Dados:** MySQL

Para instalar as dependências:

composer install

---
## Arquitetura
O projeto segue o padrão MVC.

### Estrutura de Pastas:

* 📂 src/
  * controller/
  * dao/
  * model/
  * utils/
  * view/
* 📂 test/
---
O sistema implementa CRUD completo para Admin, Cliente, Pedido e Produto.
A classe UserModel é a base para Admin e Cliente.
