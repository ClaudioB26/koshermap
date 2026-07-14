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
<p>Y que quede claro que esto no es tan fácil como suena. A uno de nosotros, en casa, siempre le tocaba revisar las verduras. Con los años empezó a necesitar anteojos, y un día vio un puntito negro en una hoja. Convencido de que no era nada, le preguntó a su hija: ¿esto qué es? Y la hija le contestó: ¿no ves que es un bicho, que se le ven las patitas? Desde ese día no revisa una sola hoja sin los anteojos puestos. La enseñanza es simple: revisar bien exige buena luz y buena vista, y ante la duda, una segunda mirada nunca sobra.</p>
<p>Muchas certificadoras kosher ofrecen guías específicas, ilustradas, de cómo revisar cada tipo de verdura según la región donde fue cultivada, porque la prevalencia de insectos varía según el clima y el método de cultivo. Hoy también existen verduras "pre-revisadas" o cultivadas bajo supervisión específica para minimizar la infestación, lo que simplifica mucho el proceso en la cocina diaria.</p>',
            ],
            [
                'slug' => 'carne-y-leche',
                'category' => 'halajot',
                'title' => 'Carne y leche: por qué no se mezclan',
                'excerpt' => 'La separación entre carne y leche es uno de los pilares más conocidos del kashrut. Te explicamos su origen, sus alcances y cómo se aplica en la práctica.',
                'content' => '<p>"No cocinarás un cabrito en la leche de su madre." Ese versículo aparece tres veces en la Torá, y de esa repetición la tradición oral sacó una prohibición triple: no cocinar carne con leche, no comerla mezclada y tampoco sacar ningún provecho de esa mezcla.</p>
<p>De ahí sale la división de todos los alimentos en tres grupos que cualquier persona que sigue el kashrut maneja de memoria: <strong>cárnicos</strong> (carne y derivados), <strong>lácteos</strong> (leche y derivados) y <strong>parve</strong> (todo lo que no es ni una cosa ni la otra: frutas, verduras, huevos, pescado).</p>
<p>Y esto no se queda en la teoría. En una cocina kosher observante los utensilios, las ollas, los platos e incluso el lavavajillas se dividen en dos juegos, uno para carne y otro para leche, porque el calor y el uso repetido hacen que los sabores y las partículas pasen de una superficie a otra. También hay que esperar entre comer carne y comer lácteos (el tiempo varía según la familia, generalmente entre una y seis horas), mientras que para pasar de lácteos a carne alcanza con enjuagarse la boca y comer algo neutro en el medio.</p>
<p>Por eso tantas etiquetas llevan una letra chiquita junto al sello de certificación: "D" para dairy (lácteo), "M" para meat (cárnico) o directamente "Pareve". Con solo mirar esa letra, sabés al instante si podés combinar ese producto con lo que estás por comer.</p>',
            ],
            [
                'slug' => 'kasherizar-horno',
                'category' => 'kasherizacion',
                'title' => 'Cómo kasherizar un horno',
                'excerpt' => 'Cuando un horno se usó con alimentos no kosher, o se quiere pasar de uso cárnico a lácteo, existe un proceso específico para volverlo apto.',
                'content' => '<p>Hay varios momentos en los que kasherizar un horno se vuelve necesario: te mudás a una casa que tenía un horno de uso no kosher, decidís pasarlo de uso cárnico a lácteo, o se acerca Pesaj y hay que sacar hasta el último rastro de jametz.</p>
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
                'content' => '<p>El microondas cocina distinto: en vez de calor seco como el horno, genera vapor por dentro, y eso cambia el método de kasherización que recomiendan la mayoría de las autoridades halájicas.</p>
<p>Los pasos más habituales:</p>
<ul>
<li>Limpiar bien el interior, sin ninguna partícula de comida visible, incluyendo el plato giratorio y las paredes.</li>
<li>No usarlo durante 24 horas antes de kasherizarlo.</li>
<li>Poner un recipiente con agua adentro y encenderlo hasta que hierva y el vapor cubra todas las superficies internas, puerta incluida.</li>
<li>Dejar que el vapor actúe sobre las paredes unos minutos más.</li>
</ul>
<p>Muchas familias directamente evitan el problema: usan siempre tapa o film para microondas al calentar comida, y reservan el aparato para un solo uso (cárnico, lácteo o parve) en vez de andar kasherizándolo cada vez. Si tu microondas tiene grill o convección, esa función calienta distinto y puede necesitar un paso adicional parecido al del horno. Como los modelos varían tanto, si tenés dudas con el tuyo, consultalo con el rav antes de darlo por kasherizado.</p>',
            ],
            [
                'slug' => 'kasherizar-lavavajillas',
                'category' => 'kasherizacion',
                'title' => 'Cómo kasherizar un lavavajillas',
                'excerpt' => 'Muchas familias usan el lavavajillas para platos cárnicos y lácteos en ciclos separados. Te contamos qué hace falta para kasherizarlo.',
                'content' => '<p>El lavavajillas complica un poco más que otros electrodomésticos: sus paredes internas, los filtros y los brazos aspersores están en contacto constante con restos de comida a alta temperatura, y eso hace que absorban sabores de forma más persistente.</p>
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
<p>El vino va descansando en distintos ambientes según la etapa, y una parte se guarda en toneles de roble, que se pueden reutilizar hasta unas tres veces antes de perder sus propiedades. Cada tonel va sellado, y ese sello no es un detalle menor: es la garantía de que nadie ajeno tocó el contenido. Nos contaron un caso que muestra hasta dónde llega la exigencia. En un tonel de miles de litros notaron que faltaba el sello en el punto donde tenía que estar cerrado: estaba abierto. Tuvo que venir el Rab de Buenos Aires a verificar la situación en persona, y dictaminó que ese vino había quedado sin supervisión. Resultado: esos miles de litros ya no se podían vender como kosher.</p>
<p>Existe además una categoría especial, el <strong>vino mevushal</strong> ("hervido"), que es vino pasteurizado a una temperatura específica. Una vez que un vino es mevushal, conserva su estatus kosher aunque después lo sirva o lo toque una persona no judía. Por eso es tan práctico para eventos, restaurantes y catering, donde no hay forma de controlar quién agarra cada botella.</p>
<p>Hoy hay vinos kosher de buena calidad en casi todas las regiones vitivinícolas del mundo. Mendoza es un polo importante en Argentina, y también se producen en Chile, Francia, España, Italia y por supuesto Israel, certificados por las principales agencias rabínicas.</p>',
            ],
            [
                'slug' => 'gelatina-kosher',
                'category' => 'productos',
                'title' => 'Gelatina kosher: el debate halájico',
                'excerpt' => 'La gelatina es uno de los ingredientes más debatidos en el mundo del kashrut, porque su origen animal puede comprometer su estatus.',
                'content' => '<p>Para hacer gelatina tradicional se hierven huesos, piel y tejido conectivo de animales (por lo general vacas o cerdos) hasta extraer el colágeno. Ahí aparecen dos problemas para el kashrut: el origen del animal (¿es una especie kosher?) y el proceso (¿fue faenado según shejita?).</p>
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
                'content' => '<p>Pareve (también se escribe parve) es la palabra que describe a los alimentos que no son ni cárnicos ni lácteos: frutas, verduras, huevos, pescado, granos y la mayoría de los productos hechos sin ingredientes de origen animal lácteo o cárnico.</p>
<p>La gran ventaja de un producto pareve es que se puede combinar libremente tanto con comidas cárnicas como con lácteas, sin generar ningún conflicto de kashrut. Por eso muchas industrias alimenticias buscan activamente sacar versiones pareve de productos que tradicionalmente llevan lácteos (chocolate, margarina, sustitutos de crema), para ampliar el mercado al que le pueden vender.</p>
<p>Ahora, hay un matiz que conviene tener claro: un alimento puede ser pareve por sus ingredientes y perder ese estatus si se fabricó en un equipo que también procesa lácteos o carne, según las trazas que puedan haber quedado. Por eso la certificación no solo mira ingredientes, también revisa el equipo de producción y la limpieza entre lotes.</p>
<p>Algunos ejemplos típicos de productos pareve: aceite de oliva, pasta seca sin huevo, la mayoría de los panes (aunque algunos llevan manteca y ahí pasan a ser lácteos), frutos secos sin procesar, bebidas gaseosas. La única forma de estar seguro es revisar siempre la etiqueta, porque la receta puede cambiar entre marcas o incluso entre presentaciones de la misma marca.</p>',
            ],
            [
                'slug' => 'shejita-sacrificio-kosher',
                'category' => 'kashrut-basico',
                'title' => 'Shejita: el método de sacrificio kosher',
                'excerpt' => 'Para que la carne de un animal kosher sea apta para consumo, debe faenarse según un método ritual específico llamado shejita.',
                'content' => '<p>La shejita es el método de sacrificio ritual judío, realizado por un shojet (matarife capacitado y certificado) usando un cuchillo extremadamente afilado y sin mellas, diseñado específicamente para producir un corte rápido y preciso en la garganta del animal, seccionando la tráquea y el esófago en un solo movimiento continuo.</p>
<p>El objetivo de este método es minimizar el sufrimiento del animal y producir una pérdida de conciencia prácticamente instantánea. El shojet inspecciona el cuchillo antes y después de cada faena para asegurarse de que no tenga ninguna imperfección, por mínima que sea, ya que cualquier irregularidad invalida el procedimiento.</p>
<p>Un familiar de nuestro equipo trabaja en shejita, y da una idea de lo obsesivo que es el control en la práctica. El jalef (el cuchillo) no lo revisa solamente el shojet antes y después de cada animal: además hay un supervisor que controla todos los cuchillos, día por día, con un criterio extremadamente estricto. Una imperfección que a simple vista ni se percibe alcanza para dejar un cuchillo fuera de uso. Es un ambiente de máxima seriedad y precisión, donde ante cualquier situación fuera de lo común lo primero es frenar todo y priorizar la seguridad.</p>
<p>Después de la shejita, se realiza una inspección (bedika) de los órganos internos del animal, especialmente los pulmones, para descartar enfermedades o adherencias que invalidarían la carne como kosher. Solo los animales que pasan esta inspección se consideran aptos.</p>
<p>Adicionalmente, la carne debe pasar por un proceso de salado (kashering) para extraer la sangre, ya que la Torá prohíbe consumir sangre. Esto se hace remojando la carne en agua, salándola y dejándola reposar antes de enjuagarla nuevamente, un proceso que hoy en día generalmente realiza la propia carnicería o frigorífico certificado antes de que el producto llegue al consumidor.</p>',
            ],
            [
                'slug' => 'glatt-kosher',
                'category' => 'kashrut-basico',
                'title' => 'Glatt kosher: qué diferencia hay con el kosher común',
                'excerpt' => 'El término "glatt" aparece frecuentemente en carnicerías y restaurantes kosher. Te explicamos qué nivel de estrictez representa.',
                'content' => '<p>"Glatt" quiere decir "liso" en ídish, y originalmente se refería puntualmente al estado de los pulmones de un animal después de la shejita: si no tenían ninguna adherencia (sirja), el animal se consideraba "glatt", el nivel más alto de certeza de que esa carne es kosher sin ninguna duda.</p>
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
<p>En muchas casas esto se vive como un momento especial. En la nuestra, por ejemplo, las chicas siempre tratan de llegar a la cantidad mínima de masa justamente para poder separar la jalá con berajá, ya que hacerlo con la bendición tiene un valor agregado. Más allá del gesto técnico, termina siendo un ritual familiar alrededor del horno.</p>',
            ],
            [
                'slug' => 'calendario-judio-festividades-alimentacion',
                'category' => 'festividades',
                'title' => 'El calendario judío y las fiestas que afectan la alimentación kosher',
                'excerpt' => 'Varias festividades judías tienen costumbres alimentarias específicas, más allá de las reglas generales del kashrut.',
                'content' => '<p>Además de las normas de kashrut que rigen todo el año, el calendario judío trae festividades con costumbres de comida propias, y conocerlas ayuda a entender por qué ciertos productos aparecen o desaparecen de las góndolas en determinadas épocas:</p>
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
