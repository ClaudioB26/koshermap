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
                'about'         => 'La Orthodox Union (OU) es la agencia de certificación kosher más grande y reconocida del mundo. Fundada en 1898 en Estados Unidos, certifica cientos de miles de productos de miles de empresas en más de 100 países, y su símbolo — una "U" dentro de un círculo — es uno de los más reconocidos internacionalmente en el etiquetado de alimentos kosher.',
                'website'       => 'https://oukosher.org',
                'contact_email' => 'kosherq@ou.org',
                'phone'         => '+1 212-563-4000',
                'address'       => '40 Rector St, 4th Floor, New York, NY 10006, Estados Unidos',
            ],
            'ajdut-kosher' => [
                'about'         => 'Ajdut Kosher es la certificadora kosher más importante de Argentina, dirigida por el Rab. Daniel Oppenheimer. Supervisa la producción de alimentos y establecimientos con estándares de kashrut reconocidos por la comunidad judía argentina y de la región.',
                'website'       => 'https://kosher.org.ar',
                // Email tomado de un directorio externo, no confirmado en la web oficial. Verificar.
                'contact_email' => 'info@kosher.org.ar',
                'phone'         => '+54 11 4787-6712',
                'address'       => 'Moldes 2449, CABA (C1428), Argentina',
            ],
            'bdk-brasil' => [
                'about'         => 'BDK do Brasil (Beit Din Kashrut) es una agencia de certificación kosher con sede en San Pablo, que supervisa la producción de alimentos para la industria alimenticia de Brasil, trabajando con cientos de empresas y miles de productos aprobados.',
                'website'       => 'https://bdk.com.br',
                'contact_email' => 'kosherbr@bdk.com.br',
                'phone'         => '+55 11 3082-9295',
                'address'       => 'Alameda Lorena 1304, cj. 1508, Cerqueira César, São Paulo - SP, 01424-001, Brasil',
            ],
            'kosher-chile' => [
                'about'         => 'Chile Kosher es la agencia encargada de la certificación kosher de productos y establecimientos en Chile, bajo la supervisión del Rab. Eliahu Tamim, trabajando con cientos de marcas y miles de productos aprobados.',
                'website'       => 'https://www.chilekosher.cl',
                'contact_email' => 'info@chilekosher.cl',
                'phone'         => '+56 2 2656 9288',
            ],
            'kehila' => [
                'about'         => 'Kehila Uruguay certifica productos y establecimientos kosher bajo la supervisión del Gran Rabinato de la Comunidad Israelita de Uruguay, siendo la principal referencia en materia de kashrut a nivel nacional.',
                'website'       => 'https://kehila.org.uy',
                'contact_email' => 'info@kehila.org.uy',
                'phone'         => '+598 2712 0100',
            ],
            'kmd-mexico' => [
                'about'         => 'KMD (Kashrut Maguén David) es la certificadora kosher líder en México, con más de 40 años de trayectoria en certificación para el mercado local y de exportación.',
                'website'       => 'https://www.kosher.com.mx',
                'contact_email' => 'atencionaclientes@kosher.com.mx',
                'phone'         => '+52 55 3872 5050',
                'address'       => 'Horacio 1008, Col. Polanco, Ciudad de México, 11560, México',
            ],
            'uk-kosher-latam' => [
                'about'         => 'U-K Kosher es una certificadora fundada por el Rab. Gabriel Yabra, con origen en Argentina y presencia hoy en distintos países de Latinoamérica (Brasil, Bolivia, Chile, Uruguay, Ecuador, Perú y Colombia).',
                'website'       => 'https://ukkosher.org',
                'contact_email' => 'uk@ukkosher.org',
            ],
        ];

        foreach ($data as $slug => $fields) {
            Certifier::where('slug', $slug)->update($fields);
        }
    }
}
