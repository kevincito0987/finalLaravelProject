<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para crear una nueva pregunta de evaluación (POST).
 * No incluye 'questionId' ya que se crea automáticamente.
 */
class StoreEvaluationQuestionRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        // Ajusta la lógica de autorización según tu aplicación (e.g., verificar si el usuario es administrador)
        return true; 
    }

    /**
     * Obtiene las reglas de validación que aplican a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'evaluation_id_question' => ['required', 'integer', 'exists:evaluations,evaluation_id'],
            'card_id_evaluation' => ['required', 'integer', 'exists:cards,card_id'],
            'question_text' => ['required', 'string', 'min:3', 'max:500'],
            'correct_answer' => ['required', 'string', 'max:255'],
            'options' => ['nullable', 'array'],
            'options.*' => ['string', 'max:255'],
        ];
    }
}
