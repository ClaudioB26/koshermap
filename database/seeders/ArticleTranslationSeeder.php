<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleTranslationSeeder extends Seeder
{
    public function run(): void
    {
        foreach (self::translations() as $locale => $articles) {
            foreach ($articles as $slug => $data) {
                $row = DB::table('articles')->where('slug', $slug)->first();
                if (!$row) {
                    continue;
                }

                $title = json_decode($row->title, true) ?: ['es' => $row->title];
                $excerpt = json_decode($row->excerpt, true) ?: ['es' => $row->excerpt];
                $content = json_decode($row->content, true) ?: ['es' => $row->content];

                $title[$locale] = $data['title'];
                $excerpt[$locale] = $data['excerpt'];
                $content[$locale] = $data['content'];

                DB::table('articles')->where('slug', $slug)->update([
                    'title' => json_encode($title, JSON_UNESCAPED_UNICODE),
                    'excerpt' => json_encode($excerpt, JSON_UNESCAPED_UNICODE),
                    'content' => json_encode($content, JSON_UNESCAPED_UNICODE),
                ]);
            }
        }
    }

    private static function translations(): array
    {
        return [
            'en' => self::en(),
            'pt' => self::pt(),
            'fr' => self::fr(),
        ];
    }

    private static function en(): array
    {
        return [
            'insectos-frutas-verduras' => [
                'title' => 'Insects in fruits and vegetables: how to check them properly',
                'excerpt' => 'The Torah forbids eating insects, so checking fruits and vegetables before eating them is a core part of keeping a kosher kitchen.',
                'content' => '<p>The prohibition against eating insects (sheratzim) is one of the strictest in the Torah: unlike other dietary prohibitions, eating a single insect can count as multiple transgressions. That is why checking fruits and vegetables before cooking or serving them is an unavoidable step in a kosher kitchen.</p>
<p>Leafy vegetables such as lettuce, spinach, broccoli and cauliflower require the most care, because aphids and other small insects hide between the leaves and are hard to spot with the naked eye. The usual technique involves separating the leaves, washing them individually under running water and checking them against the light.</p>
<ul>
<li><strong>Leafy vegetables:</strong> separate leaf by leaf and wash under running water, checking both sides.</li>
<li><strong>Cauliflower and broccoli:</strong> soak in water with a bit of soap or vinegar so insects detach from the florets.</li>
<li><strong>Strawberries and raspberries:</strong> soak in salted or vinegar water before rinsing.</li>
<li><strong>Dried legumes (lentils, chickpeas):</strong> spread on a light surface and check before cooking.</li>
</ul>
<p>Many kosher certifiers offer specific, illustrated guides on how to check each type of vegetable depending on where it was grown, since insect prevalence varies by climate and farming method. Today there are also "pre-checked" vegetables or ones grown under specific supervision to minimize infestation, which greatly simplifies the process in everyday cooking.</p>',
            ],
            'carne-y-leche' => [
                'title' => 'Meat and milk: why they don\'t mix',
                'excerpt' => 'The separation of meat and milk is one of the best-known pillars of kashrut. We explain its origin, its scope and how it applies in practice.',
                'content' => '<p>The prohibition on mixing meat and milk is based on a verse that appears three times in the Torah: "You shall not cook a kid in its mother\'s milk." Oral tradition interpreted this phrase as a triple prohibition: not to cook, not to eat, and not to derive any benefit from a mixture of meat and milk.</p>
<p>In practice, this means food is divided into three categories: <strong>meat</strong> (meat and its derivatives), <strong>dairy</strong> (milk and its derivatives) and <strong>pareve</strong> (neutral, such as fruits, vegetables, eggs and fish, which are neither meat nor milk).</p>
<p>An observant kosher kitchen keeps separate utensils, pots, plates and even dishwashers for meat and for milk, since heat and repeated use can transfer flavors and particles between surfaces. In addition, a waiting period is required between eating meat and eating dairy — which varies by family custom, generally between one and six hours — while going from dairy to meat usually only requires rinsing one\'s mouth and eating something neutral.</p>
<p>This separation is why so many product labels carry the letters "D" (dairy), "M" (meat) or "Pareve" next to the certification symbol: it lets the consumer instantly know which category a product falls into before combining it with other foods.</p>',
            ],
            'kasherizar-horno' => [
                'title' => 'How to kosher an oven',
                'excerpt' => 'When an oven was used with non-kosher food, or you want to switch it from meat to dairy use, there is a specific process to make it kosher.',
                'content' => '<p>Koshering an oven is necessary in several situations: when buying a house with an oven previously used in a non-kosher way, when switching an oven\'s use (for example, from meat to dairy), or before Passover, when every trace of chametz must be removed.</p>
<p>The traditional method for ovens is called <em>libun</em> (self-cleaning through intense heat) and consists of:</p>
<ul>
<li>Cleaning the oven thoroughly, removing all visible dirt and food residue.</li>
<li>Not using the oven for 24 hours before koshering it.</li>
<li>Turning the oven to its highest possible temperature (ideally using the self-cleaning function, if the oven has one) for at least one hour.</li>
</ul>
<p>Metal racks and trays can usually be koshered separately by immersion in boiling water (hagalah), while glass or enamel surfaces generally require libun because they are more absorbent materials.</p>
<p>It is important to consult a rabbi before koshering a particular oven, since the exact procedure can vary depending on the material, model and community custom (minhag). Some modern ovens with special coatings may not be suitable for high-temperature libun, so it is worth checking the manufacturer\'s manual.</p>',
            ],
            'kasherizar-microondas' => [
                'title' => 'How to kosher a microwave',
                'excerpt' => 'Microwaves have a different koshering process than a traditional oven, because they cook with steam rather than dry heat.',
                'content' => '<p>Unlike a conventional oven, a microwave heats food by generating steam inside it, which changes the koshering method recommended by most halachic authorities.</p>
<p>The most common process includes:</p>
<ul>
<li>Thoroughly cleaning the interior, removing every visible food particle, including the turntable and walls.</li>
<li>Not using the microwave for 24 hours before koshering it.</li>
<li>Placing a container of water inside the microwave and running it until the water boils and generates enough steam to cover all interior surfaces, including the door.</li>
<li>Letting the steam act on the walls for several minutes.</li>
</ul>
<p>Many families also choose to always use a microwave-safe lid or wrap when heating food, and reserve the appliance for a single use (meat, dairy or pareve) to avoid having to kosher it repeatedly. Microwaves with a grill or convection function may require an additional process similar to a traditional oven for that specific function. As with any koshering, it is recommended to consult a rabbi about the particular model and material of the appliance.</p>',
            ],
            'kasherizar-lavavajillas' => [
                'title' => 'How to kosher a dishwasher',
                'excerpt' => 'Many families use the dishwasher for meat and dairy dishes in separate cycles. Here is what it takes to kosher it.',
                'content' => '<p>The dishwasher poses a particular challenge because its internal walls, filters and spray arms are in constant contact with food residue at high temperature, which can absorb flavors more persistently than other appliances.</p>
<p>For this reason, many rabbinic authorities are stricter about koshering dishwashers than other appliances, and some directly discourage using one for both categories (meat and dairy), even on different days. Those who do allow it generally require:</p>
<ul>
<li>Thorough cleaning of filters, spray arms and rubber seals.</li>
<li>Not using the dishwasher for 24 hours before koshering it.</li>
<li>Running a full empty cycle at the highest possible temperature, ideally with a strong cleaning product.</li>
<li>In some communities, it is recommended to use separate, interchangeable racks or trays for meat and dairy, rather than koshering the entire appliance between uses.</li>
</ul>
<p>Since customs vary quite a bit on this topic — some Sephardic and Ashkenazi communities differ notably — this is one of those cases where it is well worth consulting directly with the congregation\'s rabbi before deciding how to organize the kitchen.</p>',
            ],
            'hagala-utensilios-metal' => [
                'title' => 'How to kosher metal utensils (hagalah)',
                'excerpt' => 'Hagalah is the traditional method of immersion in boiling water used to kosher pots, cutlery and other metal utensils.',
                'content' => '<p>Hagalah is one of the oldest koshering methods and is mainly used on metal utensils that were heated directly by fire or boiling liquid, such as pots, cutlery, pans (without a non-stick coating) and certain other kitchenware.</p>
<p>The principle behind hagalah is "as it absorbed, so it releases": if a utensil absorbed non-kosher (or meat/dairy) flavor through boiling liquid, it is purified the same way, by immersing it in boiling water.</p>
<p>The basic procedure is:</p>
<ul>
<li>Clean the utensil thoroughly, with no rust, stuck-on food or caked dirt.</li>
<li>Wait 24 hours without using the utensil before doing hagalah.</li>
<li>Bring a large pot of water to a rolling boil.</li>
<li>Fully submerge the utensil in the boiling water, making sure all its surfaces come into contact with the water at that temperature.</li>
<li>Remove it with an implement that has not been in contact with non-kosher food, and rinse it in cold water.</li>
</ul>
<p>Utensils with wooden or plastic handles, or with parts glued with adhesives that cannot withstand boiling water, are generally not suitable for hagalah and need a different method, or simply cannot be koshered. Non-stick (Teflon) pans also are not usually koshered via hagalah, since the coating is damaged by the heat.</p>',
            ],
            'vajilla-para-pesaj' => [
                'title' => 'Passover dishware: everything you need to know',
                'excerpt' => 'During Passover, stricter rules than the rest of the year apply to kitchenware, due to the prohibition of chametz.',
                'content' => '<p>Passover is the holiday with the strictest dietary rules in the Jewish calendar, because in addition to the usual kashrut rules, there is the total prohibition on eating or owning chametz (fermented products from five grains: wheat, barley, oats, rye and spelt).</p>
<p>Since chametz may have been in contact with pots, plates and cutlery all year, many families choose to keep a separate set of dishware exclusively for Passover, stored away the rest of the year. This is the simplest option and avoids having to kosher items every year.</p>
<p>Those without separate Passover dishware can kosher certain utensils:</p>
<ul>
<li><strong>Uncoated metal</strong> (pots, cutlery): generally suitable for hagalah.</li>
<li><strong>Glass</strong>: depending on custom, some consider thorough washing sufficient, others require immersion.</li>
<li><strong>Ceramic and porcelain</strong>: generally cannot be koshered for Passover and a separate set must be used.</li>
<li><strong>Plastic and rubber</strong>: most opinions do not allow koshering them.</li>
</ul>
<p>Before Passover, it is worth consulting the community\'s or local certifier\'s specific koshering guide, since deadlines and exact methods can vary depending on the material and how the utensil was used during the year.</p>',
            ],
            'jametz-pesaj' => [
                'title' => 'Chametz: what it is and how it is eliminated before Passover',
                'excerpt' => 'Chametz is the fermented food forbidden during Passover. Knowing which products contain it is key to preparing for the holiday.',
                'content' => '<p>Chametz is any product made from one of the five grains — wheat, barley, oats, rye or spelt — that came into contact with water and fermented (rose) for more than 18 minutes without being baked. This includes bread, beer, most pasta, cookies, and a huge number of processed products that use these grains as an ingredient or derivative.</p>
<p>The Torah forbids not only eating chametz during Passover, but also owning it. That is why, in the weeks before the holiday, Jewish families do a deep cleaning of the house (bedikat chametz) to remove any trace of bread, flour or chametz products from cupboards, cars, bags and any corner where a crumb might have fallen.</p>
<p>For chametz that cannot or should not be thrown away (for example, expensive or hard-to-replace products), there is the option of symbolically "selling" it to a non-Jewish person through a contract called <em>mechirat chametz</em>, usually coordinated by the community rabbi. The sold chametz is kept closed and set aside during the holiday and is automatically "bought back" once Passover ends.</p>
<p>The night before Passover, a ritual search for chametz is performed throughout the house (bedikat chametz), usually with a candle, a feather and a wooden spoon, followed by burning what was found (biur chametz) the next morning.</p>',
            ],
            'vino-kosher' => [
                'title' => 'Kosher wine: why it needs special supervision',
                'excerpt' => 'Wine has a particular status in halacha: to be kosher, it must be made and handled exclusively by observant Jews.',
                'content' => '<p>Unlike most foods, where it is enough for the ingredients and process to meet certain requirements, kosher wine also requires that everyone who touches it during production — from harvesting the grapes to bottling — be an observant Jew. This rule originated historically to prevent wine from being used in idolatrous rituals.</p>
<p>This means a winery producing kosher wine must operate under constant rabbinic supervision: non-Jewish workers can help with tasks that do not involve directly touching the wine or grape juice, but the core process must remain in the hands of observant Jewish staff.</p>
<p>There is a special category called <strong>mevushal wine</strong> (literally "boiled"), which is wine pasteurized at a specific temperature. Once mevushal, the wine keeps its kosher status even if served or touched by a non-Jew, which makes it much more practical for events, restaurants and catering where it cannot be guaranteed that only Jews will handle the bottles.</p>
<p>Today there is quality kosher wine in virtually every wine-growing region in the world, including Argentina, Chile, France, Spain, Italy and, of course, Israel, certified by the leading rabbinic agencies.</p>',
            ],
            'gelatina-kosher' => [
                'title' => 'Kosher gelatin: the halachic debate',
                'excerpt' => 'Gelatin is one of the most debated ingredients in the kosher world, because its animal origin can compromise its status.',
                'content' => '<p>Traditional gelatin is obtained by boiling bones, skin and connective tissue from animals — usually cows or pigs — until the collagen is extracted. This raises two problems for kashrut: the animal\'s origin (is it a kosher species?) and the processing method (was the animal slaughtered according to shechita?).</p>
<p>For decades, various rabbinic authorities debated whether gelatin, having gone through such a radical chemical transformation, changes its halachic status (a concept called <em>panim chadashot</em>, or total transformation). Some more lenient opinions held that the process is so radical that the final product is no longer considered meat in a halachic sense; most mainstream kosher certifiers, however, do not accept this view for gelatin of non-kosher origin.</p>
<p>That is why most kosher-certified products that require gelatin (candy, desserts, medicine capsules, marshmallows) today use certified alternatives:</p>
<ul>
<li>Kosher fish gelatin.</li>
<li>Beef gelatin from animals slaughtered according to shechita.</li>
<li>Plant-based substitutes such as agar-agar or pectin, which avoid the debate entirely.</li>
</ul>
<p>When a product carries the seal of a recognized certifier, there is no longer any need to investigate the gelatin\'s origin: the certification guarantees that point has already been verified.</p>',
            ],
            'alcohol-bebidas-espirituosas' => [
                'title' => 'Alcohol and spirits: what it takes for them to be kosher',
                'excerpt' => 'Whiskey, vodka, rum and other spirits are often kosher by nature, but there are important exceptions to keep in mind.',
                'content' => '<p>Most spirits — whiskey, vodka, rum, gin — are made from grains, potato or sugar cane, ingredients that on their own do not present kashrut problems. That is why many simple spirits are kosher without needing special certification, as long as no non-kosher flavors, colorings or additives are added.</p>
<p>However, there are important points to watch for:</p>
<ul>
<li><strong>Aging in wine or sherry barrels:</strong> some whiskeys and rums are aged in barrels that previously held non-kosher wine, which can compromise their status.</li>
<li><strong>Flavorings and additives:</strong> cream-, chocolate- or fruit-flavored liqueurs often include ingredients that require verification.</li>
<li><strong>Wine-based drinks</strong> (such as vermouth or certain liqueurs): inherit all the restrictions of kosher wine, including the need for rabbinic supervision during production.</li>
<li><strong>Beer:</strong> generally kosher due to its base ingredients (water, barley, hops, yeast), except for variants with special flavorings.</li>
</ul>
<p>During Passover, extra attention is needed because many spirits are made from grains that constitute chametz, so a specific "kosher for Passover" certification is required during that time of year.</p>',
            ],
            'comer-kosher-restaurante' => [
                'title' => 'How to eat kosher at a non-certified restaurant',
                'excerpt' => 'Traveling or eating out without a kosher restaurant nearby doesn\'t mean breaking your diet. There are options to stay within the rules.',
                'content' => '<p>A certified kosher restaurant isn\'t always available, especially when traveling or living in cities with little community infrastructure. Even so, there are strategies to stay within kashrut at regular restaurants.</p>
<ul>
<li><strong>Vegetarian or vegan options:</strong> by removing meat and dairy from the plate, the risk is greatly reduced, though it is still necessary to check ingredients (meat broth, butter, animal-based sauces).</li>
<li><strong>Raw fruits and vegetables:</strong> with no cooking or complex handling, these are usually a safe option almost anywhere.</li>
<li><strong>Fish with fins and scales:</strong> at simple-cuisine restaurants, plain grilled fish without sauces can be a reasonable alternative for those following a more flexible approach (as long as it is not cooked alongside shellfish or non-kosher meat on the same equipment, depending on personal criteria).</li>
<li><strong>Bottled and sealed beverages:</strong> water, sodas and juices in their original packaging generally present no problems.</li>
</ul>
<p>Each person and community has a different level of strictness about what is considered acceptable outside a certified restaurant (some only eat packaged and sealed products, others accept certain simple preparations). When in doubt, it is best to ask the congregation\'s rabbi which criteria to follow.</p>',
            ],
            'simbolos-certificacion-kosher' => [
                'title' => 'The most common kosher certification symbols',
                'excerpt' => 'OU, OK, Star-K, KSA... there are dozens of kosher certification symbols around the world. We help you recognize the most common ones.',
                'content' => '<p>When a product goes through the kosher certification process, the certifying agency authorizes the use of a symbol (hechsher) on the packaging that lets it be identified at a glance. There are hundreds of certifiers worldwide, but some are especially well known for their global reach.</p>
<ul>
<li><strong>OU (Orthodox Union):</strong> a "U" inside a circle. It is probably the most recognized kosher symbol worldwide, based in the United States.</li>
<li><strong>OK Kosher Certification:</strong> a "K" inside a circle, another major U.S. agency.</li>
<li><strong>Star-K:</strong> a star with a "K" in the center.</li>
<li><strong>KSA (Kosher Supervision of America):</strong> a certifier with a strong presence in industrial products.</li>
<li><strong>Badatz:</strong> a seal used by several rabbinic courts in Israel, associated with very high standards of strictness.</li>
<li><strong>Local certifiers:</strong> in countries like Argentina, Brazil or Mexico there are local community certifiers (such as each kehilah\'s Va\'ad Hakashrut) with their own seals.</li>
</ul>
<p>Besides the symbol, many labels include an additional letter: "D" (dairy), "M" (meat), "Pareve" (neutral) or "DE" (dairy equipment, made on dairy equipment but without direct dairy ingredients). Knowing these symbols greatly speeds up shopping, especially when traveling to countries where you don\'t speak the local language.</p>',
            ],
            'que-significa-pareve' => [
                'title' => 'Pareve: what it means and why it\'s so common on labels',
                'excerpt' => 'Pareve is one of the most repeated words in kosher labeling. We explain what it means and why it\'s so valued.',
                'content' => '<p>"Pareve" (also spelled parve) describes foods that are neither meat nor dairy: fruits, vegetables, eggs, fish, grains and most products made without dairy or meat ingredients of animal origin.</p>
<p>The great advantage of a pareve product is its flexibility: it can be freely combined with both meat and dairy meals, without creating any kashrut conflict. That is why many food companies actively develop pareve versions of products that traditionally contain dairy — such as chocolate, margarine or cream substitutes — to expand their market.</p>
<p>It\'s worth noting a nuance: a food can be pareve by ingredients but lose that status if it was made on equipment that also processes dairy or meat, depending on possible traces left behind. That is why certification doesn\'t just review ingredients, but also the production equipment and cleaning processes between batches.</p>
<p>Some common examples of pareve products: olive oil, dry pasta without egg, most breads (although some contain butter and become dairy), unprocessed nuts, and soft drinks. Always checking the label is the only sure way to confirm it, since the recipe can vary between brands or even between presentations of the same brand.</p>',
            ],
            'shejita-sacrificio-kosher' => [
                'title' => 'Shechita: the kosher slaughter method',
                'excerpt' => 'For the meat of a kosher animal to be fit for consumption, it must be slaughtered using a specific ritual method called shechita.',
                'content' => '<p>Shechita is the Jewish ritual slaughter method, performed by a shochet (a trained and certified slaughterer) using an extremely sharp, nick-free knife, designed specifically to make a quick, precise cut across the animal\'s throat, severing the trachea and esophagus in a single continuous motion.</p>
<p>The goal of this method is to minimize the animal\'s suffering and produce an almost instantaneous loss of consciousness. The shochet inspects the knife before and after each slaughter to make sure it has no imperfection, however small, since any irregularity invalidates the procedure.</p>
<p>After shechita, an inspection (bedika) of the animal\'s internal organs is performed, especially the lungs, to rule out diseases or adhesions that would invalidate the meat as kosher. Only animals that pass this inspection are considered fit.</p>
<p>Additionally, the meat must go through a salting process (kashering) to extract the blood, since the Torah forbids consuming blood. This is done by soaking the meat in water, salting it and letting it rest before rinsing it again — a process that today is usually carried out by the certified butcher shop or slaughterhouse itself before the product reaches the consumer.</p>',
            ],
            'glatt-kosher' => [
                'title' => 'Glatt kosher: how is it different from regular kosher',
                'excerpt' => 'The term "glatt" appears frequently in kosher butcher shops and restaurants. We explain what level of strictness it represents.',
                'content' => '<p>"Glatt" means "smooth" in Yiddish and originally referred specifically to the condition of an animal\'s lungs after shechita: if the lungs had no adhesions (sirchah), the animal was considered "glatt," the highest level of certainty that the meat is kosher beyond doubt.</p>
<p>Over time, especially in Ashkenazi communities in the United States, the term "glatt kosher" colloquially expanded to describe an overall stricter standard throughout a food\'s entire production chain, not just lung inspection. Today it is common to see "glatt kosher" on restaurant and product labels to indicate they meet the most demanding criteria possible.</p>
<p>It is important to note that a "kosher" product without the "glatt" label is not less halachically valid: it simply follows a different certification standard, generally accepted by the vast majority of communities. Choosing between standard kosher and glatt kosher usually depends on family or community custom, rather than an objective difference in validity.</p>
<p>For poultry and fish, the concept of "glatt" technically does not apply in the same way as for mammals, although colloquially it is sometimes used to indicate a generally more rigorous level of supervision.</p>',
            ],
            'como-leer-etiqueta-kosher' => [
                'title' => 'How to read a kosher product label',
                'excerpt' => 'Beyond the certification symbol, kosher labels contain key information about whether a product is suitable for your table.',
                'content' => '<p>Properly reading a kosher label goes beyond looking for the certification symbol. There are several elements worth always checking:</p>
<ul>
<li><strong>The certifier\'s symbol:</strong> identifies which agency supervised the product. It\'s important to recognize trustworthy certifiers, since not every symbol in the world has the same level of rigor.</li>
<li><strong>The category:</strong> "Dairy" or "D," "Meat" or "M," "Pareve" (neutral), or "Fish" (which in many traditions is treated as a category separate from meat).</li>
<li><strong>"Kosher for Passover":</strong> an additional indication needed during the holiday, distinct from the regular kosher certification used the rest of the year.</li>
<li><strong>Certification date:</strong> some certifiers include a code or date to verify that the seal is still current, since recipes and factory processes can change.</li>
</ul>
<p>When a product has no visible certification but the ingredient list looks simple (for example, just water, salt and a vegetable), some people choose to investigate further, but the general recommendation from kashrut authorities is not to assume a product is kosher just because its ingredients look simple: many additives and industrial processes are not obvious at a glance.</p>
<p>On KosherMap you can search products by name or barcode and filter directly by certifier, category and type, so you don\'t have to rely solely on the physical label.</p>',
            ],
            'bishul-akum' => [
                'title' => 'Bishul Akum: why some cooked foods need Jewish supervision',
                'excerpt' => 'There is a specific category of rabbinic laws about food cooked by non-Jews, known as bishul akum. We explain what it\'s about.',
                'content' => '<p>Bishul akum (literally "cooking of a non-Jew") is a category of rabbinic laws that restricts the consumption of certain foods cooked entirely by a non-Jewish person, even if all the ingredients are kosher. The prohibition was established by the Talmudic sages, mainly to encourage social cohesion and prevent cultural assimilation.</p>
<p>This law does not apply to all foods: it is generally limited to foods considered "fit to serve at a king\'s table" (chashivut) and that are not eaten raw. That is why fruits, raw vegetables and most processed snacks do not fall into this category.</p>
<p>There are two common ways to resolve the issue in an industrial production or certified restaurant context:</p>
<ul>
<li>An observant Jew actively participates in the cooking process, for example by lighting the fire or cooking equipment.</li>
<li>Rabbinic supervision certifies that a Jewish representative was present when the cooking equipment was turned on during each production shift.</li>
</ul>
<p>This is one of the reasons why kosher certification of a food factory is not limited to reviewing ingredients: it also supervises processes, staff presence and operational protocols at the facility, which makes certifiers\' work much more complex than a simple ingredient checklist.</p>',
            ],
            'vino-mevushal' => [
                'title' => 'Mevushal: kosher wine that can be served without restrictions',
                'excerpt' => 'Mevushal wine is a special category that allows it to be served at events without requiring that only Jews handle it.',
                'content' => '<p>As we saw when discussing kosher wine, the general rule requires that only observant Jews handle the wine from production to serving. Mevushal wine ("boiled" or pasteurized) is a practical exception to this rule: once the wine goes through a heating process at a specific minimum temperature, it keeps its kosher status no matter who serves it afterward.</p>
<p>This category exists thanks to a halachic principle according to which wine altered by heat loses the ritual "dignity" that originally motivated the restriction, since historically that concern was aimed at wine\'s use in idolatrous ceremonies — something a boiled wine did not lend itself to in that context.</p>
<p>Mevushal wine is very popular for:</p>
<ul>
<li>Catering and events where the serving staff is not necessarily Jewish.</li>
<li>Kosher restaurants open to the general public.</li>
<li>Airlines and hotels that offer kosher options.</li>
</ul>
<p>Today there are modern flash pasteurization techniques that allow producing high-quality mevushal wine, which historically was harder to achieve without affecting the wine\'s flavor. This has greatly expanded the range of premium mevushal wines available on the market.</p>',
            ],
            'tevilat-kelim' => [
                'title' => 'Tevilat Kelim: the ritual immersion of new utensils',
                'excerpt' => 'Before using certain new kitchen utensils made by a non-Jewish manufacturer for the first time, there is a custom of immersing them in a mikveh.',
                'content' => '<p>Tevilat Kelim is the practice of immersing new kitchen utensils — metal or glass, bought from a non-Jewish manufacturer — in a mikveh (ritual bath) or a natural water source before using them for food for the first time.</p>
<p>This custom mainly applies to utensils that come into direct contact with food: pots, pans, cutlery, glass plates and cups. It usually does not apply to electric appliances (such as a toaster or a blender) nor to plastic or wooden utensils, although opinions vary depending on each community\'s tradition, so it is advisable to consult a rabbi about specific cases.</p>
<p>The process itself is simple: the utensil is cleaned well (with no labels, seals or adhesives left), fully immersed in the mikveh\'s water while a blessing is recited, and is then ready for normal use.</p>
<p>Many community mikvehs have a specific time slot available for tevilat kelim, separate from personal ritual use, as well as detailed instructions on which materials require immersion and which do not. It is one of those practices that, although it may seem like a minor detail, is an integral part of how many observant Jewish families equip their kitchen.</p>',
            ],
            'armar-cocina-kosher' => [
                'title' => 'How to set up a kosher kitchen from scratch',
                'excerpt' => 'Starting to keep a kosher kitchen can seem overwhelming at first. We give you a practical guide to the first steps.',
                'content' => '<p>Setting up a kosher kitchen from scratch is a gradual process, and you don\'t need to figure it all out in one day. These are the most common steps families starting out follow:</p>
<ul>
<li><strong>Define the physical separation:</strong> decide which utensils, pots and dishware will be meat and which dairy. The most practical approach is usually to use different colors (for example, red for meat, blue for dairy) to avoid daily mix-ups.</li>
<li><strong>Separate work surfaces:</strong> cutting boards, dish towels and sponges must also be divided by category.</li>
<li><strong>Evaluate shared appliances:</strong> oven, microwave and dishwasher can be koshered between uses or, more simply, assigned to a single category from the start (for example, microwave only for pareve).</li>
<li><strong>Buy certified products:</strong> check the certification symbol on every purchase, until it becomes an automatic habit.</li>
<li><strong>Coordinate with a rabbi:</strong> especially to kosher items that were already in the kitchen before starting this process.</li>
</ul>
<p>A strategy many people just starting out use is to gradually incorporate the separation: first everyday utensils, then table dishware, and finally appliances. There is no need to replace the entire kitchen at once, and many families take months to complete the transition without that being a halachic problem in itself.</p>',
            ],
            'certificaciones-kosher-mundo' => [
                'title' => 'Differences between kosher certifications around the world',
                'excerpt' => 'Not all kosher certifiers follow exactly the same criteria. Knowing these differences helps you choose products with confidence.',
                'content' => '<p>Although the fundamental principles of kashrut are universal, there are hundreds of certifying agencies in the world, and each can have slightly different criteria on specific topics — for example, what level of supervision it requires for bishul akum, or how it handles certain chemical additives whose origin is hard to trace.</p>
<p>Some common differences between regions:</p>
<ul>
<li><strong>United States:</strong> home to the largest industrial certifiers (OU, OK, Star-K, Kof-K), with highly standardized processes for mass export.</li>
<li><strong>Israel:</strong> the Rabbanut (chief rabbinate) offers official state certification, while organizations like the Badatz maintain additional standards considered stricter by certain communities.</li>
<li><strong>Europe:</strong> certifiers like the Beth Din of various cities (London, Paris, Zurich) supervise both local production and imports.</li>
<li><strong>Latin America:</strong> each community usually has its own local Va\'ad Hakashrut (for example, in Buenos Aires, São Paulo or Mexico City), which certifies both local products and restaurants.</li>
</ul>
<p>For the consumer, the most important thing is to learn to recognize the active certifiers in their region and, when in doubt about an unfamiliar symbol, consult the community rabbi or look into the agency\'s reputation before trusting a product. Most large certifiers publish public lists of certified products on their websites.</p>',
            ],
            'queso-kosher-cuajo' => [
                'title' => 'Kosher cheese: why it needs special rennet',
                'excerpt' => 'Cheese is one of the dairy products with the most kosher restrictions, mainly because of the origin of the rennet used to make it.',
                'content' => '<p>Rennet is the enzyme traditionally used to coagulate milk and separate the whey in cheesemaking. The problem from a kashrut standpoint is that traditional rennet is extracted from a calf\'s stomach, and for it to be fit, that animal must have been slaughtered via shechita (the kosher slaughter method) — something that almost never happens in conventional cheese manufacturing.</p>
<p>That is why practically all "regular" cheese on the market, even if made only with milk and rennet, is not kosher without specific certification, since the rennet\'s origin cannot be verified by sight.</p>
<p>Options used by kosher cheese manufacturers include:</p>
<ul>
<li><strong>Kosher animal rennet:</strong> extracted from animals slaughtered according to shechita and under rabbinic supervision throughout the chain.</li>
<li><strong>Microbial rennet:</strong> produced through fermentation, with no animal origin, increasingly common in industrial and kosher cheeses.</li>
<li><strong>Plant-based rennet:</strong> extracted from certain plants, traditionally used in some specific artisanal cheese varieties.</li>
</ul>
<p>Besides rennet, there is another relevant factor: many communities require that the cheese be made under constant Jewish supervision (Gvinat Yisrael) to consider it fully kosher, an additional criterion beyond a simple ingredient analysis. That is why buying cheese with recognized certification is the most reliable way to avoid mistakes.</p>',
            ],
            'huevos-kosher' => [
                'title' => 'Kosher eggs: what to check before using them',
                'excerpt' => 'Eggs are pareve and generally kosher, but there is a mandatory checking step before cooking them.',
                'content' => '<p>Eggs from kosher birds (such as chickens) are, in principle, pareve and fit for consumption. However, before using an egg, tradition requires checking that it contains no blood spots in the yolk, since an egg with blood is considered unfit for consumption.</p>
<p>The procedure is simple: when cracking the egg, visually check the yolk (and sometimes the white) against the light, looking for red spots or marks. If blood is found, the egg is discarded entirely; if the yolk is clean, the egg is fit to use normally.</p>
<p>Some additional facts about eggs and kashrut:</p>
<ul>
<li>The shell and white generally don\'t carry the same risk as the yolk, although custom varies by community.</li>
<li>Eggs from non-kosher birds (such as ostrich or certain birds of prey) are also not fit, regardless of the presence of blood.</li>
<li>Industrial products containing egg (such as pasta or mayonnaise) generally go through a quality control process that includes automatic detection of eggs with blood, but still require certification to guarantee that control was done correctly.</li>
</ul>
<p>It is one of the simplest habits to incorporate into a daily kosher kitchen: checking each egg as soon as it is cracked, before mixing it with the rest of the ingredients.</p>',
            ],
            'pescado-kosher-aletas-escamas' => [
                'title' => 'Kosher fish: fins and scales, the basic rules',
                'excerpt' => 'Unlike meat, kosher fish does not require shechita, but it must meet a specific physical criterion.',
                'content' => '<p>The Torah sets a relatively simple rule for identifying kosher fish: it must have both fins (snapir) and scales (kaskeset) visible to the naked eye. This combination is present in the vast majority of freshwater and saltwater fish commonly eaten, such as salmon, tuna, hake, trout and mackerel.</p>
<p>Excluded from kashrut, among others, are:</p>
<ul>
<li>All shellfish (shrimp, prawns, crab, mussels, oysters).</li>
<li>Octopus and squid.</li>
<li>Shark and monkfish (they lack true scales according to most halachic opinions).</li>
<li>Eel (lacks visible scales).</li>
<li>Swordfish (its status has historically been debated among different rabbinic authorities).</li>
</ul>
<p>An important difference from meat: kosher fish does not require shechita or a blood-removal salting process, which greatly simplifies its preparation. However, in many traditions — especially Ashkenazi — fish is treated as a category separate from meat and dairy, avoiding combining it with meat in the same dish (although it does not require the same strict utensil separation that applies between meat and milk).</p>
<p>When buying fresh fish, it\'s worth verifying that it retains skin with visible scales, since some filleting removes the skin entirely, making verification difficult. That is why many kosher fishmongers leave an identifiable patch of skin on the cut.</p>',
            ],
            'frutos-secos-contaminacion-cruzada' => [
                'title' => 'Nuts and kashrut: cross-contamination risks',
                'excerpt' => 'Nuts are naturally pareve, but industrial processing can introduce kashrut risks that are not obvious.',
                'content' => '<p>Almonds, walnuts, peanuts and most nuts are, in their raw, natural form, pareve foods with no kashrut restrictions. The problem appears when they enter the industrial processing chain, where they can mix with other products on the same production lines.</p>
<p>Some common risks:</p>
<ul>
<li><strong>Dairy flavorings:</strong> nuts "roasted with butter" or coated in milk chocolate are no longer pareve.</li>
<li><strong>Shared lines:</strong> a factory may process pareve nuts on the same equipment later used for dairy or meat-derived products, generating non-kosher traces if there is no certified cleaning between batches.</li>
<li><strong>Cooking oils:</strong> some fried nuts use oils shared with other non-kosher products.</li>
<li><strong>Glazes and coatings:</strong> "candied" nuts or those with a sweet coating may contain gelatin or other animal-derived ingredients.</li>
</ul>
<p>That is why, although a raw, unprocessed nut almost never presents problems, industrial products (nut mixes, flavored snacks, granola bars) should always be checked for certification, without assuming they are automatically kosher just because the main ingredient is.</p>',
            ],
            'kashrut-y-veganismo' => [
                'title' => 'Kashrut and veganism: is eating vegan the same as eating kosher?',
                'excerpt' => 'Many people assume a vegan product is automatically kosher. The reality is more nuanced.',
                'content' => '<p>It\'s a common confusion: if a product contains no animal-derived ingredient, it would seem logical to assume it is automatically kosher. However, kashrut is not based solely on ingredients, but also on production processes, the equipment used and, in some cases, who supervises production.</p>
<p>Some examples where a vegan product may not be kosher:</p>
<ul>
<li><strong>Shared equipment:</strong> a vegan factory may use the same production line that previously processed meat or dairy products, without the certified cleaning kashrut requires between batches.</li>
<li><strong>Wine and derivatives:</strong> a vegan wine (without animal-derived fining agents) still requires the entire production process to be in the hands of observant Jews to be kosher.</li>
<li><strong>Insects:</strong> certain dyes (such as carmine, of animal origin) are forbidden in kosher but are sometimes labeled vegan-friendly by mistake or under different vegan certification standards.</li>
<li><strong>Bishul akum:</strong> a vegan food cooked entirely by a non-Jewish person may fall under this restriction, depending on how the product is classified.</li>
</ul>
<p>Conversely, it is also true that many pareve kosher products are, in fact, vegan. But the equivalence is not automatic in either direction: the safest approach is always to look for explicit kosher certification, rather than assuming "vegan" equals "kosher."</p>',
            ],
            'separar-la-jala' => [
                'title' => 'How to separate challah',
                'excerpt' => 'Separating challah is a specific commandment that applies when kneading dough in large quantities, rooted in the Temple offerings.',
                'content' => '<p>Separating challah (hafrashat challah) is a biblical commandment that originally required giving a portion of bread dough to the priests (kohanim) of the Temple in Jerusalem. After the Temple\'s destruction, the practice changed: today, instead of being given away, the separated portion is burned or discarded respectfully.</p>
<p>This mitzvah applies when kneading a significant amount of dough made with one of the five grains (wheat, barley, oats, rye or spelt) — the exact minimum amount (generally around 1.2 kg of flour) varies depending on which halachic opinion is followed.</p>
<p>The basic process is:</p>
<ul>
<li>Knead the bread dough normally, until it reaches the required minimum amount.</li>
<li>Separate a small portion (traditionally olive-sized or larger, depending on custom).</li>
<li>Recite the corresponding blessing before separating the portion.</li>
<li>Burn the separated portion (wrapped in aluminum foil, in the oven) or discard it in a way that it is not used for regular consumption.</li>
</ul>
<p>This practice is why many certified industrial kosher bakeries separate challah as part of their production process, and why many Jewish women and families do it at home whenever they bake bread or challah for Shabbat in sufficient quantity.</p>',
            ],
            'calendario-judio-festividades-alimentacion' => [
                'title' => 'The Jewish calendar and the holidays that affect kosher eating',
                'excerpt' => 'Several Jewish holidays have their own food customs, beyond the general rules of kashrut.',
                'content' => '<p>Besides the kashrut rules that apply all year, the Jewish calendar brings holidays with their own food customs worth knowing:</p>
<ul>
<li><strong>Rosh Hashanah:</strong> it is customary to eat apple with honey to symbolize a sweet year, and to avoid bitter or sour foods at the festive table.</li>
<li><strong>Yom Kippur:</strong> a full 25-hour fast day, with no food or drink, except for specific medical exceptions.</li>
<li><strong>Sukkot:</strong> it is customary to eat in a temporary outdoor hut (sukkah) throughout the holiday week.</li>
<li><strong>Hanukkah:</strong> a tradition of eating foods fried in oil (such as sufganiyot, filled doughnuts, and latkes, potato pancakes) commemorating the miracle of the oil.</li>
<li><strong>Purim:</strong> hamantaschen (Haman\'s ears), filled triangular pastries, are prepared, and it is customary to share gift baskets of food (mishloach manot) with friends and family.</li>
<li><strong>Passover:</strong> the holiday with the most dietary restrictions, centered on the prohibition of chametz, as we saw in detail.</li>
<li><strong>Shavuot:</strong> a custom of eating dairy foods, with dishes like cheesecake and blintzes (cheese-filled pancakes) taking center stage.</li>
</ul>
<p>Knowing this calendar helps explain why certain products (such as matzah, sufganiyot or kosher-for-Passover wine) appear with greater availability on shelves and in stores at certain times of the year.</p>',
            ],
            'errores-comunes-empezar-comer-kosher' => [
                'title' => 'Common mistakes when starting to eat kosher',
                'excerpt' => 'Adopting kashrut for the first time is a learning process. We go over the most frequent mistakes to avoid from the start.',
                'content' => '<p>Starting to keep a kosher diet is a process that takes time, and it\'s normal to make mistakes at first. Here are some of the most common ones:</p>
<ul>
<li><strong>Assuming "natural" or "preservative-free" means kosher:</strong> a product\'s marketing has no direct relation to its kashrut status. You should always look for certification.</li>
<li><strong>Not checking products that seem obviously pareve:</strong> snacks, baked goods and candy sometimes contain dairy ingredients or gelatin that aren\'t obvious from the product name.</li>
<li><strong>Mixing meat and dairy utensils by accident:</strong> at first it\'s easy to forget the separation; labeling or using different colors helps a lot during the transition.</li>
<li><strong>Not checking leafy vegetables for insects:</strong> a step many people new to kashrut are completely unaware of.</li>
<li><strong>Trusting unknown or unclear certifications:</strong> not every symbol on a package is a real kosher certification; some are quality seals unrelated to kashrut.</li>
<li><strong>Not asking:</strong> many doubts are resolved quickly with a question to the community rabbi or someone with more experience, instead of guessing.</li>
</ul>
<p>The most important thing is to understand that the transition doesn\'t need to be perfect from day one. Most Jewish communities value the gradual learning process, and there are many resources — including certifiers, rabbis and tools like KosherMap — to support that journey.</p>',
            ],
        ];
    }

    private static function pt(): array
    {
        return [
            'insectos-frutas-verduras' => [
                'title' => 'Insetos em frutas e verduras: como revisá-las corretamente',
                'excerpt' => 'A Torá proíbe comer insetos, por isso revisar frutas e verduras antes de consumi-las é uma parte central de manter uma cozinha kosher.',
                'content' => '<p>A proibição de comer insetos (sheratzim) é uma das mais estritas da Torá: diferente de outras proibições alimentares, comer um único inseto pode contar como várias transgressões. Por isso, revisar frutas e verduras antes de cozinhá-las ou servi-las é um passo indispensável em uma cozinha kosher.</p>
<p>Verduras de folha como alface, espinafre, brócolis e couve-flor são as que exigem mais cuidado, porque pulgões e outros insetos pequenos se escondem entre as folhas e são difíceis de detectar a olho nu. A técnica habitual inclui separar as folhas, lavá-las individualmente sob um jato de água e revisá-las contra a luz.</p>
<ul>
<li><strong>Verduras de folha:</strong> separar folha por folha e lavar sob água corrente, revisando os dois lados.</li>
<li><strong>Couve-flor e brócolis:</strong> deixar de molho em água com um pouco de sabão ou vinagre para que os insetos se desprendam dos ramalhetes.</li>
<li><strong>Morangos e framboesas:</strong> deixar de molho em água salgada ou com vinagre antes de enxaguar.</li>
<li><strong>Leguminosas secas (lentilhas, grão-de-bico):</strong> espalhar sobre uma superfície clara e revisar antes de cozinhar.</li>
</ul>
<p>Muitas certificadoras kosher oferecem guias específicos e ilustrados de como revisar cada tipo de verdura conforme a região onde foi cultivada, já que a prevalência de insetos varia conforme o clima e o método de cultivo. Hoje também existem verduras "pré-revisadas" ou cultivadas sob supervisão específica para minimizar a infestação, o que simplifica muito o processo na cozinha do dia a dia.</p>',
            ],
            'carne-y-leche' => [
                'title' => 'Carne e leite: por que não se misturam',
                'excerpt' => 'A separação entre carne e leite é um dos pilares mais conhecidos do kashrut. Explicamos sua origem, seu alcance e como se aplica na prática.',
                'content' => '<p>A proibição de misturar carne e leite se baseia em um versículo que aparece três vezes na Torá: "Não cozinharás um cabrito no leite de sua mãe". A tradição oral interpretou essa frase como uma proibição tripla: não cozinhar, não comer e não obter nenhum benefício de uma mistura de carne e leite.</p>
<p>Na prática, isso significa que os alimentos se dividem em três categorias: <strong>cárneos</strong> (carne e derivados), <strong>lácteos</strong> (leite e derivados) e <strong>pareve</strong> (neutros, como frutas, verduras, ovos e peixe, que não são nem carne nem leite).</p>
<p>Uma cozinha kosher observante mantém utensílios, panelas, pratos e até lava-louças separados para carne e para leite, já que o calor e o uso repetido podem transferir sabores e partículas entre superfícies. Além disso, exige-se um tempo de espera entre comer carne e comer laticínios — que varia conforme o costume familiar, geralmente entre uma e seis horas — enquanto de laticínios para carne basta enxaguar a boca e comer algo neutro.</p>
<p>Essa separação é a razão pela qual muitos rótulos de produtos trazem as letras "D" (dairy/lácteo), "M" (meat/cárneo) ou "Pareve" junto ao símbolo de certificação: permite ao consumidor saber de imediato em que categoria o produto se enquadra antes de combiná-lo com outros alimentos.</p>',
            ],
            'kasherizar-horno' => [
                'title' => 'Como casherizar um forno',
                'excerpt' => 'Quando um forno foi usado com alimentos não kosher, ou se quer mudar de uso cárneo para lácteo, existe um processo específico para torná-lo apto.',
                'content' => '<p>Casherizar um forno é necessário em várias situações: ao comprar uma casa com um forno usado anteriormente de forma não kosher, ao querer mudar o uso de um forno (por exemplo, de cárneo para lácteo) ou antes de Pessach, quando é preciso eliminar todo vestígio de chametz.</p>
<p>O método tradicional para fornos é chamado <em>libun</em> (autolimpeza por calor intenso) e consiste em:</p>
<ul>
<li>Limpar bem o forno, eliminando toda sujeira e resíduo visível de comida.</li>
<li>Não usar o forno durante 24 horas antes da casherização.</li>
<li>Ligar o forno na temperatura mais alta possível (idealmente usando a função de autolimpeza, se o forno tiver) durante pelo menos uma hora.</li>
</ul>
<p>As grades e bandejas metálicas geralmente podem ser casherizadas separadamente por imersão em água fervente (hagalá), enquanto as superfícies de vidro ou esmalte geralmente exigem libun por serem materiais que absorvem mais.</p>
<p>É importante consultar um rabino antes de casherizar um forno específico, já que o procedimento exato pode variar conforme o material, o modelo e o costume (minhag) de cada comunidade. Alguns fornos modernos com revestimentos especiais podem não ser adequados para libun em alta temperatura, por isso vale a pena revisar o manual do fabricante.</p>',
            ],
            'kasherizar-microondas' => [
                'title' => 'Como casherizar um micro-ondas',
                'excerpt' => 'O micro-ondas tem um processo de casherização diferente do forno tradicional, porque cozinha com vapor e não com calor seco.',
                'content' => '<p>Diferente do forno convencional, o micro-ondas aquece os alimentos gerando vapor em seu interior, o que muda o método de casherização recomendado pela maioria das autoridades halájicas.</p>
<p>O processo mais comum inclui:</p>
<ul>
<li>Limpar minuciosamente o interior, eliminando toda partícula de comida visível, incluindo o prato giratório e as paredes.</li>
<li>Não usar o micro-ondas durante 24 horas antes de casherizá-lo.</li>
<li>Colocar um recipiente com água dentro do micro-ondas e ligá-lo até que a água ferva e gere vapor suficiente para cobrir todas as superfícies internas, incluindo a porta.</li>
<li>Deixar o vapor agir sobre as paredes durante vários minutos.</li>
</ul>
<p>Muitas famílias optam, além disso, por usar sempre uma tampa ou filme próprio para micro-ondas ao aquecer comida, e reservar o aparelho para um único uso (cárneo, lácteo ou pareve) para evitar ter que casherizá-lo repetidamente. Micro-ondas com função grill ou convecção podem exigir um processo adicional semelhante ao do forno tradicional para essa função específica. Como em qualquer casherização, é recomendável consultar um rabino sobre o caso particular do modelo e material do aparelho.</p>',
            ],
            'kasherizar-lavavajillas' => [
                'title' => 'Como casherizar uma lava-louças',
                'excerpt' => 'Muitas famílias usam a lava-louças para pratos cárneos e lácteos em ciclos separados. Contamos o que é preciso para casherizá-la.',
                'content' => '<p>A lava-louças apresenta um desafio particular porque suas paredes internas, filtros e braços aspersores estão em contato constante com restos de comida em alta temperatura, o que pode absorver sabores de maneira mais persistente que outros eletrodomésticos.</p>
<p>Por isso, muitas autoridades rabínicas são mais rigorosas com a casherização de lava-louças do que com outros aparelhos, e algumas diretamente desaconselham usá-la para as duas categorias (cárneo e lácteo), mesmo em dias diferentes. Quem permite, geralmente exige:</p>
<ul>
<li>Limpeza profunda de filtros, braços aspersores e juntas de borracha.</li>
<li>Não usar a lava-louças durante 24 horas antes de casherizá-la.</li>
<li>Rodar um ciclo completo vazio, na temperatura mais alta possível, idealmente com um produto de limpeza forte.</li>
<li>Em algumas comunidades, recomenda-se usar cestos ou bandejas separadas e intercambiáveis para cárneo e lácteo, em vez de casherizar o aparelho inteiro entre usos.</li>
</ul>
<p>Como os costumes variam bastante nesse tema — algumas comunidades sefarditas e asquenazitas diferem notavelmente —, é um dos casos em que mais vale a pena consultar diretamente o rabino da congregação antes de definir como organizar a cozinha.</p>',
            ],
            'hagala-utensilios-metal' => [
                'title' => 'Como casherizar utensílios de metal (hagalá)',
                'excerpt' => 'A hagalá é o método tradicional de imersão em água fervente para casherizar panelas, talheres e outros utensílios metálicos.',
                'content' => '<p>A hagalá é um dos métodos de casherização mais antigos e é usada principalmente em utensílios de metal que foram aquecidos diretamente com fogo ou líquido fervente, como panelas, talheres, frigideiras (sem revestimento antiaderente) e algumas outras peças de cozinha.</p>
<p>O princípio por trás da hagalá é "como absorveu, assim expele": se um utensílio absorveu sabor não kosher (ou cárneo/lácteo) por meio de líquido fervente, ele se purifica da mesma maneira, sendo submerso em água fervente.</p>
<p>O procedimento básico é:</p>
<ul>
<li>Limpar bem o utensílio, sem restos de ferrugem, comida grudada ou sujeira incrustada.</li>
<li>Esperar 24 horas sem usar o utensílio antes da hagalá.</li>
<li>Ferver uma panela grande de água até atingir fervura plena.</li>
<li>Submergir completamente o utensílio na água fervente, garantindo que todas as suas superfícies entrem em contato com a água nessa temperatura.</li>
<li>Retirá-lo com um instrumento que não tenha estado em contato com comida não kosher, e enxaguá-lo em água fria.</li>
</ul>
<p>Utensílios com cabo de madeira ou plástico, ou com peças coladas com adesivos que não resistem à água fervente, geralmente não são adequados para hagalá e precisam de outro método, ou simplesmente não podem ser casherizados. Frigideiras antiaderentes (teflon) também não costumam ser casherizadas por hagalá, já que o revestimento é danificado pelo calor.</p>',
            ],
            'vajilla-para-pesaj' => [
                'title' => 'Louças para Pessach: tudo o que você precisa saber',
                'excerpt' => 'Durante Pessach valem regras mais estritas do que o resto do ano quanto a utensílios de cozinha, devido à proibição de chametz.',
                'content' => '<p>Pessach é a festividade com as regras alimentares mais estritas do calendário judaico, porque além das normas habituais de kashrut, soma-se a proibição total de consumir ou possuir chametz (produtos fermentados de cinco grãos: trigo, cevada, aveia, centeio e espelta).</p>
<p>Como o chametz pode ter estado em contato com panelas, pratos e talheres durante todo o ano, muitas famílias optam por ter um conjunto de louças separado, exclusivo para Pessach, guardado o resto do ano. Essa é a opção mais simples e que evita ter que casherizar todo ano.</p>
<p>Quem não tem louças separadas para Pessach pode casherizar certos utensílios:</p>
<ul>
<li><strong>Metal sem revestimento</strong> (panelas, talheres): geralmente apto para hagalá.</li>
<li><strong>Vidro</strong>: conforme o costume, alguns consideram que uma boa lavagem basta, outros exigem imersão.</li>
<li><strong>Cerâmica e porcelana</strong>: em geral, não podem ser casherizadas para Pessach e deve-se usar um conjunto à parte.</li>
<li><strong>Plástico e borracha</strong>: a maioria das opiniões não permite casherizá-los.</li>
</ul>
<p>Antes de Pessach, vale consultar o guia específico de casherização da comunidade ou certificadora local, já que os prazos e métodos exatos podem variar conforme o tipo de material e o uso que o utensílio teve durante o ano.</p>',
            ],
            'jametz-pesaj' => [
                'title' => 'Chametz: o que é e como se elimina antes de Pessach',
                'excerpt' => 'O chametz é o alimento fermentado proibido durante Pessach. Conhecer quais produtos o contêm é fundamental para preparar a festividade.',
                'content' => '<p>Chametz é qualquer produto elaborado com um dos cinco grãos — trigo, cevada, aveia, centeio ou espelta — que entrou em contato com água e fermentou (cresceu) por mais de 18 minutos sem ser assado. Isso inclui pão, cerveja, a maioria das massas, biscoitos e uma enorme quantidade de produtos industrializados que usam esses grãos como ingrediente ou derivado.</p>
<p>A Torá proíbe não só comer chametz durante Pessach, mas também possuí-lo. Por isso, nas semanas anteriores à festividade, as famílias judaicas fazem uma limpeza profunda da casa (bedikat chametz) para eliminar qualquer resto de pão, farinha ou produtos com chametz de armários, carros, bolsas e qualquer canto onde possa ter caído uma migalha.</p>
<p>Para o chametz que não pode ou não convém jogar fora (por exemplo, produtos caros ou de difícil reposição), existe a opção de "vendê-lo" simbolicamente a uma pessoa não judia por meio de um contrato chamado <em>mechirat chametz</em>, geralmente coordenado pelo rabino da comunidade. O chametz vendido fica guardado fechado e à parte durante a festividade e é "recomprado" automaticamente ao final de Pessach.</p>
<p>Na noite anterior a Pessach, realiza-se uma busca ritual de chametz pela casa (bedikat chametz), geralmente com uma vela, uma pena e uma colher de madeira, seguida da queima do que foi encontrado (biur chametz) na manhã seguinte.</p>',
            ],
            'vino-kosher' => [
                'title' => 'Vinho kosher: por que precisa de supervisão especial',
                'excerpt' => 'O vinho tem um status particular na halachá: para ser kosher, deve ser elaborado e manipulado exclusivamente por judeus observantes.',
                'content' => '<p>Diferente da maioria dos alimentos, em que basta que os ingredientes e o processo cumpram certos requisitos, o vinho kosher exige ainda que toda pessoa que o toque durante sua elaboração — desde a colheita da uva até o engarrafamento — seja judia e observante. Essa regra se originou historicamente para evitar que o vinho fosse usado em rituais de idolatria.</p>
<p>Isso significa que uma vinícola que produz vinho kosher deve operar sob supervisão rabínica constante: trabalhadores não judeus podem ajudar em tarefas que não envolvem tocar diretamente no vinho ou no suco de uva, mas o processo central deve ficar nas mãos de pessoal judeu observante.</p>
<p>Existe uma categoria especial chamada <strong>vinho mevushal</strong> (literalmente "fervido"), que é vinho pasteurizado a uma temperatura específica. Uma vez mevushal, o vinho mantém seu status kosher mesmo que seja servido ou tocado por uma pessoa não judia, o que o torna muito mais prático para eventos, restaurantes e bufês onde não se pode garantir que só judeus manipulem as garrafas.</p>
<p>Hoje existem vinhos kosher de qualidade em praticamente todas as regiões vinícolas do mundo, incluindo Argentina, Chile, França, Espanha, Itália e, claro, Israel, certificados pelas principais agências rabínicas.</p>',
            ],
            'gelatina-kosher' => [
                'title' => 'Gelatina kosher: o debate halájico',
                'excerpt' => 'A gelatina é um dos ingredientes mais debatidos no mundo do kashrut, porque sua origem animal pode comprometer seu status.',
                'content' => '<p>A gelatina tradicional é obtida fervendo ossos, pele e tecido conectivo de animais — geralmente vacas ou porcos — até extrair o colágeno. Isso traz dois problemas do ponto de vista do kashrut: a origem do animal (é uma espécie kosher?) e o método de processamento (o animal foi abatido segundo a shechitá?).</p>
<p>Durante décadas, diferentes autoridades rabínicas debateram se a gelatina, ao passar por um processo químico tão transformador, muda de status halájico (um conceito chamado <em>panim chadashot</em>, ou transformação total). Algumas opiniões mais permissivas sustentaram que o processo é tão radical que o produto final já não é considerado carne em sentido halájico; a maioria das certificadoras kosher mainstream, no entanto, não aceita essa posição para gelatina de origem não kosher.</p>
<p>Por isso, hoje a grande maioria dos produtos com certificação kosher que requerem gelatina (balas, sobremesas, cápsulas de medicamentos, marshmallows) usa alternativas certificadas:</p>
<ul>
<li>Gelatina de peixe kosher.</li>
<li>Gelatina bovina de animais abatidos segundo a shechitá.</li>
<li>Substitutos vegetais como ágar-ágar ou pectina, que evitam o debate por completo.</li>
</ul>
<p>Quando um produto traz o selo de uma certificadora reconhecida, já não é preciso investigar a origem da gelatina: a certificação garante que esse ponto já foi verificado.</p>',
            ],
            'alcohol-bebidas-espirituosas' => [
                'title' => 'Álcool e bebidas espirituosas: o que é preciso para serem kosher',
                'excerpt' => 'Whisky, vodca, rum e outros destilados costumam ser kosher por natureza, mas há exceções importantes a se considerar.',
                'content' => '<p>A maioria dos destilados — whisky, vodca, rum, gin — é elaborada a partir de grãos, batata ou cana-de-açúcar, ingredientes que por si só não apresentam problemas de kashrut. Por isso, muitos destilados simples são kosher sem necessidade de certificação especial, desde que não sejam adicionados sabores, corantes ou aditivos de origem não kosher.</p>
<p>No entanto, há pontos de atenção importantes:</p>
<ul>
<li><strong>Envelhecimento em barris de vinho ou xerez:</strong> alguns whiskies e runs envelhecem em barris que antes continham vinho não kosher, o que pode comprometer seu status.</li>
<li><strong>Aromatizantes e aditivos:</strong> licores com sabor de creme, chocolate ou frutas costumam incluir ingredientes que requerem verificação.</li>
<li><strong>Bebidas à base de vinho</strong> (como vermute ou alguns licores): herdam todas as restrições do vinho kosher, incluindo a necessidade de supervisão rabínica em sua elaboração.</li>
<li><strong>Cerveja:</strong> geralmente kosher por seus ingredientes básicos (água, cevada, lúpulo, levedura), exceto variantes com aromatizantes especiais.</li>
</ul>
<p>Durante Pessach, além disso, é preciso prestar atenção especial porque muitos destilados são elaborados com grãos que constituem chametz, por isso é necessária uma certificação específica "kosher para Pessach" nessa época do ano.</p>',
            ],
            'comer-kosher-restaurante' => [
                'title' => 'Como comer kosher em um restaurante não certificado',
                'excerpt' => 'Viajar ou sair para comer sem um restaurante kosher por perto não significa quebrar a dieta. Há opções para se manter dentro das normas.',
                'content' => '<p>Nem sempre há um restaurante com certificação kosher disponível, especialmente ao viajar ou viver em cidades com pouca infraestrutura comunitária. Mesmo assim, existem estratégias para se manter dentro do kashrut em restaurantes comuns.</p>
<ul>
<li><strong>Opções vegetarianas ou veganas:</strong> ao eliminar carne e laticínios do prato, reduz-se muito o risco, embora ainda seja necessário verificar ingredientes (caldo de carne, manteiga, molhos à base animal).</li>
<li><strong>Frutas e verduras cruas:</strong> sem cozimento nem manipulação complexa, costumam ser uma opção segura em quase qualquer lugar.</li>
<li><strong>Peixe com barbatanas e escamas:</strong> em restaurantes de cozinha simples, um peixe grelhado sem molhos pode ser uma alternativa razoável para quem segue um critério mais flexível (desde que não seja cozido junto com frutos do mar ou carne não kosher no mesmo equipamento, conforme o critério de cada pessoa).</li>
<li><strong>Bebidas engarrafadas e lacradas:</strong> água, refrigerantes e sucos na embalagem original geralmente não apresentam problemas.</li>
</ul>
<p>Cada pessoa e cada comunidade tem um nível de rigor diferente sobre o que é considerado aceitável fora de um restaurante certificado (algumas só comem produtos embalados e lacrados, outras aceitam certos preparos simples). Na dúvida, o mais recomendável é consultar o rabino da congregação sobre qual critério seguir.</p>',
            ],
            'simbolos-certificacion-kosher' => [
                'title' => 'Símbolos de certificação kosher mais comuns',
                'excerpt' => 'OU, OK, Star-K, KSA... existem dezenas de símbolos de certificação kosher no mundo. Ajudamos você a reconhecer os mais usados.',
                'content' => '<p>Quando um produto passa pelo processo de certificação kosher, a agência certificadora autoriza o uso de um símbolo (hechsher) na embalagem que permite identificá-lo rapidamente. Existem centenas de certificadoras no mundo, mas algumas são especialmente conhecidas por seu alcance global.</p>
<ul>
<li><strong>OU (Orthodox Union):</strong> um "U" dentro de um círculo. É provavelmente o símbolo kosher mais reconhecido mundialmente, sediado nos Estados Unidos.</li>
<li><strong>OK Kosher Certification:</strong> um "K" dentro de um círculo, outra das grandes agências americanas.</li>
<li><strong>Star-K:</strong> uma estrela com um "K" no centro.</li>
<li><strong>KSA (Kosher Supervision of America):</strong> certificadora com forte presença em produtos industriais.</li>
<li><strong>Badatz:</strong> selo utilizado por vários tribunais rabínicos em Israel, associado a padrões de rigor muito altos.</li>
<li><strong>Certificadoras locais:</strong> em países como Argentina, Brasil ou México existem certificadoras comunitárias locais (como o Vaad Hakashrut de cada kehilá) com seus próprios selos.</li>
</ul>
<p>Além do símbolo, muitos rótulos incluem uma letra adicional: "D" (dairy/lácteo), "M" (meat/cárneo), "Pareve" (neutro) ou "DE" (dairy equipment, elaborado em equipamento lácteo mas sem ingredientes lácteos diretos). Conhecer esses símbolos agiliza muito as compras, principalmente ao viajar para países onde não se domina o idioma local.</p>',
            ],
            'que-significa-pareve' => [
                'title' => 'Pareve: o que significa e por que é tão comum nos rótulos',
                'excerpt' => 'Pareve é uma das palavras mais repetidas na rotulagem kosher. Explicamos o que significa e por que é tão valorizada.',
                'content' => '<p>"Pareve" (também escrito parve) descreve os alimentos que não são nem cárneos nem lácteos: frutas, verduras, ovos, peixe, grãos e a maioria dos produtos elaborados sem ingredientes de origem animal láctea ou cárnea.</p>
<p>A grande vantagem de um produto pareve é sua flexibilidade: pode se combinar livremente tanto com refeições cárneas quanto lácteas, sem gerar nenhum conflito de kashrut. Por isso, muitas indústrias alimentícias buscam ativamente desenvolver versões pareve de produtos que tradicionalmente levam laticínios — como chocolate, margarina ou substitutos de creme — para ampliar seu mercado.</p>
<p>É importante esclarecer um detalhe: um alimento pode ser pareve pelos ingredientes, mas perder esse status se foi elaborado em equipamento que também processa laticínios ou carne, dependendo dos traços que possam restar. Por isso a certificação não analisa apenas ingredientes, mas também o equipamento de produção e os processos de limpeza entre lotes.</p>
<p>Alguns exemplos comuns de produtos pareve: azeite de oliva, massa seca sem ovo, a maioria dos pães (embora alguns levem manteiga e passem a ser lácteos), castanhas sem processamento, e refrigerantes. Revisar sempre o rótulo é a única forma certeira de confirmar, já que a receita pode variar entre marcas ou até entre apresentações da mesma marca.</p>',
            ],
            'shejita-sacrificio-kosher' => [
                'title' => 'Shechitá: o método de abate kosher',
                'excerpt' => 'Para que a carne de um animal kosher seja apta para consumo, deve ser abatida segundo um método ritual específico chamado shechitá.',
                'content' => '<p>A shechitá é o método de abate ritual judaico, realizado por um shochet (abatedor capacitado e certificado) usando uma faca extremamente afiada e sem mossas, projetada especificamente para produzir um corte rápido e preciso na garganta do animal, seccionando a traqueia e o esôfago em um único movimento contínuo.</p>
<p>O objetivo desse método é minimizar o sofrimento do animal e produzir uma perda de consciência praticamente instantânea. O shochet inspeciona a faca antes e depois de cada abate para garantir que não tenha nenhuma imperfeição, por mínima que seja, já que qualquer irregularidade invalida o procedimento.</p>
<p>Após a shechitá, é feita uma inspeção (bedika) dos órgãos internos do animal, especialmente os pulmões, para descartar doenças ou aderências que invalidariam a carne como kosher. Apenas os animais que passam nessa inspeção são considerados aptos.</p>
<p>Além disso, a carne deve passar por um processo de salga (kashering) para extrair o sangue, já que a Torá proíbe consumir sangue. Isso é feito deixando a carne de molho em água, salgando-a e deixando-a descansar antes de enxaguá-la novamente — um processo que hoje em dia geralmente é realizado pelo próprio açougue ou frigorífico certificado antes de o produto chegar ao consumidor.</p>',
            ],
            'glatt-kosher' => [
                'title' => 'Glatt kosher: que diferença há para o kosher comum',
                'excerpt' => 'O termo "glatt" aparece frequentemente em açougues e restaurantes kosher. Explicamos que nível de rigor representa.',
                'content' => '<p>"Glatt" significa "liso" em iídiche e originalmente se referia especificamente ao estado dos pulmões de um animal após a shechitá: se os pulmões não apresentassem nenhuma aderência (sircá), o animal era considerado "glatt", o nível mais alto de certeza de que a carne é kosher sem dúvida alguma.</p>
<p>Com o tempo, especialmente em comunidades asquenazitas dos Estados Unidos, o termo "glatt kosher" se estendeu coloquialmente para descrever um padrão geral de maior rigor em toda a cadeia de produção de um alimento, não só na inspeção de pulmões. Hoje é comum ver "glatt kosher" em rótulos de restaurantes e produtos para indicar que cumprem os critérios mais exigentes possíveis.</p>
<p>É importante destacar que um produto "kosher" sem o rótulo "glatt" não é menos válido halajicamente: simplesmente segue um padrão de certificação diferente, geralmente aceito pela ampla maioria das comunidades. A escolha entre kosher padrão e glatt kosher costuma depender do costume familiar ou comunitário, mais do que de uma diferença objetiva de validade.</p>
<p>No caso de aves e peixes, o conceito de "glatt" tecnicamente não se aplica da mesma forma que em mamíferos, embora coloquialmente às vezes seja usado para indicar um nível de supervisão mais rigoroso em geral.</p>',
            ],
            'como-leer-etiqueta-kosher' => [
                'title' => 'Como ler um rótulo de produto kosher',
                'excerpt' => 'Além do símbolo de certificação, os rótulos kosher contêm informações-chave para saber se um produto é apto para sua mesa.',
                'content' => '<p>Ler corretamente um rótulo kosher vai além de procurar o símbolo de certificação. Há vários elementos que vale a pena sempre revisar:</p>
<ul>
<li><strong>O símbolo da certificadora:</strong> identifica qual agência supervisionou o produto. É importante reconhecer certificadoras confiáveis, já que nem todos os símbolos do mundo têm o mesmo nível de exigência.</li>
<li><strong>A categoria:</strong> "Dairy" ou "D" (lácteo), "Meat" ou "M" (cárneo), "Pareve" (neutro), ou "Fish" (peixe, que em muitas tradições é tratado como categoria separada da carne).</li>
<li><strong>"Kosher para Pessach":</strong> indicação adicional necessária durante a festividade, diferente da certificação kosher habitual do resto do ano.</li>
<li><strong>Data de certificação:</strong> algumas certificadoras incluem um código ou data para verificar se o selo continua válido, já que receitas e processos de fábrica podem mudar.</li>
</ul>
<p>Quando um produto não tem certificação visível mas a lista de ingredientes parece simples (por exemplo, só água, sal e um vegetal), algumas pessoas optam por investigar mais, mas a recomendação geral das autoridades de kashrut é não assumir que um produto é kosher só pela aparência de seus ingredientes: muitos aditivos e processos industriais não são evidentes à primeira vista.</p>
<p>No KosherMap você pode buscar produtos por nome ou código de barras e filtrar diretamente por certificadora, categoria e tipo, para não depender só do rótulo físico.</p>',
            ],
            'bishul-akum' => [
                'title' => 'Bishul Akum: por que alguns alimentos cozidos precisam de supervisão judaica',
                'excerpt' => 'Existe uma categoria de leis específica sobre alimentos cozinhados por não judeus, conhecida como bishul akum. Explicamos do que se trata.',
                'content' => '<p>Bishul akum (literalmente "cozimento de um não judeu") é uma categoria de leis rabínicas que restringe o consumo de certos alimentos cozinhados inteiramente por uma pessoa não judia, mesmo que todos os ingredientes sejam kosher. A proibição foi estabelecida pelos sábios talmúdicos, principalmente para fomentar a coesão social e evitar a assimilação cultural.</p>
<p>Essa lei não se aplica a todos os alimentos: geralmente se limita a alimentos considerados "dignos de servir na mesa de um rei" (chashivut) e que não se comem crus. Por isso, frutas, verduras cruas e a maioria dos lanches industrializados não entram nessa categoria.</p>
<p>Há duas formas habituais de resolver o problema em um contexto de produção industrial ou restaurantes certificados:</p>
<ul>
<li>Que um judeu observante participe ativamente do processo de cozimento, por exemplo, acendendo o fogo ou o equipamento de cocção.</li>
<li>Que a supervisão rabínica certifique que um representante judeu esteve presente durante o acendimento dos equipamentos de cocção em cada turno de produção.</li>
</ul>
<p>Essa é uma das razões pelas quais a certificação kosher de uma fábrica de alimentos não se limita a revisar ingredientes: também supervisiona processos, presença de pessoal e protocolos operacionais do estabelecimento, o que torna o trabalho das certificadoras muito mais complexo do que uma simples lista de checagem de insumos.</p>',
            ],
            'vino-mevushal' => [
                'title' => 'Mevushal: vinho kosher que pode ser servido sem restrições',
                'excerpt' => 'O vinho mevushal é uma categoria especial que permite servi-lo em eventos sem necessidade de que só judeus o manipulem.',
                'content' => '<p>Como vimos ao falar sobre vinho kosher, a regra geral exige que só judeus observantes manipulem o vinho desde a elaboração até o serviço. O vinho mevushal ("fervido" ou pasteurizado) é uma exceção prática a essa regra: uma vez que o vinho passa por um processo de aquecimento a uma temperatura mínima específica, ele mantém seu status kosher independentemente de quem o sirva depois.</p>
<p>Essa categoria existe graças a um princípio halájico segundo o qual o vinho que foi alterado por meio de calor perde a "dignidade" ritual que originalmente motivou a restrição, já que historicamente essa preocupação visava o uso do vinho em cerimônias idólatras — algo que um vinho fervido não se prestava a fazer nesse contexto.</p>
<p>O vinho mevushal é muito popular em:</p>
<ul>
<li>Bufês e eventos onde a equipe de serviço não é necessariamente judia.</li>
<li>Restaurantes kosher abertos ao público geral.</li>
<li>Companhias aéreas e hotéis que oferecem opções kosher.</li>
</ul>
<p>Hoje existem técnicas modernas de pasteurização rápida (flash pasteurization) que permitem produzir vinho mevushal de alta qualidade, o que historicamente era mais difícil de conseguir sem afetar o sabor do vinho. Isso ampliou muito a oferta de vinhos mevushal premium disponíveis no mercado.</p>',
            ],
            'tevilat-kelim' => [
                'title' => 'Tevilat Kelim: a imersão ritual de utensílios novos',
                'excerpt' => 'Antes de usar pela primeira vez certos utensílios de cozinha fabricados por não judeus, existe o costume de submergi-los em um mikvê.',
                'content' => '<p>Tevilat Kelim é a prática de submergir utensílios de cozinha novos — de metal ou vidro, comprados de um fabricante não judeu — em um mikvê (banho ritual) ou uma fonte de água natural antes de usá-los pela primeira vez para alimentos.</p>
<p>Esse costume se aplica principalmente a utensílios que entram em contato direto com a comida: panelas, frigideiras, talheres, pratos de vidro e copos. Geralmente não se aplica a utensílios elétricos (como uma torradeira ou liquidificador) nem a utensílios de plástico ou madeira, embora as opiniões variem conforme a tradição de cada comunidade, por isso é recomendável consultar um rabino sobre casos específicos.</p>
<p>O processo em si é simples: o utensílio é bem limpo (sem restos de etiquetas, lacres ou adesivos), submergido completamente na água do mikvê enquanto se recita uma bênção, e então está pronto para ser usado normalmente.</p>
<p>Muitos mikvês comunitários têm um horário específico habilitado para tevilat kelim, separado do uso ritual pessoal, assim como instruções detalhadas sobre quais materiais requerem imersão e quais não. É uma dessas práticas que, embora pareça um detalhe menor, faz parte integral de como muitas famílias judaicas observantes equipam sua cozinha.</p>',
            ],
            'armar-cocina-kosher' => [
                'title' => 'Como montar uma cozinha kosher do zero',
                'excerpt' => 'Começar a manter uma cozinha kosher pode parecer assustador no início. Damos um guia prático dos primeiros passos.',
                'content' => '<p>Montar uma cozinha kosher do zero é um processo gradual, e não é preciso resolver tudo em um dia. Estes são os passos mais comuns que famílias iniciantes seguem:</p>
<ul>
<li><strong>Definir a separação física:</strong> estabelecer quais utensílios, panelas e louças serão cárneos e quais lácteos. O mais prático costuma ser usar cores diferentes (por exemplo, vermelho para carne, azul para leite) para evitar confusões diárias.</li>
<li><strong>Separar as superfícies de trabalho:</strong> tábuas de corte, panos de prato e esponjas também devem ser divididos por categoria.</li>
<li><strong>Avaliar eletrodomésticos compartilhados:</strong> forno, micro-ondas e lava-louças podem ser casherizados entre usos ou, mais simples, designados a uma só categoria desde o início (por exemplo, micro-ondas só para pareve).</li>
<li><strong>Comprar produtos certificados:</strong> revisar o símbolo de certificação em cada compra, até que vire um hábito automático.</li>
<li><strong>Coordenar com um rabino:</strong> especialmente para casherizar itens que já estavam na cozinha antes de começar esse processo.</li>
</ul>
<p>Uma estratégia muito usada por quem está começando é incorporar a separação aos poucos: primeiro os utensílios de uso diário, depois as louças de mesa, e finalmente os eletrodomésticos. Não é necessário trocar toda a cozinha de uma vez, e muitas famílias levam meses para completar a transição sem que isso seja um problema halájico em si.</p>',
            ],
            'certificaciones-kosher-mundo' => [
                'title' => 'Diferenças entre as certificações kosher ao redor do mundo',
                'excerpt' => 'Nem todas as certificadoras kosher seguem exatamente os mesmos critérios. Conhecer essas diferenças ajuda a escolher produtos com confiança.',
                'content' => '<p>Embora os princípios fundamentais do kashrut sejam universais, existem centenas de agências certificadoras no mundo, e cada uma pode ter critérios ligeiramente diferentes sobre temas específicos — por exemplo, qual nível de supervisão exige para bishul akum, ou como aborda certos aditivos químicos cuja origem é difícil de rastrear.</p>
<p>Algumas diferenças comuns entre regiões:</p>
<ul>
<li><strong>Estados Unidos:</strong> tem as maiores certificadoras a nível industrial (OU, OK, Star-K, Kof-K), com processos muito padronizados para exportação em massa.</li>
<li><strong>Israel:</strong> o Rabanut (rabinato) oferece certificação oficial estatal, enquanto organizações como o Badatz mantêm padrões adicionais considerados mais estritos por certas comunidades.</li>
<li><strong>Europa:</strong> certificadoras como o Beth Din de várias cidades (Londres, Paris, Zurique) supervisionam tanto a produção local quanto importações.</li>
<li><strong>América Latina:</strong> cada comunidade costuma ter seu Vaad Hakashrut local (por exemplo, em Buenos Aires, São Paulo ou Cidade do México), que certifica tanto produtos locais quanto restaurantes.</li>
</ul>
<p>Para o consumidor, o mais importante é aprender a reconhecer as certificadoras ativas em sua região e, em caso de dúvida sobre um símbolo desconhecido, consultar o rabino da comunidade ou pesquisar a reputação da agência antes de confiar em um produto. A maioria das grandes certificadoras publica listas públicas de produtos certificados em seus sites.</p>',
            ],
            'queso-kosher-cuajo' => [
                'title' => 'Queijo kosher: por que precisa de coalho especial',
                'excerpt' => 'O queijo é um dos produtos lácteos com mais restrições kosher, principalmente pela origem do coalho usado para elaborá-lo.',
                'content' => '<p>O coalho (rennet) é a enzima usada tradicionalmente para coagular o leite e separar o soro na elaboração do queijo. O problema do ponto de vista do kashrut é que o coalho tradicional é extraído do estômago de bezerros, e para ser apto, esse animal deve ter sido abatido por meio da shechitá (o método de abate kosher) — algo que na indústria queijeira convencional quase nunca acontece.</p>
<p>Por isso, praticamente todo o queijo "comum" do mercado, mesmo feito só com leite e coalho, não é kosher sem certificação específica, já que a origem do coalho não pode ser verificada a olho nu.</p>
<p>As opções usadas pelos fabricantes de queijo kosher incluem:</p>
<ul>
<li><strong>Coalho animal kosher:</strong> extraído de animais abatidos segundo a shechitá e sob supervisão rabínica em toda a cadeia.</li>
<li><strong>Coalho microbiano:</strong> produzido por fermentação, sem origem animal, cada vez mais comum em queijos industriais e kosher.</li>
<li><strong>Coalho vegetal:</strong> extraído de certas plantas, usado tradicionalmente em algumas variedades específicas de queijos artesanais.</li>
</ul>
<p>Além do coalho, há outro fator relevante: muitas comunidades exigem que o queijo seja elaborado sob supervisão judaica constante (Gvinat Yisrael) para considerá-lo plenamente kosher, um critério adicional à simples análise de ingredientes. Por isso, comprar queijo com certificação reconhecida é a forma mais confiável de evitar erros.</p>',
            ],
            'huevos-kosher' => [
                'title' => 'Ovos kosher: o que revisar antes de usá-los',
                'excerpt' => 'Os ovos são pareve e geralmente kosher, mas existe um passo de revisão obrigatório antes de cozinhá-los.',
                'content' => '<p>Os ovos de aves kosher (como a galinha) são, em princípio, pareve e aptos para consumo. No entanto, antes de usar um ovo, a tradição exige revisar que não contenha manchas de sangue na gema, já que um ovo com sangue é considerado não apto para consumo.</p>
<p>O procedimento é simples: ao quebrar o ovo, revisa-se visualmente a gema (e às vezes a clara) contra a luz, procurando pontos vermelhos ou manchas. Se for encontrado sangue, o ovo é descartado por completo; se a gema estiver limpa, o ovo é apto para usar normalmente.</p>
<p>Alguns dados adicionais sobre ovos e kashrut:</p>
<ul>
<li>A casca e a clara geralmente não apresentam o mesmo risco que a gema, embora o costume varie conforme a comunidade.</li>
<li>Ovos de aves não kosher (como avestruz ou certas aves de rapina) também não são aptos, independentemente da presença de sangue.</li>
<li>Produtos industrializados com ovo (como massas ou maionese) geralmente passam por um processo de controle de qualidade que inclui a detecção automática de ovos com sangue, mas ainda exigem certificação para garantir que esse controle foi feito corretamente.</li>
</ul>
<p>É um dos hábitos mais simples de incorporar em uma cozinha kosher diária: revisar cada ovo assim que é quebrado, antes de misturá-lo com o restante dos ingredientes.</p>',
            ],
            'pescado-kosher-aletas-escamas' => [
                'title' => 'Peixe kosher: barbatanas e escamas, as regras básicas',
                'excerpt' => 'Diferente da carne, o peixe kosher não requer shechitá, mas deve cumprir um critério físico específico.',
                'content' => '<p>A Torá estabelece uma regra relativamente simples para identificar peixe kosher: deve ter tanto barbatanas (snapir) quanto escamas (kaskeset) visíveis a olho nu. Essa combinação está presente na grande maioria dos peixes de água doce e salgada consumidos habitualmente, como salmão, atum, merluza, truta e cavala.</p>
<p>Ficam excluídos do kashrut, entre outros:</p>
<ul>
<li>Todos os frutos do mar (camarão, lagostim, caranguejo, mexilhão, ostra).</li>
<li>Polvo e lula.</li>
<li>Tubarão e tamboril (carecem de escamas verdadeiras segundo a maioria das opiniões halájicas).</li>
<li>Enguia (carece de escamas visíveis).</li>
<li>Peixe-espada (seu status é objeto de debate histórico entre diferentes autoridades rabínicas).</li>
</ul>
<p>Uma diferença importante em relação à carne: o peixe kosher não requer shechitá nem um processo de salga para extrair sangue, o que simplifica bastante seu preparo. No entanto, em muitas tradições — especialmente asquenazitas — o peixe é tratado como uma categoria separada da carne e dos laticínios, evitando combiná-lo com carne no mesmo prato (embora não exija a mesma separação estrita de utensílios que rege entre carne e leite).</p>
<p>Ao comprar peixe fresco, vale verificar se ele mantém a pele com escamas visíveis, já que algumas filetagens removem completamente a pele, dificultando a verificação. Por isso muitas peixarias kosher deixam uma porção de pele identificável no corte.</p>',
            ],
            'frutos-secos-contaminacion-cruzada' => [
                'title' => 'Castanhas e kashrut: riscos de contaminação cruzada',
                'excerpt' => 'As castanhas são naturalmente pareve, mas o processamento industrial pode introduzir riscos de kashrut que não são evidentes.',
                'content' => '<p>Amêndoas, nozes, amendoim e a maioria das castanhas são, em sua forma crua e natural, alimentos pareve sem restrições de kashrut. O problema aparece quando entram na cadeia de processamento industrial, onde podem se misturar com outros produtos nas mesmas linhas de produção.</p>
<p>Alguns riscos comuns:</p>
<ul>
<li><strong>Aromatizantes lácteos:</strong> castanhas "tostadas com manteiga" ou com cobertura de chocolate ao leite deixam de ser pareve.</li>
<li><strong>Linhas compartilhadas:</strong> uma fábrica pode processar castanhas pareve no mesmo equipamento onde depois processa produtos com leite ou derivados cárneos, gerando traços não kosher se não houver limpeza certificada entre lotes.</li>
<li><strong>Óleos de cozimento:</strong> algumas castanhas fritas usam óleos compartilhados com outros produtos não kosher.</li>
<li><strong>Glaceados e coberturas:</strong> castanhas "carameladas" ou com cobertura doce podem conter gelatina ou outros ingredientes de origem animal.</li>
</ul>
<p>Por isso, embora uma castanha crua e sem processamento quase nunca apresente problemas, os produtos industrializados (mix de castanhas, lanches aromatizados, barras de cereal) sempre devem ser verificados quanto à certificação, sem assumir que são automaticamente kosher só porque o ingrediente principal é.</p>',
            ],
            'kashrut-y-veganismo' => [
                'title' => 'Kashrut e veganismo: comer vegano é o mesmo que comer kosher?',
                'excerpt' => 'Muitas pessoas assumem que um produto vegano é automaticamente kosher. A realidade é mais sutil.',
                'content' => '<p>É uma confusão comum: se um produto não contém nenhum ingrediente de origem animal, pareceria lógico assumir que é automaticamente kosher. No entanto, o kashrut não se baseia apenas nos ingredientes, mas também nos processos de elaboração, no equipamento utilizado e, em alguns casos, em quem supervisiona a produção.</p>
<p>Alguns exemplos em que um produto vegano pode não ser kosher:</p>
<ul>
<li><strong>Equipamento compartilhado:</strong> uma fábrica vegana pode usar a mesma linha de produção que antes processava produtos cárneos ou lácteos, sem a limpeza certificada que o kashrut exige entre lotes.</li>
<li><strong>Vinho e derivados:</strong> um vinho vegano (sem clarificantes de origem animal) ainda exige que todo o processo de elaboração esteja nas mãos de judeus observantes para ser kosher.</li>
<li><strong>Insetos:</strong> certos corantes (como a cochonilha, de origem animal) são proibidos no kosher mas às vezes são rotulados como adequados para veganos por erro ou por padrões diferentes de certificação vegana.</li>
<li><strong>Bishul akum:</strong> um alimento vegano cozido inteiramente por uma pessoa não judia pode cair nessa restrição, dependendo de como o produto é classificado.</li>
</ul>
<p>Por outro lado, também é verdade que muitos produtos kosher pareve são, de fato, veganos. Mas a equivalência não é automática em nenhum sentido: o mais seguro é sempre buscar a certificação kosher explícita, em vez de assumir que "vegano" equivale a "kosher".</p>',
            ],
            'separar-la-jala' => [
                'title' => 'Como separar a chalá',
                'excerpt' => 'Separar a chalá é um mandamento específico que se aplica ao sovar massa em grandes quantidades, com raízes nas ofertas do Templo.',
                'content' => '<p>A separação de chalá (hafrashat chalá) é um mandamento bíblico que originalmente exigia entregar uma porção da massa de pão aos sacerdotes (kohanim) do Templo de Jerusalém. Após a destruição do Templo, a prática se transformou: hoje, em vez de ser entregue, a porção separada é queimada ou descartada de maneira respeitosa.</p>
<p>Essa mitzvá se aplica ao sovar uma quantidade significativa de massa feita com um dos cinco grãos (trigo, cevada, aveia, centeio ou espelta) — a quantidade mínima exata (geralmente em torno de 1,2 kg de farinha) varia conforme a opinião halájica seguida.</p>
<p>O processo básico é:</p>
<ul>
<li>Sovar a massa de pão normalmente, até atingir a quantidade mínima exigida.</li>
<li>Separar uma pequena porção (tradicionalmente do tamanho de uma azeitona ou maior, conforme o costume).</li>
<li>Recitar a bênção correspondente antes de separar a porção.</li>
<li>Queimar a porção separada (envolta em papel alumínio, no forno) ou descartá-la de forma que não seja usada para consumo regular.</li>
</ul>
<p>Essa prática é a razão pela qual muitas padarias kosher industriais certificadas separam chalá como parte de seu processo de produção, e pela qual muitas mulheres e famílias judaicas a realizam em casa sempre que assam pão ou chalá para Shabat em quantidade suficiente.</p>',
            ],
            'calendario-judio-festividades-alimentacion' => [
                'title' => 'O calendário judaico e as festividades que afetam a alimentação kosher',
                'excerpt' => 'Várias festividades judaicas têm costumes alimentares específicos, além das regras gerais do kashrut.',
                'content' => '<p>Além das normas de kashrut válidas o ano todo, o calendário judaico traz festividades com costumes alimentares próprios que vale a pena conhecer:</p>
<ul>
<li><strong>Rosh Hashaná:</strong> costuma-se comer maçã com mel para simbolizar um ano doce, e evitar alimentos amargos ou ácidos na mesa festiva.</li>
<li><strong>Yom Kipur:</strong> dia de jejum completo de 25 horas, sem comida nem bebida, salvo exceções médicas específicas.</li>
<li><strong>Sucot:</strong> costuma-se comer em uma cabana temporária (suká) ao ar livre durante toda a semana da festividade.</li>
<li><strong>Chanucá:</strong> tradição de comer alimentos fritos em óleo (como os suganiot, sonhos recheados, e os latkes, panquecas de batata) em comemoração ao milagre do óleo.</li>
<li><strong>Purim:</strong> preparam-se hamantaschen (orelhas de Haman), massas triangulares recheadas, e costuma-se compartilhar cestas de comida (mishloach manot) com amigos e família.</li>
<li><strong>Pessach:</strong> a festividade com mais restrições alimentares, centrada na proibição de chametz, como já vimos em detalhe.</li>
<li><strong>Shavuot:</strong> costume de comer alimentos lácteos, com pratos como cheesecake e blintzes (panquecas recheadas de queijo) como protagonistas.</li>
</ul>
<p>Conhecer esse calendário ajuda a entender por que certos produtos (como matzá, suganiot ou vinho kosher para Pessach) aparecem com maior disponibilidade nas prateleiras e comércios em determinadas épocas do ano.</p>',
            ],
            'errores-comunes-empezar-comer-kosher' => [
                'title' => 'Erros comuns ao começar a comer kosher',
                'excerpt' => 'Adotar o kashrut pela primeira vez implica um processo de aprendizado. Revisamos os erros mais frequentes para evitá-los desde o início.',
                'content' => '<p>Começar a seguir uma dieta kosher é um processo que leva tempo, e é normal cometer erros no início. Estes são alguns dos mais comuns:</p>
<ul>
<li><strong>Assumir que "natural" ou "sem conservantes" significa kosher:</strong> o marketing de um produto não tem relação direta com seu status de kashrut. Sempre é preciso buscar a certificação.</li>
<li><strong>Não revisar produtos que parecem obviamente pareve:</strong> lanches, produtos de panificação e doces às vezes contêm ingredientes lácteos ou gelatina não evidentes no nome do produto.</li>
<li><strong>Misturar utensílios cárneos e lácteos por descuido:</strong> no início é fácil esquecer a separação; etiquetar ou usar cores diferentes ajuda muito durante a transição.</li>
<li><strong>Não revisar verduras de folha quanto a insetos:</strong> um passo que muitas pessoas novas no kashrut desconhecem completamente.</li>
<li><strong>Confiar em certificações desconhecidas ou pouco claras:</strong> nem todos os símbolos em uma embalagem são certificações kosher reais; alguns são selos de qualidade sem relação com o kashrut.</li>
<li><strong>Não perguntar:</strong> muitas dúvidas se resolvem rapidamente com uma consulta ao rabino da comunidade ou a alguém com mais experiência, em vez de adivinhar.</li>
</ul>
<p>O mais importante é entender que a transição não precisa ser perfeita desde o primeiro dia. A maioria das comunidades judaicas valoriza o processo de aprendizado gradual, e há muitos recursos — incluindo certificadoras, rabinos e ferramentas como o KosherMap — para acompanhar esse caminho.</p>',
            ],
        ];
    }

    private static function fr(): array
    {
        return [
            'insectos-frutas-verduras' => [
                'title' => 'Insectes dans les fruits et légumes : comment bien les vérifier',
                'excerpt' => 'La Torah interdit de manger des insectes, c\'est pourquoi vérifier les fruits et légumes avant de les consommer est essentiel pour une cuisine cachère.',
                'content' => '<p>L\'interdiction de manger des insectes (sheratzim) est l\'une des plus strictes de la Torah : contrairement à d\'autres interdits alimentaires, manger un seul insecte peut compter comme plusieurs transgressions. C\'est pourquoi vérifier les fruits et légumes avant de les cuisiner ou de les servir est une étape incontournable dans une cuisine cachère.</p>
<p>Les légumes-feuilles comme la laitue, les épinards, le brocoli et le chou-fleur demandent le plus de vigilance, car les pucerons et autres petits insectes se cachent entre les feuilles et sont difficiles à repérer à l\'œil nu. La technique habituelle consiste à séparer les feuilles, à les laver individuellement sous l\'eau courante et à les examiner à contre-jour.</p>
<ul>
<li><strong>Légumes-feuilles :</strong> séparer feuille par feuille et laver à l\'eau courante, en vérifiant les deux côtés.</li>
<li><strong>Chou-fleur et brocoli :</strong> faire tremper dans l\'eau avec un peu de savon ou de vinaigre pour détacher les insectes des bouquets.</li>
<li><strong>Fraises et framboises :</strong> tremper dans de l\'eau salée ou vinaigrée avant de rincer.</li>
<li><strong>Légumineuses sèches (lentilles, pois chiches) :</strong> étaler sur une surface claire et vérifier avant cuisson.</li>
</ul>
<p>De nombreux organismes de certification cachère proposent des guides illustrés détaillant comment vérifier chaque type de légume selon la région où il a été cultivé, car la prévalence des insectes varie selon le climat et la méthode de culture. Aujourd\'hui, il existe aussi des légumes "pré-vérifiés" ou cultivés sous supervision spécifique pour minimiser l\'infestation, ce qui simplifie beaucoup le processus au quotidien.</p>',
            ],
            'carne-y-leche' => [
                'title' => 'Viande et lait : pourquoi ils ne se mélangent pas',
                'excerpt' => 'La séparation entre viande et lait est l\'un des piliers les plus connus de la cacherout. Nous expliquons son origine, sa portée et son application pratique.',
                'content' => '<p>L\'interdiction de mélanger viande et lait repose sur un verset qui apparaît trois fois dans la Torah : "Tu ne cuiras pas un chevreau dans le lait de sa mère". La tradition orale a interprété cette phrase comme une triple interdiction : ne pas cuire, ne pas manger et ne tirer aucun bénéfice d\'un mélange de viande et de lait.</p>
<p>En pratique, cela signifie que les aliments se divisent en trois catégories : <strong>carnés</strong> (viande et dérivés), <strong>lactés</strong> (lait et dérivés) et <strong>parve</strong> (neutres, comme les fruits, légumes, œufs et poisson, qui ne sont ni viande ni lait).</p>
<p>Une cuisine cachère pratiquante conserve des ustensiles, casseroles, assiettes et même lave-vaisselle séparés pour la viande et pour le lait, car la chaleur et l\'usage répété peuvent transférer saveurs et particules entre surfaces. De plus, un temps d\'attente est exigé entre manger de la viande et manger des produits laitiers — variable selon les coutumes familiales, généralement entre une et six heures — tandis que passer du lait à la viande nécessite seulement de se rincer la bouche et de manger quelque chose de neutre.</p>
<p>Cette séparation explique pourquoi tant d\'étiquettes de produits portent les lettres "D" (dairy/lacté), "M" (meat/carné) ou "Pareve" à côté du symbole de certification : cela permet au consommateur de savoir immédiatement dans quelle catégorie se situe un produit avant de le combiner avec d\'autres aliments.</p>',
            ],
            'kasherizar-horno' => [
                'title' => 'Comment cachériser un four',
                'excerpt' => 'Lorsqu\'un four a été utilisé avec des aliments non cachères, ou pour le faire passer d\'un usage carné à laitier, il existe un processus spécifique pour le rendre apte.',
                'content' => '<p>Cachériser un four est nécessaire dans plusieurs situations : lors de l\'achat d\'une maison avec un four utilisé précédemment de façon non cachère, pour changer l\'usage d\'un four (par exemple, de carné à laitier) ou avant Pessah, lorsqu\'il faut éliminer toute trace de hametz.</p>
<p>La méthode traditionnelle pour les fours s\'appelle <em>liboun</em> (autonettoyage par chaleur intense) et consiste à :</p>
<ul>
<li>Nettoyer le four à fond, en éliminant toute saleté et tout résidu de nourriture visible.</li>
<li>Ne pas utiliser le four pendant 24 heures avant la cachérisation.</li>
<li>Allumer le four à la température la plus élevée possible (idéalement en utilisant la fonction autonettoyante, si le four en dispose) pendant au moins une heure.</li>
</ul>
<p>Les grilles et plaques métalliques peuvent généralement être cachérisées séparément par immersion dans l\'eau bouillante (hagala), tandis que les surfaces en verre ou émaillées nécessitent généralement le liboun car ce sont des matériaux plus absorbants.</p>
<p>Il est important de consulter un rabbin avant de cachériser un four particulier, car la procédure exacte peut varier selon le matériau, le modèle et la coutume (minhag) de chaque communauté. Certains fours modernes avec revêtements spéciaux peuvent ne pas convenir au liboun à haute température, il vaut donc la peine de consulter le manuel du fabricant.</p>',
            ],
            'kasherizar-microondas' => [
                'title' => 'Comment cachériser un four à micro-ondes',
                'excerpt' => 'Le four à micro-ondes a un processus de cachérisation différent de celui du four traditionnel, car il cuit à la vapeur et non à chaleur sèche.',
                'content' => '<p>Contrairement au four classique, le micro-ondes chauffe les aliments en générant de la vapeur à l\'intérieur, ce qui change la méthode de cachérisation recommandée par la plupart des autorités halakhiques.</p>
<p>Le processus le plus courant comprend :</p>
<ul>
<li>Nettoyer minutieusement l\'intérieur, en éliminant toute particule de nourriture visible, y compris le plateau tournant et les parois.</li>
<li>Ne pas utiliser le micro-ondes pendant 24 heures avant de le cachériser.</li>
<li>Placer un récipient d\'eau à l\'intérieur du micro-ondes et le faire fonctionner jusqu\'à ce que l\'eau bout et génère assez de vapeur pour couvrir toutes les surfaces intérieures, y compris la porte.</li>
<li>Laisser la vapeur agir sur les parois pendant plusieurs minutes.</li>
</ul>
<p>De nombreuses familles choisissent en outre d\'utiliser toujours un couvercle ou un film adapté au micro-ondes pour réchauffer la nourriture, et de réserver l\'appareil à un seul usage (carné, laitier ou parve) pour éviter d\'avoir à le cachériser à répétition. Les micro-ondes avec fonction grill ou convection peuvent nécessiter un processus supplémentaire similaire à celui du four traditionnel pour cette fonction spécifique. Comme pour toute cachérisation, il est recommandé de consulter un rabbin sur le cas particulier du modèle et du matériau de l\'appareil.</p>',
            ],
            'kasherizar-lavavajillas' => [
                'title' => 'Comment cachériser un lave-vaisselle',
                'excerpt' => 'De nombreuses familles utilisent le lave-vaisselle pour la vaisselle carnée et laitière en cycles séparés. Voici ce qu\'il faut pour le cachériser.',
                'content' => '<p>Le lave-vaisselle pose un défi particulier car ses parois internes, filtres et bras d\'aspersion sont en contact constant avec des résidus alimentaires à haute température, ce qui peut absorber les saveurs de manière plus persistante que d\'autres appareils.</p>
<p>C\'est pourquoi de nombreuses autorités rabbiniques sont plus strictes concernant la cachérisation des lave-vaisselle que pour d\'autres appareils, et certaines déconseillent carrément de l\'utiliser pour les deux catégories (carné et laitier), même à des jours différents. Celles qui l\'autorisent exigent généralement :</p>
<ul>
<li>Un nettoyage approfondi des filtres, bras d\'aspersion et joints en caoutchouc.</li>
<li>Ne pas utiliser le lave-vaisselle pendant 24 heures avant de le cachériser.</li>
<li>Faire tourner un cycle complet à vide, à la température la plus élevée possible, idéalement avec un produit nettoyant puissant.</li>
<li>Dans certaines communautés, il est recommandé d\'utiliser des paniers ou plateaux séparés et interchangeables pour le carné et le laitier, plutôt que de cachériser l\'appareil entier entre les usages.</li>
</ul>
<p>Comme les coutumes varient beaucoup sur ce sujet — certaines communautés séfarades et ashkénazes diffèrent notablement —, c\'est un des cas où il vaut mieux consulter directement le rabbin de la congrégation avant de définir comment organiser la cuisine.</p>',
            ],
            'hagala-utensilios-metal' => [
                'title' => 'Comment cachériser des ustensiles en métal (hagala)',
                'excerpt' => 'La hagala est la méthode traditionnelle d\'immersion dans l\'eau bouillante pour cachériser casseroles, couverts et autres ustensiles métalliques.',
                'content' => '<p>La hagala est l\'une des méthodes de cachérisation les plus anciennes et s\'utilise principalement sur les ustensiles en métal chauffés directement au feu ou par un liquide bouillant, comme les casseroles, les couverts, les poêles (sans revêtement antiadhésif) et certaines autres pièces de cuisine.</p>
<p>Le principe derrière la hagala est "comme il a absorbé, ainsi il rejette" : si un ustensile a absorbé une saveur non cachère (ou carnée/laitière) par un liquide bouillant, il se purifie de la même manière, en étant immergé dans l\'eau bouillante.</p>
<p>La procédure de base est :</p>
<ul>
<li>Nettoyer l\'ustensile à fond, sans rouille, nourriture collée ou saleté incrustée.</li>
<li>Attendre 24 heures sans utiliser l\'ustensile avant la hagala.</li>
<li>Faire bouillir une grande casserole d\'eau jusqu\'à pleine ébullition.</li>
<li>Immerger complètement l\'ustensile dans l\'eau bouillante, en s\'assurant que toutes ses surfaces entrent en contact avec l\'eau à cette température.</li>
<li>Le retirer avec un instrument n\'ayant pas été en contact avec de la nourriture non cachère, et le rincer à l\'eau froide.</li>
</ul>
<p>Les ustensiles avec manche en bois ou en plastique, ou avec des pièces collées avec des adhésifs ne résistant pas à l\'eau bouillante, ne conviennent généralement pas à la hagala et nécessitent une autre méthode, ou ne peuvent simplement pas être cachérisés. Les poêles antiadhésives (téflon) ne sont généralement pas non plus cachérisées par hagala, car le revêtement est endommagé par la chaleur.</p>',
            ],
            'vajilla-para-pesaj' => [
                'title' => 'Vaisselle pour Pessah : tout ce qu\'il faut savoir',
                'excerpt' => 'Pendant Pessah, des règles plus strictes que le reste de l\'année s\'appliquent aux ustensiles de cuisine, en raison de l\'interdiction du hametz.',
                'content' => '<p>Pessah est la fête aux règles alimentaires les plus strictes du calendrier juif, car en plus des normes habituelles de cacherout, s\'ajoute l\'interdiction totale de consommer ou de posséder du hametz (produits fermentés à base de cinq céréales : blé, orge, avoine, seigle et épeautre).</p>
<p>Comme le hametz peut avoir été en contact avec casseroles, assiettes et couverts toute l\'année, de nombreuses familles choisissent d\'avoir un service de vaisselle séparé, exclusif à Pessah, rangé le reste de l\'année. C\'est l\'option la plus simple et qui évite d\'avoir à cachériser chaque année.</p>
<p>Ceux qui n\'ont pas de vaisselle séparée pour Pessah peuvent cachériser certains ustensiles :</p>
<ul>
<li><strong>Métal sans revêtement</strong> (casseroles, couverts) : généralement apte à la hagala.</li>
<li><strong>Verre</strong> : selon la coutume, certains considèrent qu\'un bon lavage suffit, d\'autres exigent une immersion.</li>
<li><strong>Céramique et porcelaine</strong> : en général, ne peuvent pas être cachérisées pour Pessah et il faut utiliser un service à part.</li>
<li><strong>Plastique et caoutchouc</strong> : la plupart des avis ne permettent pas de les cachériser.</li>
</ul>
<p>Avant Pessah, il vaut la peine de consulter le guide spécifique de cachérisation de la communauté ou du certificateur local, car les délais et méthodes exactes peuvent varier selon le type de matériau et l\'usage de l\'ustensile pendant l\'année.</p>',
            ],
            'jametz-pesaj' => [
                'title' => 'Le hametz : qu\'est-ce que c\'est et comment l\'éliminer avant Pessah',
                'excerpt' => 'Le hametz est l\'aliment fermenté interdit pendant Pessah. Connaître les produits qui en contiennent est essentiel pour préparer la fête.',
                'content' => '<p>Le hametz est tout produit élaboré avec l\'une des cinq céréales — blé, orge, avoine, seigle ou épeautre — entré en contact avec de l\'eau et ayant fermenté (levé) pendant plus de 18 minutes sans être cuit au four. Cela inclut le pain, la bière, la plupart des pâtes, les biscuits et un nombre énorme de produits industriels utilisant ces céréales comme ingrédient ou dérivé.</p>
<p>La Torah interdit non seulement de manger du hametz pendant Pessah, mais aussi d\'en posséder. C\'est pourquoi, dans les semaines précédant la fête, les familles juives effectuent un grand nettoyage de la maison (bedikat hametz) pour éliminer tout reste de pain, de farine ou de produits avec hametz des placards, voitures, sacs et tout coin où une miette aurait pu tomber.</p>
<p>Pour le hametz qui ne peut ou ne doit pas être jeté (par exemple, des produits coûteux ou difficiles à remplacer), il existe l\'option de le "vendre" symboliquement à une personne non juive par un contrat appelé <em>mekhirat hametz</em>, généralement coordonné par le rabbin de la communauté. Le hametz vendu est gardé fermé et à part pendant la fête et est "racheté" automatiquement à la fin de Pessah.</p>
<p>La nuit précédant Pessah, une recherche rituelle du hametz est effectuée dans toute la maison (bedikat hametz), généralement avec une bougie, une plume et une cuillère en bois, suivie de la combustion de ce qui a été trouvé (bi\'our hametz) le lendemain matin.</p>',
            ],
            'vino-kosher' => [
                'title' => 'Le vin cachère : pourquoi il nécessite une supervision spéciale',
                'excerpt' => 'Le vin a un statut particulier en halakha : pour être cachère, il doit être élaboré et manipulé exclusivement par des juifs pratiquants.',
                'content' => '<p>Contrairement à la plupart des aliments, où il suffit que les ingrédients et le processus respectent certaines exigences, le vin cachère exige en plus que toute personne le touchant pendant son élaboration — depuis la récolte du raisin jusqu\'à la mise en bouteille — soit juive et pratiquante. Cette règle est née historiquement pour éviter que le vin soit utilisé dans des rituels d\'idolâtrie.</p>
<p>Cela signifie qu\'un domaine produisant du vin cachère doit opérer sous supervision rabbinique constante : les travailleurs non juifs peuvent aider à des tâches n\'impliquant pas de toucher directement le vin ou le jus de raisin, mais le processus central doit rester entre les mains de personnel juif pratiquant.</p>
<p>Il existe une catégorie spéciale appelée <strong>vin mevoushal</strong> (littéralement "bouilli"), qui est un vin pasteurisé à une température spécifique. Une fois mevoushal, le vin conserve son statut cachère même s\'il est servi ou touché par une personne non juive, ce qui le rend beaucoup plus pratique pour les événements, restaurants et traiteurs où l\'on ne peut garantir que seuls des juifs manipulent les bouteilles.</p>
<p>Aujourd\'hui, il existe des vins cachères de qualité dans pratiquement toutes les régions viticoles du monde, dont l\'Argentine, le Chili, la France, l\'Espagne, l\'Italie et bien sûr Israël, certifiés par les principales agences rabbiniques.</p>',
            ],
            'gelatina-kosher' => [
                'title' => 'La gélatine cachère : le débat halakhique',
                'excerpt' => 'La gélatine est l\'un des ingrédients les plus débattus dans le monde de la cacherout, car son origine animale peut compromettre son statut.',
                'content' => '<p>La gélatine traditionnelle s\'obtient en faisant bouillir des os, de la peau et du tissu conjonctif d\'animaux — généralement des vaches ou des porcs — jusqu\'à en extraire le collagène. Cela pose deux problèmes du point de vue de la cacherout : l\'origine de l\'animal (est-ce une espèce cachère ?) et la méthode de transformation (l\'animal a-t-il été abattu selon la shehita ?).</p>
<p>Pendant des décennies, différentes autorités rabbiniques ont débattu de la question de savoir si la gélatine, en subissant un processus chimique aussi radical, change de statut halakhique (un concept appelé <em>panim hadashot</em>, ou transformation totale). Certains avis plus permissifs ont soutenu que le processus est si radical que le produit final n\'est plus considéré comme de la viande au sens halakhique ; la plupart des certificateurs cachères grand public, cependant, n\'acceptent pas cette position pour la gélatine d\'origine non cachère.</p>
<p>C\'est pourquoi aujourd\'hui, la grande majorité des produits certifiés cachères nécessitant de la gélatine (bonbons, desserts, capsules de médicaments, marshmallows) utilisent des alternatives certifiées :</p>
<ul>
<li>Gélatine de poisson cachère.</li>
<li>Gélatine bovine d\'animaux abattus selon la shehita.</li>
<li>Substituts végétaux comme l\'agar-agar ou la pectine, qui évitent complètement le débat.</li>
</ul>
<p>Lorsqu\'un produit porte le sceau d\'un certificateur reconnu, il n\'est plus nécessaire d\'enquêter sur l\'origine de la gélatine : la certification garantit que ce point a déjà été vérifié.</p>',
            ],
            'alcohol-bebidas-espirituosas' => [
                'title' => 'Alcool et spiritueux : ce qu\'il faut pour qu\'ils soient cachères',
                'excerpt' => 'Whisky, vodka, rhum et autres spiritueux sont souvent cachères par nature, mais il existe d\'importantes exceptions à connaître.',
                'content' => '<p>La plupart des spiritueux — whisky, vodka, rhum, gin — sont élaborés à partir de céréales, de pomme de terre ou de canne à sucre, des ingrédients qui en eux-mêmes ne posent pas de problèmes de cacherout. C\'est pourquoi de nombreux spiritueux simples sont cachères sans certification spéciale, tant qu\'aucun arôme, colorant ou additif d\'origine non cachère n\'est ajouté.</p>
<p>Cependant, il existe des points d\'attention importants :</p>
<ul>
<li><strong>Vieillissement en fûts de vin ou de xérès :</strong> certains whiskies et rhums vieillissent dans des fûts ayant contenu auparavant du vin non cachère, ce qui peut compromettre leur statut.</li>
<li><strong>Arômes et additifs :</strong> les liqueurs à la crème, au chocolat ou aux fruits contiennent souvent des ingrédients nécessitant une vérification.</li>
<li><strong>Boissons à base de vin</strong> (comme le vermouth ou certaines liqueurs) : héritent de toutes les restrictions du vin cachère, y compris la nécessité d\'une supervision rabbinique lors de leur élaboration.</li>
<li><strong>Bière :</strong> généralement cachère grâce à ses ingrédients de base (eau, orge, houblon, levure), sauf variantes avec arômes spéciaux.</li>
</ul>
<p>Pendant Pessah, il faut en outre porter une attention particulière car de nombreux spiritueux sont élaborés à partir de céréales constituant du hametz, d\'où la nécessité d\'une certification spécifique "cachère pour Pessah" à cette période de l\'année.</p>',
            ],
            'comer-kosher-restaurante' => [
                'title' => 'Comment manger cachère dans un restaurant non certifié',
                'excerpt' => 'Voyager ou sortir manger sans restaurant cachère à proximité ne signifie pas rompre le régime. Il existe des options pour rester dans les normes.',
                'content' => '<p>Un restaurant avec certification cachère n\'est pas toujours disponible, surtout en voyage ou en vivant dans des villes avec peu d\'infrastructure communautaire. Néanmoins, il existe des stratégies pour rester dans le cadre de la cacherout dans des restaurants ordinaires.</p>
<ul>
<li><strong>Options végétariennes ou véganes :</strong> en éliminant viande et produits laitiers du plat, le risque diminue beaucoup, bien qu\'il faille toujours vérifier les ingrédients (bouillon de viande, beurre, sauces à base animale).</li>
<li><strong>Fruits et légumes crus :</strong> sans cuisson ni manipulation complexe, ils constituent souvent une option sûre presque partout.</li>
<li><strong>Poisson à nageoires et écailles :</strong> dans les restaurants à cuisine simple, un poisson grillé sans sauce peut être une alternative raisonnable pour ceux suivant un critère plus flexible (à condition qu\'il ne soit pas cuisiné avec des fruits de mer ou de la viande non cachère sur le même équipement, selon le critère de chacun).</li>
<li><strong>Boissons en bouteille et scellées :</strong> eau, sodas et jus dans leur emballage d\'origine ne posent généralement pas de problème.</li>
</ul>
<p>Chaque personne et chaque communauté a un niveau de rigueur différent quant à ce qui est considéré acceptable en dehors d\'un restaurant certifié (certains ne mangent que des produits emballés et scellés, d\'autres acceptent certaines préparations simples). En cas de doute, il est recommandé de consulter le rabbin de la congrégation sur le critère à suivre.</p>',
            ],
            'simbolos-certificacion-kosher' => [
                'title' => 'Les symboles de certification cachère les plus courants',
                'excerpt' => 'OU, OK, Star-K, KSA... il existe des dizaines de symboles de certification cachère dans le monde. Nous vous aidons à reconnaître les plus utilisés.',
                'content' => '<p>Lorsqu\'un produit passe par le processus de certification cachère, l\'agence certificatrice autorise l\'utilisation d\'un symbole (hekhsher) sur l\'emballage permettant de l\'identifier d\'un coup d\'œil. Il existe des centaines de certificateurs dans le monde, mais certains sont particulièrement connus pour leur portée mondiale.</p>
<ul>
<li><strong>OU (Orthodox Union) :</strong> un "U" dans un cercle. C\'est probablement le symbole cachère le plus reconnu mondialement, basé aux États-Unis.</li>
<li><strong>OK Kosher Certification :</strong> un "K" dans un cercle, une autre grande agence américaine.</li>
<li><strong>Star-K :</strong> une étoile avec un "K" au centre.</li>
<li><strong>KSA (Kosher Supervision of America) :</strong> certificateur avec une forte présence dans les produits industriels.</li>
<li><strong>Badatz :</strong> sceau utilisé par plusieurs tribunaux rabbiniques en Israël, associé à des normes de rigueur très élevées.</li>
<li><strong>Certificateurs locaux :</strong> dans des pays comme l\'Argentine, le Brésil ou le Mexique, il existe des certificateurs communautaires locaux (comme le Va\'ad Hakashrut de chaque kehila) avec leurs propres sceaux.</li>
</ul>
<p>Outre le symbole, de nombreuses étiquettes incluent une lettre supplémentaire : "D" (dairy/lacté), "M" (meat/carné), "Pareve" (neutre) ou "DE" (dairy equipment, élaboré sur équipement laitier mais sans ingrédients laitiers directs). Connaître ces symboles facilite grandement les achats, surtout en voyageant dans des pays dont on ne maîtrise pas la langue.</p>',
            ],
            'que-significa-pareve' => [
                'title' => 'Parve : que signifie ce terme si fréquent sur les étiquettes',
                'excerpt' => 'Parve est l\'un des mots les plus répétés dans l\'étiquetage cachère. Nous expliquons sa signification et pourquoi il est tant valorisé.',
                'content' => '<p>"Parve" (aussi orthographié pareve) décrit les aliments qui ne sont ni carnés ni laitiers : fruits, légumes, œufs, poisson, céréales et la plupart des produits élaborés sans ingrédients d\'origine animale laitière ou carnée.</p>
<p>Le grand avantage d\'un produit parve est sa flexibilité : il peut se combiner librement avec des repas tant carnés que laitiers, sans générer aucun conflit de cacherout. C\'est pourquoi de nombreuses industries alimentaires cherchent activement à développer des versions parve de produits traditionnellement laitiers — comme le chocolat, la margarine ou les substituts de crème — pour élargir leur marché.</p>
<p>Il est important de préciser une nuance : un aliment peut être parve par ses ingrédients, mais perdre ce statut s\'il a été élaboré sur un équipement traitant aussi des produits laitiers ou carnés, selon les traces pouvant subsister. C\'est pourquoi la certification n\'analyse pas seulement les ingrédients, mais aussi l\'équipement de production et les processus de nettoyage entre les lots.</p>
<p>Quelques exemples courants de produits parve : huile d\'olive, pâtes sèches sans œuf, la plupart des pains (bien que certains contiennent du beurre et deviennent laitiers), fruits secs non transformés, et boissons gazeuses. Toujours vérifier l\'étiquette est le seul moyen sûr de le confirmer, car la recette peut varier selon les marques ou même selon les présentations d\'une même marque.</p>',
            ],
            'shejita-sacrificio-kosher' => [
                'title' => 'La shehita : la méthode d\'abattage cachère',
                'excerpt' => 'Pour que la viande d\'un animal cachère soit apte à la consommation, elle doit être abattue selon une méthode rituelle spécifique appelée shehita.',
                'content' => '<p>La shehita est la méthode d\'abattage rituel juif, réalisée par un shohet (abatteur formé et certifié) utilisant un couteau extrêmement aiguisé et sans ébréchure, conçu spécifiquement pour produire une coupe rapide et précise sur la gorge de l\'animal, sectionnant la trachée et l\'œsophage en un seul mouvement continu.</p>
<p>L\'objectif de cette méthode est de minimiser la souffrance de l\'animal et de produire une perte de conscience pratiquement instantanée. Le shohet inspecte le couteau avant et après chaque abattage pour s\'assurer qu\'il n\'a aucune imperfection, aussi minime soit-elle, car toute irrégularité invalide la procédure.</p>
<p>Après la shehita, une inspection (bedika) des organes internes de l\'animal est effectuée, en particulier les poumons, pour écarter les maladies ou adhérences qui invalideraient la viande comme cachère. Seuls les animaux passant cette inspection sont considérés aptes.</p>
<p>De plus, la viande doit subir un processus de salage (kashering) pour extraire le sang, car la Torah interdit de consommer du sang. Cela se fait en faisant tremper la viande dans l\'eau, en la salant et en la laissant reposer avant de la rincer à nouveau — un processus généralement réalisé aujourd\'hui par la boucherie ou l\'abattoir certifié lui-même avant que le produit n\'atteigne le consommateur.</p>',
            ],
            'glatt-kosher' => [
                'title' => 'Glatt cachère : quelle différence avec le cachère ordinaire',
                'excerpt' => 'Le terme "glatt" apparaît fréquemment dans les boucheries et restaurants cachères. Nous expliquons le niveau de rigueur qu\'il représente.',
                'content' => '<p>"Glatt" signifie "lisse" en yiddish et désignait à l\'origine spécifiquement l\'état des poumons d\'un animal après la shehita : si les poumons ne présentaient aucune adhérence (sircha), l\'animal était considéré "glatt", le niveau le plus élevé de certitude que la viande est cachère sans aucun doute.</p>
<p>Avec le temps, particulièrement dans les communautés ashkénazes des États-Unis, le terme "glatt kosher" s\'est étendu familièrement pour décrire un standard général de plus grande rigueur dans toute la chaîne de production d\'un aliment, pas seulement dans l\'inspection des poumons. Aujourd\'hui, il est courant de voir "glatt kosher" sur les étiquettes de restaurants et de produits pour indiquer qu\'ils respectent les critères les plus exigeants possibles.</p>
<p>Il est important de souligner qu\'un produit "cachère" sans l\'étiquette "glatt" n\'est pas moins valide halakhiquement : il suit simplement un standard de certification différent, généralement accepté par la grande majorité des communautés. Le choix entre cachère standard et glatt kosher dépend généralement de la coutume familiale ou communautaire, plus que d\'une différence objective de validité.</p>
<p>Pour la volaille et le poisson, le concept de "glatt" ne s\'applique techniquement pas de la même manière que pour les mammifères, bien qu\'il soit parfois utilisé familièrement pour indiquer un niveau de supervision plus rigoureux en général.</p>',
            ],
            'como-leer-etiqueta-kosher' => [
                'title' => 'Comment lire une étiquette de produit cachère',
                'excerpt' => 'Au-delà du symbole de certification, les étiquettes cachères contiennent des informations clés pour savoir si un produit convient à votre table.',
                'content' => '<p>Bien lire une étiquette cachère va au-delà de chercher le symbole de certification. Plusieurs éléments méritent toujours d\'être vérifiés :</p>
<ul>
<li><strong>Le symbole du certificateur :</strong> identifie quelle agence a supervisé le produit. Il est important de reconnaître les certificateurs fiables, car tous les symboles du monde n\'ont pas le même niveau d\'exigence.</li>
<li><strong>La catégorie :</strong> "Dairy" ou "D" (lacté), "Meat" ou "M" (carné), "Pareve" (neutre), ou "Fish" (poisson, traité dans de nombreuses traditions comme une catégorie distincte de la viande).</li>
<li><strong>"Cachère pour Pessah" :</strong> indication supplémentaire nécessaire pendant la fête, distincte de la certification cachère habituelle du reste de l\'année.</li>
<li><strong>Date de certification :</strong> certains certificateurs incluent un code ou une date pour vérifier que le sceau est toujours valable, car les recettes et processus d\'usine peuvent changer.</li>
</ul>
<p>Quand un produit n\'a pas de certification visible mais que la liste d\'ingrédients semble simple (par exemple, seulement de l\'eau, du sel et un légume), certaines personnes choisissent d\'enquêter davantage, mais la recommandation générale des autorités de cacherout est de ne pas supposer qu\'un produit est cachère uniquement à cause de l\'apparence simple de ses ingrédients : de nombreux additifs et processus industriels ne sont pas évidents au premier coup d\'œil.</p>
<p>Sur KosherMap, vous pouvez rechercher des produits par nom ou code-barres et filtrer directement par certificateur, catégorie et type, pour ne pas dépendre uniquement de l\'étiquette physique.</p>',
            ],
            'bishul-akum' => [
                'title' => 'Bishoul Akoum : pourquoi certains aliments cuits nécessitent une supervision juive',
                'excerpt' => 'Il existe une catégorie de lois spécifique sur les aliments cuisinés par des non-juifs, connue sous le nom de bishoul akoum. Nous expliquons de quoi il s\'agit.',
                'content' => '<p>Le bishoul akoum (littéralement "cuisson d\'un non-juif") est une catégorie de lois rabbiniques restreignant la consommation de certains aliments cuisinés entièrement par une personne non juive, même si tous les ingrédients sont cachères. L\'interdiction fut établie par les sages talmudiques, principalement pour favoriser la cohésion sociale et éviter l\'assimilation culturelle.</p>
<p>Cette loi ne s\'applique pas à tous les aliments : elle se limite généralement aux aliments considérés "dignes d\'être servis à la table d\'un roi" (hachivout) et ne se mangeant pas crus. C\'est pourquoi les fruits, légumes crus et la plupart des en-cas industriels n\'entrent pas dans cette catégorie.</p>
<p>Il existe deux façons habituelles de résoudre le problème dans un contexte de production industrielle ou de restaurants certifiés :</p>
<ul>
<li>Qu\'un juif pratiquant participe activement au processus de cuisson, par exemple en allumant le feu ou l\'équipement de cuisson.</li>
<li>Que la supervision rabbinique certifie qu\'un représentant juif était présent lors de l\'allumage des équipements de cuisson à chaque équipe de production.</li>
</ul>
<p>C\'est l\'une des raisons pour lesquelles la certification cachère d\'une usine alimentaire ne se limite pas à examiner les ingrédients : elle supervise aussi les processus, la présence du personnel et les protocoles opérationnels de l\'établissement, ce qui rend le travail des certificateurs bien plus complexe qu\'une simple liste de vérification des intrants.</p>',
            ],
            'vino-mevushal' => [
                'title' => 'Mevoushal : le vin cachère qui peut être servi sans restrictions',
                'excerpt' => 'Le vin mevoushal est une catégorie spéciale permettant de le servir lors d\'événements sans que seuls des juifs aient besoin de le manipuler.',
                'content' => '<p>Comme nous l\'avons vu en parlant du vin cachère, la règle générale exige que seuls des juifs pratiquants manipulent le vin de l\'élaboration au service. Le vin mevoushal ("bouilli" ou pasteurisé) est une exception pratique à cette règle : une fois que le vin subit un processus de chauffage à une température minimale spécifique, il conserve son statut cachère quel que soit qui le sert ensuite.</p>
<p>Cette catégorie existe grâce à un principe halakhique selon lequel le vin altéré par la chaleur perd la "dignité" rituelle ayant initialement motivé la restriction, car historiquement cette préoccupation visait l\'usage du vin dans des cérémonies idolâtres — chose à laquelle un vin bouilli ne se prêtait pas dans ce contexte.</p>
<p>Le vin mevoushal est très populaire pour :</p>
<ul>
<li>Les traiteurs et événements où le personnel de service n\'est pas nécessairement juif.</li>
<li>Les restaurants cachères ouverts au grand public.</li>
<li>Les compagnies aériennes et hôtels proposant des options cachères.</li>
</ul>
<p>Il existe aujourd\'hui des techniques modernes de pasteurisation rapide (flash pasteurization) permettant de produire du vin mevoushal de haute qualité, ce qui était historiquement plus difficile à réaliser sans affecter le goût du vin. Cela a beaucoup élargi l\'offre de vins mevoushal premium disponibles sur le marché.</p>',
            ],
            'tevilat-kelim' => [
                'title' => 'Tevilat Kelim : l\'immersion rituelle des ustensiles neufs',
                'excerpt' => 'Avant d\'utiliser pour la première fois certains ustensiles de cuisine fabriqués par un non-juif, il existe la coutume de les immerger dans un mikvé.',
                'content' => '<p>Tevilat Kelim est la pratique consistant à immerger des ustensiles de cuisine neufs — en métal ou en verre, achetés à un fabricant non juif — dans un mikvé (bain rituel) ou une source d\'eau naturelle avant de les utiliser pour la première fois avec des aliments.</p>
<p>Cette coutume s\'applique principalement aux ustensiles en contact direct avec la nourriture : casseroles, poêles, couverts, assiettes en verre et verres. Elle ne s\'applique généralement pas aux appareils électriques (comme un grille-pain ou un mixeur) ni aux ustensiles en plastique ou en bois, bien que les avis varient selon la tradition de chaque communauté, c\'est pourquoi il est recommandé de consulter un rabbin pour des cas spécifiques.</p>
<p>Le processus en lui-même est simple : l\'ustensile est bien nettoyé (sans restes d\'étiquettes, scellés ou adhésifs), immergé complètement dans l\'eau du mikvé pendant que l\'on récite une bénédiction, et il est ensuite prêt à être utilisé normalement.</p>
<p>De nombreux mikvaot communautaires ont un horaire spécifique réservé à la tevilat kelim, séparé de l\'usage rituel personnel, ainsi que des instructions détaillées sur les matériaux nécessitant l\'immersion et ceux qui n\'en ont pas besoin. C\'est l\'une de ces pratiques qui, bien que paraissant un détail mineur, fait partie intégrante de la façon dont de nombreuses familles juives pratiquantes équipent leur cuisine.</p>',
            ],
            'armar-cocina-kosher' => [
                'title' => 'Comment organiser une cuisine cachère à partir de zéro',
                'excerpt' => 'Commencer à tenir une cuisine cachère peut sembler accablant au début. Voici un guide pratique des premiers pas.',
                'content' => '<p>Organiser une cuisine cachère à partir de zéro est un processus graduel, et il n\'est pas nécessaire de tout résoudre en un jour. Voici les étapes les plus courantes suivies par les familles qui débutent :</p>
<ul>
<li><strong>Définir la séparation physique :</strong> établir quels ustensiles, casseroles et vaisselle seront carnés et lesquels laitiers. Le plus pratique est généralement d\'utiliser des couleurs différentes (par exemple, rouge pour la viande, bleu pour le lait) pour éviter les confusions quotidiennes.</li>
<li><strong>Séparer les surfaces de travail :</strong> planches à découper, torchons et éponges doivent aussi être divisés par catégorie.</li>
<li><strong>Évaluer les appareils partagés :</strong> four, micro-ondes et lave-vaisselle peuvent être cachérisés entre les usages ou, plus simplement, assignés à une seule catégorie dès le départ (par exemple, micro-ondes uniquement pour le parve).</li>
<li><strong>Acheter des produits certifiés :</strong> vérifier le symbole de certification à chaque achat, jusqu\'à ce que cela devienne une habitude automatique.</li>
<li><strong>Coordonner avec un rabbin :</strong> en particulier pour cachériser des éléments déjà présents dans la cuisine avant de commencer ce processus.</li>
</ul>
<p>Une stratégie très utilisée par les débutants consiste à incorporer la séparation petit à petit : d\'abord les ustensiles d\'usage quotidien, ensuite la vaisselle de table, et enfin les appareils électroménagers. Il n\'est pas nécessaire de remplacer toute la cuisine d\'un coup, et de nombreuses familles mettent des mois à compléter la transition sans que cela pose un problème halakhique en soi.</p>',
            ],
            'certificaciones-kosher-mundo' => [
                'title' => 'Différences entre les certifications cachères à travers le monde',
                'excerpt' => 'Tous les certificateurs cachères ne suivent pas exactement les mêmes critères. Connaître ces différences aide à choisir des produits en toute confiance.',
                'content' => '<p>Bien que les principes fondamentaux de la cacherout soient universels, il existe des centaines d\'agences certificatrices dans le monde, et chacune peut avoir des critères légèrement différents sur des sujets spécifiques — par exemple, quel niveau de supervision elle exige pour le bishoul akoum, ou comment elle traite certains additifs chimiques dont l\'origine est difficile à tracer.</p>
<p>Quelques différences courantes entre régions :</p>
<ul>
<li><strong>États-Unis :</strong> abrite les plus grands certificateurs au niveau industriel (OU, OK, Star-K, Kof-K), avec des processus très standardisés pour l\'exportation de masse.</li>
<li><strong>Israël :</strong> le Rabbanout (rabbinat) offre une certification officielle d\'État, tandis que des organisations comme le Badatz maintiennent des normes additionnelles considérées plus strictes par certaines communautés.</li>
<li><strong>Europe :</strong> des certificateurs comme le Beth Din de différentes villes (Londres, Paris, Zurich) supervisent à la fois la production locale et les importations.</li>
<li><strong>Amérique latine :</strong> chaque communauté a généralement son Va\'ad Hakashrut local (par exemple, à Buenos Aires, São Paulo ou Mexico), qui certifie tant les produits locaux que les restaurants.</li>
</ul>
<p>Pour le consommateur, le plus important est d\'apprendre à reconnaître les certificateurs actifs dans sa région et, en cas de doute sur un symbole inconnu, de consulter le rabbin de la communauté ou d\'enquêter sur la réputation de l\'agence avant de faire confiance à un produit. La plupart des grands certificateurs publient des listes publiques de produits certifiés sur leurs sites web.</p>',
            ],
            'queso-kosher-cuajo' => [
                'title' => 'Fromage cachère : pourquoi il nécessite une présure spéciale',
                'excerpt' => 'Le fromage est l\'un des produits laitiers avec le plus de restrictions cachères, principalement à cause de l\'origine de la présure utilisée pour le fabriquer.',
                'content' => '<p>La présure (rennet) est l\'enzyme traditionnellement utilisée pour coaguler le lait et séparer le petit-lait dans la fabrication du fromage. Le problème du point de vue de la cacherout est que la présure traditionnelle s\'extrait de l\'estomac de veaux, et pour être apte, cet animal doit avoir été abattu par shehita (la méthode d\'abattage cachère) — chose qui n\'arrive presque jamais dans l\'industrie fromagère conventionnelle.</p>
<p>C\'est pourquoi pratiquement tout le fromage "ordinaire" du marché, même fait uniquement avec du lait et de la présure, n\'est pas cachère sans certification spécifique, car l\'origine de la présure ne peut être vérifiée à l\'œil nu.</p>
<p>Les options utilisées par les fabricants de fromage cachère incluent :</p>
<ul>
<li><strong>Présure animale cachère :</strong> extraite d\'animaux abattus selon la shehita et sous supervision rabbinique tout au long de la chaîne.</li>
<li><strong>Présure microbienne :</strong> produite par fermentation, sans origine animale, de plus en plus courante dans les fromages industriels et cachères.</li>
<li><strong>Présure végétale :</strong> extraite de certaines plantes, utilisée traditionnellement dans certaines variétés spécifiques de fromages artisanaux.</li>
</ul>
<p>Au-delà de la présure, il y a un autre facteur pertinent : de nombreuses communautés exigent que le fromage soit élaboré sous supervision juive constante (Gvinat Yisrael) pour le considérer pleinement cachère, un critère additionnel à la simple analyse des ingrédients. C\'est pourquoi acheter du fromage avec une certification reconnue est le moyen le plus fiable d\'éviter les erreurs.</p>',
            ],
            'huevos-kosher' => [
                'title' => 'Œufs cachères : que vérifier avant de les utiliser',
                'excerpt' => 'Les œufs sont parve et généralement cachères, mais une étape de vérification obligatoire existe avant de les cuisiner.',
                'content' => '<p>Les œufs d\'oiseaux cachères (comme la poule) sont, en principe, parve et aptes à la consommation. Cependant, avant d\'utiliser un œuf, la tradition exige de vérifier qu\'il ne contient pas de taches de sang dans le jaune, car un œuf avec du sang est considéré inapte à la consommation.</p>
<p>La procédure est simple : en cassant l\'œuf, on examine visuellement le jaune (et parfois le blanc) à contre-jour, en cherchant des points rouges ou des taches. Si du sang est trouvé, l\'œuf est entièrement jeté ; si le jaune est propre, l\'œuf est apte à être utilisé normalement.</p>
<p>Quelques informations supplémentaires sur les œufs et la cacherout :</p>
<ul>
<li>La coquille et le blanc présentent généralement le même risque que le jaune, bien que la coutume varie selon la communauté.</li>
<li>Les œufs d\'oiseaux non cachères (comme l\'autruche ou certains rapaces) ne sont pas aptes non plus, indépendamment de la présence de sang.</li>
<li>Les produits industriels contenant de l\'œuf (comme les pâtes ou la mayonnaise) passent généralement par un processus de contrôle de qualité incluant la détection automatique d\'œufs avec du sang, mais nécessitent quand même une certification pour garantir que ce contrôle a été bien effectué.</li>
</ul>
<p>C\'est l\'une des habitudes les plus simples à intégrer dans une cuisine cachère quotidienne : vérifier chaque œuf dès qu\'il est cassé, avant de le mélanger avec le reste des ingrédients.</p>',
            ],
            'pescado-kosher-aletas-escamas' => [
                'title' => 'Poisson cachère : nageoires et écailles, les règles de base',
                'excerpt' => 'Contrairement à la viande, le poisson cachère ne nécessite pas de shehita, mais doit répondre à un critère physique spécifique.',
                'content' => '<p>La Torah établit une règle relativement simple pour identifier le poisson cachère : il doit avoir à la fois des nageoires (snapir) et des écailles (kaskeset) visibles à l\'œil nu. Cette combinaison est présente chez la grande majorité des poissons d\'eau douce et de mer habituellement consommés, comme le saumon, le thon, le merlu, la truite et le maquereau.</p>
<p>Sont exclus de la cacherout, entre autres :</p>
<ul>
<li>Tous les fruits de mer (crevettes, langoustines, crabe, moules, huîtres).</li>
<li>Poulpe et calamar.</li>
<li>Requin et lotte (manquent d\'écailles véritables selon la plupart des avis halakhiques).</li>
<li>Anguille (manque d\'écailles visibles).</li>
<li>Espadon (son statut fait l\'objet d\'un débat historique entre différentes autorités rabbiniques).</li>
</ul>
<p>Une différence importante par rapport à la viande : le poisson cachère ne nécessite ni shehita ni processus de salage pour extraire le sang, ce qui simplifie beaucoup sa préparation. Cependant, dans de nombreuses traditions — surtout ashkénazes — le poisson est traité comme une catégorie distincte de la viande et des produits laitiers, évitant de le combiner avec de la viande dans le même plat (bien qu\'il ne nécessite pas la même séparation stricte des ustensiles que celle régissant viande et lait).</p>
<p>Lors de l\'achat de poisson frais, il vaut la peine de vérifier qu\'il conserve la peau avec des écailles visibles, car certains filetages enlèvent complètement la peau, rendant la vérification difficile. C\'est pourquoi de nombreuses poissonneries cachères laissent une portion de peau identifiable sur la coupe.</p>',
            ],
            'frutos-secos-contaminacion-cruzada' => [
                'title' => 'Fruits secs et cacherout : risques de contamination croisée',
                'excerpt' => 'Les fruits secs sont naturellement parve, mais la transformation industrielle peut introduire des risques de cacherout qui ne sont pas évidents.',
                'content' => '<p>Amandes, noix, cacahuètes et la plupart des fruits secs sont, sous leur forme crue et naturelle, des aliments parve sans restrictions de cacherout. Le problème apparaît quand ils entrent dans la chaîne de transformation industrielle, où ils peuvent se mélanger avec d\'autres produits sur les mêmes lignes de production.</p>
<p>Quelques risques courants :</p>
<ul>
<li><strong>Arômes laitiers :</strong> les fruits secs "grillés au beurre" ou enrobés de chocolat au lait ne sont plus parve.</li>
<li><strong>Lignes partagées :</strong> une usine peut transformer des fruits secs parve sur le même équipement utilisé ensuite pour des produits laitiers ou dérivés de viande, générant des traces non cachères en l\'absence de nettoyage certifié entre les lots.</li>
<li><strong>Huiles de cuisson :</strong> certains fruits secs frits utilisent des huiles partagées avec d\'autres produits non cachères.</li>
<li><strong>Glaçages et enrobages :</strong> les fruits secs "caramélisés" ou avec un enrobage sucré peuvent contenir de la gélatine ou d\'autres ingrédients d\'origine animale.</li>
</ul>
<p>C\'est pourquoi, bien qu\'un fruit sec cru et non transformé pose presque jamais de problème, les produits industriels (mélanges de fruits secs, en-cas aromatisés, barres de céréales) doivent toujours être vérifiés pour leur certification, sans supposer qu\'ils sont automatiquement cachères simplement parce que l\'ingrédient principal l\'est.</p>',
            ],
            'kashrut-y-veganismo' => [
                'title' => 'Cacherout et véganisme : manger végane équivaut-il à manger cachère ?',
                'excerpt' => 'Beaucoup de gens supposent qu\'un produit végane est automatiquement cachère. La réalité est plus nuancée.',
                'content' => '<p>C\'est une confusion courante : si un produit ne contient aucun ingrédient d\'origine animale, il semblerait logique de supposer qu\'il est automatiquement cachère. Cependant, la cacherout ne repose pas uniquement sur les ingrédients, mais aussi sur les processus d\'élaboration, l\'équipement utilisé et, dans certains cas, sur qui supervise la production.</p>
<p>Quelques exemples où un produit végane peut ne pas être cachère :</p>
<ul>
<li><strong>Équipement partagé :</strong> une usine végane peut utiliser la même ligne de production ayant auparavant traité des produits carnés ou laitiers, sans le nettoyage certifié exigé par la cacherout entre les lots.</li>
<li><strong>Vin et dérivés :</strong> un vin végane (sans clarifiants d\'origine animale) exige toujours que tout le processus d\'élaboration soit entre les mains de juifs pratiquants pour être cachère.</li>
<li><strong>Insectes :</strong> certains colorants (comme le carmin, d\'origine animale) sont interdits en cachère mais parfois étiquetés comme adaptés aux véganes par erreur ou selon des normes différentes de certification végane.</li>
<li><strong>Bishoul akoum :</strong> un aliment végane cuisiné entièrement par une personne non juive peut tomber sous cette restriction, selon la classification du produit.</li>
</ul>
<p>Inversement, il est aussi vrai que de nombreux produits cachères parve sont, en fait, véganes. Mais l\'équivalence n\'est automatique dans aucun sens : le plus sûr est toujours de chercher la certification cachère explicite, plutôt que de supposer que "végane" équivaut à "cachère".</p>',
            ],
            'separar-la-jala' => [
                'title' => 'Comment prélever la halla',
                'excerpt' => 'Prélever la halla est un commandement spécifique s\'appliquant lors du pétrissage de pâte en grandes quantités, avec des racines dans les offrandes du Temple.',
                'content' => '<p>Le prélèvement de la halla (hafrachat halla) est un commandement biblique exigeant originellement de donner une portion de la pâte à pain aux prêtres (cohanim) du Temple de Jérusalem. Après la destruction du Temple, la pratique s\'est transformée : aujourd\'hui, au lieu d\'être donnée, la portion prélevée est brûlée ou jetée de manière respectueuse.</p>
<p>Cette mitsva s\'applique lors du pétrissage d\'une quantité significative de pâte faite avec l\'une des cinq céréales (blé, orge, avoine, seigle ou épeautre) — la quantité minimale exacte (généralement autour de 1,2 kg de farine) varie selon l\'avis halakhique suivi.</p>
<p>Le processus de base est :</p>
<ul>
<li>Pétrir la pâte à pain normalement, jusqu\'à atteindre la quantité minimale requise.</li>
<li>Prélever une petite portion (traditionnellement de la taille d\'une olive ou plus, selon la coutume).</li>
<li>Réciter la bénédiction correspondante avant de prélever la portion.</li>
<li>Brûler la portion prélevée (enveloppée dans du papier aluminium, au four) ou la jeter d\'une manière qu\'elle ne soit pas utilisée pour une consommation régulière.</li>
</ul>
<p>Cette pratique explique pourquoi de nombreuses boulangeries cachères industrielles certifiées prélèvent la halla dans le cadre de leur processus de production, et pourquoi de nombreuses femmes et familles juives la réalisent à la maison chaque fois qu\'elles font cuire du pain ou de la halla pour Chabbat en quantité suffisante.</p>',
            ],
            'calendario-judio-festividades-alimentacion' => [
                'title' => 'Le calendrier juif et les fêtes qui affectent l\'alimentation cachère',
                'excerpt' => 'Plusieurs fêtes juives ont des coutumes alimentaires spécifiques, au-delà des règles générales de la cacherout.',
                'content' => '<p>Outre les règles de cacherout valables toute l\'année, le calendrier juif apporte des fêtes avec des coutumes alimentaires propres qu\'il vaut la peine de connaître :</p>
<ul>
<li><strong>Roch Hachana :</strong> on a coutume de manger une pomme avec du miel pour symboliser une année douce, et d\'éviter les aliments amers ou acides à la table festive.</li>
<li><strong>Yom Kippour :</strong> jour de jeûne complet de 25 heures, sans nourriture ni boisson, sauf exceptions médicales spécifiques.</li>
<li><strong>Souccot :</strong> on a coutume de manger dans une hutte temporaire (souccah) en plein air pendant toute la semaine de la fête.</li>
<li><strong>Hanouca :</strong> tradition de manger des aliments frits dans l\'huile (comme les soufganiot, beignets fourrés, et les latkes, galettes de pomme de terre) en commémoration du miracle de l\'huile.</li>
<li><strong>Pourim :</strong> on prépare des hamantaschen (oreilles d\'Haman), pâtisseries triangulaires fourrées, et on a coutume de partager des paniers de nourriture (michloah manot) avec amis et famille.</li>
<li><strong>Pessah :</strong> la fête aux restrictions alimentaires les plus nombreuses, centrée sur l\'interdiction du hametz, comme nous l\'avons vu en détail.</li>
<li><strong>Chavouot :</strong> coutume de manger des aliments laitiers, avec des plats comme le cheesecake et les blintzes (crêpes fourrées au fromage) en vedette.</li>
</ul>
<p>Connaître ce calendrier aide à comprendre pourquoi certains produits (comme la matsa, les soufganiot ou le vin cachère pour Pessah) sont plus disponibles dans les rayons et commerces à certaines périodes de l\'année.</p>',
            ],
            'errores-comunes-empezar-comer-kosher' => [
                'title' => 'Erreurs courantes en commençant à manger cachère',
                'excerpt' => 'Adopter la cacherout pour la première fois implique un processus d\'apprentissage. Nous passons en revue les erreurs les plus fréquentes pour les éviter dès le départ.',
                'content' => '<p>Commencer à suivre un régime cachère est un processus qui prend du temps, et il est normal de faire des erreurs au début. Voici quelques-unes des plus courantes :</p>
<ul>
<li><strong>Supposer que "naturel" ou "sans conservateurs" signifie cachère :</strong> le marketing d\'un produit n\'a aucun rapport direct avec son statut de cacherout. Il faut toujours chercher la certification.</li>
<li><strong>Ne pas vérifier les produits semblant évidemment parve :</strong> les en-cas, produits de boulangerie et confiseries contiennent parfois des ingrédients laitiers ou de la gélatine non évidents dans le nom du produit.</li>
<li><strong>Mélanger les ustensiles carnés et laitiers par mégarde :</strong> au début, il est facile d\'oublier la séparation ; étiqueter ou utiliser des couleurs différentes aide beaucoup pendant la transition.</li>
<li><strong>Ne pas vérifier les légumes-feuilles pour les insectes :</strong> une étape que beaucoup de nouveaux pratiquants de la cacherout ignorent complètement.</li>
<li><strong>Faire confiance à des certifications inconnues ou peu claires :</strong> tous les symboles sur un emballage ne sont pas de véritables certifications cachères ; certains sont des labels de qualité sans rapport avec la cacherout.</li>
<li><strong>Ne pas demander :</strong> de nombreux doutes se résolvent rapidement en consultant le rabbin de la communauté ou quelqu\'un de plus expérimenté, plutôt que de deviner.</li>
</ul>
<p>Le plus important est de comprendre que la transition n\'a pas besoin d\'être parfaite dès le premier jour. La plupart des communautés juives valorisent le processus d\'apprentissage graduel, et il existe de nombreuses ressources — y compris des certificateurs, des rabbins et des outils comme KosherMap — pour accompagner ce chemin.</p>',
            ],
        ];
    }
}
