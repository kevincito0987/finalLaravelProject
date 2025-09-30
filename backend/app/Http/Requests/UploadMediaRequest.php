<?php

// app/Http/Requests/UploadMediaRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 * schema="UploadMediaRequest",
 * title="Upload Media Request",
 * description="Petición para subir un archivo (imagen o audio).",
 * required={"file"},
 * @OA\Property(
 * property="file",
 * type="string",
 * format="binary",
 * description="El archivo a subir. Debe ser un tipo de imagen (jpeg, png, jpg) o audio (mp3, wav) y no superar los 10MB.",
 * )
 * )
 */
class UploadMediaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:jpeg,png,jpg,mp3,wav|max:10240',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
