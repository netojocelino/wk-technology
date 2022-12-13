# WK Technology

## Teste Técnico

Neste teste foi necessário desenvolver o teste aplicando técnicas de

- POO
- MVC
- Clean Code

## Para executar

A aplicação não foi disponibilizada online, desta forma, para executar é necessário clonar o repositório e levantar uma instância local das aplicações.

### Backend

> Passos de atribuir variáveis de ambiente, instalar pacotes, levantar migração e executar testes e aplicação.
>
> Os passos abaixo devem ser executados no diretório `api/`.

Antes de executar é necessário possuir o PHP, Composer e MySQL instalados e copiar o arquivo `.env.example` <sup>[[1]]</sup> e alterar os dados necessários (`DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

Após alterar as informações do banco será necessário instalar os pacotes com `composer install`, e após isso subir as migrações com `php artisan migrate`.

Antes de testar manualmente a aplicação é possível executar testes de funcionalidade, executando `composer test`.

Para executar o **backend** basta executar, na basta `api/` <sup>[[2]]</sup> deste projeto `php -S localhost:8082 -t public`.

### Frontend

> É importante salientar que, em caso de alterar a porta que estará sendo utilizada, também será necessário alterar no projeto frontend a url base da aplicação, que fica em `ng-app/src/app/api` <sup>[[3]]</sup>.

Não é utilizado variáveis de ambiente na aplicação frontend, tendo como necessário apenas a url da api, que para alterar é no arquivo `ng-app/src/app/api/api.service.ts` <sup>[[4]]</sup>.

Para instalar os pacotes da aplicação é executando `npm install`.

Para executar a aplicação **frontend**, na pasta `ng-app/`<sup>[[5]]</sup> o comando `npm run start`.

## Desafio

Para o desafio será necessário desenvolver, utilizando *PHP* e *Angular*, VueJS ou React os requisitos: :white_check_mark:

- Menu Inicial;
- Listagem e cadastro de clientes
  - Código de identificação do Cliente;
  - Nome;
  - CPF;
  - Endereço Completo (CEP, Logradouro, Número, Bairro, Complemento, Cidade);
  - Email;
  - Data de Nascimento.
- Listagem e Cadastro de Produtos
  - Código de identificação de vendas;
  - Nome;
  - Valor Unitário
- Pedido de Vendas
  - Código de identificação de venda;
  - Data e Hora de venda;
  - Identificação do cliente;
  - Identificação dos itens da venda (Lista de produtos);
  - Total da venda.

## Avaliação

1. Banco de dados da preferência;
    - Foi utilizando o banco de dados MySQL para desenvolvimento e SQLite para testes do backend
1. UI/UX;
1. CSS/SCSS;
    - Foi utilizado CSS apenas para algumas poucas estilizações
1. Formatação e código limpo;
1. Conceitos de OOP;
1. Repositório [GitHub]

[GitHub]: https://github.com/netojocelino/wk-technology
[1]: https://github.com/netojocelino/wk-technology/blob/main/api/.env.example
[2]: https://github.com/netojocelino/wk-technology/tree/main/api
[3]: https://github.com/netojocelino/wk-technology/blob/main/ng-app/src/app/api
[4]: https://github.com/netojocelino/wk-technology/blob/main/ng-app/src/app/api/api.service.ts
[5]: https://github.com/netojocelino/wk-technology/blob/main/ng-app
