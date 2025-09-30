<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para actualizar una pregunta de evaluación (PUT/PATCH).
 * No incluye 'questionId' ya que se obtiene de la URL.
 */
class UpdateEvaluationQuestionRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        // Ajusta la lógica de autorización
        return true; 
    }

    /**
     * Obtiene las reglas de validación que aplican a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Usamos 'sometimes' porque en una actualización, los campos son opcionales.
        return [
            // --- Claves Foráneas (Pueden ser opcionales en el PUT) ---
            // Nota: Si quieres permitir que la pregunta se mueva de evaluación o card.
            'evaluation_id_question' => ['sometimes', 'integer', 'exists:evaluations,evaluation_id'],
            'card_id_evaluation' => ['sometimes', 'integer', 'exists:cards,card_id'],

            // --- Contenido de la Pregunta ---
            'question_text' => ['sometimes', 'string', 'min:3', 'max:500'],
            'correct_answer' => ['sometimes', 'string', 'max:255'],
            'options' => ['sometimes', 'array'],
            'options.*' => ['string', 'max:255'],
        ];
    }
}
