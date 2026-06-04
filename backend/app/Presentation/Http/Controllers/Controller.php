<?php

namespace App\Presentation\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Mi API Profesional con Laravel",
    version: "1.0.0",
    description: "Documentación de los endpoints del Backend para la integración con el Frontend."
)]
#[OA\Server(
    url: "http://127.0.0.1:8000",
    description: "Servidor Local de Desarrollo"
)]
abstract class Controller
{
    // 📁 Agregamos un endpoint aquí para que el conteo de paths deje de ser CERO
    #[OA\Get(
        path: "/api/ping",
        summary: "Ping de verificación",
        description: "Ruta inicial requerida por OpenAPI para poder compilar con éxito."
    )]
    #[OA\Response(
        response: 200,
        description: "Swagger inicializado correctamente."
    )]
    public function ping()
    {
        return response()->json(['status' => 'online']);
    }
}
