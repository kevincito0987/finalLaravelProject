<?php

// app/Http/Requests/UploadMediaRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

