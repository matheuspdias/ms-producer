# Guia de Documentação Swagger

## Acesso à Documentação

A documentação interativa da API está disponível em:

**http://localhost:8000/api/documentation**

## Arquitetura da Documentação

Para manter o código limpo e organizado, a documentação Swagger foi implementada usando **Traits**, separando as anotações da lógica do controller.

### Estrutura

```
src/app/Http/Controllers/
├── Controller.php                      # Anotações globais (Info, Server, Tags)
├── UserController.php                  # Lógica do controller (limpo)
└── Traits/
    └── SwaggerUserDocs.php             # Anotações Swagger dos endpoints
```

## Vantagens desta Abordagem

✅ **Separação de responsabilidades**: Lógica separada da documentação
✅ **Controllers limpos**: Fácil leitura e manutenção
✅ **Documentação centralizada**: Traits dedicadas à documentação
✅ **Reutilização**: Traits podem ser compartilhadas entre controllers
✅ **Organização**: Estrutura escalável para projetos grandes

## Como Funciona

### 1. Controller Base (Controller.php)

Define informações globais da API:

```php
#[OA\Info(
    version: "1.0.0",
    title: "MS Producer API",
    description: "API para cadastro de usuários com integração ao RabbitMQ"
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Servidor Local"
)]
#[OA\Tag(name: "Health", description: "Endpoints de health check")]
#[OA\Tag(name: "Users", description: "Endpoints de gerenciamento de usuários")]
abstract class Controller
```

### 2. Trait de Documentação (SwaggerUserDocs.php)

Contém todas as anotações Swagger dos endpoints:

```php
trait SwaggerUserDocs
{
    #[OA\Post(
        path: "/api/users",
        summary: "Cadastrar novo usuário",
        // ... documentação completa
    )]
    public function store(UserRequest $request): JsonResponse
    {
        // Implementation in UserController
    }
}
```

### 3. Controller (UserController.php)

Usa o trait e implementa apenas a lógica:

```php
class UserController extends Controller
{
    use SwaggerUserDocs;

    public function store(UserRequest $request): JsonResponse
    {
        try {
            $result = $this->userService->createUser($request->validated());
            return response()->json($result, 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar requisição',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
```

## Endpoints Documentados

### POST /api/users

**Cadastrar novo usuário**

- ✅ Request body documentado
- ✅ Validações especificadas
- ✅ Respostas de sucesso (201)
- ✅ Respostas de erro (422, 500)
- ✅ Exemplos de requisição e resposta

### GET /api/health

**Health check**

- ✅ Resposta de sucesso (200)
- ✅ Estrutura do JSON de resposta
- ✅ Exemplos

## Como Adicionar Novos Endpoints

### 1. Criar novo Trait de documentação

```php
// src/app/Http/Controllers/Traits/SwaggerProductDocs.php

namespace App\Http\Controllers\Traits;

use OpenApi\Attributes as OA;

trait SwaggerProductDocs
{
    #[OA\Get(
        path: "/api/products",
        summary: "Listar produtos",
        tags: ["Products"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de produtos",
                // ...
            )
        ]
    )]
    public function index(): JsonResponse
    {
        // Implementation in ProductController
    }
}
```

### 2. Usar o Trait no Controller

```php
class ProductController extends Controller
{
    use SwaggerProductDocs;

    public function index(): JsonResponse
    {
        // Sua implementação aqui
    }
}
```

### 3. Adicionar Tag no Controller base

```php
// src/app/Http/Controllers/Controller.php

#[OA\Tag(
    name: "Products",
    description: "Endpoints de gerenciamento de produtos"
)]
```

### 4. Regenerar documentação

```bash
php artisan l5-swagger:generate
```

## Testando na Interface Swagger

1. Acesse http://localhost:8000/api/documentation
2. Explore os endpoints disponíveis
3. Clique em "Try it out" em qualquer endpoint
4. Preencha os dados necessários
5. Clique em "Execute"
6. Veja a resposta da API

## Configuração

O arquivo de configuração está em:
```
src/config/l5-swagger.php
```

Principais configurações:
- `api.title`: Título da API
- `routes.api`: Rota da documentação
- `paths.annotations`: Onde procurar as anotações

## Comandos Úteis

```bash
# Gerar/Regenerar documentação
php artisan l5-swagger:generate

# Publicar configuração (já feito)
php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"
```

## Exemplo de JSON Schema

A documentação gera automaticamente um arquivo OpenAPI/Swagger em:
```
src/storage/api-docs/api-docs.json
```

Este arquivo pode ser importado em ferramentas como:
- Postman
- Insomnia
- Swagger Editor
- API testing tools

## Boas Práticas

✅ **Use traits** para separar documentação da lógica
✅ **Documente todos os campos** de request e response
✅ **Adicione exemplos** realistas
✅ **Especifique todas as respostas** possíveis (200, 201, 400, 422, 500)
✅ **Use tags** para organizar endpoints por domínio
✅ **Mantenha descrições claras** e objetivas
✅ **Regenere a documentação** após mudanças

## Integração com CI/CD

Para validar a documentação automaticamente:

```bash
# No seu pipeline
php artisan l5-swagger:generate
# Verificar se foi gerado sem erros
test -f storage/api-docs/api-docs.json
```

## Referências

- [L5-Swagger Documentation](https://github.com/DarkaOnLine/L5-Swagger)
- [OpenAPI Specification](https://swagger.io/specification/)
- [Swagger PHP Attributes](https://zircote.github.io/swagger-php/)
