<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 * title="API Blog",
 * version="1.0.0",
 * description="Documentación de la API (Laravel 11 + Passport)."
 * )
 *
 * @OA\Server(
 * url=L5_SWAGGER_CONST_HOST,
 * description="Servidor base"
 * )
 *
 * @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * type="http",
 * scheme="bearer",
 * bearerFormat="JWT"
 * )
 *
 * @OA\Tag(name="Auth", description="Autenticación y perfil")
 * @OA\Tag(name="Posts", description="Gestión de posts")
 */
class OpenApi 
{
    /**
     * @OA\Get(
     * path="/api/ping",
     * summary="Ping de prueba",
     * description="Ruta temporal para forzar la inicialización de Swagger",
     * @OA\Response(
     * response=200,
     * description="Sano y salvo"
     * )
     * )
     */
    public function ping()
    {
        // Al meter el método dentro de la clase con su anotación encima,
        // obligamos al lector estático a registrar un PathItem real.
    }
}