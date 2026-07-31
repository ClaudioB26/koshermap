<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateArticleSlugs extends Command
{
    protected $signature = 'articles:generate-slugs {--force : Regenerar aunque ya existan}';

    protected $description = 'Genera slugs por idioma (en/pt/fr/ru/he) a partir de los títulos ya traducidos';

    // Idiomas donde el slug se transliteran a ASCII (lo estándar en la mayoría
    // de los sitios en esos idiomas: legible, sin caracteres especiales en la URL).
    private const ASCII_LOCALES = ['en', 'pt', 'fr'];

    // Idiomas donde el slug conserva la escritura original: es la convención
    // real de los sitios en ruso y en hebreo, y Google la indexa sin problema.
    private const NATIVE_SCRIPT_LOCALES = ['ru', 'he'];

    public function handle(): void
    {
        $force = (bool) $this->option('force');
        $articles = Article::orderBy('sort_order')->get();

        // Para evitar slugs duplicados dentro de un mismo idioma
        $used = [];
        foreach (['en', 'pt', 'fr', 'ru', 'he'] as $locale) {
            $used[$locale] = [];
        }

        $updated = 0;

        foreach ($articles as $article) {
            $slugs = $article->slugs ?? [];
            $changed = false;

            foreach (array_merge(self::ASCII_LOCALES, self::NATIVE_SCRIPT_LOCALES) as $locale) {
                if (!empty($slugs[$locale]) && !$force) {
                    $used[$locale][$slugs[$locale]] = true;
                    continue;
                }

                $title = $article->rawTitleFor($locale);
                if (!$title) {
                    continue;
                }

                $base = in_array($locale, self::ASCII_LOCALES, true)
                    ? Str::slug($title)
                    : $this->nativeScriptSlug($title);

                if ($base === '') {
                    continue;
                }

                $slug = $base;
                $n = 2;
                while (isset($used[$locale][$slug])) {
                    $slug = $base . '-' . $n;
                    $n++;
                }

                $used[$locale][$slug] = true;
                $slugs[$locale] = $slug;
                $changed = true;
            }

            if ($changed) {
                $article->slugs = $slugs;
                $article->save();
                $updated++;
                $this->line("  {$article->slug}: " . json_encode($slugs, JSON_UNESCAPED_UNICODE));
            }
        }

        $this->info("Artículos actualizados: {$updated} de {$articles->count()}");
    }

    /**
     * Slug que conserva la escritura original (para ruso y hebreo): pasa a
     * minúsculas, cambia espacios/puntuación por guiones, y solo elimina
     * símbolos que no son letras ni números de ningún alfabeto.
     */
    private function nativeScriptSlug(string $text): string
    {
        $text = mb_strtolower(trim($text), 'UTF-8');
        $text = preg_replace('/[\'"«»„""\'\']/u', '', $text);
        $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
        $text = trim($text, '-');

        return $text;
    }
}
