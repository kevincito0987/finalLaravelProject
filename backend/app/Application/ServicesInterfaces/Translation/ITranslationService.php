<?php

namespace App\Application\ServicesInterfaces\Translation;

interface ITranslationService
{
    /**
     * Traduce un texto de forma interactiva.
     *
     * @param string $text Texto original (ej: "Frutas")
     * @param string $targetLanguage Idioma destino (ej: "en")
     * @param string $sourceLanguage Idioma origen (por defecto "es")
     * @return string Texto traducido (ej: "Fruits")
     */
    public function translate(string $text, string $targetLanguage, string $sourceLanguage = 'es'): string;
}
