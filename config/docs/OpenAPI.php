<?php

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "API Livros",
    description: "Documentação da API Livros"
)]
#[OA\Server(
    url: "http://localhost:8080/api-livros",
    description: "Servidor local"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
#[OA\SecurityScheme(
    securityScheme: "userUuid",
    type: "apiKey",
    in: "header",
    name: "X-USER-UUID",
    description: "UUID do usuário"
)]
class OpenApiSpec
{
    
}