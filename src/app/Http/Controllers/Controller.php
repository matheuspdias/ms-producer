<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "MS Producer API",
    description: "API para cadastro de usuários com integração ao RabbitMQ"
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Servidor Local"
)]
#[OA\Tag(
    name: "Health",
    description: "Endpoints de health check"
)]
#[OA\Tag(
    name: "Users",
    description: "Endpoints de gerenciamento de usuários"
)]
abstract class Controller
{
    //
}
