<?php

namespace Database\Seeders;

use App\Models\Certifier;
use Illuminate\Database\Seeder;

class CertifierAboutSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'ou' => [
                'about'   => 'La Orthodox Union (OU) es la agencia de certificación kosher más grande y reconocida del mundo. Fundada en 1898 en Estados Unidos, certifica cientos de miles de productos de miles de empresas en más de 100 países, y su símbolo — una "U" dentro de un círculo — es uno de los más reconocidos internacionalmente en el etiquetado de alimentos kosher.',
                'website' => 'https://oukosher.org',
            ],
            'ajdut-kosher' => [
                'about'   => 'Ajdut Kosher es la certificadora kosher más importante de Argentina, dirigida por el Rab. Daniel Oppenheimer. Supervisa la producción de alimentos y establecimientos con estándares de kashrut reconocidos por la comunidad judía argentina y de la región.',
                'website' => 'https://kosher.org.ar',
            ],
            // Los siguientes textos son una descripción genérica inicial.
            // Deben revisarse y completarse con la información real de cada certificadora.
            'bdk-brasil' => [
                'about' => 'BDK Brasil es una agencia de certificación kosher que supervisa productos y establecimientos en Brasil, verificando que cumplan con las leyes de la kashrut para la comunidad judía del país.',
            ],
            'kosher-chile' => [
                'about' => 'Chile Kosher es la agencia encargada de la certificación kosher de productos y establecimientos en Chile, supervisando que cumplan con los estándares de la kashrut para la comunidad judía local.',
            ],
            'kehila' => [
                'about' => 'Kehila Uruguay certifica productos y establecimientos kosher en Uruguay, garantizando el cumplimiento de las leyes de la kashrut para la comunidad judía del país.',
            ],
            'kmd-mexico' => [
                'about' => 'KMD México es una agencia de certificación kosher que supervisa productos y establecimientos en México, verificando el cumplimiento de las leyes de la kashrut para la comunidad judía local.',
            ],
            'uk-kosher-latam' => [
                'about' => 'UK Kosher Latinoamérica certifica productos kosher en distintos países de la región, supervisando que cumplan con los estándares de la kashrut.',
            ],
        ];

        foreach ($data as $slug => $fields) {
            Certifier::where('slug', $slug)->update($fields);
        }
    }
}
