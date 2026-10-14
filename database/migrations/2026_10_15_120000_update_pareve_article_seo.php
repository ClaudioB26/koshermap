<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * SEO de la nota "Parve / Pareve": en Search Console la nota aparece en las
 * posiciones 10-17 para "parve", "pareve", "pareve significado", "parve que es" y
 * "парве это", con 0 clics. Se ajustan título y descripción (los que se ven en el
 * resultado de búsqueda) para incluir las dos grafías y la respuesta directa.
 * No se tocan los slugs ni el contenido: las URLs y el texto siguen igual.
 */
return new class extends Migration
{
    private array $title = [
        'es' => 'Parve (pareve): qué significa y por qué aparece en tantas etiquetas',
        'en' => "Pareve (parve): what it means and why it's on so many kosher labels",
        'pt' => 'Pareve (parve): o que significa e por que aparece em tantos rótulos',
        'fr' => "Parvé (pareve) : que signifie ce terme et pourquoi il figure sur tant d'étiquettes",
        'ru' => 'Парве (pareve): что это значит и почему так часто встречается на этикетках',
        'he' => 'פרווה (Pareve): מה זה אומר ולמה זה מופיע בכל כך הרבה תוויות',
    ];

    private array $excerpt = [
        'es' => 'Parve o pareve significa que un alimento no es ni cárnico ni lácteo. Te explicamos qué es parve, qué alimentos lo son y por qué es tan valorado en las etiquetas kosher.',
        'en' => 'Pareve (also spelled parve) means a food is neither meat nor dairy. Learn what pareve means, which foods are pareve and why it matters on kosher labels.',
        'pt' => 'Pareve (ou parve) significa que um alimento não é nem cárneo nem lácteo. Entenda o que é pareve, quais alimentos são pareve e por que isso importa nos rótulos kosher.',
        'fr' => 'Parvé (ou pareve) désigne un aliment ni carné ni laitier. Découvrez ce que signifie parvé, quels aliments le sont et pourquoi c\'est important sur les étiquettes casher.',
        'ru' => 'Парве (pareve) означает, что продукт не мясной и не молочный. Объясняем, что такое парве, какие продукты к нему относятся и почему это важно на кошерных этикетках.',
        'he' => 'פרווה (Pareve / Parve) פירושו מאכל שאינו בשרי ואינו חלבי. נסביר מה זה פרווה, אילו מאכלים פרווה ולמה זה חשוב בתוויות כשרות.',
    ];

    public function up(): void
    {
        $row = DB::table('articles')->where('slug', 'que-significa-pareve')->first();
        if (! $row) {
            return;
        }

        DB::table('articles')->where('id', $row->id)->update([
            'title'      => $this->merge($row->title, $this->title),
            'excerpt'    => $this->merge($row->excerpt, $this->excerpt),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Sin reversa: el texto anterior sigue en el historial del repositorio.
    }

    /** Pisa solo los idiomas definidos y conserva el resto del JSON. */
    private function merge(?string $current, array $new): string
    {
        $decoded = json_decode((string) $current, true);
        if (! is_array($decoded)) {
            $decoded = [];
        }

        return json_encode(array_merge($decoded, $new), JSON_UNESCAPED_UNICODE);
    }
};
