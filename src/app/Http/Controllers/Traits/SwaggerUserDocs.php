<?php

namespace App\Http\Controllers\Traits;

use OpenApi\Attributes as OA;

trait SwaggerUserDocs
{
    #[OA\Get(
        path: "/api/users",
        summary: "Listar todos os usuários",
        description: "Retorna a lista de todos os usuários cadastrados no consumer",
        tags: ["Users"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de usuários retornada com sucesso",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "success",
                            type: "boolean",
                            example: true
                        ),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: "id",
                                        type: "string",
                                        format: "uuid",
                                        example: "9bcdcc8d-4bbb-42f0-8217-3c5b133ee6c9"
                                    ),
                                    new OA\Property(
                                        property: "name",
                                        type: "string",
                                        example: "João da Silva"
                                    ),
                                    new OA\Property(
                                        property: "email",
                                        type: "string",
                                        example: "joao.silva@example.com"
                                    ),
                                    new OA\Property(
                                        property: "created_at",
                                        type: "string",
                                        format: "date-time",
                                        example: "2025-11-28T03:06:11.000000Z"
                                    ),
                                    new OA\Property(
                                        property: "updated_at",
                                        type: "string",
                                        format: "date-time",
                                        example: "2025-11-28T03:06:11.000000Z"
                                    ),
                                ],
                                type: "object"
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Erro ao buscar usuários",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "success",
                            type: "boolean",
                            example: false
                        ),
                        new OA\Property(
                            property: "message",
                            type: "string",
                            example: "Erro ao buscar usuários"
                        ),
                        new OA\Property(
                            property: "error",
                            type: "string",
                            example: "Erro ao fazer requisição: Connection refused"
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index(): \Illuminate\Http\JsonResponse
    {
        // Implementation in UserController
    }

    #[OA\Get(
        path: "/api/users/{id}",
        summary: "Buscar usuário específico",
        description: "Retorna os dados de um usuário específico pelo ID",
        tags: ["Users"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID do usuário (UUID)",
                schema: new OA\Schema(
                    type: "string",
                    format: "uuid",
                    example: "9bcdcc8d-4bbb-42f0-8217-3c5b133ee6c9"
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Usuário encontrado com sucesso",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "success",
                            type: "boolean",
                            example: true
                        ),
                        new OA\Property(
                            property: "data",
                            properties: [
                                new OA\Property(
                                    property: "id",
                                    type: "string",
                                    format: "uuid",
                                    example: "9bcdcc8d-4bbb-42f0-8217-3c5b133ee6c9"
                                ),
                                new OA\Property(
                                    property: "name",
                                    type: "string",
                                    example: "João da Silva"
                                ),
                                new OA\Property(
                                    property: "email",
                                    type: "string",
                                    example: "joao.silva@example.com"
                                ),
                                new OA\Property(
                                    property: "created_at",
                                    type: "string",
                                    format: "date-time",
                                    example: "2025-11-28T03:06:11.000000Z"
                                ),
                                new OA\Property(
                                    property: "updated_at",
                                    type: "string",
                                    format: "date-time",
                                    example: "2025-11-28T03:06:11.000000Z"
                                ),
                            ],
                            type: "object"
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Usuário não encontrado",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "success",
                            type: "boolean",
                            example: false
                        ),
                        new OA\Property(
                            property: "message",
                            type: "string",
                            example: "Usuário não encontrado"
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Erro ao buscar usuário",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "success",
                            type: "boolean",
                            example: false
                        ),
                        new OA\Property(
                            property: "message",
                            type: "string",
                            example: "Erro ao buscar usuário"
                        ),
                        new OA\Property(
                            property: "error",
                            type: "string",
                            example: "Erro ao fazer requisição: Connection refused"
                        ),
                    ]
                )
            ),
        ]
    )]
    public function show(string $id): \Illuminate\Http\JsonResponse
    {
        // Implementation in UserController
    }

    #[OA\Post(
        path: "/api/users",
        summary: "Cadastrar novo usuário",
        description: "Cadastra um novo usuário e envia para processamento via RabbitMQ",
        tags: ["Users"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email"],
                properties: [
                    new OA\Property(
                        property: "name",
                        type: "string",
                        description: "Nome completo do usuário",
                        example: "João da Silva",
                        minLength: 3,
                        maxLength: 255
                    ),
                    new OA\Property(
                        property: "email",
                        type: "string",
                        format: "email",
                        description: "E-mail do usuário",
                        example: "joao.silva@example.com"
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Usuário cadastrado com sucesso",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "success",
                            type: "boolean",
                            example: true
                        ),
                        new OA\Property(
                            property: "message",
                            type: "string",
                            example: "Usuário enviado para processamento"
                        ),
                        new OA\Property(
                            property: "data",
                            properties: [
                                new OA\Property(
                                    property: "event_id",
                                    type: "string",
                                    example: "user_656f8e4a5d1c83.12345678"
                                ),
                                new OA\Property(
                                    property: "user",
                                    properties: [
                                        new OA\Property(
                                            property: "name",
                                            type: "string",
                                            example: "João da Silva"
                                        ),
                                        new OA\Property(
                                            property: "email",
                                            type: "string",
                                            example: "joao.silva@example.com"
                                        ),
                                    ],
                                    type: "object"
                                ),
                            ],
                            type: "object"
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Erro de validação",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "message",
                            type: "string",
                            example: "O nome deve ter no mínimo 3 caracteres (and 1 more error)"
                        ),
                        new OA\Property(
                            property: "errors",
                            properties: [
                                new OA\Property(
                                    property: "name",
                                    type: "array",
                                    items: new OA\Items(
                                        type: "string",
                                        example: "O nome deve ter no mínimo 3 caracteres"
                                    )
                                ),
                                new OA\Property(
                                    property: "email",
                                    type: "array",
                                    items: new OA\Items(
                                        type: "string",
                                        example: "O e-mail deve ser válido"
                                    )
                                ),
                            ],
                            type: "object"
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Erro interno do servidor",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "success",
                            type: "boolean",
                            example: false
                        ),
                        new OA\Property(
                            property: "message",
                            type: "string",
                            example: "Erro ao processar requisição"
                        ),
                        new OA\Property(
                            property: "error",
                            type: "string",
                            example: "Não foi possível conectar ao RabbitMQ"
                        ),
                    ]
                )
            ),
        ]
    )]
    public function store(\App\Http\Requests\UserRequest $request): \Illuminate\Http\JsonResponse
    {
        // Implementation in UserController
    }

    #[OA\Get(
        path: "/api/health",
        summary: "Health check",
        description: "Verifica se a API está rodando corretamente",
        tags: ["Health"],
        responses: [
            new OA\Response(
                response: 200,
                description: "API está funcionando",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "success",
                            type: "boolean",
                            example: true
                        ),
                        new OA\Property(
                            property: "service",
                            type: "string",
                            example: "ms-producer"
                        ),
                        new OA\Property(
                            property: "status",
                            type: "string",
                            example: "running"
                        ),
                        new OA\Property(
                            property: "timestamp",
                            type: "string",
                            format: "date-time",
                            example: "2025-11-28T03:06:11+00:00"
                        ),
                    ]
                )
            ),
        ]
    )]
    public function healthCheck(): \Illuminate\Http\JsonResponse
    {
        // Implementation in UserController
    }
}
