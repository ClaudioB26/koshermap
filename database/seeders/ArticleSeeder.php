<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'slug' => 'insectos-frutas-verduras',
                'category' => 'halajot',
                'title' => 'Insectos en frutas y verduras: cómo revisarlas correctamente',
                'excerpt' => 'La Torá prohíbe comer insectos, por lo que revisar frutas y verduras antes de consumirlas es una parte central de mantener una cocina kosher.',
                'content' => '<p>La prohibición de comer insectos (sheratzim) es una de las más estrictas de la Torá: a diferencia de otras prohibiciones alimentarias, comer un solo insecto puede constar como múltiples transgresiones. Por eso, revisar frutas y verduras antes de cocinarlas o servirlas es un paso ineludible en una cocina kosher.</p>
<p>Las verduras de hoja como la lechuga, la espinaca, el brócoli y la coliflor son las que más cuidado requieren, porque los pulgones y otros insectos pequeños se esconden entre las hojas y son difíciles de detectar a simple vista. La técnica habitual incluye separar las hojas, lavarlas individualmente bajo un chorro de agua y revisarlas a contraluz.</p>
<ul>
<li><strong>Verduras de hoja:</strong> separar hoja por hoja y lavar bajo agua corriente, revisando ambos lados.</li>
<li><strong>Coliflor y brócoli:</strong> remojar en agua con un poco de jabón o vinagre para que los insectos se desprendan de los racimos.</li>
<li><strong>Frutillas y frambuesas:</strong> remojar en agua salada o con vinagre antes de enjuagar.</li>
<li><strong>Legumbres secas (lentejas, garbanzos):</strong> extender sobre una superficie clara y revisar antes de cocinar.</li>
</ul>
<p>Muchas certificadoras kosher ofrecen guías específicas, ilustradas, de cómo revisar cada tipo de verdura según la región donde fue cultivada, porque la prevalencia de insectos varía según el clima y el método de cultivo. Hoy también existen verduras "pre-revisadas" o cultivadas bajo supervisión específica para minimizar la infestación, lo que simplifica mucho el proceso en la cocina diaria.</p>',
            ],
            [
                'slug' => 'carne-y-leche',
                'category' => 'halajot',
                'title' => 'Carne y leche: por qué no se mezclan',
                'excerpt' => 'La separación entre carne y leche es uno de los pilares más conocidos del kashrut. Te explicamos su origen, sus alcances y cómo se aplica en la práctica.',
                'content' => '<p>La prohibición de mezclar carne y leche se basa en un versículo que aparece tres veces en la Torá: "No cocinarás un cabrito en la leche de su madre". La tradición oral interpretó esta frase como una prohibición triple: no cocinar, no comer y no obtener ningún beneficio de una mezcla de carne y leche.</p>
<p>En la práctica, esto significa que los alimentos se dividen en tres categorías: <strong>cárnicos</strong> (carne y derivados), <strong>lácteos</strong> (leche y derivados) y <strong>parve</strong> (neutros, como frutas, verduras, huevos y pescado, que no son ni carne ni leche).</p>
<p>Una cocina kosher observante mantiene utensilios, ollas, platos y hasta lavavajillas separados para carne y para leche, ya que el calor y el uso repetido pueden transferir sabores y partículas entre superficies. Además, se exige un tiempo de espera entre comer carne y comer lácteos —que varía según la costumbre familiar, generalmente entre una y seis horas— mientras que de lácteos a carne alcanza con enjuagarse la boca y comer algo neutro.</p>
<p>Esta separación es la razón por la que muchas etiquetas de productos llevan las letras "D" (dairy/lácteo), "M" (meat/cárnico) o "Pareve" junto al símbolo de certificación: le permite al consumidor saber de inmediato en qué categoría entra el producto antes de combinarlo con otros alimentos.</p>',
            ],
            [
                'slug' => 'kasherizar-horno',
                'category' => 'kasherizacion',
                'title' => 'Cómo kasherizar un horno',
                'excerpt' => 'Cuando un horno se usó con alimentos no kosher, o se quiere pasar de uso cárnico a lácteo, existe un proceso específico para volverlo apto.',
                'content' => '<p>Kasherizar un horno es necesario en varias situaciones: cuando se compra una casa con un horno usado previamente de forma no kosher, cuando se quiere cambiar el uso de un horno (por ejemplo, de cárnico a lácteo) o antes de Pesaj, cuando se necesita eliminar todo rastro de jametz.</p>
<p>El método tradicional para hornos se llama <em>libun</em> (autolimpieza por calor intenso) y consiste en:</p>
<ul>
<li>Limpiar a fondo el horno, eliminando toda suciedad y residuos visibles de comida.</li>
<li>No usar el horno durante 24 horas antes de la kasherización.</li>
<li>Encender el horno a la temperatura más alta posible (idealmente usando la función de autolimpieza, si el horno la tiene) durante al menos una hora.</li>
</ul>
<p>Las rejillas y bandejas metálicas suelen poder kasherizarse aparte por inmersión en agua hirviendo (hagalá), mientras que las superficies de vidrio o esmalte generalmente requieren libun por ser materiales que absorben más.</p>
<p>Es importante consultar con un rabino antes de kasherizar un horno particular, ya que el procedimiento exacto puede variar según el material, el modelo y la costumbre (minhag) de cada comunidad. Algunos hornos modernos con recubrimientos especiales pueden no ser aptos para libun a alta temperatura, por lo que conviene revisar el manual del fabricante.</p>',
            ],
            [
                'slug' => 'kasherizar-microondas',
                'category' => 'kasherizacion',
                'title' => 'Cómo kasherizar un microondas',
                'excerpt' => 'El microondas tiene un proceso de kasherización distinto al del horno tradicional, porque cocina con vapor y no con calor seco.',
                'content' => '<p>A diferencia del horno convencional, el microondas calienta los alimentos generando vapor en su interior, lo que cambia el método de kasherización recomendado por la mayoría de las autoridades halájicas.</p>
<p>El proceso más común incluye:</p>
<ul>
<li>Limpiar minuciosamente el interior, eliminando toda partícula de comida visible, incluyendo el plato giratorio y las paredes.</li>
<li>No usar el microondas durante 24 horas antes de kasherizarlo.</li>
<li>Colocar un recipiente con agua dentro del microondas y encenderlo hasta que el agua hierva y genere suficiente vapor como para cubrir todas las superficies interiores, incluyendo la puerta.</li>
<li>Dejar que el vapor actúe sobre las paredes durante varios minutos.</li>
</ul>
<p>Muchas familias optan, además, por usar siempre una tapa o film apto para microondas al calentar comida, y reservar el aparato para un solo uso (cárnico, lácteo o parve) para evitar tener que kasherizarlo repetidamente. Los microondas con función de grill o convección pueden requerir un proceso adicional similar al del horno tradicional para esa función específica. Como con cualquier kasherización, es recomendable consultar con un rabino sobre el caso particular del modelo y material del aparato.</p>',
            ],
            [
                'slug' => 'kasherizar-lavavajillas',
                'category' => 'kasherizacion',
                'title' => 'Cómo kasherizar un lavavajillas',
                'excerpt' => 'Muchas familias usan el lavavajillas para platos cárnicos y lácteos en ciclos separados. Te contamos qué hace falta para kasherizarlo.',
                'content' => '<p>El lavavajillas plantea un desafío particular porque sus paredes internas, filtros y brazos aspersores están en contacto constante con restos de comida a alta temperatura, lo que puede absorber sabores de manera más persistente que otros electrodomésticos.</p>
<p>Por eso, muchas autoridades rabínicas son más estrictas con la kasherización de lavavajillas que con otros aparatos, y algunas directamente desaconsejan usarlo para ambas categorías (cárnico y lácteo), incluso en días distintos. Quienes sí lo permiten, generalmente exigen:</p>
<ul>
<li>Limpieza profunda de filtros, brazos rociadores y juntas de goma.</li>
<li>No usar el lavavajillas durante 24 horas antes de kasherizarlo.</li>
<li>Correr un ciclo completo en vacío, a la temperatura más alta posible, idealmente con un producto de limpieza fuerte.</li>
<li>En algunas comunidades, se recomienda usar canastos o bandejas separadas e intercambiables para cárnico y lácteo, en vez de kasherizar el aparato entero entre usos.</li>
</ul>
<p>Dado que las costumbres varían bastante en este tema —algunas comunidades sefaradíes y asquenazíes difieren notablemente—, es uno de los casos donde más vale la pena consultar directamente con el rabino de la congregación antes de definir cómo organizar la cocina.</p>',
            ],
            [
                'slug' => 'hagala-utensilios-metal',
                'category' => 'kasherizacion',
                'title' => 'Cómo kasherizar utensilios de metal (hagalá)',
                'excerpt' => 'La hagalá es el método tradicional de inmersión en agua hirviendo para kasherizar ollas, cubiertos y otros utensilios metálicos.',
                'content' => '<p>La hagalá es uno de los métodos de kasherización más antiguos y se usa principalmente en utensilios de metal que se calentaron directamente con fuego o líquido hirviendo, como ollas, cubiertos, sartenes (sin recubrimiento antiadherente) y algunas piezas de cocina.</p>
<p>El principio detrás de la hagalá es "como absorbió, así expulsa": si un utensilio absorbió sabor no kosher (o cárnico/lácteo) mediante líquido hirviendo, se purifica de la misma manera, sumergiéndolo en agua hirviendo.</p>
<p>El procedimiento básico es:</p>
<ul>
<li>Limpiar el utensilio a fondo, sin restos de óxido, comida pegada o suciedad incrustada.</li>
<li>Esperar 24 horas sin usar el utensilio antes de la hagalá.</li>
<li>Hervir una olla grande de agua hasta que rompa el hervor.</li>
<li>Sumergir completamente el utensilio en el agua hirviendo, asegurando que todas sus superficies entren en contacto con el agua a esa temperatura.</li>
<li>Retirarlo con un instrumento que no haya estado en contacto con comida no kosher, y enjuagarlo en agua fría.</li>
</ul>
<p>Los utensilios con mango de madera o plástico, o con piezas pegadas con adhesivos que no resisten el agua hirviendo, generalmente no son aptos para hagalá y necesitan un método distinto, o directamente no pueden kasherizarse. Las sartenes antiadherentes (teflón) tampoco suelen kasherizarse por hagalá, ya que el recubrimiento se daña con el calor.</p>',
            ],
            [
                'slug' => 'vajilla-para-pesaj',
                'category' => 'festividades',
                'title' => 'Vajilla para Pesaj: todo lo que hay que saber',
                'excerpt' => 'Durante Pesaj rigen reglas más estrictas que el resto del año en cuanto a utensilios de cocina, debido a la prohibición de jametz.',
                'content' => '<p>Pesaj es la festividad con las reglas alimentarias más estrictas del calendario judío, porque además de las normas habituales de kashrut, se suma la prohibición total de consumir o poseer jametz (productos fermentados de cinco granos: trigo, cebada, avena, centeno y espelta).</p>
<p>Como el jametz puede haber estado en contacto con ollas, platos y cubiertos durante todo el año, muchas familias optan por tener un juego de vajilla separado, exclusivo para Pesaj, guardado el resto del año. Esta es la opción más simple y la que evita tener que kasherizar cada año.</p>
<p>Quienes no tienen vajilla separada para Pesaj pueden kasherizar ciertos utensilios:</p>
<ul>
<li><strong>Metal sin recubrimiento</strong> (ollas, cubiertos): generalmente apto para hagalá.</li>
<li><strong>Vidrio</strong>: según la costumbre, algunos consideran que alcanza con un buen lavado, otros requieren inmersión.</li>
<li><strong>Cerámica y porcelana</strong>: en general, no se pueden kasherizar para Pesaj y se debe usar un juego aparte.</li>
<li><strong>Plástico y goma</strong>: la mayoría de las opiniones no permite kasherizarlos.</li>
</ul>
<p>Antes de Pesaj conviene consultar la guía específica de kasherización de la comunidad o certificadora local, ya que las fechas límite y los métodos exactos pueden variar según el tipo de material y el uso que tuvo el utensilio durante el año.</p>',
            ],
            [
                'slug' => 'jametz-pesaj',
                'category' => 'festividades',
                'title' => 'Jametz: qué es y cómo se elimina antes de Pesaj',
                'excerpt' => 'El jametz es el alimento fermentado prohibido durante Pesaj. Conocer qué productos lo contienen es clave para preparar la festividad.',
                'content' => '<p>El jametz es cualquier producto elaborado con uno de los cinco granos —trigo, cebada, avena, centeno o espelta— que entró en contacto con agua y fermentó (leudó) durante más de 18 minutos sin ser horneado. Esto incluye al pan, la cerveza, la mayoría de las pastas, galletitas y una enorme cantidad de productos industrializados que usan estos granos como ingrediente o derivado.</p>
<p>La Torá prohíbe no solo comer jametz durante Pesaj, sino también poseerlo. Por eso, en las semanas previas a la festividad, las familias judías realizan una limpieza profunda de la casa (bedikat jametz) para eliminar cualquier resto de pan, harina o productos con jametz de armarios, autos, carteras y cualquier rincón donde pudiera haber caído una miga.</p>
<p>Para el jametz que no se puede o no conviene tirar (por ejemplo, productos caros o de difícil reposición), existe la opción de "venderlo" simbólicamente a una persona no judía mediante un contrato llamado <em>mejirat jametz</em>, que suele coordinar el rabino de la comunidad. El jametz vendido se guarda cerrado y aparte durante la festividad y "se recompra" automáticamente al finalizar Pesaj.</p>
<p>La noche antes de Pesaj se realiza una búsqueda ritual de jametz por la casa (bedikat jametz), generalmente con una vela, una pluma y una cuchara de madera, seguida de la quema de lo encontrado (biur jametz) a la mañana siguiente.</p>',
            ],
            [
                'slug' => 'vino-kosher',
                'category' => 'productos',
                'title' => 'Vino kosher: por qué necesita supervisión especial',
                'excerpt' => 'El vino tiene un estatus particular en la halajá: para ser kosher, debe ser elaborado y manipulado exclusivamente por judíos observantes.',
                'content' => '<p>A diferencia de la mayoría de los alimentos, donde alcanza con que los ingredientes y el proceso cumplan ciertos requisitos, el vino kosher exige además que toda persona que lo toque durante su elaboración —desde la cosecha de la uva hasta el embotellado— sea judía y observante. Esta regla se originó históricamente para evitar que el vino se usara en rituales de idolatría.</p>
<p>Esto significa que una bodega que produce vino kosher debe operar bajo supervisión rabínica constante: los trabajadores no judíos pueden ayudar en tareas que no involucran tocar directamente el vino o el jugo de uva, pero el proceso central debe quedar en manos de personal judío observante.</p>
<p>Existe una categoría especial llamada <strong>vino mevushal</strong> (literalmente "hervido"), que es vino pasteurizado a una temperatura específica. Una vez mevushal, el vino mantiene su estatus kosher incluso si lo sirve o lo toca una persona no judía, lo que lo hace mucho más práctico para eventos, restaurantes y catering donde no se puede garantizar que solo judíos manipulen las botellas.</p>
<p>Hoy existen vinos kosher de calidad en prácticamente todas las regiones vitivinícolas del mundo, incluyendo Argentina, Chile, Francia, España, Italia y por supuesto Israel, certificados por las principales agencias rabínicas.</p>',
            ],
            [
                'slug' => 'gelatina-kosher',
                'category' => 'productos',
                'title' => 'Gelatina kosher: el debate halájico',
                'excerpt' => 'La gelatina es uno de los ingredientes más debatidos en el mundo del kashrut, porque su origen animal puede comprometer su estatus.',
                'content' => '<p>La gelatina tradicional se obtiene hirviendo huesos, piel y tejido conectivo de animales —generalmente vacas o cerdos— hasta extraer el colágeno. Esto plantea dos problemas para el kashrut: el origen del animal (¿es una especie kosher?) y el método de procesamiento (¿el animal fue faenado según la shejita?).</p>
<p>Durante décadas, distintas autoridades rabínicas debatieron si la gelatina, al pasar por un proceso químico tan transformador, cambia de estatus halájico (un concepto llamado <em>panim jadashot</em> o transformación total). Algunas opiniones más permisivas sostuvieron que el proceso es tan radical que el producto final ya no se considera carne en sentido halájico; la mayoría de las certificadoras kosher mainstream, sin embargo, no aceptan esta postura para gelatina de origen no kosher.</p>
<p>Por eso, hoy la gran mayoría de los productos con certificación kosher que requieren gelatina (golosinas, postres, cápsulas de medicamentos, malvaviscos) usan alternativas certificadas:</p>
<ul>
<li>Gelatina de pescado kosher.</li>
<li>Gelatina bovina de animales faenados según shejita.</li>
<li>Sustitutos vegetales como agar-agar o pectina, que evitan el debate por completo.</li>
</ul>
<p>Cuando un producto lleva el sello de una certificadora reconocida, ya no hace falta investigar el origen de la gelatina: la certificación garantiza que ese punto ya fue verificado.</p>',
            ],
            [
                'slug' => 'alcohol-bebidas-espirituosas',
                'category' => 'productos',
                'title' => 'Alcohol y bebidas espirituosas: qué hace falta para que sean kosher',
                'excerpt' => 'Whisky, vodka, ron y otros destilados suelen ser kosher por naturaleza, pero hay excepciones importantes a tener en cuenta.',
                'content' => '<p>La mayoría de los destilados —whisky, vodka, ron, gin— se elaboran a partir de granos, papa o caña de azúcar, ingredientes que en sí mismos no presentan problemas de kashrut. Por eso, muchos destilados simples son kosher sin necesidad de certificación especial, siempre que no se les agreguen sabores, colorantes o aditivos de origen no kosher.</p>
<p>Sin embargo, hay puntos de atención importantes:</p>
<ul>
<li><strong>Envejecimiento en barricas de vino o jerez:</strong> algunos whiskies y rones se añejan en barriles que antes contuvieron vino no kosher, lo que puede comprometer su estatus.</li>
<li><strong>Saborizantes y aditivos:</strong> licores con sabor a crema, chocolate o frutas suelen incluir ingredientes que requieren verificación.</li>
<li><strong>Bebidas con base de vino</strong> (como vermut o algunos licores): heredan todas las restricciones del vino kosher, incluyendo la necesidad de supervisión rabínica en su elaboración.</li>
<li><strong>Cerveza:</strong> generalmente kosher por sus ingredientes base (agua, cebada, lúpulo, levadura), salvo variantes con saborizantes especiales.</li>
</ul>
<p>Durante Pesaj, además, hay que prestar especial atención porque muchos destilados se elaboran con granos que constituyen jametz, por lo que se necesita una certificación específica "kosher para Pesaj" en esa época del año.</p>',
            ],
            [
                'slug' => 'comer-kosher-restaurante',
                'category' => 'vida-diaria',
                'title' => 'Cómo comer kosher en un restaurante no certificado',
                'excerpt' => 'Viajar o salir a comer sin un restaurante kosher cerca no significa romper la dieta. Hay opciones para mantenerse dentro de las normas.',
                'content' => '<p>No siempre hay un restaurante con certificación kosher disponible, especialmente al viajar o vivir en ciudades con poca infraestructura comunitaria. Aun así, existen estrategias para mantenerse dentro del kashrut en restaurantes comunes.</p>
<ul>
<li><strong>Opciones vegetarianas o veganas:</strong> al eliminar carne y lácteos del plato, se reduce mucho el riesgo, aunque sigue siendo necesario verificar ingredientes (caldo de carne, manteca, salsas con base animal).</li>
<li><strong>Frutas y verduras crudas:</strong> sin cocción ni manipulación compleja, suelen ser una opción segura en casi cualquier lugar.</li>
<li><strong>Pescado con aletas y escamas:</strong> en restaurantes de cocina simple, un pescado a la plancha sin salsas puede ser una alternativa razonable para quienes siguen un criterio más flexible (siempre que no se cocine junto a mariscos o carne no kosher en el mismo equipamiento, según el criterio de cada persona).</li>
<li><strong>Bebidas embotelladas y selladas:</strong> agua, gaseosas y jugos en su envase original generalmente no presentan problemas.</li>
</ul>
<p>Cada persona y cada comunidad tiene un nivel de estrictez distinto sobre qué se considera aceptable fuera de un restaurante certificado (algunos solo comen productos envasados y sellados, otros aceptan ciertos preparados simples). Ante la duda, lo más recomendable es consultar con el rabino de la congregación cuál es el criterio que corresponde seguir.</p>',
            ],
            [
                'slug' => 'simbolos-certificacion-kosher',
                'category' => 'productos',
                'title' => 'Símbolos de certificación kosher más comunes',
                'excerpt' => 'OU, OK, Star-K, KSA... existen decenas de símbolos de certificación kosher en el mundo. Te ayudamos a reconocer los más usados.',
                'content' => '<p>Cuando un producto pasa por el proceso de certificación kosher, la agencia certificadora autoriza el uso de un símbolo (hechsher) en el packaging que permite identificarlo de un vistazo. Existen cientos de certificadoras en el mundo, pero algunas son especialmente conocidas por su alcance global.</p>
<ul>
<li><strong>OU (Orthodox Union):</strong> una "U" dentro de un círculo. Es probablemente el símbolo kosher más reconocido a nivel mundial, con sede en Estados Unidos.</li>
<li><strong>OK Kosher Certification:</strong> una "K" dentro de un círculo, otra de las grandes agencias estadounidenses.</li>
<li><strong>Star-K:</strong> una estrella con una "K" en el centro.</li>
<li><strong>KSA (Kosher Supervision of America):</strong> certificadora con fuerte presencia en productos industriales.</li>
<li><strong>Badatz:</strong> sello utilizado por varios tribunales rabínicos en Israel, asociado a estándares de estrictez muy altos.</li>
<li><strong>KS / certificadoras locales:</strong> en países como Argentina, Brasil o México existen certificadoras comunitarias locales (como la Va\'ad Hakashrut de cada kehilá) con sus propios sellos.</li>
</ul>
<p>Además del símbolo, muchas etiquetas incluyen una letra adicional: "D" (dairy/lácteo), "M" (meat/cárnico), "Pareve" (neutro) o "DE" (dairy equipment, elaborado en equipo lácteo pero sin ingredientes lácteos directos). Conocer estos símbolos agiliza enormemente las compras, sobre todo al viajar a países donde no se domina el idioma local.</p>',
            ],
            [
                'slug' => 'que-significa-pareve',
                'category' => 'kashrut-basico',
                'title' => 'Pareve: qué significa y por qué es tan común en las etiquetas',
                'excerpt' => 'Pareve es una de las palabras más repetidas en el etiquetado kosher. Te explicamos qué significa y por qué es tan valorada.',
                'content' => '<p>"Pareve" (también escrito parve) describe a los alimentos que no son ni cárnicos ni lácteos: frutas, verduras, huevos, pescado, granos y la mayoría de los productos elaborados sin ingredientes de origen animal lácteo o cárnico.</p>
<p>La gran ventaja de un producto pareve es su flexibilidad: puede combinarse libremente tanto con comidas cárnicas como lácteas, sin generar ningún conflicto de kashrut. Por eso, muchas industrias alimenticias buscan activamente desarrollar versiones pareve de productos que tradicionalmente llevan lácteos —como chocolate, margarina o sustitutos de crema— para ampliar su mercado.</p>
<p>Es importante aclarar un matiz: un alimento puede ser pareve por ingredientes, pero perder ese estatus si se elaboró en equipamiento que también procesa lácteos o carne, dependiendo de las trazas que puedan quedar. Por eso la certificación no solo analiza ingredientes, sino también el equipo de producción y los procesos de limpieza entre lotes.</p>
<p>Algunos ejemplos comunes de productos pareve: aceite de oliva, pasta seca sin huevo, la mayoría de los panes (aunque algunos llevan manteca y pasan a ser lácteos), frutos secos sin procesar, y bebidas gaseosas. Revisar siempre la etiqueta es la única forma certera de confirmarlo, ya que la receta puede variar entre marcas o incluso entre presentaciones de la misma marca.</p>',
            ],
            [
                'slug' => 'shejita-sacrificio-kosher',
                'category' => 'kashrut-basico',
                'title' => 'Shejita: el método de sacrificio kosher',
                'excerpt' => 'Para que la carne de un animal kosher sea apta para consumo, debe faenarse según un método ritual específico llamado shejita.',
                'content' => '<p>La shejita es el método de sacrificio ritual judío, realizado por un shojet (matarife capacitado y certificado) usando un cuchillo extremadamente afilado y sin mellas, diseñado específicamente para producir un corte rápido y preciso en la garganta del animal, seccionando la tráquea y el esófago en un solo movimiento continuo.</p>
<p>El objetivo de este método es minimizar el sufrimiento del animal y producir una pérdida de conciencia prácticamente instantánea. El shojet inspecciona el cuchillo antes y después de cada faena para asegurarse de que no tenga ninguna imperfección, por mínima que sea, ya que cualquier irregularidad invalida el procedimiento.</p>
<p>Después de la shejita, se realiza una inspección (bedika) de los órganos internos del animal, especialmente los pulmones, para descartar enfermedades o adherencias que invalidarían la carne como kosher. Solo los animales que pasan esta inspección se consideran aptos.</p>
<p>Adicionalmente, la carne debe pasar por un proceso de salado (kashering) para extraer la sangre, ya que la Torá prohíbe consumir sangre. Esto se hace remojando la carne en agua, salándola y dejándola reposar antes de enjuagarla nuevamente —un proceso que hoy en día generalmente realiza la propia carnicería o frigorífico certificado antes de que el producto llegue al consumidor.</p>',
            ],
            [
                'slug' => 'glatt-kosher',
                'category' => 'kashrut-basico',
                'title' => 'Glatt kosher: qué diferencia hay con el kosher común',
                'excerpt' => 'El término "glatt" aparece frecuentemente en carnicerías y restaurantes kosher. Te explicamos qué nivel de estrictez representa.',
                'content' => '<p>"Glatt" significa "liso" en yiddish y originalmente se refería específicamente al estado de los pulmones de un animal tras la shejita: si los pulmones no presentaban ninguna adherencia (sirja), el animal se consideraba "glatt", el nivel más alto de certeza de que la carne es kosher sin lugar a dudas.</p>
<p>Con el tiempo, especialmente en comunidades asquenazíes de Estados Unidos, el término "glatt kosher" se extendió coloquialmente para describir un estándar general de mayor estrictez en toda la cadena de producción de un alimento, no solo en la inspección de pulmones. Hoy es común ver "glatt kosher" en etiquetas de restaurantes y productos para indicar que cumplen con los criterios más exigentes posibles.</p>
<p>Es importante remarcar que un producto "kosher" sin la etiqueta "glatt" no es menos válido halájicamente: simplemente sigue un estándar de certificación distinto, generalmente aceptado por la amplia mayoría de las comunidades. La elección entre kosher estándar y glatt kosher suele depender de la costumbre familiar o comunitaria, más que de una diferencia objetiva de validez.</p>
<p>En el caso de aves y pescado, el concepto de "glatt" técnicamente no aplica de la misma manera que en mamíferos, aunque coloquialmente a veces se usa para indicar un nivel de supervisión más riguroso en general.</p>',
            ],
            [
                'slug' => 'como-leer-etiqueta-kosher',
                'category' => 'productos',
                'title' => 'Cómo leer una etiqueta de producto kosher',
                'excerpt' => 'Más allá del símbolo de certificación, las etiquetas kosher contienen información clave para saber si un producto es apto para tu mesa.',
                'content' => '<p>Leer correctamente una etiqueta kosher va más allá de buscar el símbolo de certificación. Hay varios elementos que conviene revisar siempre:</p>
<ul>
<li><strong>El símbolo de la certificadora:</strong> identifica qué agencia supervisó el producto. Es importante reconocer certificadoras confiables, ya que no todos los símbolos del mundo tienen el mismo nivel de exigencia.</li>
<li><strong>La categoría:</strong> "Dairy" o "D" (lácteo), "Meat" o "M" (cárnico), "Pareve" (neutro), o "Fish" (pescado, que en muchas tradiciones se trata como categoría aparte de la carne).</li>
<li><strong>"Kosher para Pesaj":</strong> indicación adicional necesaria durante la festividad, distinta de la certificación kosher habitual del resto del año.</li>
<li><strong>Fecha de certificación:</strong> algunas certificadoras incluyen un código o fecha para verificar que el sello sigue vigente, ya que las recetas y procesos de fábrica pueden cambiar.</li>
</ul>
<p>Cuando un producto no tiene certificación visible pero el listado de ingredientes parece simple (por ejemplo, solo agua, sal y un vegetal), algunas personas optan por investigar más, pero la recomendación general de las autoridades de kashrut es no asumir que un producto es kosher solo por la apariencia de sus ingredientes: muchos aditivos y procesos industriales no son evidentes a simple vista.</p>
<p>En KosherMap podés buscar productos por nombre o código de barras y filtrar directamente por certificadora, categoría y tipo, para no depender únicamente de la etiqueta física.</p>',
            ],
            [
                'slug' => 'bishul-akum',
                'category' => 'halajot',
                'title' => 'Bishul Akum: por qué algunos alimentos cocidos necesitan supervisión judía',
                'excerpt' => 'Existe una categoría de leyes específica sobre alimentos cocinados por no judíos, conocida como bishul akum. Te explicamos de qué se trata.',
                'content' => '<p>Bishul akum (literalmente "cocción de un no judío") es una categoría de leyes rabínicas que restringe el consumo de ciertos alimentos cocinados enteramente por una persona no judía, incluso si todos los ingredientes son kosher. La prohibición fue establecida por los sabios talmúdicos, principalmente para fomentar la cohesión social y evitar la asimilación cultural.</p>
<p>Esta ley no aplica a todos los alimentos: generalmente se limita a alimentos que se consideran "dignos de servir en la mesa de un rey" (jaschivut) y que no se comen crudos. Por eso, frutas, verduras crudas y la mayoría de los snacks industrializados no entran en esta categoría.</p>
<p>Hay dos formas habituales de resolver el problema en un contexto de producción industrial o restaurantes certificados:</p>
<ul>
<li>Que un judío observante participe activamente en el proceso de cocción, por ejemplo, encendiendo el fuego o el equipo de cocción.</li>
<li>Que la supervisión rabínica certifique que un representante judío estuvo presente durante el encendido de los equipos de cocción en cada turno de producción.</li>
</ul>
<p>Esta es una de las razones por las que la certificación kosher de una fábrica de alimentos no se limita a revisar ingredientes: también supervisa procesos, presencia de personal y protocolos operativos del establecimiento, lo que hace que el trabajo de las certificadoras sea mucho más complejo que una simple lista de chequeo de insumos.</p>',
            ],
            [
                'slug' => 'vino-mevushal',
                'category' => 'productos',
                'title' => 'Mevushal: vino kosher que se puede servir sin restricciones',
                'excerpt' => 'El vino mevushal es una categoría especial que permite servirlo en eventos sin necesidad de que solo judíos lo manipulen.',
                'content' => '<p>Como vimos al hablar de vino kosher, la regla general exige que solo judíos observantes manipulen el vino desde la elaboración hasta el servido. El vino mevushal ("hervido" o pasteurizado) es una excepción práctica a esta regla: una vez que el vino pasa por un proceso de calentamiento a una temperatura mínima específica, conserva su estatus kosher sin importar quién lo sirva después.</p>
<p>Esta categoría existe gracias a un principio halájico según el cual el vino que fue alterado mediante calor pierde la "dignidad" ritual que originalmente motivó la restricción, ya que históricamente esa preocupación apuntaba al uso del vino en ceremonias idólatras —algo que un vino hervido no se prestaba a hacer en ese contexto.</p>
<p>El vino mevushal es muy popular en:</p>
<ul>
<li>Catering y eventos donde el personal de servicio no es necesariamente judío.</li>
<li>Restaurantes kosher abiertos al público general.</li>
<li>Líneas aéreas y hoteles que ofrecen opciones kosher.</li>
</ul>
<p>Hoy existen técnicas modernas de pasteurización rápida (flash pasteurization) que permiten producir vino mevushal de alta calidad, lo que históricamente era más difícil de lograr sin afectar el sabor del vino. Esto amplió mucho la oferta de vinos mevushal premium disponibles en el mercado.</p>',
            ],
            [
                'slug' => 'tevilat-kelim',
                'category' => 'halajot',
                'title' => 'Tevilat Kelim: la inmersión ritual de utensilios nuevos',
                'excerpt' => 'Antes de usar por primera vez ciertos utensilios de cocina fabricados por no judíos, existe la costumbre de sumergirlos en una mikve.',
                'content' => '<p>Tevilat Kelim es la práctica de sumergir utensilios de cocina nuevos —de metal o vidrio, comprados a un fabricante no judío— en una mikve (baño ritual) o una fuente de agua natural antes de usarlos por primera vez para alimentos.</p>
<p>Esta costumbre aplica principalmente a utensilios que entran en contacto directo con la comida: ollas, sartenes, cubiertos, platos de vidrio y vasos. No suele aplicarse a utensilios eléctricos (como una tostadora o una batidora) ni a utensilios de plástico o madera, aunque las opiniones varían según la tradición de cada comunidad, por lo que es recomendable consultar con un rabino sobre casos específicos.</p>
<p>El proceso en sí es simple: el utensilio se limpia bien (sin restos de etiquetas, precintos o adhesivos), se sumerge completamente en el agua de la mikve mientras se recita una bendición, y luego está listo para usarse normalmente.</p>
<p>Muchas mikvaot comunitarias tienen un horario específico habilitado para tevilat kelim, separado del uso ritual personal, así como instrucciones detalladas sobre qué materiales requieren inmersión y cuáles no. Es una de esas prácticas que, aunque parezca un detalle menor, forma parte integral de cómo muchas familias judías observantes equipan su cocina.</p>',
            ],
            [
                'slug' => 'armar-cocina-kosher',
                'category' => 'vida-diaria',
                'title' => 'Cómo armar una cocina kosher desde cero',
                'excerpt' => 'Empezar a mantener una cocina kosher puede parecer abrumador al principio. Te damos una guía práctica de los primeros pasos.',
                'content' => '<p>Armar una cocina kosher desde cero es un proceso gradual, y no hace falta resolverlo todo en un día. Estos son los pasos más comunes que siguen las familias que empiezan:</p>
<ul>
<li><strong>Definir la separación física:</strong> establecer qué utensilios, ollas y vajilla serán cárnicos y cuáles lácteos. Lo más práctico suele ser usar colores distintos (por ejemplo, rojo para carne, azul para leche) para evitar confusiones diarias.</li>
<li><strong>Separar las superficies de trabajo:</strong> tablas de cortar, repasadores y esponjas también deben dividirse por categoría.</li>
<li><strong>Evaluar electrodomésticos compartidos:</strong> horno, microondas y lavavajillas pueden kasherizarse entre usos o, más simple, asignarse a una sola categoría desde el principio (por ejemplo, microondas solo para parve).</li>
<li><strong>Comprar productos certificados:</strong> revisar el símbolo de certificación en cada compra, hasta que se vuelva un hábito automático.</li>
<li><strong>Coordinar con un rabino:</strong> especialmente para kasherizar elementos que ya estaban en la cocina antes de empezar este proceso.</li>
</ul>
<p>Una estrategia muy usada por quienes recién empiezan es ir incorporando la separación de a poco: primero los utensilios de uso diario, después la vajilla de mesa, y finalmente los electrodomésticos. No es necesario reemplazar toda la cocina de una vez, y muchas familias tardan meses en completar la transición sin que eso sea un problema halájico en sí mismo.</p>',
            ],
            [
                'slug' => 'certificaciones-kosher-mundo',
                'category' => 'productos',
                'title' => 'Diferencias entre las certificaciones kosher alrededor del mundo',
                'excerpt' => 'No todas las certificadoras kosher siguen exactamente los mismos criterios. Conocer estas diferencias ayuda a elegir productos con confianza.',
                'content' => '<p>Aunque los principios fundamentales del kashrut son universales, existen cientos de agencias certificadoras en el mundo, y cada una puede tener criterios ligeramente distintos sobre temas específicos —por ejemplo, qué nivel de supervisión exige para bishul akum, o cómo aborda ciertos aditivos químicos cuyo origen es difícil de rastrear.</p>
<p>Algunas diferencias comunes entre regiones:</p>
<ul>
<li><strong>Estados Unidos:</strong> tiene las certificadoras más grandes a nivel industrial (OU, OK, Star-K, Kof-K), con procesos muy estandarizados para exportación masiva.</li>
<li><strong>Israel:</strong> el Rabanut (rabinato) ofrece certificación oficial estatal, mientras que organizaciones como el Badatz mantienen estándares adicionales considerados más estrictos por ciertas comunidades.</li>
<li><strong>Europa:</strong> certificadoras como la Beth Din de distintas ciudades (Londres, París, Zúrich) supervisan tanto producción local como importaciones.</li>
<li><strong>Latinoamérica:</strong> cada comunidad suele tener su Va\'ad Hakashrut local (por ejemplo, en Buenos Aires, San Pablo o Ciudad de México), que certifica tanto productos locales como restaurantes.</li>
</ul>
<p>Para el consumidor, lo más importante es aprender a reconocer las certificadoras activas en su región y, ante la duda sobre un símbolo desconocido, consultar con el rabino de la comunidad o investigar la reputación de la agencia antes de confiar en un producto. La mayoría de las certificadoras grandes publican listas públicas de productos certificados en sus sitios web.</p>',
            ],
            [
                'slug' => 'queso-kosher-cuajo',
                'category' => 'productos',
                'title' => 'Queso kosher: por qué necesita cuajo especial',
                'excerpt' => 'El queso es uno de los productos lácteos con más restricciones kosher, principalmente por el origen del cuajo utilizado para elaborarlo.',
                'content' => '<p>El cuajo (rennet) es la enzima que se usa tradicionalmente para coagular la leche y separar el suero en la elaboración de queso. El problema desde el punto de vista del kashrut es que el cuajo tradicional se extrae del estómago de terneros, y para que sea apto, ese animal debe haber sido faenado mediante shejita (el método de sacrificio kosher) —algo que en la industria quesera convencional casi nunca ocurre.</p>
<p>Por eso, prácticamente todo el queso "regular" del mercado, aunque esté hecho solo con leche y cuajo, no es kosher si no tiene certificación específica, ya que el origen del cuajo no se puede verificar a simple vista.</p>
<p>Las opciones que usan los fabricantes de queso kosher incluyen:</p>
<ul>
<li><strong>Cuajo animal kosher:</strong> extraído de animales faenados según shejita y bajo supervisión rabínica en toda la cadena.</li>
<li><strong>Cuajo microbiano:</strong> producido por fermentación, sin origen animal, cada vez más común en quesos industriales y kosher.</li>
<li><strong>Cuajo vegetal:</strong> extraído de ciertas plantas, usado tradicionalmente en algunas variedades específicas de quesos artesanales.</li>
</ul>
<p>Además del cuajo, hay otro factor relevante: muchas comunidades exigen que el queso se elabore bajo supervisión judía constante (Gvinat Yisrael) para considerarlo plenamente kosher, un criterio adicional al simple análisis de ingredientes. Por eso, comprar queso con certificación reconocida es la forma más confiable de evitar errores.</p>',
            ],
            [
                'slug' => 'huevos-kosher',
                'category' => 'kashrut-basico',
                'title' => 'Huevos kosher: qué hay que revisar antes de usarlos',
                'excerpt' => 'Los huevos son pareve y generalmente kosher, pero existe un paso de revisión obligatorio antes de cocinarlos.',
                'content' => '<p>Los huevos de aves kosher (como la gallina) son, en principio, pareve y aptos para consumo. Sin embargo, antes de usar un huevo, la tradición exige revisar que no contenga manchas de sangre en la yema, ya que un huevo con sangre se considera no apto para consumo.</p>
<p>El procedimiento es simple: al romper el huevo, se revisa visualmente la yema (y a veces la clara) contra la luz, buscando puntos rojos o manchas. Si se encuentra sangre, el huevo se descarta por completo; si la yema está limpia, el huevo es apto para usar con normalidad.</p>
<p>Algunos datos adicionales sobre huevos y kashrut:</p>
<ul>
<li>La cáscara y la clara generalmente no presentan el mismo riesgo que la yema, aunque la costumbre varía según la comunidad.</li>
<li>Los huevos de aves no kosher (como el avestruz o ciertas aves rapaces) tampoco son aptos, independientemente de la presencia de sangre.</li>
<li>Los productos industrializados con huevo (como pastas o mayonesa) generalmente pasan por un proceso de control de calidad que incluye la detección automática de huevos con sangre, pero igual requieren certificación para garantizar que ese control se hizo correctamente.</li>
</ul>
<p>Es uno de los hábitos más simples de incorporar en una cocina kosher diaria: revisar cada huevo apenas se rompe, antes de mezclarlo con el resto de los ingredientes.</p>',
            ],
            [
                'slug' => 'pescado-kosher-aletas-escamas',
                'category' => 'kashrut-basico',
                'title' => 'Pescado kosher: aletas y escamas, las reglas básicas',
                'excerpt' => 'A diferencia de la carne, el pescado kosher no requiere shejita, pero sí debe cumplir con un criterio físico específico.',
                'content' => '<p>La Torá establece una regla relativamente simple para identificar pescado kosher: debe tener tanto aletas (snapir) como escamas (kaskeset) visibles a simple vista. Esta combinación está presente en la gran mayoría de los peces de agua dulce y salada que se consumen habitualmente, como el salmón, el atún, la merluza, la trucha y la caballa.</p>
<p>Quedan excluidos del kashrut, entre otros:</p>
<ul>
<li>Todos los mariscos (camarones, langostinos, cangrejos, mejillones, ostras).</li>
<li>Pulpo y calamar.</li>
<li>Tiburón y rape (carecen de escamas verdaderas según la mayoría de las opiniones halájicas).</li>
<li>Anguila (carece de escamas visibles).</li>
<li>Pez espada (su estatus es objeto de debate histórico entre distintas autoridades rabínicas).</li>
</ul>
<p>Una diferencia importante respecto a la carne: el pescado kosher no requiere shejita ni un proceso de salado para extraer sangre, lo que simplifica bastante su preparación. Sin embargo, en muchas tradiciones —especialmente asquenazíes— el pescado se trata como una categoría aparte de la carne y los lácteos, evitando combinarlo con carne en el mismo plato (aunque no exige la misma separación estricta de utensilios que rige entre carne y leche).</p>
<p>Al comprar pescado fresco, conviene verificar que mantenga la piel con escamas visibles, ya que algunos fileteados quitan completamente la piel, dificultando la verificación. Por eso muchas pescaderías kosher dejan una porción de piel identificable en el corte.</p>',
            ],
            [
                'slug' => 'frutos-secos-contaminacion-cruzada',
                'category' => 'productos',
                'title' => 'Frutos secos y kashrut: riesgos de contaminación cruzada',
                'excerpt' => 'Los frutos secos son naturalmente pareve, pero el procesamiento industrial puede introducir riesgos de kashrut que no son evidentes.',
                'content' => '<p>Almendras, nueces, maní y la mayoría de los frutos secos son, en su forma cruda y natural, alimentos pareve sin restricciones de kashrut. El problema aparece cuando entran en la cadena de procesamiento industrial, donde pueden mezclarse con otros productos en las mismas líneas de producción.</p>
<p>Algunos riesgos comunes:</p>
<ul>
<li><strong>Saborizantes lácteos:</strong> frutos secos "tostados con manteca" o con recubrimientos de chocolate con leche dejan de ser pareve.</li>
<li><strong>Líneas compartidas:</strong> una fábrica puede procesar frutos secos pareve en el mismo equipo donde luego procesa productos con leche o derivados cárnicos, generando trazas no kosher si no hay una limpieza certificada entre lotes.</li>
<li><strong>Aceites de cocción:</strong> algunos frutos secos fritos usan aceites compartidos con otros productos no kosher.</li>
<li><strong>Glaseados y recubrimientos:</strong> los frutos secos "garrapiñados" o con cobertura dulce pueden contener gelatina u otros ingredientes de origen animal.</li>
</ul>
<p>Por eso, aunque un fruto seco crudo y sin procesar casi nunca presenta problemas, los productos industrializados (mix de frutos secos, snacks saborizados, barras de cereal) siempre deben revisarse por certificación, sin asumir que son automáticamente kosher solo porque el ingrediente principal lo es.</p>',
            ],
            [
                'slug' => 'kashrut-y-veganismo',
                'category' => 'kashrut-basico',
                'title' => 'Kashrut y veganismo: ¿es lo mismo comer vegano que comer kosher?',
                'excerpt' => 'Muchas personas asumen que un producto vegano es automáticamente kosher. La realidad es más matizada.',
                'content' => '<p>Es una confusión común: si un producto no contiene ningún ingrediente de origen animal, parecería lógico asumir que es automáticamente kosher. Sin embargo, el kashrut no se basa únicamente en los ingredientes, sino también en los procesos de elaboración, el equipamiento utilizado y, en algunos casos, quién supervisa la producción.</p>
<p>Algunos ejemplos donde un producto vegano puede no ser kosher:</p>
<ul>
<li><strong>Equipamiento compartido:</strong> una fábrica vegana puede usar la misma línea de producción que antes procesó productos cárnicos o lácteos, sin la limpieza certificada que exige el kashrut entre lotes.</li>
<li><strong>Vino y derivados:</strong> un vino vegano (sin clarificantes de origen animal) sigue requiriendo que todo el proceso de elaboración esté en manos de judíos observantes para ser kosher.</li>
<li><strong>Insectos:</strong> ciertos colorantes (como el carmín, de origen animal) están prohibidos en kosher pero a veces se etiquetan como aptos para veganos por error o por estándares distintos de certificación vegana.</li>
<li><strong>Bishul akum:</strong> un alimento vegano cocido enteramente por una persona no judía puede caer dentro de esta restricción, dependiendo de cómo se clasifique el producto.</li>
</ul>
<p>A la inversa, también es cierto que muchos productos kosher pareve son, de hecho, veganos. Pero la equivalencia no es automática en ningún sentido: lo más seguro siempre es buscar la certificación kosher explícita, en lugar de asumir que "vegano" equivale a "kosher".</p>',
            ],
            [
                'slug' => 'separar-la-jala',
                'category' => 'halajot',
                'title' => 'Cómo separar la challá (jalá)',
                'excerpt' => 'Separar la jalá es un mandamiento específico que se aplica al amasar pan en grandes cantidades, con raíces en las ofrendas del Templo.',
                'content' => '<p>La separación de jalá (hafrashat jalá) es un mandamiento bíblico que originalmente requería entregar una porción de la masa de pan a los sacerdotes (kohanim) del Templo de Jerusalén. Tras la destrucción del Templo, la práctica se transformó: hoy, en lugar de entregarse, la porción separada se quema o se desecha de una manera respetuosa.</p>
<p>Esta mitzvá aplica cuando se amasa una cantidad significativa de masa hecha con alguno de los cinco granos (trigo, cebada, avena, centeno o espelta) — la cantidad mínima exacta (generalmente alrededor de 1,2 kg de harina) varía según la opinión halájica que se siga.</p>
<p>El proceso básico es:</p>
<ul>
<li>Amasar la masa de pan normalmente, hasta que alcance la cantidad mínima requerida.</li>
<li>Separar una pequeña porción (tradicionalmente del tamaño de una aceituna o más, según la costumbre).</li>
<li>Recitar la bendición correspondiente antes de separar la porción.</li>
<li>Quemar la porción separada (envuelta en papel de aluminio, en el horno) o desecharla de forma que no se use para consumo regular.</li>
</ul>
<p>Esta práctica es la razón por la que muchas panaderías kosher industriales certificadas separan jalá como parte de su proceso de producción, y por la que muchas mujeres y familias judías la realizan en casa cada vez que hornean pan o jalá para Shabat en cantidad suficiente.</p>',
            ],
            [
                'slug' => 'calendario-judio-festividades-alimentacion',
                'category' => 'festividades',
                'title' => 'El calendario judío y las fiestas que afectan la alimentación kosher',
                'excerpt' => 'Varias festividades judías tienen costumbres alimentarias específicas, más allá de las reglas generales del kashrut.',
                'content' => '<p>Además de las normas de kashrut que rigen todo el año, el calendario judío trae festividades con costumbres alimentarias propias que conviene conocer:</p>
<ul>
<li><strong>Rosh Hashaná:</strong> se acostumbra comer manzana con miel para simbolizar un año dulce, y evitar alimentos amargos o ácidos en la mesa festiva.</li>
<li><strong>Iom Kipur:</strong> día de ayuno completo de 25 horas, sin comida ni bebida, salvo excepciones médicas específicas.</li>
<li><strong>Sucot:</strong> se acostumbra comer en una cabaña temporal (sucá) al aire libre durante toda la semana de la festividad.</li>
<li><strong>Janucá:</strong> tradición de comer alimentos fritos en aceite (como las sufganiot, rosquillas rellenas, y los latkes, panqueques de papa) en conmemoración del milagro del aceite.</li>
<li><strong>Purim:</strong> se preparan hamantaschen (orejas de Hamán), masas triangulares rellenas, y se acostumbra compartir canastas de comida (mishloaj manot) con amigos y familiares.</li>
<li><strong>Pesaj:</strong> la festividad con más restricciones alimentarias, centrada en la prohibición de jametz, como ya vimos en detalle.</li>
<li><strong>Shavuot:</strong> costumbre de comer alimentos lácteos, con platos como el cheesecake y los blintzes (panqueques rellenos de queso) como protagonistas.</li>
</ul>
<p>Conocer este calendario ayuda a entender por qué ciertos productos (como matzá, sufganiot o vino kosher para Pesaj) aparecen con mayor disponibilidad en góndolas y comercios en determinadas épocas del año.</p>',
            ],
            [
                'slug' => 'errores-comunes-empezar-comer-kosher',
                'category' => 'vida-diaria',
                'title' => 'Errores comunes al empezar a comer kosher',
                'excerpt' => 'Adoptar el kashrut por primera vez implica un proceso de aprendizaje. Repasamos los errores más frecuentes para evitarlos desde el principio.',
                'content' => '<p>Empezar a llevar una dieta kosher es un proceso que lleva tiempo, y es normal cometer errores al principio. Estos son algunos de los más comunes:</p>
<ul>
<li><strong>Asumir que "natural" o "sin conservantes" significa kosher:</strong> el marketing de un producto no tiene relación directa con su estatus de kashrut. Siempre hay que buscar la certificación.</li>
<li><strong>No revisar productos que parecen obviamente pareve:</strong> snacks, panificados y golosinas a veces contienen ingredientes lácteos o gelatina no evidentes en el nombre del producto.</li>
<li><strong>Mezclar utensilios cárnicos y lácteos por descuido:</strong> al principio es fácil olvidarse de la separación; etiquetar o usar colores distintos ayuda mucho durante la transición.</li>
<li><strong>No revisar verduras de hoja por insectos:</strong> un paso que muchas personas nuevas en el kashrut desconocen por completo.</li>
<li><strong>Confiar en certificaciones desconocidas o poco claras:</strong> no todos los símbolos en un paquete son certificaciones kosher reales; algunos son sellos de calidad sin relación con el kashrut.</li>
<li><strong>No preguntar:</strong> muchas dudas se resuelven rápido con una consulta al rabino de la comunidad o a alguien con más experiencia, en lugar de adivinar.</li>
</ul>
<p>Lo más importante es entender que la transición no necesita ser perfecta desde el primer día. La mayoría de las comunidades judías valoran el proceso de aprendizaje gradual, y hay muchos recursos —incluyendo certificadoras, rabinos y herramientas como KosherMap— para acompañar ese camino.</p>',
            ],
        ];

        foreach ($articles as $i => $data) {
            Article::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category' => $data['category'],
                    'title' => ['es' => $data['title']],
                    'excerpt' => ['es' => $data['excerpt']],
                    'content' => ['es' => $data['content']],
                    'sort_order' => $i + 1,
                    'is_published' => true,
                ]
            );
        }
    }
}
