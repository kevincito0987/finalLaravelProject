<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource para el modelo User.
 * Incluye campos expuestos y la ruta de la imagen de perfil.
 */
class UserResource extends JsonResource
{
    /**
     * Transforma el recurso de usuario en un array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile_image_path' => $this->profile_image_path, // Campo de tu modelo
            
            // Opcional: Incluir los roles del usuario (si la relación está cargada)
            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->pluck('name'); // Retorna solo un array de nombres de rol
            }),

            'created_at' => $this->created_at,
        ];
    }
}
