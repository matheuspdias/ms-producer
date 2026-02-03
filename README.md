# MS Producer - Microserviço Producer

Microserviço producer para cadastro de usuários com integração ao RabbitMQ.

## 🚀 Tecnologias

- PHP 8.4
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

```bash
# 1. Clone e entre no diretório
git clone https://github.com/matheuspdias/ms-producer.git
cd ms-producer

# 2. Configure o ambiente e suba os containers
cp src/.env.example src/.env
docker compose up -d --build
```

A aplicação estará disponível em: **http://localhost:8000**

> **Nota**: O `composer install` é executado automaticamente na primeira inicialização do container.

## 📡 Uso da API

### 📖 Documentação Swagger

A documentação completa da API está disponível via Swagger UI:

**http://localhost:8000/api/documentation**

A documentação é gerada automaticamente a partir das anotações nos controllers usando **Traits** para manter o código limpo e organizado.

#### Health Check
```bash
GET http://localhost:8000/api/health
```

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

### Filas Dinâmicas

O microserviço utiliza filas específicas por contexto:
- **user_events**: Eventos de usuários (cadastro, atualização, etc.)
- Futuros endpoints terão suas próprias filas (ex: `order_events`, `payment_events`)

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
├── src/                                # Código Laravel
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Controller.php      # Base com anotações Swagger globais
│   │   │   │   ├── UserController.php  # Controller limpo (usa trait)
│   │   │   │   └── Traits/
│   │   │   │       └── SwaggerUserDocs.php  # Documentação Swagger
│   │   │   ├── Middleware/             # Middlewares customizados
│   │   │   └── Requests/               # Form Requests
│   │   └── Services/                   # Services (lógica de negócio)
│   ├── routes/api.php                  # Rotas da API
│   ├── config/
│   │   ├── app.php                     # Configurações da app
│   │   └── l5-swagger.php              # Configuração Swagger
│   └── .env.example                    # Template de variáveis
├── Dockerfile
├── docker-compose.yml
└── README.md
```

## 🎯 Features Implementadas

✅ Cadastro de usuários com validação
✅ Integração com RabbitMQ
✅ Logs estruturados
✅ Middleware para forçar JSON
✅ Health check endpoint
✅ **Documentação Swagger** (usando Traits)
✅ Documentação completa (Markdown)
✅ Coleção Postman
✅ Arquitetura em camadas limpa
✅ Separação de responsabilidades (Traits para docs)

## 📝 Licença

MIT
