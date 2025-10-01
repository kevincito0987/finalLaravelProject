<?php

namespace App\Http\Controllers;

use App\Models\UserProgress;
use App\Http\Requests\StoreUserProgressRequest;
use App\Http\Requests\UpdateUserProgressRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserProgressController extends Controller
{
    /**
     * Muestra una lista de todos los registros de progreso del usuario.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        // Esto podría ser filtrado por el usuario autenticado en un entorno real.
        $progress = UserProgress::with(['user', 'card'])->get();
        return response()->json($progress);
    }

    /**
     * Almacena un nuevo registro de progreso en la base de datos.
     *
     * @param  \App\Http\Requests\StoreUserProgressRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserProgressRequest $request): JsonResponse
    {
        // Los datos ya están validados por StoreUserProgressRequest
        $progress = UserProgress::create($request->validated());

        // Cargamos las relaciones para la respuesta
        $progress->load('user', 'card');
        
        return response()->json([
            'message' => 'Progreso de usuario creado exitosamente.',
            'data' => $progress
        ], 201); // Código 201 para "Creado"
    }

    /**
     * Muestra un registro de progreso específico.
     *
     * @param  \App\Models\UserProgress  $userProgress
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(UserProgress $userProgress): JsonResponse
    {
        // Cargamos las relaciones para asegurar que la respuesta es completa
        $userProgress->load('user', 'card');
        return response()->json($userProgress);
    }

    /**
     * Actualiza un registro de progreso existente en la base de datos.
     *
     * @param  \App\Http\Requests\UpdateUserProgressRequest  $request
     * @param  \App\Models\UserProgress  $userProgress
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserProgressRequest $request, UserProgress $userProgress): JsonResponse
    {
        // Los datos ya están validados por UpdateUserProgressRequest.
        // Solo se actualizarán los campos que se hayan enviado en la solicitud.
        $userProgress->update($request->validated());

        // Volvemos a cargar las relaciones (opcional, pero útil para verificar)
        $userProgress->load('user', 'card');

        return response()->json([
            'message' => 'Progreso de usuario actualizado exitosamente.',
            'data' => $userProgress
        ]);
    }

    /**
     * Elimina un registro de progreso de la base de datos.
     *
     * @param  \App\Models\UserProgress  $userProgress
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(UserProgress $userProgress): JsonResponse
    {
        $userProgress->delete();

        return response()->json([
            'message' => 'Progreso de usuario eliminado exitosamente.'
        ], 204); // Código 204 para "Sin Contenido"
    }
}
