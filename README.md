
# 📚 Book API - API RESTful para Cadastro de Livros e Índices (Sumário)

API RESTful em PHP (Laravel) para cadastro e gerenciamento de livros e seus índices (sumário). Usuários autenticados podem criar livros, listar livros com filtros e importar índices via arquivo XML.

---

## 🚀 Funcionalidades

- Autenticação via token JWT (rota `/v1/auth/token`)
- Cadastro de livros vinculados ao usuário publicador
- Listagem de livros com filtros por título do livro e título do índice
- Importação de índices/sumário em XML via job assíncrono
- Persistência em banco MySQL (configurável para PostgreSQL)
- Testes unitários e de integração para principais funcionalidades
- Ambiente containerizado via Docker e Docker Compose

---

## 🛠 Tecnologias

- PHP 8.x com Laravel 10
- MySQL (via Docker)
- Docker & Docker Compose
- PHPUnit para testes
- Sanctum ou JWT para autenticação (ajustar conforme implementado)

---

## 📋 Estrutura do Banco

### Tabelas principais

- `users`: usuários padrão do Laravel
- `books`: livros cadastrados
  - `id`
  - `usuario_publicador_id` (FK para users)
  - `titulo`
- `indices`: índices dos livros
  - `id`
  - `livro_id` (FK para books)
  - `indice_pai_id` (auto relacionamento para hierarquia)
  - `titulo`
  - `pagina`

---

## 📌 Rotas

| Método | Rota                                 | Descrição                                       |
|--------|-------------------------------------|------------------------------------------------|
| POST   | `/v1/auth/token`                    | Recuperar token de acesso do usuário           |
| GET    | `/v1/livros`                       | Listar livros (com filtros via query params)   |
| POST   | `/v1/livros`                       | Cadastrar livro com índices                      |
| POST   | `/v1/livros/{livroId}/importar-indices-xml` | Importar índices via XML (job assíncrono)       |

### Filtros para GET /v1/livros

- `titulo`: filtra livros que contenham o texto no título
- `titulo_do_indice`: filtra livros que contenham índice com o título informado

---

## 🔧 Como rodar o projeto

### Pré-requisitos

- Docker e Docker Compose instalados
- Git

### Passos

```bash
git clone https://github.com/helouisedayane/book-api.git
cd book-api

# Copie o arquivo de ambiente e ajuste se necessário
cp .env.example .env

# Suba os containers Docker (PHP + MySQL)
docker-compose up -d

# Instale as dependências PHP
docker exec -it book-api_app_1 composer install

# Gere a chave do Laravel
docker-compose exec -it book-api_app_1 php artisan key:generate

# Rode as migrations
docker-compose exec app php artisan migrate       

# Execute os testes (opcional)
docker exec -it book-api_app_1 php artisan test

#xml:
docker-compose exec app php artisan test --filter=ImportIndicesFromXmlTest
```

---

## ⚙️ Estrutura Docker

- `docker-compose.yml` contendo serviços:
  - `book-api_app`: container PHP com Laravel
  - `mysql`: container MySQL
- Dockerfile para o serviço PHP (com PHP, Composer e extensões necessárias)

---

## 🧪 Testes

- Testes unitários e feature para as rotas principais
- Testes para job de importação de índices via XML
- Executar testes:

```bash
docker exec -it book-api_app_1 php artisan test

#xml:
docker-compose exec app php artisan test --filter=ImportIndicesFromXmlTest
```

---

## 📁 Exemplo de XML para importação de índices

```xml
<indices>
  <indice titulo="Capítulo 1" pagina="1">
    <indice titulo="Seção 1.1" pagina="2"/>
  </indice>
  <indice titulo="Capítulo 2" pagina="10"/>
</indices>
```

---

## 👤 Autores

- Desenvolvido por Helouise Dayane - [GitHub Profile](https://github.com/helouisedayane)


