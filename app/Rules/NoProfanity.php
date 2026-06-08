<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoProfanity implements ValidationRule
{
    /**
     * Palabras bloqueadas — se comparan como palabras completas (word boundary),
     * así "disputa" no activa "puta" y "computadora" no activa nada.
     */
    private array $profanities = [
        // Español
        'puto', 'puta', 'putas', 'putos', 'putear', 'puteo',
        'mierda', 'mierdas',
        'carajo', 'carajos',
        'concha', 'conchas',
        'boludo', 'boluda', 'boludos', 'boludas', 'boludez',
        'pelotudo', 'pelotuda', 'pelotudos', 'pelotudas',
        'cagada', 'cagado', 'cagar',
        'idiota', 'idiotas',
        'imbecil', 'imbécil',
        'estupido', 'estupida', 'estúpido', 'estúpida',
        'tonto', 'tonta', 'tontos', 'tontas',
        'hdp',        // hijo de puta abreviado
        'forro', 'forra', 'forros',
        'mogolico', 'mogólico',
        'tarado', 'tarada',
        'gil', 'giles',
        'cretino', 'cretina',
        'culo', 'culos',
        'pija', 'pijas',
        'verga', 'vergas',
        'coño',
        'joder',
        // Portugués
        'merda', 'porra', 'caralho', 'foda', 'fodase',
        'buceta', 'viado',
        // Inglés
        'fuck', 'fucking', 'fucker', 'shit', 'ass', 'asshole',
        'bitch', 'cunt', 'damn', 'bastard', 'idiot', 'stupid',
        'dick', 'cock', 'pussy',
        // Francés
        'merde', 'putain', 'connard', 'salaud',
        // Ruso transliterado
        'blyad', 'suka', 'pizda', 'khuy',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Normalizar: quitar acentos para capturar variantes sin tilde
        $normalized = $this->normalize($value);

        foreach ($this->profanities as $word) {
            $normalizedWord = $this->normalize($word);
            // \b = word boundary → no bloquea "disputa" por "puta"
            if (preg_match('/\b' . preg_quote($normalizedWord, '/') . '\b/iu', $normalized)) {
                $fail('El contenido contiene lenguaje inapropiado.');
                return;
            }
        }
    }

    private function normalize(string $text): string
    {
        // Minúsculas
        $text = mb_strtolower($text);
        // Quitar acentos para que "estúpido" y "estupido" sean equivalentes
        $text = str_replace(
            ['á','é','í','ó','ú','ü','ñ','à','â','ê','î','ô','û','ç'],
            ['a','e','i','o','u','u','n','a','a','e','i','o','u','c'],
            $text
        );
        return $text;
    }
}
