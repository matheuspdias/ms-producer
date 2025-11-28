# Producer Service

Microserviço responsável por produzir eventos e enviá-los via RabbitMQ.

## 🐳 Subir ambiente

```bash
# 1. Copiar arquivo de variáveis de ambiente
cp .env.example .env

# 2. Build e subir o container
docker compose build
docker compose up -d
```

## 📝 Notas

- O Laravel já está instalado no diretório `src/`
- O `composer install` é executado automaticamente durante o build
- A aplicação roda na porta **8000**: http://localhost:8000
- O container se conecta ao RabbitMQ externo via `host.docker.internal:5672`
- Certifique-se de que o RabbitMQ esteja rodando no host (localhost:15672)

## 🔧 Variáveis de ambiente

As variáveis do RabbitMQ já estão configuradas em `src/.env`:
- `RABBITMQ_HOST=host.docker.internal`
- `RABBITMQ_PORT=5672`
- `RABBITMQ_USER=rabbitmq`
- `RABBITMQ_PASS=rabbitmq`

## 📦 Comandos úteis

```bash
# Ver logs do container
docker compose logs -f app

# Parar o container
docker compose down

# Acessar o container
docker compose exec app bash

# Executar comandos artisan
docker compose exec app php artisan <comando>
```
