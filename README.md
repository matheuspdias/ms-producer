# MS Producer - Microserviço Producer

Microserviço producer para cadastro de usuários com integração ao RabbitMQ.

## 🚀 Tecnologias

- PHP 8.2+
- Laravel 12
- RabbitMQ
- Docker

## 📐 Arquitetura

Arquitetura em camadas:
- **Request Layer**: Validação de dados ([UserRequest](src/app/Http/Requests/UserRequest.php))
- **Controller Layer**: Recepção de requisições ([UserController](src/app/Http/Controllers/UserController.php))
- **Service Layer**: Lógica de negócio ([UserService](src/app/Services/UserService.php))
- **Integration Layer**: Cliente RabbitMQ ([RabbitMQService](src/app/Services/RabbitMQService.php))

## 🐳 Setup do Projeto

### 1. Clone o repositório

```bash
git clone <seu-repositorio>
cd ms-producer
```

### 2. Configure as variáveis de ambiente

```bash
cp .env.example .env
```

**IMPORTANTE**: O `.env` na raiz é para variáveis do Docker/RabbitMQ. O Laravel usa `src/.env`.

### 3. Suba os containers

```bash
docker compose build
docker compose up -d
```

A aplicação estará disponível em: **http://localhost:8000**

## 📡 Uso da API

### Endpoints

#### Health Check
```bash
GET http://localhost:8000/api/health
```

#### Cadastrar Usuário
```bash
POST http://localhost:8000/api/users
Content-Type: application/json

{
  "name": "João da Silva",
  "email": "joao.silva@example.com"
}
```

### 📖 Documentação Completa

- [API Examples](API_EXAMPLES.md) - Exemplos de requisições e respostas
- [Postman Guide](POSTMAN_GUIDE.md) - Guia completo para uso com Postman
- [MS-Producer.postman_collection.json](MS-Producer.postman_collection.json) - Coleção Postman

## ✅ Validações

- **name**: obrigatório, mínimo 3 caracteres, máximo 255
- **email**: obrigatório, formato válido

## 🐰 RabbitMQ

### Verificar mensagens na fila

```bash
docker exec rabbitmq rabbitmqctl list_queues
```

### Interface Web

```
http://localhost:15672

Usuário: rabbit
Senha: rabbit
```

### Estrutura do Evento

```json
{
  "event_id": "user_656f8e4a5d1c83.12345678",
  "event_type": "user.created",
  "timestamp": "2025-11-27T23:30:00Z",
  "payload": {
    "name": "João da Silva",
    "email": "joao.silva@example.com"
  },
  "metadata": {
    "source": "ms-producer",
    "version": "1.0",
    "environment": "local"
  }
}
```

## 📦 Comandos Úteis

```bash
# Ver logs do container
docker compose logs -f app

# Parar o container
docker compose down

# Acessar o container
docker compose exec app bash

# Executar comandos artisan
docker compose exec app php artisan <comando>

# Rodar testes
docker compose exec app php artisan test
```

## 🔧 Troubleshooting

### Container não sobe

```bash
docker compose down
docker compose build --no-cache
docker compose up -d
```

### Erro de conexão com RabbitMQ

Verifique se o RabbitMQ está rodando:

```bash
docker ps | grep rabbit
```

Credenciais corretas no `src/.env`:
```env
RABBITMQ_USER=rabbit
RABBITMQ_PASS=rabbit
```

## 📂 Estrutura de Arquivos

```
ms-producer/
├── src/                          # Código Laravel
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/      # Controllers
│   │   │   ├── Middleware/       # Middlewares customizados
│   │   │   └── Requests/         # Form Requests
│   │   └── Services/             # Services (lógica de negócio)
│   ├── routes/api.php            # Rotas da API
│   └── config/app.php            # Configurações
├── Dockerfile
├── docker-compose.yml
├── .env.example                  # Template de variáveis
└── README.md
```

## 🎯 Features Implementadas

✅ Cadastro de usuários com validação
✅ Integração com RabbitMQ
✅ Logs estruturados
✅ Middleware para forçar JSON
✅ Health check endpoint
✅ Documentação completa
✅ Coleção Postman

## 📝 Licença

MIT
