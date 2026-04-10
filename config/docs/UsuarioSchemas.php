<?php

use OpenApi\Attributes as OA;

/*
|--------------------------------------------------------------------------
| REQUESTS
|--------------------------------------------------------------------------
*/

#[OA\Schema(
    schema: "UsuarioFotoRequest",
    type: "object",
    required: ["url_foto"],
    properties: [
        new OA\Property(
            property: "url_foto",
            type: "string",
            format: "uri",
            example: "https://meusite.com/imagens/foto.jpg"
        )
    ]
)]
class UsuarioFotoRequestSchema {}

#[OA\Schema(
    schema: "UsuarioUpdateRequest",
    type: "object",
    properties: [
        new OA\Property(
            property: "nome",
            type: "string",
            example: "Luiz Felipe"
        ),
        new OA\Property(
            property: "email",
            type: "string",
            format: "email",
            example: "luiz@email.com"
        )
    ]
)]
class UsuarioUpdateRequestSchema {}


/*
|--------------------------------------------------------------------------
| RESOURCES
|--------------------------------------------------------------------------
*/

#[OA\Schema(
    schema: "UsuarioResource",
    type: "object",
    properties: [
        new OA\Property(property: "id_usuario", type: "integer", example: 1),
        new OA\Property(property: "UUID", type: "string", example: "e04230085f34fcdc518137ac826725"),
        new OA\Property(property: "nome", type: "string", example: "Luiz Felipe"),
        new OA\Property(property: "email", type: "string", format: "email", example: "teste@email.com"),
        new OA\Property(property: "foto_perfil", type: "string", nullable: true, example: null)
    ]
)]
class UsuarioResourceSchema {}


/*
|--------------------------------------------------------------------------
| RESPONSES
|--------------------------------------------------------------------------
*/

#[OA\Schema(
    schema: "UsuarioFotoResponse",
    type: "object",
    properties: [
        new OA\Property(property: "success", type: "boolean", example: true),
        new OA\Property(
            property: "detail",
            type: "object",
            properties: [
                new OA\Property(property: "mensagem", type: "string", example: "Foto de perfil atualizada com sucesso"),
                new OA\Property(property: "foto_perfil", type: "string", format: "uri", example: "https://meusite.com/imagens/foto.jpg")
            ]
        )
    ]
)]
class UsuarioFotoResponseSchema {}

#[OA\Schema(
    schema: "UsuarioUpdateResponse",
    type: "object",
    properties: [
        new OA\Property(property: "success", type: "boolean", example: true),
        new OA\Property(
            property: "detail",
            type: "object",
            properties: [
                new OA\Property(property: "mensagem", type: "string", example: "Usuario atualizado com sucesso"),
                new OA\Property(
                    property: "usuario",
                    type: "object",
                    properties: [
                        new OA\Property(property: "id_usuario", type: "integer", example: 1)
                    ]
                )
            ]
        )
    ]
)]
class UsuarioUpdateResponseSchema {}

#[OA\Schema(
    schema: "UsuarioDeleteResponse",
    type: "object",
    properties: [
        new OA\Property(property: "success", type: "boolean", example: true),
        new OA\Property(
            property: "detail",
            type: "object",
            properties: [
                new OA\Property(property: "mensagem", type: "string", example: "Usuário deletado com sucesso")
            ]
        )
    ]
)]
class UsuarioDeleteResponseSchema {}

#[OA\Schema(
    schema: "UsuarioListResponse",
    type: "object",
    properties: [
        new OA\Property(property: "success", type: "boolean", example: true),
        new OA\Property(
            property: "detail",
            type: "object",
            properties: [
                new OA\Property(property: "mensagem", type: "string", example: "Usuário encontrado"),
                new OA\Property(
                    property: "usuario",
                    ref: "#/components/schemas/UsuarioResource"
                )
            ]
        )
    ]
)]
class UsuarioListResponseSchema {}