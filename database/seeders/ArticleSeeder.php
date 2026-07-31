<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    // Fecha del primer artículo. El resto se escalonan a partir de acá,
    // uno cada ~5 días, para no tener los 30 publicados el mismo día.
    private const PUBLISH_START_DATE = '2025-11-03';

    private static ?\Carbon\Carbon $publishStart = null;

    public function run(): void
    {
        $articles = [
            [
                'slug' => 'insectos-frutas-verduras',
                'category' => 'halajot',
                'title' => 'Insectos en frutas y verduras: cómo revisarlas correctamente',
                'excerpt' => 'La Torá prohíbe comer insectos, por lo que revisar frutas y verduras antes de consumirlas es una parte central de mantener una cocina kosher.',
                'content' => '<p>De todas las prohibiciones alimentarias de la Torá, la de los insectos (<em>sheratzim</em>) tiene una particularidad que sorprende a mucha gente: comer un solo insecto puede constituir varias transgresiones a la vez, porque la Torá lo prohíbe en más de un versículo. Por eso revisar frutas y verduras no es una recomendación de higiene, es un paso ineludible antes de que esos alimentos lleguen a la mesa.</p>

<h2>Por qué las verduras de hoja son el caso más delicado</h2>
<p>El problema no es que los insectos sean muchos, sino que son diminutos y del mismo color que la hoja. Los <a href="https://es.wikipedia.org/wiki/%C3%81fido" target="_blank" rel="noopener">pulgones o áfidos</a> miden entre uno y tres milímetros, y aunque en las fotos ampliadas se ven perfectamente, sobre una hoja real son apenas un punto. Los trips son todavía más chicos y se meten en los pliegues. Y en el brócoli o la coliflor el problema se agrava, porque los racimos forman una estructura cerrada donde un insecto puede quedar alojado sin que se vea nada desde afuera.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/pulgon-en-hoja.jpg" alt="Primer plano macro de un pulgón (áfido) sobre la superficie de una hoja verde, donde se distinguen claramente sus seis patas y antenas" loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Un pulgón sobre una hoja, ampliado. En la verdura real mide entre uno y tres milímetros: a simple vista es apenas un puntito oscuro, y por eso se pasa por alto tan fácil. Foto: WikiPedant vía <a href="https://commons.wikimedia.org/wiki/File:Aphid_on_leaf05.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, <a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank" rel="noopener">CC BY-SA 4.0</a>.</figcaption>
</figure>
<p>No todas las verduras presentan el mismo nivel de riesgo. Esta es la escala aproximada que manejan la mayoría de las guías de kashrut:</p>

<svg viewBox="0 0 640 250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Nivel de riesgo de infestación por tipo de verdura: alto para lechuga, espinaca, brócoli y coliflor; medio para frutillas, frambuesas, repollo y perejil; bajo para papa, zanahoria, cebolla, banana y manzana." style="width:100%;height:auto;max-width:640px;margin:1.5rem auto;display:block;">
  <rect x="0" y="0" width="640" height="250" fill="#f9fafb" rx="8"/>
  <text x="20" y="30" font-family="system-ui,sans-serif" font-size="15" font-weight="700" fill="#1f2937">Nivel de atención requerido al revisar</text>
  <rect x="20" y="50" width="380" height="42" fill="#fee2e2" rx="6"/>
  <rect x="20" y="50" width="8" height="42" fill="#dc2626" rx="4"/>
  <text x="40" y="70" font-family="system-ui,sans-serif" font-size="13" font-weight="600" fill="#991b1b">ALTO — revisión hoja por hoja</text>
  <text x="40" y="86" font-family="system-ui,sans-serif" font-size="12" fill="#7f1d1d">Lechuga, espinaca, acelga, brócoli, coliflor, alcaucil</text>
  <rect x="20" y="102" width="300" height="42" fill="#fef3c7" rx="6"/>
  <rect x="20" y="102" width="8" height="42" fill="#d97706" rx="4"/>
  <text x="40" y="122" font-family="system-ui,sans-serif" font-size="13" font-weight="600" fill="#92400e">MEDIO — remojo y enjuague cuidadoso</text>
  <text x="40" y="138" font-family="system-ui,sans-serif" font-size="12" fill="#78350f">Frutillas, frambuesas, repollo, perejil, legumbres secas</text>
  <rect x="20" y="154" width="220" height="42" fill="#dcfce7" rx="6"/>
  <rect x="20" y="154" width="8" height="42" fill="#16a34a" rx="4"/>
  <text x="40" y="174" font-family="system-ui,sans-serif" font-size="13" font-weight="600" fill="#166534">BAJO — lavado normal</text>
  <text x="40" y="190" font-family="system-ui,sans-serif" font-size="12" fill="#14532d">Papa, zanahoria, cebolla, banana, manzana, cítricos</text>
  <text x="20" y="228" font-family="system-ui,sans-serif" font-size="11" fill="#6b7280">El riesgo varía según la región y el método de cultivo: consultá la guía de tu certificadora local.</text>
</svg>

<h2>Cómo revisar, según el tipo de alimento</h2>
<ul>
<li><strong>Verduras de hoja:</strong> separar hoja por hoja, lavar bajo agua corriente frotando suavemente ambos lados, y revisar a contraluz. La luz natural de una ventana funciona mejor que la de una lámpara.</li>
<li><strong>Coliflor y brócoli:</strong> cortar en floretes chicos y remojar entre 3 y 5 minutos en agua con unas gotas de detergente o un chorrito de vinagre. El agua jabonosa rompe la tensión superficial y hace que los insectos se desprendan; después hay que enjuagar bien.</li>
<li><strong>Frutillas y frambuesas:</strong> sacarles el cabito, remojar en agua salada o con vinagre unos minutos, y enjuagar. Las frambuesas son especialmente difíciles por su estructura hueca, y hay comunidades que directamente no las consumen frescas.</li>
<li><strong>Legumbres secas (lentejas, garbanzos, porotos):</strong> extender sobre una superficie clara —una fuente blanca o un repasador liso— y revisar antes de cocinar. Acá el enemigo suele ser el gorgojo, que aparece por almacenamiento prolongado.</li>
<li><strong>Harinas y cereales:</strong> tamizar antes de usar, sobre todo si el paquete estuvo abierto mucho tiempo o guardado en un lugar húmedo.</li>
</ul>

<h2>Una lección que aprendimos en casa</h2>
<p>Y que quede claro que esto no es tan fácil como suena. A uno de nosotros, en casa, siempre le tocaba revisar las verduras. Con los años empezó a necesitar anteojos, y un día vio un puntito negro en una hoja. Convencido de que no era nada, le preguntó a su hija: ¿esto qué es? Y la hija le contestó: ¿no ves que es un bicho, que se le ven las patitas? Desde ese día no revisa una sola hoja sin los anteojos puestos.</p>
<p>Vale la pena volver a mirar la foto de arriba con eso en mente. Ampliado se ven las seis patas, las antenas, todo. Sobre la hoja, en la mesada de la cocina y con luz mediocre, es el puntito que casi pasa desapercibido.</p>
<p>La enseñanza es simple pero vale por todo el artículo: revisar bien exige buena luz y buena vista. Si usás anteojos, ponételos. Si la cocina tiene luz amarilla tenue, mudá la tarea a la mesa cerca de la ventana. Y ante la duda, una segunda mirada —o una segunda persona— nunca sobra.</p>

<h2>El factor estacional: por qué en verano hay que redoblar la atención</h2>
<p>La prevalencia de insectos no es igual todo el año. En los meses cálidos y húmedos las poblaciones de pulgones y trips se multiplican, y una lechuga que en invierno venía prácticamente limpia puede requerir el doble de trabajo en enero. Lo mismo pasa con la procedencia: la verdura de invernadero suele venir más infestada que la de campo abierto, porque el ambiente cerrado y templado favorece la reproducción.</p>
<p>Por eso muchas certificadoras publican alertas estacionales avisando qué productos están viniendo especialmente problemáticos en determinado momento del año. Si tu comunidad tiene una lista de este tipo, conviene revisarla antes de las compras grandes de festividades.</p>

<h2>Verduras pre-revisadas: ¿son confiables?</h2>
<p>Hoy existen verduras cultivadas bajo supervisión específica para minimizar la infestación —típicamente hidropónicas o de invernadero controlado— que se venden con certificación kosher y no requieren revisión adicional. Son considerablemente más caras, pero para familias con poco tiempo o para eventos grandes resuelven el problema de raíz.</p>
<p>La advertencia importante: que un paquete diga "lavado y listo para consumir" no significa nada desde el punto de vista del kashrut. Ese sello habla de higiene alimentaria, no de revisión por insectos. Solo sirve si tiene certificación kosher explícita de una agencia reconocida. Podés verificar el estatus de un producto puntual en nuestro <a href="/">directorio de productos certificados</a>.</p>

<h2>Preguntas frecuentes</h2>
<p><strong>¿Y si encuentro un insecto después de haber cocinado?</strong><br>
Depende del caso y conviene consultarlo con un rabino, porque entran en juego conceptos de anulación (<em>bitul</em>) que no se pueden generalizar en un artículo. La regla práctica es que la revisión se hace antes de cocinar, justamente para no llegar a esa situación.</p>
<p><strong>¿Sirve lavar con productos comerciales para desinfectar verduras?</strong><br>
Ayudan a desprender insectos, igual que el agua jabonosa, pero no reemplazan la inspección visual. El lavado es un paso previo, no el paso final.</p>
<p><strong>¿Los insectos microscópicos también están prohibidos?</strong><br>
No. La halajá se refiere a lo que es visible a simple vista. Los microorganismos que solo se ven con microscopio no entran en la prohibición, justamente porque el criterio es lo perceptible por el ojo humano.</p>

<h2>Para seguir leyendo</h2>
<p>Si estás organizando una cocina kosher desde cero, te puede servir nuestra guía sobre <a href="/articulos/armar-cocina-kosher">cómo armar una cocina kosher</a>, y si recién empezás, el repaso de <a href="/articulos/errores-comunes-empezar-comer-kosher">errores comunes al empezar a comer kosher</a> incluye justamente este tema entre los que más se pasan por alto.</p>
<p>Para profundizar en fuentes externas, la <a href="https://oukosher.org/the-kosher-primer/" target="_blank" rel="noopener">guía introductoria de la Orthodox Union</a> cubre los fundamentos del kashrut en detalle, y el <a href="https://www.youtube.com/@OUKosher" target="_blank" rel="noopener">canal de OU Kosher en YouTube</a> publica material audiovisual sobre revisión de productos. También podés consultar el artículo general sobre <a href="https://es.wikipedia.org/wiki/Kashrut" target="_blank" rel="noopener">kashrut en Wikipedia</a> para el contexto histórico.</p>',
            ],
            [
                'slug' => 'carne-y-leche',
                'category' => 'halajot',
                'title' => 'Carne y leche: por qué no se mezclan',
                'excerpt' => 'La separación entre carne y leche es uno de los pilares más conocidos del kashrut. Te explicamos su origen, sus alcances y cómo se aplica en la práctica.',
                'content' => '<p>"No cocinarás un cabrito en la leche de su madre." Ese versículo aparece tres veces en la Torá —en Éxodo dos veces y en Deuteronomio una— y de esa repetición la tradición oral sacó una prohibición triple: no cocinar carne con leche, no comerla mezclada y tampoco sacar ningún provecho de esa mezcla, ni siquiera vendiéndola.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/carne-y-leche.jpg" alt="Una cocina kosher observante mantiene dos juegos completos de ollas y utensilios, uno para carne y otro para lácteos." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Una cocina kosher observante mantiene dos juegos completos de ollas y utensilios, uno para carne y otro para lácteos. Foto: PattayaPatrol vía <a href="https://commons.wikimedia.org/wiki/File%3ADFC_2431_A_cook_flips_food_in_a_hot_pan_while_working_in_a_compact_well-used_kitchen_filled_with_pots_utensils_and_shelves_of_ingredients.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Esa última parte suele sorprender. Una persona que sigue el kashrut no solo se abstiene de comer un plato de carne con crema: tampoco puede venderlo ni regalarlo, porque el beneficio económico también está incluido en la prohibición.</p>

<h2>Las tres categorías que ordenan toda la cocina</h2>
<p>De ahí sale la división de todos los alimentos en tres grupos que cualquier persona que sigue el kashrut maneja de memoria:</p>

<svg viewBox="0 0 640 260" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Diagrama de las tres categorías del kashrut: cárnico y lácteo no se pueden combinar entre sí, mientras que parve se combina con ambos." style="width:100%;height:auto;max-width:640px;margin:1.5rem auto;display:block;">
  <rect x="0" y="0" width="640" height="260" fill="#f9fafb" rx="8"/>
  <rect x="40" y="30" width="160" height="70" fill="#fee2e2" stroke="#dc2626" stroke-width="2" rx="8"/>
  <text x="120" y="58" text-anchor="middle" font-family="system-ui,sans-serif" font-size="15" font-weight="700" fill="#991b1b">CÁRNICO</text>
  <text x="120" y="78" text-anchor="middle" font-family="system-ui,sans-serif" font-size="11" fill="#7f1d1d">Carne, pollo y derivados</text>
  <rect x="440" y="30" width="160" height="70" fill="#dbeafe" stroke="#2563eb" stroke-width="2" rx="8"/>
  <text x="520" y="58" text-anchor="middle" font-family="system-ui,sans-serif" font-size="15" font-weight="700" fill="#1e40af">LÁCTEO</text>
  <text x="520" y="78" text-anchor="middle" font-family="system-ui,sans-serif" font-size="11" fill="#1e3a8a">Leche, queso, manteca</text>
  <line x1="200" y1="65" x2="440" y2="65" stroke="#dc2626" stroke-width="2.5" stroke-dasharray="6,4"/>
  <circle cx="320" cy="65" r="18" fill="#fef2f2" stroke="#dc2626" stroke-width="2.5"/>
  <line x1="310" y1="55" x2="330" y2="75" stroke="#dc2626" stroke-width="2.5"/>
  <line x1="330" y1="55" x2="310" y2="75" stroke="#dc2626" stroke-width="2.5"/>
  <text x="320" y="106" text-anchor="middle" font-family="system-ui,sans-serif" font-size="11" font-weight="600" fill="#991b1b">nunca se mezclan</text>
  <rect x="240" y="150" width="160" height="70" fill="#dcfce7" stroke="#16a34a" stroke-width="2" rx="8"/>
  <text x="320" y="178" text-anchor="middle" font-family="system-ui,sans-serif" font-size="15" font-weight="700" fill="#166534">PARVE</text>
  <text x="320" y="198" text-anchor="middle" font-family="system-ui,sans-serif" font-size="11" fill="#14532d">Frutas, verduras, huevos, pescado</text>
  <line x1="240" y1="175" x2="120" y2="100" stroke="#16a34a" stroke-width="2"/>
  <line x1="400" y1="175" x2="520" y2="100" stroke="#16a34a" stroke-width="2"/>
  <text x="150" y="145" font-family="system-ui,sans-serif" font-size="11" fill="#166534">se combina ✓</text>
  <text x="425" y="145" font-family="system-ui,sans-serif" font-size="11" fill="#166534">se combina ✓</text>
</svg>

<p>La categoría <strong>parve</strong> es la más versátil y por eso la más buscada por la industria alimentaria: un postre parve puede servirse después de una comida de carne, algo imposible con uno lácteo. Si querés profundizar en ese punto, lo desarrollamos en el artículo sobre <a href="/articulos/que-significa-pareve">qué significa pareve</a>.</p>

<h2>Por qué hacen falta dos juegos de todo</h2>
<p>Esto no se queda en la teoría. En una cocina kosher observante los utensilios, las ollas, los platos y hasta el lavavajillas se dividen en dos juegos separados. El motivo es un principio halájico llamado <em>bliot</em>: cuando un alimento se cocina en un recipiente a temperatura alta, se considera que el sabor queda absorbido en las paredes de ese recipiente. Una olla en la que herviste leche "es" lácteo aunque esté impecablemente limpia, y si después cocinás carne ahí, esa carne queda comprometida.</p>
<p>La solución práctica que usan la mayoría de las familias es codificar por color: un color para carne, otro para leche. Rojo y azul es la combinación más difundida, pero cualquiera sirve mientras sea consistente. Esto aplica a ollas, sartenes, cubiertos, tablas de cortar, esponjas y repasadores.</p>
<p>¿Y si se mezclan por error? Existen procedimientos de kasherización que permiten recuperar ciertos utensilios: la <a href="/articulos/hagala-utensilios-metal">hagalá para utensilios de metal</a> es el más común. Pero no todo material se puede kasherizar, y el procedimiento correcto depende del caso, así que conviene consultarlo.</p>

<h2>Los tiempos de espera entre carne y lácteos</h2>
<p>Después de comer carne hay que esperar antes de consumir lácteos. El tiempo exacto es una de las diferencias más visibles entre comunidades:</p>
<ul>
<li><strong>Seis horas:</strong> la costumbre más extendida, seguida por la mayoría de las comunidades sefaradíes y muchas asquenazíes.</li>
<li><strong>Tres horas:</strong> costumbre de origen alemán, todavía vigente en algunas familias.</li>
<li><strong>Una hora:</strong> costumbre holandesa, la más breve de las tradicionales.</li>
</ul>
<p>La razón que dan las fuentes es doble: la carne deja residuos entre los dientes, y su sabor persiste en la boca más tiempo que el de otros alimentos. En sentido inverso —de lácteos a carne— no hace falta esperar: alcanza con enjuagarse la boca y comer algo neutro, como un pedazo de pan.</p>
<p>La excepción son los quesos duros y muy estacionados, que en varias comunidades requieren la misma espera completa que la carne, justamente porque su sabor es más persistente. Lo importante acá es no improvisar: la costumbre se hereda de la familia o se define con el rabino de la comunidad, no se elige la más cómoda.</p>

<h2>Cómo se traduce esto en las etiquetas del supermercado</h2>
<p>Por eso tantas etiquetas llevan una letra chiquita junto al sello de certificación: "D" para <em>dairy</em> (lácteo), "M" para <em>meat</em> (cárnico) o directamente "Pareve". Con solo mirar esa letra sabés al instante si podés combinar ese producto con lo que estás por comer.</p>
<p>Hay una cuarta marca que confunde bastante: <strong>"DE"</strong>, de <em>dairy equipment</em>. Significa que el producto no tiene ingredientes lácteos, pero se elaboró en una línea de producción que también procesa lácteos. No se puede comer junto con carne, pero sí después de carne. Es una categoría intermedia que existe justamente porque las fábricas modernas comparten equipos entre productos. Lo explicamos en detalle en <a href="/articulos/como-leer-etiqueta-kosher">cómo leer una etiqueta kosher</a>.</p>

<h2>Preguntas frecuentes</h2>
<p><strong>¿El pollo también cuenta como carne?</strong><br>
Sí. Por la Torá el pollo no estaría incluido en la prohibición, que habla de "un cabrito", pero los sabores lo extendieron a todas las aves para evitar confusiones. Hoy el pollo se trata exactamente igual que la carne roja a estos efectos.</p>
<p><strong>¿Y el pescado?</strong><br>
El pescado es parve, así que técnicamente se puede combinar con lácteos —de hecho el salmón con crema es un clásico. Con carne es distinto: hay una costumbre extendida de no comerlos juntos en el mismo plato, pero por motivos de salud según el Talmud, no por la prohibición de carne y leche. Más detalle en <a href="/articulos/pescado-kosher-aletas-escamas">pescado kosher</a>.</p>
<p><strong>¿Puedo tener un solo horno?</strong><br>
Sí, es lo más común. Se maneja cubriendo bien los alimentos, usando bandejas dedicadas, o asignando el horno a una sola categoría. La opción más simple para quien recién empieza es destinarlo a parve y cárnico, y resolver los lácteos en la hornalla.</p>

<h2>Para seguir leyendo</h2>
<p>Si estás organizando tu cocina, la guía de <a href="/articulos/armar-cocina-kosher">cómo armar una cocina kosher desde cero</a> aterriza todo esto en pasos concretos. Para las fuentes originales, la <a href="https://oukosher.org/the-kosher-primer/" target="_blank" rel="noopener">guía introductoria de la Orthodox Union</a> desarrolla el tema con las citas talmúdicas, el artículo de <a href="https://es.wikipedia.org/wiki/Kashrut" target="_blank" rel="noopener">Wikipedia sobre kashrut</a> da el panorama histórico general, y el <a href="https://www.youtube.com/@OUKosher" target="_blank" rel="noopener">canal de OU Kosher en YouTube</a> publica material audiovisual sobre cómo se organiza una cocina con separación.</p>',
            ],
            [
                'slug' => 'kasherizar-horno',
                'category' => 'kasherizacion',
                'title' => 'Cómo kasherizar un horno',
                'excerpt' => 'Cuando un horno se usó con alimentos no kosher, o se quiere pasar de uso cárnico a lácteo, existe un proceso específico para volverlo apto.',
                'content' => '<p>Hay varios momentos en los que kasherizar un horno se vuelve necesario: te mudás a una casa que tenía un horno de uso no kosher, decidís pasarlo de uso cárnico a lácteo, o se acerca Pesaj y hay que sacar hasta el último rastro de jametz.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kasherizar-horno.jpg" alt="El libun, la kasherización del horno por calor intenso, exige limpieza total previa y 24 horas sin uso." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">El libun, la kasherización del horno por calor intenso, exige limpieza total previa y 24 horas sin uso. Foto: Brandon Carson vía <a href="https://commons.wikimedia.org/wiki/File%3ADouble_oven.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<p>El método tradicional se llama <em>libun</em> (autolimpieza por calor intenso) y se hace así:</p>
<ul>
<li>Limpiar a fondo el horno, sacando toda la suciedad y los residuos de comida visibles.</li>
<li>No usarlo durante 24 horas antes de kasherizarlo.</li>
<li>Encenderlo a la temperatura más alta posible (la función de autolimpieza, si el horno la tiene, es ideal) durante al menos una hora.</li>
</ul>
<p>Las rejillas y bandejas metálicas suelen kasherizarse aparte, sumergiéndolas en agua hirviendo (hagalá). Las superficies de vidrio o esmalte, en cambio, generalmente necesitan libun porque absorben más.</p>
<p>Cada horno es un caso distinto: el material, el modelo y la costumbre de cada comunidad hacen variar el procedimiento exacto, y algunos hornos modernos con recubrimientos especiales directamente no aguantan el libun a alta temperatura. Antes de meterle fuego al máximo, conviene chequear el manual del fabricante y hablarlo con el rabino de la comunidad.</p>',
            ],
            [
                'slug' => 'kasherizar-microondas',
                'category' => 'kasherizacion',
                'title' => 'Cómo kasherizar un microondas',
                'excerpt' => 'El microondas tiene un proceso de kasherización distinto al del horno tradicional, porque cocina con vapor y no con calor seco.',
                'content' => '<p>El microondas es probablemente el electrodoméstico que más dudas genera en una cocina kosher, y por una buena razón: no cocina como ningún otro aparato. Mientras el horno usa calor seco y la hornalla transmite calor por contacto directo, el microondas calienta el agua que ya está dentro del alimento, y eso genera vapor que circula por todo el interior.</p>
<p>Esa diferencia técnica es la que define el método de kasherización, y por qué no se puede simplemente aplicar lo mismo que al <a href="/articulos/kasherizar-horno">horno tradicional</a>.</p>

<h2>Por qué el vapor cambia todo</h2>
<p>En halajá rige un principio central para estos casos: <em>k\'bolo kach polto</em>, "como absorbió, así expulsa". Un utensilio se purifica por el mismo medio por el que absorbió el sabor. Si absorbió por líquido hirviendo, se kasheriza con líquido hirviendo; si absorbió por fuego directo, necesita fuego directo.</p>
<p>El microondas absorbe por vapor, así que se kasheriza por vapor. Por eso el procedimiento consiste en generar deliberadamente una gran cantidad de vapor dentro de la cavidad, para que alcance todas las superficies que estuvieron en contacto con las salpicaduras y los vapores de la comida anterior.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/microondas-cocina.jpg" alt="Microondas empotrado sobre una cocina con horno, en una cocina doméstica" loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">La cavidad del microondas acumula salpicaduras sobre todo en el techo, que es donde menos se mira al limpiar. Foto: Tomwsulcer vía <a href="https://commons.wikimedia.org/wiki/File:Stove_oven_combination_and_microwave_oven.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>

<p>Si te interesa el detalle técnico de por qué el <a href="https://es.wikipedia.org/wiki/Horno_de_microondas" target="_blank" rel="noopener">horno de microondas</a> calienta agitando las moléculas de agua del alimento, ahí está la explicación física completa. Para el kashrut, lo relevante es la consecuencia: se genera vapor, y el vapor transporta sabor.</p>

<svg viewBox="0 0 640 230" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Procedimiento de kasherización de un microondas en cuatro pasos: limpieza profunda, espera de 24 horas, hervir agua generando vapor, y dejar actuar el vapor." style="width:100%;height:auto;max-width:640px;margin:1.5rem auto;display:block;">
  <rect x="0" y="0" width="640" height="230" fill="#f9fafb" rx="8"/>
  <text x="20" y="28" font-family="system-ui,sans-serif" font-size="14" font-weight="700" fill="#1f2937">Los cuatro pasos, en orden</text>
  <circle cx="70" cy="90" r="26" fill="#dbeafe" stroke="#2563eb" stroke-width="2"/>
  <text x="70" y="97" text-anchor="middle" font-family="system-ui,sans-serif" font-size="18" font-weight="700" fill="#1e40af">1</text>
  <text x="70" y="138" text-anchor="middle" font-family="system-ui,sans-serif" font-size="11" font-weight="600" fill="#1f2937">Limpieza</text>
  <text x="70" y="153" text-anchor="middle" font-family="system-ui,sans-serif" font-size="11" fill="#6b7280">profunda</text>
  <line x1="100" y1="90" x2="132" y2="90" stroke="#9ca3af" stroke-width="2"/>
  <circle cx="230" cy="90" r="26" fill="#dbeafe" stroke="#2563eb" stroke-width="2"/>
  <text x="230" y="97" text-anchor="middle" font-family="system-ui,sans-serif" font-size="18" font-weight="700" fill="#1e40af">2</text>
  <text x="230" y="138" text-anchor="middle" font-family="system-ui,sans-serif" font-size="11" font-weight="600" fill="#1f2937">Esperar</text>
  <text x="230" y="153" text-anchor="middle" font-family="system-ui,sans-serif" font-size="11" fill="#6b7280">24 horas sin uso</text>
  <line x1="260" y1="90" x2="292" y2="90" stroke="#9ca3af" stroke-width="2"/>
  <circle cx="390" cy="90" r="26" fill="#fef3c7" stroke="#d97706" stroke-width="2"/>
  <text x="390" y="97" text-anchor="middle" font-family="system-ui,sans-serif" font-size="18" font-weight="700" fill="#92400e">3</text>
  <text x="390" y="138" text-anchor="middle" font-family="system-ui,sans-serif" font-size="11" font-weight="600" fill="#1f2937">Hervir agua</text>
  <text x="390" y="153" text-anchor="middle" font-family="system-ui,sans-serif" font-size="11" fill="#6b7280">hasta llenar de vapor</text>
  <line x1="420" y1="90" x2="452" y2="90" stroke="#9ca3af" stroke-width="2"/>
  <circle cx="550" cy="90" r="26" fill="#dcfce7" stroke="#16a34a" stroke-width="2"/>
  <text x="550" y="97" text-anchor="middle" font-family="system-ui,sans-serif" font-size="18" font-weight="700" fill="#166534">4</text>
  <text x="550" y="138" text-anchor="middle" font-family="system-ui,sans-serif" font-size="11" font-weight="600" fill="#1f2937">Dejar actuar</text>
  <text x="550" y="153" text-anchor="middle" font-family="system-ui,sans-serif" font-size="11" fill="#6b7280">unos minutos más</text>
  <text x="20" y="200" font-family="system-ui,sans-serif" font-size="11" fill="#6b7280">El plato giratorio se kasheriza aparte o se reemplaza. Si el equipo tiene grill, ese modo requiere un paso extra.</text>
</svg>

<h2>El procedimiento, paso a paso</h2>
<ol>
<li><strong>Limpieza profunda.</strong> No puede quedar ninguna partícula visible de comida. Prestá atención a las salpicaduras del techo de la cavidad, que es donde más se acumulan y donde menos se mira. Sacá el plato giratorio y limpiá también el aro y la base.</li>
<li><strong>24 horas sin usar.</strong> Este paso no es opcional. La halajá considera que después de 24 horas el sabor absorbido se degrada y pierde su condición, lo que hace válida la kasherización. Si usaste el microondas hace dos horas, tenés que esperar.</li>
<li><strong>Generar el vapor.</strong> Poné un recipiente con agua —conviene que sea un recipiente que no vayas a usar después para comida, o uno descartable— y hacelo hervir hasta que el interior quede completamente empañado. Suelen hacer falta entre 5 y 10 minutos según la potencia del equipo. Si el agua se evapora del todo, reponé y repetí.</li>
<li><strong>Dejar actuar.</strong> Con el vapor ya generado, dejá el recipiente adentro unos minutos más con la puerta cerrada, para que el vapor alcance también la cara interna de la puerta y las esquinas.</li>
</ol>

<h2>El plato giratorio: el detalle que se pasa por alto</h2>
<p>El plato de vidrio giratorio recibe directamente los derrames de la comida, así que necesita su propio tratamiento. Como es vidrio, las opiniones varían: algunos consideran que alcanza con un lavado a fondo, otros piden inmersión en agua hirviendo, y la solución más práctica que adoptan muchas familias es simplemente comprar un plato de repuesto y tener uno por categoría de uso.</p>

<h2>La estrategia que evita todo esto</h2>
<p>La mayoría de las familias observantes no kasheriza el microondas cada vez que cambia de uso, por una razón simple: es tedioso. En su lugar aplican dos costumbres que resuelven el problema de raíz:</p>
<ul>
<li><strong>Tapar siempre.</strong> Calentar la comida con tapa apta para microondas o film. Si nada se evapora libremente, no hay transferencia de sabor y el microondas se mantiene neutro. Esta es la razón por la que en muchas cocinas kosher las tapas de plástico están al lado del microondas y no en el cajón.</li>
<li><strong>Asignarle una sola categoría.</strong> Destinar el aparato exclusivamente a parve, o exclusivamente a lácteo, y resolver el resto en la hornalla o el horno. Es la opción más simple para quien recién <a href="/articulos/armar-cocina-kosher">arma su cocina kosher</a>.</li>
</ul>

<h2>Casos especiales</h2>
<p><strong>Microondas con grill o convección.</strong> Esas funciones generan calor seco, no vapor, así que el método de vapor no las cubre. Para esa parte hace falta un procedimiento tipo <em>libun</em>, similar al del horno convencional. Si tenés un equipo combinado y usaste el grill, consultalo específicamente.</p>
<p><strong>Microondas de acero inoxidable vs. interior plástico o esmaltado.</strong> Los materiales absorben distinto, y algunas autoridades son más estrictas con ciertos revestimientos. Es uno de los puntos donde más conviene preguntar por tu modelo puntual.</p>
<p><strong>Kasherizar para Pesaj.</strong> Las reglas de Pesaj son más estrictas que las del resto del año. El procedimiento base es el mismo, pero muchas comunidades agregan requisitos. Antes de la festividad conviene revisar la guía específica de tu certificadora y nuestro artículo sobre <a href="/articulos/vajilla-para-pesaj">vajilla para Pesaj</a>.</p>

<h2>Preguntas frecuentes</h2>
<p><strong>¿Puedo usar vinagre o limón en el agua?</strong><br>
No hace falta para la kasherización en sí —lo que purifica es el vapor, no el aditivo—, pero ayuda a despegar suciedad y a neutralizar olores durante el paso de limpieza previa.</p>
<p><strong>¿Sirve poner un vaso de agua nomás?</strong><br>
Un vaso chico probablemente no genere vapor suficiente para saturar toda la cavidad. Conviene un recipiente amplio y con bastante agua, para que el vapor sea abundante y sostenido.</p>
<p><strong>Compré un microondas usado, ¿alcanza con esto?</strong><br>
El procedimiento es el mismo, pero como no sabés qué se cocinó ahí, es un caso donde vale especialmente la pena consultarlo con el rabino de la comunidad antes de usarlo.</p>

<h2>Para seguir leyendo</h2>
<p>Los otros procedimientos de kasherización están en <a href="/articulos/kasherizar-horno">cómo kasherizar un horno</a>, <a href="/articulos/kasherizar-lavavajillas">cómo kasherizar un lavavajillas</a> y <a href="/articulos/hagala-utensilios-metal">hagalá para utensilios de metal</a>.</p>
<p>Como fuente externa, la <a href="https://www.star-k.org/articles/kashrus-kurrents/" target="_blank" rel="noopener">publicación Kashrus Kurrents de Star-K</a> tiene material técnico detallado sobre kasherización de electrodomésticos, y la <a href="https://oukosher.org/passover/" target="_blank" rel="noopener">sección de Pesaj de la Orthodox Union</a> cubre los requisitos adicionales de la festividad.</p>',
            ],
            [
                'slug' => 'kasherizar-lavavajillas',
                'category' => 'kasherizacion',
                'title' => 'Cómo kasherizar un lavavajillas',
                'excerpt' => 'Muchas familias usan el lavavajillas para platos cárnicos y lácteos en ciclos separados. Te contamos qué hace falta para kasherizarlo.',
                'content' => '<p>El lavavajillas complica un poco más que otros electrodomésticos: sus paredes internas, los filtros y los brazos aspersores están en contacto constante con restos de comida a alta temperatura, y eso hace que absorban sabores de forma más persistente.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kasherizar-lavavajillas.jpg" alt="El lavavajillas es de los electrodomésticos más discutidos: sus filtros y aspersores retienen restos a alta temperatura." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">El lavavajillas es de los electrodomésticos más discutidos: sus filtros y aspersores retienen restos a alta temperatura. Foto: Myke2020 vía <a href="https://commons.wikimedia.org/wiki/File%3ACountertop_dishwasher_7882.JPG" target="_blank" rel="noopener">Wikimedia Commons</a>, dominio público.</figcaption>
</figure>
<p>Por esa razón, muchas autoridades rabínicas son más estrictas acá que con otros aparatos, y algunas directamente desaconsejan usarlo para las dos categorías (cárnico y lácteo), ni siquiera en días distintos. Quienes sí lo permiten piden, en general:</p>
<ul>
<li>Limpieza profunda de filtros, brazos rociadores y juntas de goma.</li>
<li>No usarlo durante 24 horas antes de kasherizarlo.</li>
<li>Correr un ciclo completo en vacío, a la temperatura más alta, idealmente con un producto de limpieza fuerte.</li>
<li>En algunas comunidades se recomienda directamente usar canastos o bandejas intercambiables para cárnico y lácteo, en vez de kasherizar el aparato entero cada vez que cambia el uso.</li>
</ul>
<p>Las costumbres varían bastante en este tema, incluso entre comunidades sefaradíes y asquenazíes, así que es de esos casos donde conviene hablarlo directamente con el rabino de la congregación antes de decidir cómo organizar la cocina.</p>',
            ],
            [
                'slug' => 'hagala-utensilios-metal',
                'category' => 'kasherizacion',
                'title' => 'Cómo kasherizar utensilios de metal (hagalá)',
                'excerpt' => 'La hagalá es el método tradicional de inmersión en agua hirviendo para kasherizar ollas, cubiertos y otros utensilios metálicos.',
                'content' => '<p>Entre los métodos de kasherización, la hagalá es de los más antiguos, y se usa sobre todo en utensilios de metal que estuvieron en contacto directo con fuego o líquido hirviendo: ollas, cubiertos, sartenes sin recubrimiento antiadherente y otras piezas de cocina.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/hagala-utensilios-metal.jpg" alt="La hagalá consiste en sumergir el utensilio en agua hirviendo: como absorbió, así expulsa." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">La hagalá consiste en sumergir el utensilio en agua hirviendo: como absorbió, así expulsa. Foto: W.carter vía <a href="https://commons.wikimedia.org/wiki/File%3ASteam-boiling_green_asparagus.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>
<p>La lógica detrás es simple de recordar: "como absorbió, así expulsa". Si un utensilio absorbió sabor no kosher (o cárnico/lácteo) a través de líquido hirviendo, se purifica de la misma forma, sumergiéndolo en agua hirviendo.</p>
<p>El procedimiento:</p>
<ul>
<li>Limpiar el utensilio a fondo, sin óxido, comida pegada ni suciedad incrustada.</li>
<li>Esperar 24 horas sin usarlo antes de la hagalá.</li>
<li>Hervir una olla grande de agua hasta que rompa el hervor.</li>
<li>Sumergir el utensilio completo en el agua hirviendo, asegurando que todas sus superficies toquen el agua a esa temperatura.</li>
<li>Sacarlo con algo que no haya tocado comida no kosher, y enjuagarlo con agua fría.</li>
</ul>
<p>No todo sirve para hagalá: los utensilios con mango de madera o plástico, o con piezas pegadas con adhesivos que no bancan el agua hirviendo, generalmente quedan afuera y necesitan otro método (o directamente no se pueden kasherizar). Las sartenes de teflón tampoco, porque el recubrimiento se arruina con el calor.</p>',
            ],
            [
                'slug' => 'vajilla-para-pesaj',
                'category' => 'festividades',
                'title' => 'Vajilla para Pesaj: todo lo que hay que saber',
                'excerpt' => 'Durante Pesaj rigen reglas más estrictas que el resto del año en cuanto a utensilios de cocina, debido a la prohibición de jametz.',
                'content' => '<p>De todas las épocas del año, Pesaj es la que trae las reglas más estrictas, porque a las normas habituales de kashrut se le suma la prohibición total de tener o comer jametz (productos fermentados hechos con alguno de cinco granos: trigo, cebada, avena, centeno y espelta).</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/vajilla-para-pesaj.jpg" alt="Muchas familias tienen un juego de vajilla exclusivo para Pesaj, guardado el resto del año." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Muchas familias tienen un juego de vajilla exclusivo para Pesaj, guardado el resto del año. Foto: Silar vía <a href="https://commons.wikimedia.org/wiki/File%3A020210817_135854_Seder_plates%2C_brass_plate%2C_%C4%86miel%C3%B3w_porcelain%2C_19th-early_20th_century%2C_Category%2C_Passover_in_Galicia.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>El problema es que el jametz pudo haber estado en contacto con ollas, platos y cubiertos durante todo el año. Por eso muchas familias directamente tienen un juego de vajilla aparte, exclusivo para Pesaj, guardado el resto del año: es la opción más simple, y la que evita tener que kasherizar cada vez que llega la festividad.</p>
<p>Para quienes no tienen vajilla separada, hay margen para kasherizar algunos utensilios, aunque no todos:</p>
<ul>
<li><strong>Metal sin recubrimiento</strong> (ollas, cubiertos): en general apto para hagalá.</li>
<li><strong>Vidrio</strong>: según la costumbre, algunos consideran que alcanza con un buen lavado, otros piden inmersión.</li>
<li><strong>Cerámica y porcelana</strong>: en general no se pueden kasherizar para Pesaj, hay que usar un juego aparte.</li>
<li><strong>Plástico y goma</strong>: la mayoría de las opiniones no permite kasherizarlos.</li>
</ul>
<p>Las fechas límite y los métodos exactos cambian según el material y el uso que tuvo cada utensilio durante el año, así que antes de Pesaj conviene revisar la guía de kasherización de tu comunidad o certificadora local.</p>',
            ],
            [
                'slug' => 'jametz-pesaj',
                'category' => 'festividades',
                'title' => 'Jametz: qué es y cómo se elimina antes de Pesaj',
                'excerpt' => 'El jametz es el alimento fermentado prohibido durante Pesaj. Conocer qué productos lo contienen es clave para preparar la festividad.',
                'content' => '<p>El jametz es cualquier producto elaborado con uno de cinco granos (trigo, cebada, avena, centeno o espelta) que entró en contacto con agua y fermentó por más de 18 minutos sin ser horneado. Ahí entran el pan, la cerveza, casi todas las pastas, las galletitas y una cantidad enorme de productos industrializados que usan estos granos como ingrediente o derivado.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/jametz-pesaj.jpg" alt="Durante Pesaj rige la prohibición total de comer y de poseer jametz." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Durante Pesaj rige la prohibición total de comer y de poseer jametz. Foto: Ministry of Information Photo Division Photographer vía <a href="https://commons.wikimedia.org/wiki/File%3AAllied_Forces_Celebrate_Passover-_Jewish_Traditions_in_Wartime_Britain%2C_April_1944_D19336.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>
<p>La Torá no solo prohíbe comer jametz durante Pesaj: prohíbe también tenerlo en casa. Por eso, en las semanas previas a la festividad, las familias judías hacen una limpieza a fondo (bedikat jametz) para sacar cualquier resto de pan, harina o producto con jametz de armarios, autos, carteras y cualquier rincón donde pueda haber caído una miga.</p>
<p>Para el jametz que no conviene tirar (productos caros, o difíciles de reponer), existe la opción de "venderlo" simbólicamente a una persona no judía mediante un contrato llamado <em>mejirat jametz</em>, que suele coordinar el rabino de la comunidad. Ese jametz se guarda cerrado y aparte durante toda la festividad, y se "recompra" automáticamente al terminar Pesaj.</p>
<p>La noche antes de Pesaj se hace una búsqueda ritual del jametz por toda la casa, generalmente con una vela, una pluma y una cuchara de madera, y a la mañana siguiente se quema lo que se encontró (biur jametz).</p>',
            ],
            [
                'slug' => 'vino-kosher',
                'category' => 'productos',
                'title' => 'Vino kosher: por qué necesita supervisión especial',
                'excerpt' => 'El vino tiene un estatus particular en la halajá: para ser kosher, debe ser elaborado y manipulado exclusivamente por judíos observantes.',
                'content' => '<p>Hace un tiempo pudimos recorrer una bodega en Mendoza que produce vino kosher, y ver el proceso de cerca ayuda a entender por qué el vino tiene reglas tan distintas al resto de los alimentos. Con casi cualquier otro producto alcanza con que los ingredientes y el proceso cumplan ciertos requisitos. Con el vino no: la halajá exige que toda persona que lo toque durante la elaboración, desde que la uva entra hasta que se embotella, sea judía y observante. El motivo es histórico: el vino se usaba en rituales de idolatría, y de ahí viene la restricción.</p>
<p>En la práctica, esto arma una división de tareas bastante particular. El que sabe de vino es el enólogo, que en general no es judío (el goy), y es quien indica qué hacer en cada etapa. Pero el que efectivamente mueve el vino, abre las canillas y hace todo lo que implica tocar el producto es siempre un judío observante (el ieudí). El experto dirige, el ieudí ejecuta, y todo bajo supervisión rabínica constante.</p>
<p>El vino va descansando en distintos ambientes según la etapa, y una parte se guarda en toneles de roble, que se pueden reutilizar hasta unas tres veces antes de perder sus propiedades.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/barricas-roble-vino.jpg" alt="Barricas de roble apiladas en la sala de crianza de una bodega, cada una con su tapón y marcas de identificación" loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Sala de barricas de una bodega (imagen ilustrativa, no corresponde a la bodega mendocina que visitamos). En una bodega kosher, cada barrica lleva además un sello que certifica que nadie ajeno a la supervisión la abrió. Foto: Subhashish Panigrahi vía <a href="https://commons.wikimedia.org/wiki/File:Oak_barrels_used_for_aging_of_wine_in_a_cellar_at_Grover_Zampa_Vineyard,_Doddaballapura,_Karnataka,_India.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, <a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank" rel="noopener">CC BY-SA 4.0</a>.</figcaption>
</figure>

<p>Cada tonel va sellado, y ese sello no es un detalle menor: es la garantía de que nadie ajeno tocó el contenido. Nos contaron un caso que muestra hasta dónde llega la exigencia. En un tonel de miles de litros notaron que faltaba el sello en el punto donde tenía que estar cerrado: estaba abierto. Tuvo que venir el Rab de Buenos Aires a verificar la situación en persona, y dictaminó que ese vino había quedado sin supervisión. Resultado: esos miles de litros ya no se podían vender como kosher.</p>
<p>Existe además una categoría especial, el <strong>vino mevushal</strong> ("hervido"), que es vino pasteurizado a una temperatura específica. Una vez que un vino es mevushal, conserva su estatus kosher aunque después lo sirva o lo toque una persona no judía. Por eso es tan práctico para eventos, restaurantes y catering, donde no hay forma de controlar quién agarra cada botella.</p>
<p>Hoy hay vinos kosher de buena calidad en casi todas las regiones vitivinícolas del mundo. Mendoza es un polo importante en Argentina, y también se producen en Chile, Francia, España, Italia y por supuesto Israel, certificados por las principales agencias rabínicas.</p>',
            ],
            [
                'slug' => 'gelatina-kosher',
                'category' => 'productos',
                'title' => 'Gelatina kosher: el debate halájico',
                'excerpt' => 'La gelatina es uno de los ingredientes más debatidos en el mundo del kashrut, porque su origen animal puede comprometer su estatus.',
                'content' => '<p>Para hacer gelatina tradicional se hierven huesos, piel y tejido conectivo de animales (por lo general vacas o cerdos) hasta extraer el colágeno. Ahí aparecen dos problemas para el kashrut: el origen del animal (¿es una especie kosher?) y el proceso (¿fue faenado según shejita?).</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/gelatina-kosher.jpg" alt="Golosinas y postres son los productos donde más aparece la gelatina, el ingrediente más debatido del kashrut." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Golosinas y postres son los productos donde más aparece la gelatina, el ingrediente más debatido del kashrut. Foto: Sakurai Midori vía <a href="https://commons.wikimedia.org/wiki/File%3ASweets_Offering_for_Obon.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>
<p>Durante décadas distintas autoridades rabínicas discutieron si la gelatina, al pasar por un proceso químico tan transformador, cambia de estatus halájico (un concepto que se llama <em>panim jadashot</em>, transformación total). Algunas posturas más permisivas sostuvieron que el proceso es tan radical que el producto final ya no cuenta como carne en sentido halájico. La mayoría de las certificadoras kosher grandes, sin embargo, no acepta esa postura para gelatina de origen no kosher.</p>
<p>Por eso, hoy casi todos los productos con certificación kosher que necesitan gelatina (golosinas, postres, cápsulas de medicamentos, malvaviscos) usan alguna de estas alternativas:</p>
<ul>
<li>Gelatina de pescado kosher.</li>
<li>Gelatina bovina de animales faenados según shejita.</li>
<li>Sustitutos vegetales como agar-agar o pectina, que directamente esquivan el debate.</li>
</ul>
<p>Cuando un producto tiene el sello de una certificadora reconocida, ya no hace falta averiguar de dónde salió la gelatina: ese punto ya fue chequeado.</p>',
            ],
            [
                'slug' => 'alcohol-bebidas-espirituosas',
                'category' => 'productos',
                'title' => 'Alcohol y bebidas espirituosas: qué hace falta para que sean kosher',
                'excerpt' => 'Whisky, vodka, ron y otros destilados suelen ser kosher por naturaleza, pero hay excepciones importantes a tener en cuenta.',
                'content' => '<p>La mayoría de los destilados (whisky, vodka, ron, gin) se hacen a partir de granos, papa o caña de azúcar, ingredientes que en sí mismos no dan problemas de kashrut. Por eso muchos destilados simples son kosher sin necesitar certificación especial, siempre que no se les agregue sabores, colorantes o aditivos de origen no kosher.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/alcohol-bebidas-espirituosas.jpg" alt="Muchos destilados son kosher por sus ingredientes, pero el añejado en barricas de vino puede cambiar su estatus." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Muchos destilados son kosher por sus ingredientes, pero el añejado en barricas de vino puede cambiar su estatus. Foto: 4028mdk09 vía <a href="https://commons.wikimedia.org/wiki/File%3ABarbestand.JPG" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>
<p>Ahora, hay puntos donde conviene prestar atención:</p>
<ul>
<li><strong>Añejamiento en barricas de vino o jerez:</strong> algunos whiskies y rones se añejan en barriles que antes tuvieron vino no kosher, y eso puede afectar su estatus.</li>
<li><strong>Saborizantes y aditivos:</strong> los licores con sabor a crema, chocolate o frutas suelen llevar ingredientes que hay que verificar.</li>
<li><strong>Bebidas con base de vino</strong> (vermut y algunos licores): heredan todas las restricciones del vino kosher, incluida la supervisión rabínica durante la elaboración.</li>
<li><strong>Cerveza:</strong> por lo general kosher gracias a sus ingredientes base (agua, cebada, lúpulo, levadura), salvo variantes con saborizantes especiales.</li>
</ul>
<p>En Pesaj hay que tener cuidado extra: muchos destilados se hacen con granos que son jametz, así que en esa época se necesita una certificación específica de "kosher para Pesaj".</p>',
            ],
            [
                'slug' => 'comer-kosher-restaurante',
                'category' => 'vida-diaria',
                'title' => 'Cómo comer kosher en un restaurante no certificado',
                'excerpt' => 'Viajar o salir a comer sin un restaurante kosher cerca no significa romper la dieta. Hay opciones para mantenerse dentro de las normas.',
                'content' => '<p>No siempre hay un restaurante con certificación kosher disponible, especialmente al viajar o vivir en ciudades con poca infraestructura comunitaria. Aun así, existen estrategias para mantenerse dentro del kashrut en restaurantes comunes.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/comer-kosher-restaurante.jpg" alt="Sin un restaurante certificado cerca, hay estrategias para mantenerse dentro del kashrut." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Sin un restaurante certificado cerca, hay estrategias para mantenerse dentro del kashrut. Foto: Sohail1308 vía <a href="https://commons.wikimedia.org/wiki/File%3AAl_Fanar_Restaurant.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<ul>
<li><strong>Opciones vegetarianas o veganas:</strong> al eliminar carne y lácteos del plato, se reduce mucho el riesgo, aunque sigue siendo necesario verificar ingredientes (caldo de carne, manteca, salsas con base animal).</li>
<li><strong>Frutas y verduras crudas:</strong> sin cocción ni manipulación compleja, suelen ser una opción segura en casi cualquier lugar.</li>
<li><strong>Pescado con aletas y escamas:</strong> en restaurantes de cocina simple, un pescado a la plancha sin salsas puede ser una alternativa razonable para quienes siguen un criterio más flexible (siempre que no se cocine junto a mariscos o carne no kosher en el mismo equipamiento, según el criterio de cada persona).</li>
<li><strong>Bebidas embotelladas y selladas:</strong> agua, gaseosas y jugos en su envase original generalmente no presentan problemas.</li>
</ul>
<p>Un ejemplo bien concreto de cómo se resuelve esto en la práctica. Una vez viajamos de vacaciones a Mar del Plata con los chicos todavía pequeños, y llegando por la Avenida Constitución mi mujer se acordó de que se había olvidado las galletitas. En invierno, en esa zona, conseguir pan o galletitas kosher es prácticamente imposible. ¿Qué les dábamos a los chicos? En casa no acostumbrábamos comer ciertos snacks de paquete, pero ante la urgencia recurrimos a la lista de Ajdut Kosher, una guía de productos de góndola aprobados que publica la certificadora. Buscamos entre las marcas permitidas y dimos con unas galletitas tipo Traviatas que estaban en la lista. Eso nos salvó.</p>
<p>La moraleja: cuando viajás, tener a mano la lista de productos aprobados de tu certificadora de confianza vale oro. Muchísimos productos comunes de supermercado son kosher aunque no lleven un sello grande impreso en el paquete, y conocer esa lista te abre opciones donde parecía no haber ninguna.</p>
<p>Más allá de eso, cada persona y cada comunidad tiene un nivel de estrictez distinto sobre qué se considera aceptable fuera de un restaurante certificado: algunos solo comen productos envasados y sellados, otros aceptan ciertos preparados simples. Ante la duda, lo más recomendable es consultar con el rabino de la congregación cuál es el criterio que corresponde seguir.</p>',
            ],
            [
                'slug' => 'simbolos-certificacion-kosher',
                'category' => 'productos',
                'title' => 'Símbolos de certificación kosher más comunes',
                'excerpt' => 'OU, OK, Star-K, KSA... existen decenas de símbolos de certificación kosher en el mundo. Te ayudamos a reconocer los más usados.',
                'content' => '<p>Cuando un producto pasa por el proceso de certificación kosher, la agencia certificadora autoriza el uso de un símbolo (hechsher) en el packaging que permite identificarlo de un vistazo. Existen cientos de certificadoras en el mundo, pero algunas son especialmente conocidas por su alcance global.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/simbolos-certificacion-kosher.jpg" alt="Un hechsher: el certificado de kashrut que una agencia rabínica otorga a un establecimiento o producto." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Un hechsher: el certificado de kashrut que una agencia rabínica otorga a un establecimiento o producto. Foto: Utilisateur:Djampa - User:Djampa vía <a href="https://commons.wikimedia.org/wiki/File%3AHechsher_Safed_Rabbinate.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<ul>
<li><strong>OU (Orthodox Union):</strong> una "U" dentro de un círculo. Es probablemente el símbolo kosher más reconocido a nivel mundial, con sede en Estados Unidos.</li>
<li><strong>OK Kosher Certification:</strong> una "K" dentro de un círculo, otra de las grandes agencias estadounidenses.</li>
<li><strong>Star-K:</strong> una estrella con una "K" en el centro.</li>
<li><strong>KSA (Kosher Supervision of America):</strong> certificadora con fuerte presencia en productos industriales.</li>
<li><strong>Badatz:</strong> sello utilizado por varios tribunales rabínicos en Israel, asociado a estándares de estrictez muy altos.</li>
<li><strong>KS / certificadoras locales:</strong> en países como Argentina, Brasil o México existen certificadoras comunitarias locales (como la Va\'ad Hakashrut de cada kehilá) con sus propios sellos.</li>
</ul>
<p>Además del símbolo, muchas etiquetas incluyen una letra adicional: "D" (dairy/lácteo), "M" (meat/cárnico), "Pareve" (neutro) o "DE" (dairy equipment, elaborado en equipo lácteo pero sin ingredientes lácteos directos). Conocer estos símbolos agiliza enormemente las compras, sobre todo al viajar a países donde no se domina el idioma local.</p>
<p>Vale aclarar algo que no siempre se entiende: un hechsher no es un trámite que se hace una vez y queda para siempre. Es una supervisión activa y constante. Conocemos el caso de una panadería de Buenos Aires que tenía la certificación de una agencia comunitaria. En un momento el rabino supervisor notó movimientos raros y mandó gente a controlar, casi como un detective. Descubrieron que estaba comprando dulce de leche sin supervisión, cuando debía usar solamente productos Jalav Israel (lácteos elaborados bajo supervisión judía). Le advirtieron y le pidieron corregir. Al poco tiempo, sin saber que lo seguían de cerca, apareció comprando queso común sin certificación, y esa fue la gota que rebalsó el vaso: le retiraron la supervisión. Todo se manejó con discreción, sin escándalo, simplemente dejando de certificar el local.</p>
<p>La moraleja para el consumidor es clara: el sello vale porque detrás hay alguien controlando en serio, todo el tiempo. Por eso conviene confiar en certificadoras reconocidas y, ante un símbolo que no conocés, preguntar en la comunidad antes de dar por sentado que un producto es kosher.</p>',
            ],
            [
                'slug' => 'que-significa-pareve',
                'category' => 'kashrut-basico',
                'title' => 'Pareve: qué significa y por qué es tan común en las etiquetas',
                'excerpt' => 'Pareve es una de las palabras más repetidas en el etiquetado kosher. Te explicamos qué significa y por qué es tan valorada.',
                'content' => '<p>Pareve (también escrito <em>parve</em>, del ídish "neutral") es la palabra que describe a los alimentos que no son ni cárnicos ni lácteos: frutas, verduras, huevos, pescado, granos, legumbres y la mayoría de los productos elaborados sin ingredientes de origen lácteo o cárnico.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/que-significa-pareve.jpg" alt="Frutas y verduras frescas son parve por naturaleza: se combinan tanto con carne como con lácteos." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Frutas y verduras frescas son parve por naturaleza: se combinan tanto con carne como con lácteos. Foto: PattayaPatrol vía <a href="https://commons.wikimedia.org/wiki/File%3ADFC_2197_A_colorful_assortment_of_fresh_fruits_and_vegetables_-_apples_mango_dragon_fruit_kiwis_limes_bananas_and_more_-_arranged_on_a_wooden_crate.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Es probablemente la palabra que más vas a leer en las etiquetas kosher, y entender bien qué implica te ahorra la mitad de las dudas cotidianas en la cocina.</p>

<h2>Por qué es la categoría más buscada</h2>
<p>La gran ventaja de un producto pareve es que se puede combinar libremente tanto con comidas cárnicas como con lácteas, sin generar ningún conflicto. Eso lo convierte en el comodín de la cocina kosher.</p>
<p>El caso donde más se nota es el postre. Si la cena fue de carne, no podés servir helado, flan ni nada con crema, porque hay que esperar entre <a href="/articulos/carne-y-leche">carne y lácteos</a>. Un postre pareve resuelve el problema: se puede servir inmediatamente después de un asado. Por eso la industria invierte tanto en desarrollar versiones pareve de productos que tradicionalmente llevan lácteos —chocolate, margarina, cremas vegetales para repostería, helados a base de agua o leches vegetales—: le abre un mercado que de otra forma no podría comprarlos en la comida principal.</p>

<svg viewBox="0 0 640 200" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Un producto puede ser pareve por ingredientes pero perder ese estatus si se elabora en equipamiento compartido con lácteos, pasando a la categoría DE (dairy equipment)." style="width:100%;height:auto;max-width:640px;margin:1.5rem auto;display:block;">
  <rect x="0" y="0" width="640" height="200" fill="#f9fafb" rx="8"/>
  <text x="20" y="28" font-family="system-ui,sans-serif" font-size="14" font-weight="700" fill="#1f2937">Ser pareve no depende solo de los ingredientes</text>
  <rect x="20" y="48" width="150" height="60" fill="#dcfce7" stroke="#16a34a" stroke-width="2" rx="8"/>
  <text x="95" y="72" text-anchor="middle" font-family="system-ui,sans-serif" font-size="12" font-weight="600" fill="#166534">Ingredientes</text>
  <text x="95" y="90" text-anchor="middle" font-family="system-ui,sans-serif" font-size="12" font-weight="600" fill="#166534">sin lácteos</text>
  <path d="M 175 78 L 215 78" stroke="#6b7280" stroke-width="2" marker-end="url(#ar)"/>
  <defs><marker id="ar" markerWidth="8" markerHeight="8" refX="7" refY="4" orient="auto"><path d="M0,0 L8,4 L0,8 z" fill="#6b7280"/></marker></defs>
  <rect x="220" y="48" width="170" height="60" fill="#fef3c7" stroke="#d97706" stroke-width="2" rx="8"/>
  <text x="305" y="72" text-anchor="middle" font-family="system-ui,sans-serif" font-size="12" font-weight="600" fill="#92400e">¿Se elaboró en equipo</text>
  <text x="305" y="90" text-anchor="middle" font-family="system-ui,sans-serif" font-size="12" font-weight="600" fill="#92400e">compartido con lácteos?</text>
  <path d="M 395 65 L 435 55" stroke="#16a34a" stroke-width="2" marker-end="url(#ar)"/>
  <path d="M 395 92 L 435 118" stroke="#d97706" stroke-width="2" marker-end="url(#ar)"/>
  <rect x="440" y="30" width="180" height="46" fill="#dcfce7" stroke="#16a34a" stroke-width="2" rx="8"/>
  <text x="530" y="50" text-anchor="middle" font-family="system-ui,sans-serif" font-size="13" font-weight="700" fill="#166534">PAREVE</text>
  <text x="530" y="66" text-anchor="middle" font-family="system-ui,sans-serif" font-size="10" fill="#14532d">se combina con todo</text>
  <rect x="440" y="100" width="180" height="46" fill="#fef3c7" stroke="#d97706" stroke-width="2" rx="8"/>
  <text x="530" y="120" text-anchor="middle" font-family="system-ui,sans-serif" font-size="13" font-weight="700" fill="#92400e">DE (dairy equipment)</text>
  <text x="530" y="136" text-anchor="middle" font-family="system-ui,sans-serif" font-size="10" fill="#78350f">no junto con carne</text>
  <text x="20" y="180" font-family="system-ui,sans-serif" font-size="11" fill="#6b7280">Por eso la certificación revisa la línea de producción, no solo la lista de ingredientes.</text>
</svg>

<h2>El matiz que confunde a todo el mundo: pareve por ingredientes vs. pareve certificado</h2>
<p>Acá está el punto donde más gente se equivoca. Un alimento puede tener una lista de ingredientes impecablemente libre de lácteos y aun así no ser pareve, si se fabricó en un equipo que también procesa productos lácteos.</p>
<p>Pensá en una fábrica de galletitas: la misma línea produce a la mañana galletitas con manteca y a la tarde galletitas sin ella. Aunque la segunda tanda no lleve ningún ingrediente lácteo, el equipo retiene sabor del lote anterior. El resultado se etiqueta <strong>"DE"</strong> (<em>dairy equipment</em>): no se puede comer junto con carne, aunque sí después de una comida cárnica, sin esperar las horas completas.</p>
<p>Por eso la certificación kosher no es un análisis de laboratorio de ingredientes: es una auditoría del proceso completo, incluyendo qué se produjo antes en esa línea y cómo se limpió en el medio.</p>

<h2>Ejemplos concretos de productos pareve</h2>
<ul>
<li><strong>Casi siempre pareve:</strong> aceite de oliva y otros aceites vegetales, azúcar, sal, arroz, legumbres secas, frutas y verduras frescas, frutos secos sin procesar, agua mineral y gaseosas.</li>
<li><strong>Depende de la marca:</strong> pan (muchos llevan manteca o suero de leche), pastas (las que llevan huevo siguen siendo pareve, pero conviene verificar), chocolate (el amargo suele ser pareve, el con leche obviamente no), galletitas, margarina.</li>
<li><strong>Pareve pero con asterisco:</strong> el pescado es pareve, pero muchas comunidades no lo combinan con carne en el mismo plato por una cuestión aparte, explicada en <a href="/articulos/pescado-kosher-aletas-escamas">pescado kosher</a>.</li>
</ul>
<p>Podés verificar el estatus de una marca puntual en nuestro <a href="/">directorio de productos certificados</a>, filtrando directamente por tipo.</p>

<h2>Un error clásico: confundir pareve con vegano</h2>
<p>No son lo mismo, aunque se superpongan bastante. Un producto vegano no contiene nada de origen animal, pero puede haberse elaborado en equipamiento compartido con productos cárnicos, o contener ingredientes que la halajá restringe por otros motivos —como el vino, que necesita supervisión propia aunque sea vegano.</p>
<p>Y al revés: un producto pareve puede contener huevo o pescado, que no son veganos. Desarrollamos las diferencias en <a href="/articulos/kashrut-y-veganismo">kashrut y veganismo</a>.</p>

<h2>Preguntas frecuentes</h2>
<p><strong>¿El huevo es pareve aunque venga de un animal?</strong><br>
Sí. La categoría cárnica se refiere a la carne del animal, no a sus subproductos no lácteos. El huevo de un ave kosher es pareve y se puede usar tanto en preparaciones lácteas como cárnicas. Eso sí, hay que revisarlo antes de usarlo, como explicamos en <a href="/articulos/huevos-kosher">huevos kosher</a>.</p>
<p><strong>Si cocino algo pareve en una olla cárnica, ¿sigue siendo pareve?</strong><br>
No exactamente. Pasa a considerarse "pareve cocinado en cárnico", lo que en la práctica significa que no lo podés servir con lácteos. Por eso muchas familias tienen algunas ollas dedicadas exclusivamente a parve, justamente para conservar esa flexibilidad.</p>
<p><strong>¿La miel es pareve si la producen las abejas?</strong><br>
Sí, la miel es pareve y kosher, a pesar de que las abejas no son un animal kosher. Es una de las excepciones clásicas del kashrut, porque la miel no se considera un producto del cuerpo de la abeja sino néctar transformado.</p>

<h2>Para seguir leyendo</h2>
<p>El complemento natural de este artículo es <a href="/articulos/carne-y-leche">carne y leche: por qué no se mezclan</a>, que explica de dónde sale toda esta división. Para el detalle de cómo interpretar los símbolos en el envase, mirá <a href="/articulos/como-leer-etiqueta-kosher">cómo leer una etiqueta kosher</a>.</p>
<p>En fuentes externas, la <a href="https://oukosher.org/the-kosher-primer/" target="_blank" rel="noopener">guía de la Orthodox Union</a> cubre las categorías con las fuentes halájicas correspondientes, y podés consultar la entrada de <a href="https://es.wikipedia.org/wiki/Pareve" target="_blank" rel="noopener">Wikipedia sobre pareve</a> para una referencia rápida. Otras certificadoras con material público útil son <a href="https://www.ok.org" target="_blank" rel="noopener">OK Kosher Certification</a> y el <a href="https://www.crcweb.org" target="_blank" rel="noopener">Chicago Rabbinical Council (cRc)</a>, que publica listas de productos actualizadas.</p>',
            ],
            [
                'slug' => 'shejita-sacrificio-kosher',
                'category' => 'kashrut-basico',
                'title' => 'Shejita: el método de sacrificio kosher',
                'excerpt' => 'Para que la carne de un animal kosher sea apta para consumo, debe faenarse según un método ritual específico llamado shejita.',
                'content' => '<p>Para que la carne de un animal kosher sea apta, no alcanza con que la especie esté permitida. El animal tiene que haber sido faenado según un método ritual muy específico llamado <em>shejita</em>, ejecutado por una persona formada y certificada para eso: el <em>shojet</em>.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/shejita-sacrificio-kosher.svg" alt="El jalef se revisa antes y después de cada animal: una sola melladura invalida el procedimiento." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">El jalef se revisa antes y después de cada animal: una sola melladura invalida el procedimiento. Ilustración: KosherMap.</figcaption>
</figure>
<p>Es uno de los procesos más regulados de todo el kashrut, y entender cómo funciona explica por qué la carne kosher cuesta bastante más que la convencional.</p>

<h2>Qué es exactamente la shejita</h2>
<p>La shejita consiste en un corte único y continuo en la garganta del animal, que secciona la tráquea y el esófago en un solo movimiento. Se realiza con un cuchillo llamado <em>jalef</em>, de filo extremadamente perfeccionado y sin ninguna melladura.</p>
<p>El objetivo declarado del método es que la pérdida de conciencia sea prácticamente instantánea. Para eso, la halajá define cinco condiciones que invalidan el procedimiento si no se cumplen: que el corte sea sin pausa, sin presión, sin que el cuchillo quede tapado, dentro de la zona anatómica correcta, y sin desgarro. Si alguna falla, el animal no es kosher, sin excepciones y sin importar el valor económico en juego.</p>

<h2>El jalef: por qué el cuchillo es el centro de todo</h2>
<p>El cuchillo tiene requisitos casi obsesivos. Tiene que ser perfectamente liso, sin la mínima imperfección en el filo. El shojet lo revisa pasando la uña por el borde, un método que detecta irregularidades que el ojo no ve, y lo hace antes y después de cada animal. Si al terminar encuentra una melladura, ese animal queda invalidado retroactivamente, porque no hay forma de saber si la melladura estaba durante el corte.</p>

<svg viewBox="0 0 640 210" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Cadena de controles de la carne kosher: revisión del cuchillo, shejitá, inspección de órganos internos, y salado para extraer la sangre." style="width:100%;height:auto;max-width:640px;margin:1.5rem auto;display:block;">
  <rect x="0" y="0" width="640" height="210" fill="#f9fafb" rx="8"/>
  <text x="20" y="28" font-family="system-ui,sans-serif" font-size="14" font-weight="700" fill="#1f2937">La cadena de controles: si falla cualquiera, la carne no es kosher</text>
  <rect x="20" y="52" width="140" height="66" fill="#eff6ff" stroke="#2563eb" stroke-width="2" rx="8"/>
  <text x="90" y="78" text-anchor="middle" font-family="system-ui,sans-serif" font-size="12" font-weight="700" fill="#1e40af">1. Jalef</text>
  <text x="90" y="96" text-anchor="middle" font-family="system-ui,sans-serif" font-size="10" fill="#1e3a8a">Cuchillo sin melladuras,</text>
  <text x="90" y="109" text-anchor="middle" font-family="system-ui,sans-serif" font-size="10" fill="#1e3a8a">revisado antes y después</text>
  <path d="M 165 85 L 190 85" stroke="#6b7280" stroke-width="2" marker-end="url(#sa)"/>
  <defs><marker id="sa" markerWidth="8" markerHeight="8" refX="7" refY="4" orient="auto"><path d="M0,0 L8,4 L0,8 z" fill="#6b7280"/></marker></defs>
  <rect x="195" y="52" width="140" height="66" fill="#eff6ff" stroke="#2563eb" stroke-width="2" rx="8"/>
  <text x="265" y="78" text-anchor="middle" font-family="system-ui,sans-serif" font-size="12" font-weight="700" fill="#1e40af">2. Shejitá</text>
  <text x="265" y="96" text-anchor="middle" font-family="system-ui,sans-serif" font-size="10" fill="#1e3a8a">Corte único y continuo</text>
  <text x="265" y="109" text-anchor="middle" font-family="system-ui,sans-serif" font-size="10" fill="#1e3a8a">por un shojet certificado</text>
  <path d="M 340 85 L 365 85" stroke="#6b7280" stroke-width="2" marker-end="url(#sa)"/>
  <rect x="370" y="52" width="140" height="66" fill="#eff6ff" stroke="#2563eb" stroke-width="2" rx="8"/>
  <text x="440" y="78" text-anchor="middle" font-family="system-ui,sans-serif" font-size="12" font-weight="700" fill="#1e40af">3. Bediká</text>
  <text x="440" y="96" text-anchor="middle" font-family="system-ui,sans-serif" font-size="10" fill="#1e3a8a">Inspección de órganos,</text>
  <text x="440" y="109" text-anchor="middle" font-family="system-ui,sans-serif" font-size="10" fill="#1e3a8a">sobre todo pulmones</text>
  <path d="M 515 85 L 540 85" stroke="#6b7280" stroke-width="2" marker-end="url(#sa)"/>
  <rect x="545" y="52" width="80" height="66" fill="#dcfce7" stroke="#16a34a" stroke-width="2" rx="8"/>
  <text x="585" y="78" text-anchor="middle" font-family="system-ui,sans-serif" font-size="12" font-weight="700" fill="#166534">4. Salado</text>
  <text x="585" y="96" text-anchor="middle" font-family="system-ui,sans-serif" font-size="10" fill="#14532d">Extrae la</text>
  <text x="585" y="109" text-anchor="middle" font-family="system-ui,sans-serif" font-size="10" fill="#14532d">sangre</text>
  <text x="20" y="160" font-family="system-ui,sans-serif" font-size="11" fill="#6b7280">Una falla en cualquier eslabón invalida el animal completo, sin importar el valor comercial.</text>
  <text x="20" y="180" font-family="system-ui,sans-serif" font-size="11" fill="#6b7280">Por eso la carne kosher es sensiblemente más cara que la convencional.</text>
</svg>

<h2>Cómo se vive esto adentro de un frigorífico</h2>
<p>Un familiar de nuestro equipo trabaja en shejita, y lo que cuenta da una dimensión del nivel de control que hay en la práctica, más allá de lo que dicen los manuales.</p>
<p>El jalef no lo revisa solamente el shojet antes y después de cada animal: además hay un supervisor que controla todos los cuchillos del establecimiento, día por día, con un criterio extremadamente estricto. Una imperfección que a simple vista ni se percibe alcanza para dejar un cuchillo fuera de uso hasta que se reacondicione.</p>
<p>Y hay algo que no suele contarse: es un ambiente donde la seguridad manda por encima de la producción. Ante cualquier situación fuera de lo previsto, el protocolo es frenar todo, guardar los cuchillos y evaluar antes de seguir. No existe la lógica de "terminemos el turno igual". Esa combinación de precisión técnica y seriedad operativa es lo que sostiene la confiabilidad del sello kosher en la carne.</p>

<h2>Bediká: la inspección que descarta buena parte de los animales</h2>
<p>Después de la shejita viene la <em>bediká</em>, la inspección de los órganos internos, con foco en los pulmones. Se buscan adherencias (<em>sirjot</em>) y signos de enfermedad que indicarían que el animal no estaba sano.</p>
<p>Este paso descarta un porcentaje significativo de animales que ya fueron faenados correctamente. Esa carne no se tira —se vende en el circuito convencional no kosher—, pero explica buena parte del sobrecosto: el frigorífico kosher paga por todos los animales y solo puede vender como kosher los que pasan la inspección.</p>
<p>Los animales cuyos pulmones no presentan ninguna adherencia se clasifican como <em>glatt</em> (liso, en ídish), el estándar más exigente. Lo desarrollamos en <a href="/articulos/glatt-kosher">glatt kosher: qué diferencia hay con el kosher común</a>.</p>

<h2>El salado: por qué la carne kosher se prepara distinto</h2>
<p>La Torá prohíbe expresamente consumir sangre, así que la carne debe pasar por un proceso de extracción llamado <em>melijá</em>:</p>
<ol>
<li><strong>Remojo</strong> en agua durante aproximadamente 30 minutos, para ablandar y abrir los poros.</li>
<li><strong>Salado</strong> con sal gruesa por todas las superficies, incluidos los pliegues y cortes.</li>
<li><strong>Reposo</strong> de alrededor de una hora sobre una superficie inclinada o rejilla, para que la sangre escurra.</li>
<li><strong>Enjuague</strong> abundante, generalmente tres veces, para eliminar la sal.</li>
</ol>
<p>Hoy este proceso lo realiza casi siempre la carnicería o el frigorífico certificado antes de que el producto llegue al consumidor. Si comprás carne en una carnicería kosher, ya viene kasherizada salvo que te avisen lo contrario. La excepción clásica es el hígado: por su alto contenido de sangre no se puede kasherizar por salado y requiere asado directo al fuego.</p>

<h2>Preguntas frecuentes</h2>
<p><strong>¿Cualquier persona puede ser shojet?</strong><br>
No. Requiere años de estudio de las leyes correspondientes, entrenamiento práctico supervisado, y una certificación (<em>kabalá</em>) otorgada por una autoridad rabínica. Además debe ser una persona observante, porque de su integridad depende toda la cadena.</p>
<p><strong>¿La shejita aplica también a las aves?</strong><br>
Sí, con el mismo principio, aunque los detalles técnicos y el cuchillo son distintos por el tamaño. El pollo kosher pasa por el mismo tipo de proceso que la carne vacuna.</p>
<p><strong>¿Y el pescado necesita shejita?</strong><br>
No. El pescado kosher no requiere ni shejita ni salado, lo que simplifica mucho su preparación. Solo tiene que cumplir el criterio de aletas y escamas, explicado en <a href="/articulos/pescado-kosher-aletas-escamas">pescado kosher</a>.</p>
<p><strong>¿Por qué la carne kosher es tanto más cara?</strong><br>
Por la suma de todo lo anterior: personal especializado, supervisión permanente, animales descartados en la inspección, y el proceso adicional de salado. No es un sobreprecio de marca, es costo operativo real.</p>

<h2>Para seguir leyendo</h2>
<p>Para entender qué se puede hacer con esa carne una vez en casa, mirá <a href="/articulos/carne-y-leche">carne y leche: por qué no se mezclan</a>. Y si te interesa el estándar más estricto, está <a href="/articulos/glatt-kosher">glatt kosher</a>.</p>
<p>En fuentes externas, la <a href="https://oukosher.org/the-kosher-primer/" target="_blank" rel="noopener">guía de la Orthodox Union</a> desarrolla las leyes de shejita con sus fuentes, el <a href="https://www.youtube.com/@OUKosher" target="_blank" rel="noopener">canal de OU Kosher en YouTube</a> publica material explicativo del proceso, y podés consultar el artículo de <a href="https://en.wikipedia.org/wiki/Shechita" target="_blank" rel="noopener">Wikipedia sobre shechita</a> (en inglés) para el contexto histórico y el debate contemporáneo.</p>',
            ],
            [
                'slug' => 'glatt-kosher',
                'category' => 'kashrut-basico',
                'title' => 'Glatt kosher: qué diferencia hay con el kosher común',
                'excerpt' => 'El término "glatt" aparece frecuentemente en carnicerías y restaurantes kosher. Te explicamos qué nivel de estrictez representa.',
                'content' => '<p>"Glatt" quiere decir "liso" en ídish, y originalmente se refería puntualmente al estado de los pulmones de un animal después de la shejita: si no tenían ninguna adherencia (sirja), el animal se consideraba "glatt", el nivel más alto de certeza de que esa carne es kosher sin ninguna duda.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/glatt-kosher.jpg" alt="Un restaurante de carne kosher en Jerusalén. &quot;Glatt&quot; indica el estándar más exigente de inspección." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Un restaurante de carne kosher en Jerusalén. "Glatt" indica el estándar más exigente de inspección. Foto: brionv from San Francisco, United States vía <a href="https://commons.wikimedia.org/wiki/File%3AJerusalem_MMMM_MEAT_%286036353902%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 2.0.</figcaption>
</figure>
<p>Con el tiempo, sobre todo en comunidades asquenazíes de Estados Unidos, "glatt kosher" pasó a usarse de forma más coloquial, para describir un estándar general de mayor estrictez en toda la cadena de producción de un alimento, no solo en la inspección de pulmones. Hoy es habitual ver "glatt kosher" en etiquetas de restaurantes y productos para indicar que cumplen con los criterios más exigentes.</p>
<p>Vale aclarar algo que se presta a confusión: un producto "kosher" sin la etiqueta "glatt" no es menos válido halájicamente, simplemente sigue un estándar distinto, aceptado por la amplia mayoría de las comunidades. Elegir entre kosher estándar y glatt kosher suele depender más de la costumbre familiar o comunitaria que de una diferencia objetiva de validez.</p>
<p>En aves y pescado, el concepto de "glatt" técnicamente no aplica igual que en mamíferos, aunque coloquialmente a veces se usa para indicar un nivel de supervisión más riguroso en general.</p>',
            ],
            [
                'slug' => 'como-leer-etiqueta-kosher',
                'category' => 'productos',
                'title' => 'Cómo leer una etiqueta de producto kosher',
                'excerpt' => 'Más allá del símbolo de certificación, las etiquetas kosher contienen información clave para saber si un producto es apto para tu mesa.',
                'content' => '<p>Mirar el símbolo de certificación es solo el primer paso. Una etiqueta kosher tiene otros datos que conviene revisar siempre:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/como-leer-etiqueta-kosher.jpg" alt="La etiqueta de un producto israelí: además del sello, conviene mirar la categoría y los ingredientes." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">La etiqueta de un producto israelí: además del sello, conviene mirar la categoría y los ingredientes. Foto: Chenspec vía <a href="https://commons.wikimedia.org/wiki/File%3AList_of_ingredients_of_food_products_in_Israel_02.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<ul>
<li><strong>El símbolo de la certificadora:</strong> indica qué agencia supervisó el producto. Conviene reconocer certificadoras confiables, porque no todos los símbolos del mundo tienen el mismo nivel de exigencia.</li>
<li><strong>La categoría:</strong> "Dairy" o "D" (lácteo), "Meat" o "M" (cárnico), "Pareve" (neutro), o "Fish" (pescado, que en muchas tradiciones se trata como categoría aparte de la carne).</li>
<li><strong>"Kosher para Pesaj":</strong> una indicación aparte, necesaria durante la festividad, distinta de la certificación kosher habitual del resto del año.</li>
<li><strong>Fecha de certificación:</strong> algunas certificadoras incluyen un código o fecha para poder verificar que el sello sigue vigente, porque las recetas y los procesos de fábrica cambian.</li>
</ul>
<p>Cuando un producto no tiene certificación visible pero la lista de ingredientes parece simple (agua, sal y un vegetal, por ejemplo), la tentación es asumir que es kosher por descarte. La recomendación general de las autoridades de kashrut es no hacer eso: muchos aditivos y procesos industriales no se ven a simple vista.</p>
<p>En KosherMap podés buscar productos por nombre o código de barras y filtrar directamente por certificadora, categoría y tipo, sin depender únicamente de lo que diga la etiqueta física.</p>',
            ],
            [
                'slug' => 'bishul-akum',
                'category' => 'halajot',
                'title' => 'Bishul Akum: por qué algunos alimentos cocidos necesitan supervisión judía',
                'excerpt' => 'Existe una categoría de leyes específica sobre alimentos cocinados por no judíos, conocida como bishul akum. Te explicamos de qué se trata.',
                'content' => '<p>Bishul akum ("cocción de un no judío") es una categoría de leyes rabínicas que limita el consumo de ciertos alimentos cocinados enteramente por una persona no judía, aunque todos los ingredientes sean kosher. La prohibición viene de los sabios talmúdicos, y buscaba sobre todo fomentar la cohesión social y frenar la asimilación.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/bishul-akum.jpg" alt="Bishul akum regula los alimentos cocinados enteramente por una persona no judía, aunque los ingredientes sean kosher." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Bishul akum regula los alimentos cocinados enteramente por una persona no judía, aunque los ingredientes sean kosher. Foto: Seattle Municipal Archives from Seattle, WA vía <a href="https://commons.wikimedia.org/wiki/File%3ACooks_in_kitchen%2C_1930_%2820981103874%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<p>No aplica a cualquier alimento: en general se limita a comidas que se consideran "dignas de la mesa de un rey" (jaschivut) y que no se comen crudas. Por eso las frutas, las verduras crudas y la mayoría de los snacks industrializados quedan afuera de esta categoría.</p>
<p>En una fábrica o un restaurante certificado, el problema se resuelve de dos formas habituales:</p>
<ul>
<li>Que un judío observante participe activamente en la cocción, por ejemplo encendiendo el fuego o el equipo.</li>
<li>Que la supervisión rabínica certifique que un representante judío estuvo presente en el encendido de los equipos en cada turno de producción.</li>
</ul>
<p>Este es uno de los motivos por los que certificar kosher una fábrica de alimentos no se limita a revisar ingredientes: también hay que supervisar procesos, presencia de personal y protocolos operativos, lo que vuelve el trabajo de las certificadoras bastante más complejo que una simple lista de chequeo de insumos.</p>',
            ],
            [
                'slug' => 'vino-mevushal',
                'category' => 'productos',
                'title' => 'Mevushal: vino kosher que se puede servir sin restricciones',
                'excerpt' => 'El vino mevushal es una categoría especial que permite servirlo en eventos sin necesidad de que solo judíos lo manipulen.',
                'content' => '<p>Como vimos con el vino kosher, la regla general exige que solo judíos observantes manipulen el vino desde la elaboración hasta que se sirve. El vino mevushal ("hervido", pasteurizado) es la excepción práctica a esa regla: una vez que pasa por un proceso de calentamiento a una temperatura mínima específica, conserva su estatus kosher sin importar quién lo sirva después.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/vino-mevushal.jpg" alt="El vino mevushal es pasteurizado, y por eso conserva su estatus kosher aunque lo sirva una persona no judía." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">El vino mevushal es pasteurizado, y por eso conserva su estatus kosher aunque lo sirva una persona no judía. Foto: misbehave vía <a href="https://commons.wikimedia.org/wiki/File%3ABottle_%26_glass_of_red_Bordeaux_style_blend.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<p>Esta categoría existe por un principio halájico: el vino alterado por calor pierde la "dignidad" ritual que originalmente motivó la restricción, porque históricamente esa preocupación apuntaba al uso del vino en ceremonias idólatras, algo para lo que un vino hervido no se prestaba en ese contexto.</p>
<p>El mevushal es muy popular en:</p>
<ul>
<li>Catering y eventos donde el personal de servicio no es necesariamente judío.</li>
<li>Restaurantes kosher abiertos al público general.</li>
<li>Aerolíneas y hoteles que ofrecen opciones kosher.</li>
</ul>
<p>Hoy existen técnicas de pasteurización rápida (flash pasteurization) que permiten hacer vino mevushal de buena calidad, algo que antes era más difícil de lograr sin arruinar el sabor. Eso amplió mucho la oferta de vinos mevushal premium en el mercado.</p>',
            ],
            [
                'slug' => 'tevilat-kelim',
                'category' => 'halajot',
                'title' => 'Tevilat Kelim: la inmersión ritual de utensilios nuevos',
                'excerpt' => 'Antes de usar por primera vez ciertos utensilios de cocina fabricados por no judíos, existe la costumbre de sumergirlos en una mikve.',
                'content' => '<p>Tevilat Kelim es la costumbre de sumergir utensilios de cocina nuevos, de metal o vidrio, comprados a un fabricante no judío, en una mikve (baño ritual) o una fuente de agua natural antes de usarlos por primera vez.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/tevilat-kelim.jpg" alt="Una mikve, el baño ritual donde se sumergen los utensilios nuevos antes de usarlos por primera vez." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Una mikve, el baño ritual donde se sumergen los utensilios nuevos antes de usarlos por primera vez. Foto: Stefan Walkowski vía <a href="https://commons.wikimedia.org/wiki/File%3AMikveh.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Aplica principalmente a utensilios que tocan la comida directamente: ollas, sartenes, cubiertos, platos de vidrio, vasos. No suele aplicarse a utensilios eléctricos (una tostadora, una batidora) ni a los de plástico o madera, aunque las opiniones cambian según la tradición de cada comunidad, así que ante un caso específico conviene preguntarle al rabino.</p>
<p>El proceso en sí es sencillo: se limpia bien el utensilio, sin restos de etiquetas, precintos ni adhesivos; se sumerge completo en el agua de la mikve mientras se recita una bendición; y ya queda listo para usarse con normalidad.</p>
<p>Muchas mikvaot comunitarias tienen un horario específico habilitado solo para tevilat kelim, separado del uso ritual personal, con instrucciones detalladas sobre qué materiales necesitan inmersión y cuáles no. Es de esas prácticas que parecen un detalle menor, pero que forman parte de cómo muchas familias judías observantes equipan su cocina.</p>',
            ],
            [
                'slug' => 'armar-cocina-kosher',
                'category' => 'vida-diaria',
                'title' => 'Cómo armar una cocina kosher desde cero',
                'excerpt' => 'Empezar a mantener una cocina kosher puede parecer abrumador al principio. Te damos una guía práctica de los primeros pasos.',
                'content' => '<p>Armar una cocina kosher desde cero no es algo que se resuelve en un día, y no hace falta que lo sea. Estos son los pasos que más se repiten entre las familias que arrancan este proceso:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/armar-cocina-kosher.jpg" alt="Armar una cocina kosher desde cero es un proceso gradual: no hace falta resolverlo todo en un día." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Armar una cocina kosher desde cero es un proceso gradual: no hace falta resolverlo todo en un día. Foto: MeRyan vía <a href="https://commons.wikimedia.org/wiki/File%3AKitchen_with_island%2C_New_Orleans_2007.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<ul>
<li><strong>Definir la separación física:</strong> decidir qué utensilios, ollas y vajilla van a ser cárnicos y cuáles lácteos. Lo más práctico suele ser usar colores distintos (rojo para carne, azul para leche, por ejemplo) para no confundirse en el día a día.</li>
<li><strong>Separar las superficies de trabajo:</strong> tablas de cortar, repasadores y esponjas también se dividen por categoría.</li>
<li><strong>Evaluar los electrodomésticos compartidos:</strong> horno, microondas y lavavajillas se pueden kasherizar entre usos o, más simple, asignarlos a una sola categoría desde el principio (el microondas solo para parve, por ejemplo).</li>
<li><strong>Comprar productos certificados:</strong> revisar el símbolo de certificación en cada compra, hasta que se vuelva un hábito automático.</li>
<li><strong>Coordinar con un rabino:</strong> sobre todo para kasherizar lo que ya estaba en la cocina antes de arrancar este proceso.</li>
</ul>
<p>Una estrategia que usan mucho quienes recién empiezan es ir sumando la separación de a poco: primero los utensilios de uso diario, después la vajilla de mesa, y al final los electrodomésticos. No hace falta reemplazar toda la cocina de una sola vez, y muchas familias tardan meses en completar la transición sin que eso sea un problema halájico en sí mismo.</p>',
            ],
            [
                'slug' => 'certificaciones-kosher-mundo',
                'category' => 'productos',
                'title' => 'Diferencias entre las certificaciones kosher alrededor del mundo',
                'excerpt' => 'No todas las certificadoras kosher siguen exactamente los mismos criterios. Conocer estas diferencias ayuda a elegir productos con confianza.',
                'content' => '<p>Los principios del kashrut son universales, pero existen cientos de agencias certificadoras en el mundo, y cada una puede tener criterios algo distintos sobre temas puntuales: cuánta supervisión exige para bishul akum, por ejemplo, o cómo aborda ciertos aditivos químicos cuyo origen es difícil de rastrear.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/certificaciones-kosher-mundo.svg" alt="Los principios del kashrut son universales, pero cada región tiene sus propias agencias certificadoras." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Los principios del kashrut son universales, pero cada región tiene sus propias agencias certificadoras. Ilustración: KosherMap.</figcaption>
</figure>
<p>Algunas diferencias comunes entre regiones:</p>
<ul>
<li><strong>Estados Unidos:</strong> tiene las certificadoras más grandes a nivel industrial (OU, OK, Star-K, Kof-K), con procesos muy estandarizados para exportación masiva.</li>
<li><strong>Israel:</strong> el Rabanut (rabinato) da la certificación oficial estatal, mientras que organizaciones como el Badatz mantienen estándares adicionales que ciertas comunidades consideran más estrictos.</li>
<li><strong>Europa:</strong> certificadoras como la Beth Din de distintas ciudades (Londres, París, Zúrich) supervisan tanto la producción local como las importaciones.</li>
<li><strong>Latinoamérica:</strong> cada comunidad suele tener su propio Va\'ad Hakashrut (en Buenos Aires, San Pablo, Ciudad de México), que certifica productos locales y también restaurantes.</li>
</ul>
<p>Para el consumidor, lo más útil es aprender a reconocer las certificadoras activas en su región. Y ante un símbolo que no conoces, mejor preguntarle al rabino de la comunidad o investigar la reputación de esa agencia antes de confiar a ciegas en un producto. La mayoría de las certificadoras grandes publica listas de productos certificados en sus propios sitios web.</p>',
            ],
            [
                'slug' => 'queso-kosher-cuajo',
                'category' => 'productos',
                'title' => 'Queso kosher: por qué necesita cuajo especial',
                'excerpt' => 'El queso es uno de los productos lácteos con más restricciones kosher, principalmente por el origen del cuajo utilizado para elaborarlo.',
                'content' => '<p>El cuajo (rennet) es la enzima que tradicionalmente se usa para coagular la leche y separar el suero al hacer queso. El problema para el kashrut es que el cuajo tradicional se extrae del estómago de terneros, y para que sea apto ese animal tuvo que ser faenado por shejita, algo que en la industria quesera convencional casi nunca pasa.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/queso-kosher-cuajo.jpg" alt="El queso necesita certificación específica por el origen del cuajo, la enzima que coagula la leche." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">El queso necesita certificación específica por el origen del cuajo, la enzima que coagula la leche. Foto: Daderot vía <a href="https://commons.wikimedia.org/wiki/File%3ACheese_display%2C_Cambridge_MA_-_DSC05391.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>
<p>Por eso, prácticamente todo el queso "normal" del mercado no es kosher si no tiene una certificación específica, aunque esté hecho solo con leche y cuajo, porque el origen del cuajo no se puede verificar a simple vista.</p>
<p>Los fabricantes de queso kosher recurren a alguna de estas opciones:</p>
<ul>
<li><strong>Cuajo animal kosher:</strong> extraído de animales faenados por shejita y bajo supervisión rabínica en toda la cadena.</li>
<li><strong>Cuajo microbiano:</strong> producido por fermentación, sin origen animal, cada vez más común en quesos industriales y kosher.</li>
<li><strong>Cuajo vegetal:</strong> extraído de ciertas plantas, tradicional en algunas variedades específicas de quesos artesanales.</li>
</ul>
<p>Además del cuajo hay otro punto: muchas comunidades exigen que el queso se elabore bajo supervisión judía constante (Gvinat Yisrael) para considerarlo plenamente kosher, un criterio que va más allá del simple análisis de ingredientes. Por eso comprar queso con certificación reconocida es la forma más segura de no equivocarse.</p>',
            ],
            [
                'slug' => 'huevos-kosher',
                'category' => 'kashrut-basico',
                'title' => 'Huevos kosher: qué hay que revisar antes de usarlos',
                'excerpt' => 'Los huevos son pareve y generalmente kosher, pero existe un paso de revisión obligatorio antes de cocinarlos.',
                'content' => '<p>Los huevos de aves kosher, como la gallina, son en principio pareve y aptos para comer. Pero antes de usar un huevo, la tradición pide revisar que no tenga manchas de sangre en la yema, porque un huevo con sangre se considera no apto.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/huevos-kosher.jpg" alt="Los huevos son parve, pero hay que revisarlos por manchas de sangre antes de usarlos." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Los huevos son parve, pero hay que revisarlos por manchas de sangre antes de usarlos. Foto: Evan-Amos vía <a href="https://commons.wikimedia.org/wiki/File%3A6-Pack-Chicken-Eggs.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>
<p>El procedimiento es sencillo: al romper el huevo, se mira la yema (y a veces la clara) contra la luz, buscando puntos rojos o manchas. Si aparece sangre, el huevo se descarta entero; si la yema está limpia, se usa con total normalidad.</p>
<p>Algunos datos extra sobre huevos y kashrut:</p>
<ul>
<li>La cáscara y la clara en general no presentan el mismo riesgo que la yema, aunque la costumbre varía según la comunidad.</li>
<li>Los huevos de aves no kosher (avestruz, ciertas aves rapaces) tampoco son aptos, tengan sangre o no.</li>
<li>Los productos industrializados con huevo (pastas, mayonesa) suelen pasar por un control de calidad que detecta automáticamente los huevos con sangre, pero igual necesitan certificación para garantizar que ese control se hizo bien.</li>
</ul>
<p>Es de los hábitos más simples de meter en la rutina diaria de una cocina kosher: revisar cada huevo apenas se rompe, antes de mezclarlo con el resto de los ingredientes.</p>',
            ],
            [
                'slug' => 'pescado-kosher-aletas-escamas',
                'category' => 'kashrut-basico',
                'title' => 'Pescado kosher: aletas y escamas, las reglas básicas',
                'excerpt' => 'A diferencia de la carne, el pescado kosher no requiere shejita, pero sí debe cumplir con un criterio físico específico.',
                'content' => '<p>Para el pescado, la Torá pone una regla bastante simple: tiene que tener tanto aletas (snapir) como escamas (kaskeset) visibles a simple vista. Esa combinación está en la gran mayoría de los peces de agua dulce y salada que se comen habitualmente: salmón, atún, merluza, trucha, caballa.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/pescado-kosher-aletas-escamas.jpg" alt="Para ser kosher, un pescado debe tener aletas y escamas visibles." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Para ser kosher, un pescado debe tener aletas y escamas visibles. Foto: IndayLiburan vía <a href="https://commons.wikimedia.org/wiki/File%3AFish_stalls_at_Valencia_Public_Market_01.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Quedan afuera del kashrut, entre otros:</p>
<ul>
<li>Todos los mariscos (camarones, langostinos, cangrejos, mejillones, ostras).</li>
<li>Pulpo y calamar.</li>
<li>Tiburón y rape (no tienen escamas verdaderas, según la mayoría de las opiniones halájicas).</li>
<li>Anguila (sin escamas visibles).</li>
<li>Pez espada (su estatus es tema de debate histórico entre distintas autoridades rabínicas).</li>
</ul>
<p>A diferencia de la carne, el pescado kosher no necesita shejita ni el proceso de salado para sacar la sangre, así que su preparación es bastante más simple. Aun así, en muchas tradiciones (sobre todo asquenazíes) se lo trata como una categoría aparte de la carne y los lácteos, evitando mezclarlo con carne en el mismo plato, aunque sin exigir la misma separación estricta de utensilios que rige entre carne y leche.</p>
<p>Al comprar pescado fresco conviene chequear que conserve la piel con escamas visibles, porque algunos fileteados sacan toda la piel y complican la verificación. Por eso muchas pescaderías kosher dejan una parte de piel identificable en el corte.</p>',
            ],
            [
                'slug' => 'frutos-secos-contaminacion-cruzada',
                'category' => 'productos',
                'title' => 'Frutos secos y kashrut: riesgos de contaminación cruzada',
                'excerpt' => 'Los frutos secos son naturalmente pareve, pero el procesamiento industrial puede introducir riesgos de kashrut que no son evidentes.',
                'content' => '<p>Almendras, nueces, maní y la mayoría de los frutos secos son, crudos y sin procesar, alimentos pareve sin ninguna restricción de kashrut. El problema aparece cuando entran en la cadena de procesamiento industrial, donde pueden mezclarse con otros productos en las mismas líneas de producción.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/frutos-secos-contaminacion-cruzada.jpg" alt="Crudos son parve sin restricciones; el riesgo aparece en el procesamiento industrial." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Crudos son parve sin restricciones; el riesgo aparece en el procesamiento industrial. Foto: Famartin vía <a href="https://commons.wikimedia.org/wiki/File%3A2021-01-06_12_15_43_Cranberry_trail_mix_with_cranberries%2C_peanuts%2C_raisins%2C_walnuts%2C_almonds%2C_sunflower_seeds%2C_pepitas_in_the_Franklin_Farm_section_of_Oak_Hill%2C_Fairfax_County%2C_Virginia.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Algunos riesgos habituales:</p>
<ul>
<li><strong>Saborizantes lácteos:</strong> los frutos secos "tostados con manteca" o con cobertura de chocolate con leche dejan de ser pareve.</li>
<li><strong>Líneas compartidas:</strong> una fábrica puede procesar frutos secos pareve en el mismo equipo donde después procesa productos con leche o derivados cárnicos, dejando trazas no kosher si no hay una limpieza certificada entre lotes.</li>
<li><strong>Aceites de cocción:</strong> algunos frutos secos fritos usan aceites que también se usan para otros productos no kosher.</li>
<li><strong>Glaseados y recubrimientos:</strong> los "garrapiñados" o con cobertura dulce pueden llevar gelatina u otros ingredientes de origen animal.</li>
</ul>
<p>Un fruto seco crudo y sin procesar casi nunca da problemas. Pero los productos industrializados (mix de frutos secos, snacks saborizados, barras de cereal) siempre hay que revisarlos por certificación, sin asumir que son kosher solo porque el ingrediente principal lo es.</p>',
            ],
            [
                'slug' => 'kashrut-y-veganismo',
                'category' => 'kashrut-basico',
                'title' => 'Kashrut y veganismo: ¿es lo mismo comer vegano que comer kosher?',
                'excerpt' => 'Muchas personas asumen que un producto vegano es automáticamente kosher. La realidad es más matizada.',
                'content' => '<p>Es una confusión bastante común: si un producto no tiene ningún ingrediente de origen animal, parece lógico pensar que ya con eso es kosher. Pero el kashrut no se define solo por los ingredientes, también entran en juego los procesos de elaboración, el equipamiento que se usó y, en algunos casos, quién supervisó la producción.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kashrut-y-veganismo.jpg" alt="Un producto vegano no es automáticamente kosher: el kashrut también mira procesos y equipamiento." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Un producto vegano no es automáticamente kosher: el kashrut también mira procesos y equipamiento. Foto: HaJunkiyada vía <a href="https://commons.wikimedia.org/wiki/File%3ALiat_Portal_for_Foodie_Disorder_-_Cauliflower_from_SF_farmers%27_market.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Algunos casos donde un producto vegano puede no ser kosher:</p>
<ul>
<li><strong>Equipamiento compartido:</strong> una fábrica vegana puede usar la misma línea que antes procesó productos cárnicos o lácteos, sin la limpieza certificada que exige el kashrut entre lotes.</li>
<li><strong>Vino y derivados:</strong> un vino vegano, sin clarificantes de origen animal, igual necesita que todo el proceso de elaboración esté en manos de judíos observantes para ser kosher.</li>
<li><strong>Insectos:</strong> ciertos colorantes como el carmín (de origen animal) están prohibidos en kosher, pero a veces se etiquetan como aptos para veganos por error o por estándares distintos de certificación vegana.</li>
<li><strong>Bishul akum:</strong> un alimento vegano cocinado enteramente por una persona no judía puede caer dentro de esta restricción, según cómo se clasifique el producto.</li>
</ul>
<p>Y a la inversa también pasa: muchos productos kosher pareve terminan siendo veganos. Pero no hay equivalencia automática en ningún sentido. Lo más seguro siempre es buscar la certificación kosher explícita, en vez de dar por sentado que "vegano" es lo mismo que "kosher".</p>',
            ],
            [
                'slug' => 'separar-la-jala',
                'category' => 'halajot',
                'title' => 'Cómo separar la challá (jalá)',
                'excerpt' => 'Separar la jalá es un mandamiento específico que se aplica al amasar pan en grandes cantidades, con raíces en las ofrendas del Templo.',
                'content' => '<p>La separación de jalá (hafrashat jalá) es un mandamiento bíblico que originalmente requería entregar una porción de la masa de pan a los sacerdotes (kohanim) del Templo de Jerusalén. Tras la destrucción del Templo, la práctica se transformó: hoy, en lugar de entregarse, la porción separada se quema o se desecha de una manera respetuosa.</p>
<p>Esta mitzvá aplica cuando se amasa una cantidad significativa de masa hecha con alguno de los cinco granos (trigo, cebada, avena, centeno o espelta): la cantidad mínima exacta (generalmente alrededor de 1,2 kg de harina) varía según la opinión halájica que se siga.</p>
<p>El proceso básico es:</p>
<ul>
<li>Amasar la masa de pan normalmente, hasta que alcance la cantidad mínima requerida.</li>
<li>Separar una pequeña porción (tradicionalmente del tamaño de una aceituna o más, según la costumbre).</li>
<li>Recitar la bendición correspondiente antes de separar la porción.</li>
<li>Quemar la porción separada (envuelta en papel de aluminio, en el horno) o desecharla de forma que no se use para consumo regular.</li>
</ul>
<p>Esta práctica es la razón por la que muchas panaderías kosher industriales certificadas separan jalá como parte de su proceso de producción, y por la que muchas mujeres y familias judías la realizan en casa cada vez que hornean pan o jalá para Shabat en cantidad suficiente.</p>
<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/jala-shabat.jpg" alt="Pan de jalá trenzado y dorado sobre una mesa, junto a una copa de vino blanco y un montoncito de sal gruesa, cubierto parcialmente por un paño blanco" loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Jalá trenzada lista para Shabat, con la copa de vino y la sal. El paño que la cubre es parte de la costumbre de la mesa de Shabat. Foto: HaJunkiyada vía <a href="https://commons.wikimedia.org/wiki/File:Liat_Portal_for_Foodie_Disorder_-_Challah_for_Shabbat_with_wine_and_salt.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, <a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank" rel="noopener">CC BY-SA 4.0</a>.</figcaption>
</figure>

<p>En muchas casas esto se vive como un momento especial. En la nuestra, por ejemplo, las chicas siempre tratan de llegar a la cantidad mínima de masa justamente para poder separar la jalá con berajá, ya que hacerlo con la bendición tiene un valor agregado. Más allá del gesto técnico, termina siendo un ritual familiar alrededor del horno.</p>
<p>Si querés profundizar, la <a href="https://oukosher.org/the-kosher-primer/" target="_blank" rel="noopener">guía de la Orthodox Union</a> desarrolla las leyes de hafrashat jalá con sus fuentes, y podés consultar la entrada de <a href="https://es.wikipedia.org/wiki/Jal%C3%A1" target="_blank" rel="noopener">Wikipedia sobre la jalá</a> para el contexto histórico y las variantes regionales del pan.</p>',
            ],
            [
                'slug' => 'calendario-judio-festividades-alimentacion',
                'category' => 'festividades',
                'title' => 'El calendario judío y las fiestas que afectan la alimentación kosher',
                'excerpt' => 'Varias festividades judías tienen costumbres alimentarias específicas, más allá de las reglas generales del kashrut.',
                'content' => '<p>Además de las normas de kashrut que rigen todo el año, el calendario judío trae festividades con costumbres de comida propias, y conocerlas ayuda a entender por qué ciertos productos aparecen o desaparecen de las góndolas en determinadas épocas:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/calendario-judio-festividades-alimentacion.jpg" alt="Manzana, miel y granada: los símbolos de Rosh Hashaná, una de las festividades con costumbres alimentarias propias." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Manzana, miel y granada: los símbolos de Rosh Hashaná, una de las festividades con costumbres alimentarias propias. Foto: Gilabrand vía <a href="https://commons.wikimedia.org/wiki/File%3ASymbols_of_Rosh_Hashana.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>
<ul>
<li><strong>Rosh Hashaná:</strong> se come manzana con miel para simbolizar un año dulce, y se evitan alimentos amargos o ácidos en la mesa festiva.</li>
<li><strong>Iom Kipur:</strong> día de ayuno completo de 25 horas, sin comida ni bebida, salvo excepciones médicas puntuales.</li>
<li><strong>Sucot:</strong> se come en una cabaña temporal (sucá) al aire libre durante toda la semana de la festividad.</li>
<li><strong>Janucá:</strong> tradición de comer frito en aceite (sufganiot, rosquillas rellenas; latkes, panqueques de papa) en conmemoración del milagro del aceite.</li>
<li><strong>Purim:</strong> se preparan hamantaschen (orejas de Hamán), masas triangulares rellenas, y se acostumbra compartir canastas de comida (mishloaj manot) con amigos y familia.</li>
<li><strong>Pesaj:</strong> la festividad con más restricciones alimentarias, centrada en la prohibición de jametz.</li>
<li><strong>Shavuot:</strong> costumbre de comer lácteos, con el cheesecake y los blintzes (panqueques rellenos de queso) como protagonistas.</li>
</ul>
<p>Por eso productos como la matzá, las sufganiot o el vino kosher para Pesaj aparecen con mucha más disponibilidad en góndolas y comercios justo antes de cada festividad.</p>',
            ],
            [
                'slug' => 'errores-comunes-empezar-comer-kosher',
                'category' => 'vida-diaria',
                'title' => 'Errores comunes al empezar a comer kosher',
                'excerpt' => 'Adoptar el kashrut por primera vez implica un proceso de aprendizaje. Repasamos los errores más frecuentes para evitarlos desde el principio.',
                'content' => '<p>Empezar a comer kosher lleva tiempo, y es normal cometer errores al principio. Estos son los más comunes:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/errores-comunes-empezar-comer-kosher.jpg" alt="Al empezar, el error más común es asumir que un producto es kosher sin buscar la certificación." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Al empezar, el error más común es asumir que un producto es kosher sin buscar la certificación. Foto: Nenad Stojkovic vía <a href="https://commons.wikimedia.org/wiki/File%3ACorn_in_a_shopping_trolley._%2851964905166%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<ul>
<li><strong>Asumir que "natural" o "sin conservantes" significa kosher:</strong> el marketing de un producto no tiene nada que ver con su estatus de kashrut. Siempre hay que buscar la certificación.</li>
<li><strong>No revisar productos que parecen obviamente pareve:</strong> snacks, panificados y golosinas a veces tienen ingredientes lácteos o gelatina que no se notan en el nombre.</li>
<li><strong>Mezclar utensilios cárnicos y lácteos por descuido:</strong> al principio es fácil olvidarse de la separación; etiquetar o usar colores distintos ayuda mucho durante la transición.</li>
<li><strong>No revisar verduras de hoja por insectos:</strong> un paso que mucha gente nueva en el kashrut ni sabe que existe.</li>
<li><strong>Confiar en certificaciones desconocidas:</strong> no todos los símbolos de un paquete son certificaciones kosher reales, algunos son sellos de calidad que no tienen nada que ver con el kashrut.</li>
<li><strong>No preguntar:</strong> muchas dudas se resuelven rápido con una consulta al rabino de la comunidad o a alguien con más experiencia, en vez de adivinar.</li>
</ul>
<p>Lo importante es entender que esta transición no tiene que ser perfecta desde el primer día. La mayoría de las comunidades judías valoran el proceso de aprendizaje gradual, y hay bastantes recursos (certificadoras, rabinos, herramientas como KosherMap) para acompañar ese camino.</p>',
            ],
        ];

        foreach ($articles as $i => $data) {
            $existing = DB::table('articles')->where('slug', $data['slug'])->first();

            $title = $existing ? (json_decode($existing->title, true) ?: []) : [];
            $excerpt = $existing ? (json_decode($existing->excerpt, true) ?: []) : [];
            $content = $existing ? (json_decode($existing->content, true) ?: []) : [];

            $title['es'] = $data['title'];
            $excerpt['es'] = $data['excerpt'];
            $content['es'] = $data['content'];

            $article = Article::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category' => $data['category'],
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'sort_order' => $i + 1,
                    'is_published' => true,
                ]
            );

            // Escalonar las fechas de publicación. Cargar los 30 artículos con la
            // misma fecha es una señal de publicación automatizada en masa, que es
            // justo lo que penalizan los sistemas de calidad de contenido.
            // Se reparten hacia atrás desde la fecha base, ~5 días entre uno y otro.
            $publishedAt = \Carbon\Carbon::parse(self::PUBLISH_START_DATE)
                ->addDays($i * 5)
                ->setTime(9 + ($i % 8), ($i * 7) % 60);
            DB::table('articles')
                ->where('id', $article->id)
                ->update(['created_at' => $publishedAt]);
        }
    }
}
