<?php

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ErrorResponse",
    type: "object",
    properties: [
        new OA\Property(property: "success", type: "boolean", example: false),
        new OA\Property(
            property: "mensagem",
            type: "string",
            example: "Erro ao processar requisição"
        )
    ]
)]
class ErrorResponseSchema {}