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
            'he' => self::he(),
            'ru' => self::ru(),
        ];
    }

    private static function en(): array
    {
        return [
            'insectos-frutas-verduras' => [
                'title' => 'Insects in fruits and vegetables: how to check them properly',
                'excerpt' => 'The Torah forbids eating insects, so checking fruits and vegetables before eating them is a core part of keeping a kosher kitchen.',
                'content' => '<p>The prohibition against eating insects (sheratzim) is one of the strictest in the Torah: unlike other dietary prohibitions, eating a single insect can count as multiple transgressions. That is why checking fruits and vegetables before cooking or serving them is an unavoidable step in a kosher kitchen.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/pulgon-en-hoja.jpg" alt="An aphid on a leaf, magnified. On real produce it is one to three millimetres: barely a dark speck, which is why it is so easy to miss." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">An aphid on a leaf, magnified. On real produce it is one to three millimetres: barely a dark speck, which is why it is so easy to miss. Photo: WikiPedant via <a href="https://commons.wikimedia.org/wiki/File:Aphid_on_leaf05.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Leafy vegetables such as lettuce, spinach, broccoli and cauliflower require the most care, because aphids and other small insects hide between the leaves and are hard to spot with the naked eye. The usual technique involves separating the leaves, washing them individually under running water and checking them against the light.</p>
<ul>
<li><strong>Leafy vegetables:</strong> separate leaf by leaf and wash under running water, checking both sides.</li>
<li><strong>Cauliflower and broccoli:</strong> soak in water with a bit of soap or vinegar so insects detach from the florets.</li>
<li><strong>Strawberries and raspberries:</strong> soak in salted or vinegar water before rinsing.</li>
<li><strong>Dried legumes (lentils, chickpeas):</strong> spread on a light surface and check before cooking.</li>
</ul>
<p>And let\'s be honest, this is not as easy as it sounds. In our house, checking the vegetables always fell to one particular person. Over the years he started needing glasses, and one day he spotted a tiny black dot on a leaf. Convinced it was nothing, he asked his daughter what it was. She looked at him and said: can\'t you see it\'s a bug, look, it even has little legs? Since that day he doesn\'t check a single leaf without his glasses on. The lesson is simple: checking properly takes good light and good eyesight, and when in doubt, a second look never hurts.</p>
<p>Many kosher certifiers offer specific, illustrated guides on how to check each type of vegetable depending on where it was grown, since insect prevalence varies by climate and farming method. Today there are also "pre-checked" vegetables or ones grown under specific supervision to minimize infestation, which greatly simplifies the process in everyday cooking.</p>',
            ],
            'carne-y-leche' => [
                'title' => 'Meat and milk: why they don\'t mix',
                'excerpt' => 'The separation of meat and milk is one of the best-known pillars of kashrut. We explain its origin, its scope and how it applies in practice.',
                'content' => '<p>The prohibition on mixing meat and milk is based on a verse that appears three times in the Torah: "You shall not cook a kid in its mother\'s milk." Oral tradition interpreted this phrase as a triple prohibition: not to cook, not to eat, and not to derive any benefit from a mixture of meat and milk.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/carne-y-leche.jpg" alt="An observant kosher kitchen keeps two complete sets of pots and utensils, one for meat and one for dairy." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">An observant kosher kitchen keeps two complete sets of pots and utensils, one for meat and one for dairy. Photo: PattayaPatrol via <a href="https://commons.wikimedia.org/wiki/File%3ADFC_2431_A_cook_flips_food_in_a_hot_pan_while_working_in_a_compact_well-used_kitchen_filled_with_pots_utensils_and_shelves_of_ingredients.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>In practice, this means food is divided into three categories: <strong>meat</strong> (meat and its derivatives), <strong>dairy</strong> (milk and its derivatives) and <strong>pareve</strong> (neutral, such as fruits, vegetables, eggs and fish, which are neither meat nor milk).</p>
<p>An observant kosher kitchen keeps separate utensils, pots, plates and even dishwashers for meat and for milk, since heat and repeated use can transfer flavors and particles between surfaces. In addition, a waiting period is required between eating meat and eating dairy — which varies by family custom, generally between one and six hours — while going from dairy to meat usually only requires rinsing one\'s mouth and eating something neutral.</p>
<p>This separation is why so many product labels carry the letters "D" (dairy), "M" (meat) or "Pareve" next to the certification symbol: it lets the consumer instantly know which category a product falls into before combining it with other foods.</p>',
            ],
            'kasherizar-horno' => [
                'title' => 'How to kosher an oven',
                'excerpt' => 'When an oven was used with non-kosher food, or you want to switch it from meat to dairy use, there is a specific process to make it kosher.',
                'content' => '<p>Koshering an oven is necessary in several situations: when buying a house with an oven previously used in a non-kosher way, when switching an oven\'s use (for example, from meat to dairy), or before Passover, when every trace of chametz must be removed.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kasherizar-horno.jpg" alt="Libun, koshering the oven with intense heat, requires a thorough cleaning first and 24 hours without use." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Libun, koshering the oven with intense heat, requires a thorough cleaning first and 24 hours without use. Photo: Brandon Carson from San Carlos, USA via <a href="https://commons.wikimedia.org/wiki/File%3ADouble_oven.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>

<h2>Why an oven needs such a drastic method</h2>
<p>An oven\'s interior reaches very high temperatures on every use, so any absorbed residue is considered "baked in" at a much deeper level than in a pot. That is why a milder method like hagalah (boiling water immersion) is not enough here: the oven needs libun, self-cleaning through intense dry heat, which mirrors the same "as it absorbed, so it releases" principle used for metal utensils, just applied through fire instead of water.</p>

<h2>The step-by-step procedure</h2>
<ol>
<li>Clean the oven thoroughly, removing all visible dirt and food residue, including the door seal and any corner where grease tends to build up.</li>
<li>Do not use the oven for 24 hours before koshering it.</li>
<li>Turn the oven to its highest possible temperature (ideally using the self-cleaning function, if the oven has one) for at least one hour, until the interior surfaces glow.</li>
</ol>

<h2>Racks, trays and glass surfaces</h2>
<p>Metal racks and trays can usually be koshered separately by immersion in boiling water (hagalah), while glass or enamel surfaces generally require libun because they are more absorbent materials. Glass, in particular, is one of the most debated materials in kashrut: some opinions treat it as non-absorbent altogether, while others require the same intense heat as metal.</p>

<h2>Ovens that cannot handle libun</h2>
<p>Some modern ovens, especially those with plastic components, electronic touch panels or special non-stick coatings, are not built to withstand the temperature that libun requires and can be damaged or even become a fire hazard. In those cases, it is better to replace the oven entirely for a fresh kosher start rather than force a koshering method the appliance was not designed for.</p>

<h2>Frequently asked questions</h2>
<p><strong>Does the self-cleaning cycle by itself count as libun?</strong><br>
In most opinions, yes, since it reaches the required temperature, but it is worth confirming that the cycle covers every interior surface, including the door.</p>
<p><strong>Do I need to kosher the oven every time I cook something non-kosher by mistake?</strong><br>
If it was an isolated event, consulting a rabbi about the specific situation is the safest path, since the answer can depend on the food, the temperature and how long it was inside.</p>
<p><strong>Can I kosher an oven for both meat and dairy use?</strong><br>
Many families prefer to assign the oven to a single category from the start, or use separate trays and liners for each use, rather than koshering it repeatedly between uses.</p>

<h2>Related reading</h2>
<p>For the microwave, which uses a different method based on steam instead of dry heat, see <a href="/articulos/kasherizar-microondas">how to kosher a microwave</a>. For the dishwasher, one of the most debated appliances among rabbinic authorities, see <a href="/articulos/kasherizar-lavavajillas">how to kosher a dishwasher</a>.</p>',
            ],
            'kasherizar-microondas' => [
                'title' => 'How to kosher a microwave',
                'excerpt' => 'Microwaves have a different koshering process than a traditional oven, because they cook with steam rather than dry heat.',
                'content' => '<p>Unlike a conventional oven, a microwave heats food by generating steam inside it, which changes the koshering method recommended by most halachic authorities.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/microondas-cocina.jpg" alt="Splatters build up mostly on the roof of the microwave cavity, which is the spot people look at least when cleaning." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Splatters build up mostly on the roof of the microwave cavity, which is the spot people look at least when cleaning. Photo: Tomwsulcer via <a href="https://commons.wikimedia.org/wiki/File:Stove_oven_combination_and_microwave_oven.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kasherizar-lavavajillas.jpg" alt="The dishwasher is one of the most debated appliances: its filters and spray arms hold residue at high temperature." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">The dishwasher is one of the most debated appliances: its filters and spray arms hold residue at high temperature. Photo: Myke2020 via <a href="https://commons.wikimedia.org/wiki/File%3ACountertop_dishwasher_7882.JPG" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>

<h2>Why it generates so much rabbinic debate</h2>
<p>Unlike an oven or a set of pots, a dishwasher has dozens of hidden crevices, a filter that traps food particles directly, and spray arms that recirculate hot water loaded with residue throughout the entire cycle. That combination makes some authorities treat it more strictly than almost any other appliance, and a few discourage using the same unit for both meat and dairy altogether, even on different days.</p>

<h2>The procedure for those who do allow it</h2>
<ul>
<li>Thorough cleaning of filters, spray arms and rubber seals, removing every visible particle by hand.</li>
<li>Not using the dishwasher for 24 hours before koshering it.</li>
<li>Running a full empty cycle at the highest possible temperature, ideally with a strong cleaning product.</li>
<li>In some communities, it is recommended to use separate, interchangeable racks or trays for meat and dairy, rather than koshering the entire appliance between uses.</li>
</ul>

<h2>The alternative that avoids the problem at the root</h2>
<p>Many families sidestep the debate entirely by using removable rack baskets or dedicated racks, one for meat and one for dairy, that go in and out of the same machine. Since the food itself never touches the machine\'s walls or filter directly, some rabbinic opinions consider this a much simpler solution than repeated koshering.</p>

<h2>Differences between communities</h2>
<p>Customs vary quite a bit on this topic — some Sephardic and Ashkenazi communities differ notably in how strict they are about dishwashers specifically. This is one of those cases where it is well worth consulting directly with the congregation\'s rabbi before deciding how to organize the kitchen, rather than assuming the same rule that applies to hagalah for pots also applies here.</p>

<h2>Frequently asked questions</h2>
<p><strong>Is a dishwasher with a sanitizing high-heat cycle automatically easier to kosher?</strong><br>
It can help reach the required temperature faster, but the debate is less about temperature and more about the machine\'s internal structure and how food residue circulates inside it.</p>
<p><strong>Do plastic dishwasher racks need separate koshering?</strong><br>
Plastic parts are generally treated differently from the metal interior; ask your rabbi about the specific model if this comes up.</p>
<p><strong>Does using rack baskets fully resolve the issue for every community?</strong><br>
Not universally — some more stringent opinions still prefer a fully dedicated machine per category. It is worth confirming the criterion your community follows.</p>

<h2>Related reading</h2>
<p>For the koshering method used on pots, pans and cutlery, see <a href="/articulos/hagala-utensilios-metal">hagalah: how to kosher metal utensils</a>. And for the oven, which follows the same underlying logic through dry heat, see <a href="/articulos/kasherizar-horno">how to kosher an oven</a>.</p>',
            ],
            'hagala-utensilios-metal' => [
                'title' => 'How to kosher metal utensils (hagalah)',
                'excerpt' => 'Hagalah is the traditional method of immersion in boiling water used to kosher pots, cutlery and other metal utensils.',
                'content' => '<p>Hagalah is one of the oldest koshering methods and is mainly used on metal utensils that were heated directly by fire or boiling liquid, such as pots, cutlery, pans (without a non-stick coating) and certain other kitchenware.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/hagala-utensilios-metal.jpg" alt="Hagalah means immersing the utensil in boiling water: as it absorbed, so it expels." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Hagalah means immersing the utensil in boiling water: as it absorbed, so it expels. Photo: W.carter via <a href="https://commons.wikimedia.org/wiki/File%3ASteam-boiling_green_asparagus.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>

<h2>The principle behind the method</h2>
<p>The principle is simple to remember: "as it absorbed, so it releases" (<em>kevol\'o kach polto</em>). If a utensil absorbed non-kosher (or meat/dairy) flavor through boiling liquid, it is purified the same way: by immersing it in boiling water. It is the same principle behind koshering an <a href="/articulos/kasherizar-horno">oven</a>, applied through a different method depending on what kind of heat each surface absorbed.</p>

<h2>The step-by-step procedure</h2>
<ol>
<li>Clean the utensil thoroughly, with no rust, stuck-on food or caked dirt. Any residue left behind blocks direct contact with the water and invalidates the process in that spot.</li>
<li>Wait 24 hours without using the utensil before doing hagalah. That waiting period makes the absorbed flavor count as "spoiled" and easier to expel.</li>
<li>Bring a large pot of water to a rolling boil, in a container other than the one being koshered.</li>
<li>Fully submerge the utensil in the boiling water, making sure all its surfaces — including edges and crevices — come into contact with the water at that temperature.</li>
<li>Remove it with an implement that has not been in contact with non-kosher food, and rinse it immediately in cold water.</li>
</ol>

<h2>Utensils that this method cannot handle</h2>
<p>Utensils with wooden or plastic handles, or with parts glued with adhesives that cannot withstand boiling water, are generally not suitable for hagalah and need a different method, or simply cannot be koshered. Non-stick (Teflon) pans also are not usually koshered via hagalah, since the coating is damaged by the heat and the koshering process itself would ruin the surface anyway. Glass and dishware follow a different criterion, covered in <a href="/articulos/vajilla-para-pesaj">Passover dishware</a>, material by material.</p>

<h2>Frequently asked questions</h2>
<p><strong>Does hagalah work for any utensil that touched non-kosher food?</strong><br>
Not always. If the utensil was in direct contact with fire (like a grill), the correct method is different: libun, which requires heating the metal until it glows or until a piece of straw touching it would burn. Hagalah is specifically for absorption through liquid.</p>
<p><strong>How many times can the same utensil be koshered this way?</strong><br>
There is no fixed limit on reuse, as long as the utensil is in good condition. What matters each time is respecting the 24-hour waiting period beforehand.</p>
<p><strong>Do I need a rabbi present to do hagalah at home?</strong><br>
It is not required for routine home use, but when in doubt about a specific utensil (unknown material, mixed use), it is worth consulting first.</p>

<h2>Related reading</h2>
<p>If the utensil in question is an oven or a microwave, those follow their own rules: see <a href="/articulos/kasherizar-horno">how to kosher an oven</a> and <a href="/articulos/kasherizar-microondas">how to kosher a microwave</a>. For the dishwasher, one of the most debated appliances, see <a href="/articulos/kasherizar-lavavajillas">how to kosher a dishwasher</a>.</p>',
            ],
            'vajilla-para-pesaj' => [
                'title' => 'Passover dishware: everything you need to know',
                'excerpt' => 'During Passover, stricter rules than the rest of the year apply to kitchenware, due to the prohibition of chametz.',
                'content' => '<p>Passover is the holiday with the strictest dietary rules in the Jewish calendar, because in addition to the usual kashrut rules, there is the total prohibition on eating or owning <a href="/articulos/jametz-pesaj">chametz</a> (fermented products from five grains: wheat, barley, oats, rye and spelt).</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/vajilla-para-pesaj.jpg" alt="Many families keep a set of dishes used only for Passover, stored away the rest of the year." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Many families keep a set of dishes used only for Passover, stored away the rest of the year. Photo: Silar via <a href="https://commons.wikimedia.org/wiki/File%3A020210817_135854_Seder_plates%2C_brass_plate%2C_%C4%86miel%C3%B3w_porcelain%2C_19th-early_20th_century%2C_Category%2C_Passover_in_Galicia.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>Why chametz from the rest of the year is the problem</h2>
<p>The problem is that chametz may have been in contact with pots, plates and cutlery all year: every time pasta was cooked, bread baked, or crackers served, those utensils absorbed traces of chametz. The rest of the year that is not an issue, since chametz itself is kosher. But during Passover, having utensils that could "release" that absorbed flavor conflicts with the holiday\'s total prohibition.</p>

<h2>The simplest option: a separate set</h2>
<p>That is why many families keep a separate set of dishware exclusively for Passover, stored away the rest of the year in boxes or a dedicated cabinet: it is the simplest option, and it avoids having to kosher items every year. Over time, many families build this set gradually, buying or inheriting pieces, until they have a complete set dedicated only to those eight days a year.</p>

<h2>What can be koshered, material by material</h2>
<p>Those without separate Passover dishware can kosher certain utensils through <a href="/articulos/hagala-utensilios-metal">hagalah</a>, though not all of them:</p>
<ul>
<li><strong>Uncoated metal</strong> (pots, cutlery): generally suitable for hagalah, following the same procedure used the rest of the year.</li>
<li><strong>Glass</strong>: depending on custom, some consider thorough washing sufficient, others require immersion; check your community\'s specific criterion.</li>
<li><strong>Ceramic and porcelain</strong>: generally cannot be koshered for Passover under any opinion, since the porous material retains what it absorbed permanently; a separate set is required.</li>
<li><strong>Plastic and rubber</strong>: most opinions do not allow koshering them, though there are specific exceptions depending on prior use.</li>
</ul>

<h2>Frequently asked questions</h2>
<p><strong>Do I need to buy new Passover dishware every year?</strong><br>
No, once the set is assembled it is reused year after year, stored the rest of the time. You only need to replace broken pieces or add new ones as the family grows.</p>
<p><strong>Do appliances also need a separate set for Passover?</strong><br>
It depends on the appliance. Some, like a toaster, generally cannot be koshered and it is worth having one dedicated to Passover; others, like the oven, have a specific procedure detailed in <a href="/articulos/kasherizar-horno">how to kosher an oven</a>.</p>
<p><strong>How far in advance should I start preparing the dishware?</strong><br>
It is worth starting with some margin, at least a week ahead, to have time to kosher the eligible utensils without rushing and to resolve any doubts with your rabbi or local certifier before the holiday begins.</p>

<h2>Related reading</h2>
<p>To understand exactly which products need to be removed from the kitchen before the holiday, see <a href="/articulos/jametz-pesaj">chametz: what it is and how it is removed</a>. And to know what to do with the pots and pans used daily throughout the year, see <a href="/articulos/hagala-utensilios-metal">how to kosher metal utensils</a>.</p>',
            ],
            'jametz-pesaj' => [
                'title' => 'Chametz: what it is and how it is eliminated before Passover',
                'excerpt' => 'Chametz is the fermented food forbidden during Passover. Knowing which products contain it is key to preparing for the holiday.',
                'content' => '<p>Chametz is any product made from one of the five grains — wheat, barley, oats, rye or spelt — that came into contact with water and fermented (rose) for more than 18 minutes without being baked. This includes bread, beer, most pasta, cookies, and a huge number of processed products that use these grains as an ingredient or derivative.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/jametz-pesaj.jpg" alt="During Passover there is a total ban on both eating and owning chametz." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">During Passover there is a total ban on both eating and owning chametz. Photo: Ministry of Information Photo Division Photographer via <a href="https://commons.wikimedia.org/wiki/File%3AAllied_Forces_Celebrate_Passover-_Jewish_Traditions_in_Wartime_Britain%2C_April_1944_D19336.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>

<h2>Not just eating it: owning it is forbidden too</h2>
<p>The Torah forbids not only eating chametz during Passover, but also owning it, even just stored away with no intention of eating it. That is why, in the weeks before the holiday, Jewish families do a deep cleaning of the house (bedikat chametz) to remove any trace of bread, flour or chametz products from cupboards, cars, bags and any corner where a crumb might have fallen. This cleaning usually starts several days ahead, room by room, and is completed the night before Passover with a final ritual search.</p>

<h2>What to do with chametz you cannot throw away</h2>
<p>For chametz that cannot or should not be thrown away, such as expensive spirits or hard-to-replace products, there is the option of symbolically "selling" it to a non-Jewish person through a contract called <em>mechirat chametz</em>, usually coordinated by the community rabbi, often online in the weeks before the holiday. The sold chametz is kept closed and set aside during the holiday, physically still in the house but legally "sold," and is automatically "bought back" once Passover ends, with no further action needed from the owner.</p>

<h2>The ritual search and the final burning</h2>
<p>The night before Passover, a ritual search for chametz (bedikat chametz) is performed throughout the house, usually with a candle, a feather and a wooden spoon. It is common for a family member to hide a few small pieces of bread wrapped in paper beforehand so the search doesn\'t come up empty (a widespread custom in many communities). The next morning, whatever was found is burned in a ritual called <em>biur chametz</em>, accompanied by a declaration nullifying any remaining trace that might not have been found.</p>

<h2>Frequently asked questions</h2>
<p><strong>Are oats and rye really forbidden, or only wheat?</strong><br>
All five grains (wheat, barley, oats, rye and spelt) are included in the chametz prohibition when they ferment with water. Rice and corn are not part of this group, though in some communities (mainly Ashkenazi) they are avoided under a separate custom called <em>kitniyot</em>, distinct from the chametz prohibition itself.</p>
<p><strong>What happens if I find chametz at home during Passover?</strong><br>
It must be removed immediately: kept closed until the holiday ends if it was already included in the symbolic sale, or discarded if it wasn\'t.</p>
<p><strong>Does selling chametz cost anything?</strong><br>
It is generally done free of charge or with a symbolic contribution to the synagogue coordinating it; it is not a real economic sale, but a legal mechanism within halacha.</p>

<h2>Related reading</h2>
<p>To understand what happens with pots and dishes that were in contact with chametz during the year, see <a href="/articulos/vajilla-para-pesaj">Passover dishware</a>. And for the broader calendar and what is eaten at each holiday, see <a href="/articulos/calendario-judio-festividades-alimentacion">the Jewish calendar and food</a>.</p>',
            ],
            'vino-kosher' => [
                'title' => 'Kosher wine: why it needs special supervision',
                'excerpt' => 'Wine has a particular status in halacha: to be kosher, it must be made and handled exclusively by observant Jews.',
                'content' => '<p>Some time ago we were able to visit a winery in Mendoza that makes kosher wine, and seeing the process up close helps explain why wine has such different rules from every other food. With almost anything else, it is enough for the ingredients and the process to meet certain requirements. Not with wine: halacha requires that everyone who touches it during production, from the moment the grapes come in until the bottle is sealed, be an observant Jew. The reason is historical: wine used to be used in idolatrous rituals, and that is where the restriction comes from.</p>

<h2>A particular division of labor: the winemaker and the yehudi</h2>
<p>In practice, this creates a fairly particular division of labor. The person who actually knows about wine is the winemaker, who is usually not Jewish (the goy), and who tells the crew what to do at each stage: when to harvest, how to ferment, what blends to make. But the person who actually moves the wine, opens the valves, and does anything that involves touching the product is always an observant Jew (the yehudi). The expert directs, the yehudi executes, and all of it under constant rabbinic supervision at every stage of the process.</p>
<p>The wine rests in different settings depending on the stage, and part of it is kept in oak barrels, which can be reused about three times before losing their aromatic properties.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/barricas-roble-vino.jpg" alt="A winery barrel room (illustrative image, not the Mendoza winery we visited). In a kosher winery each barrel also carries a seal certifying nobody outside the supervision opened it." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">A winery barrel room (illustrative image, not the Mendoza winery we visited). In a kosher winery each barrel also carries a seal certifying nobody outside the supervision opened it. Photo: Subhashish Panigrahi via <a href="https://commons.wikimedia.org/wiki/File:Oak_barrels_used_for_aging_of_wine_in_a_cellar_at_Grover_Zampa_Vineyard,_Doddaballapura,_Karnataka,_India.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<p>Every barrel is sealed, and that seal is not a minor detail: it is the guarantee that no outsider touched the contents. We were told about a case that shows just how far this goes. In a barrel holding thousands of liters, they noticed the seal was missing right where it should have been closed: it was open. The Rabbi of Buenos Aires himself had to come verify the situation in person, and he ruled that the wine had been left without supervision. The result: those thousands of liters could no longer be sold as kosher.</p>

<h2>The exception that solves the problem for large events</h2>
<p>There is also a special category, <strong>mevushal wine</strong> ("boiled"), which is wine pasteurized at a specific temperature. Once a wine is mevushal, it keeps its kosher status even if served or touched by a non-Jew afterward. That is why it is so practical for events, restaurants and catering, where there is no way to control who picks up each bottle. The full detail of how this exception works is in <a href="/articulos/vino-mevushal">mevushal: kosher wine that can be served without restrictions</a>.</p>

<h2>Where it is produced today</h2>
<p>Today there is good-quality kosher wine in almost every wine-growing region in the world. Mendoza is an important hub in Argentina, and kosher wine is also produced in Chile, France, Spain, Italy and, of course, Israel, certified by the leading rabbinic agencies. The quality of these wines has improved enormously in recent decades: years ago kosher wine had a reputation for being sweet and of limited quality, but today it competes on equal footing with conventional wines in blind tastings.</p>

<h2>Frequently asked questions</h2>
<p><strong>Why does wine have such different rules from solid food?</strong><br>
It comes from the restriction\'s historical origin: wine was specifically used in idolatrous ceremonies in ancient times, something that didn\'t happen the same way with other foods, and that specific concern created its own, stricter rule.</p>
<p><strong>Does grape juice have the same restrictions as wine?</strong><br>
Yes, unfermented grape juice follows the same handling criteria as wine, precisely because it can ferment and turn into wine.</p>
<p><strong>How do I know if a bottle of wine has the correct supervision?</strong><br>
By looking for the seal of a recognized certifier on the label; without that seal there is no way to confirm the full chain of Jewish handling was respected.</p>

<h2>Related reading</h2>
<p>For the exception that allows serving wine without restrictions on who touches it, see <a href="/articulos/vino-mevushal">mevushal wine</a>. And for other drinks where wine\'s origin also comes into play, see <a href="/articulos/alcohol-bebidas-espirituosas">alcohol and spirits</a>.</p>',
            ],
            'gelatina-kosher' => [
                'title' => 'Kosher gelatin: the halachic debate',
                'excerpt' => 'Gelatin is one of the most debated ingredients in the kosher world, because its animal origin can compromise its status.',
                'content' => '<p>Traditional gelatin is obtained by boiling bones, skin and connective tissue from animals — usually cows or pigs — until the collagen is extracted. This raises two problems for kashrut: the animal\'s origin (is it a kosher species?) and the processing method (was the animal slaughtered according to <a href="/articulos/shejita-sacrificio-kosher">shechita</a>?).</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/gelatina-kosher.jpg" alt="Sweets and desserts are where gelatin, the most debated ingredient in kashrut, shows up most." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Sweets and desserts are where gelatin, the most debated ingredient in kashrut, shows up most. Photo: Sakurai Midori via <a href="https://commons.wikimedia.org/wiki/File%3ASweets_Offering_for_Obon.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>

<h2>The underlying debate: does the transformation change its status?</h2>
<p>For decades, various rabbinic authorities debated whether gelatin, having gone through such a radical chemical transformation, changes its halachic status, a concept called <em>panim chadashot</em> (literally "new face," total transformation). Some more lenient opinions held that the process is so radical — from bone and skin to a flavorless, colorless crystalline powder unrecognizable from the original animal — that the final product is no longer considered meat in a halachic sense. Most mainstream kosher certifiers, however, do not accept this view for gelatin of non-kosher origin, and require that the collagen\'s source be authorized from the start.</p>

<h2>The alternatives that avoid the problem at the root</h2>
<p>That is why most kosher-certified products that require gelatin (candy, desserts, medicine capsules, marshmallows) today use one of these alternatives:</p>
<ul>
<li><strong>Kosher fish gelatin:</strong> extracted from species with fins and scales, avoiding the origin problem that mammal-based gelatin carries.</li>
<li><strong>Beef gelatin from animals slaughtered according to shechita:</strong> resolves the problem at its source, though it is usually more expensive than the conventional alternative.</li>
<li><strong>Plant-based substitutes</strong> such as agar-agar (extracted from algae) or pectin (from fruit), which avoid the debate entirely since they have no animal origin.</li>
</ul>
<p>When a product carries the seal of a recognized certifier, there is no longer any need to investigate the gelatin\'s origin: the certification guarantees that point has already been checked as part of the certification process.</p>

<h2>Where gelatin shows up without you noticing</h2>
<p>Beyond obvious sweets and desserts, gelatin appears in less obvious places: medicine and supplement capsules, some yogurts and dairy desserts (for texture), wine and beer (used as a fining agent during filtration) and certain industrial baked goods. It is always worth checking the label rather than assuming a product "without a gelatin taste" doesn\'t contain any.</p>

<h2>Frequently asked questions</h2>
<p><strong>Is plant-based gelatin (agar-agar) always kosher?</strong><br>
By origin (algae), yes, but as with any product it is worth verifying it wasn\'t processed on shared equipment with non-kosher ingredients.</p>
<p><strong>Why do some kosher candy brands have a different texture than conventional ones?</strong><br>
Because many use fish gelatin or plant-based substitutes instead of standard beef or pork gelatin, which can slightly change the final texture.</p>
<p><strong>Can a product labeled just "gelatin," with no further detail, be assumed non-kosher?</strong><br>
Without visible certification, there\'s no reliable way to know; the safest approach is to look for a recognized certifier\'s seal.</p>

<h2>Related reading</h2>
<p>To understand the slaughter method that determines whether meat or animal-derived collagen is fit, see <a href="/articulos/shejita-sacrificio-kosher">shechita: the kosher slaughter method</a>. And for another equally debated ingredient, see <a href="/articulos/queso-kosher-cuajo">kosher cheese and rennet</a>.</p>',
            ],
            'alcohol-bebidas-espirituosas' => [
                'title' => 'Alcohol and spirits: what it takes for them to be kosher',
                'excerpt' => 'Whiskey, vodka, rum and other spirits are often kosher by nature, but there are important exceptions to keep in mind.',
                'content' => '<p>Most spirits — whiskey, vodka, rum, gin — are made from grains, potato or sugar cane, ingredients that on their own do not present kashrut problems. That is why many simple spirits are kosher without needing special certification, as long as no non-kosher flavors, colorings or additives are added.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/alcohol-bebidas-espirituosas.jpg" alt="Many spirits are kosher by their ingredients, but ageing in wine casks can change their status." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Many spirits are kosher by their ingredients, but ageing in wine casks can change their status. Photo: 4028mdk09 via <a href="https://commons.wikimedia.org/wiki/File%3ABarbestand.JPG" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>

<h2>Points that deserve attention</h2>
<ul>
<li><strong>Aging in wine or sherry barrels:</strong> some whiskeys and rums are aged in barrels that previously held non-kosher wine, and that contact can affect their status, similar to how <a href="/articulos/vino-kosher">kosher wine barrels</a> can only be reused a limited number of times.</li>
<li><strong>Flavorings and additives:</strong> cream-, chocolate- or fruit-flavored liqueurs often include ingredients (colorings, essences, stabilizers) that require case-by-case verification.</li>
<li><strong>Wine-based drinks</strong> (such as vermouth or certain liqueurs): inherit all the restrictions of kosher wine, including the need for rabbinic supervision throughout production.</li>
<li><strong>Beer:</strong> generally kosher due to its base ingredients (water, barley, hops, yeast), except for variants with special flavorings or filtering processes that use animal-derived fining agents.</li>
<li><strong>Cream liqueurs:</strong> since they contain dairy, beyond checking ingredients you also need to consider the rules on mixing with meat dishes.</li>
</ul>

<h2>Why vodka and gin tend to be simpler</h2>
<p>Neutral spirits like vodka or base gin tend to raise fewer questions than whiskey or rum, since they generally don\'t go through wood aging and their ingredients are more straightforward: water and fermented, repeatedly distilled grain or potato. Gin is a middle case, since botanicals (juniper, citrus, spices) are added during distillation, and that\'s where it\'s worth checking those additives don\'t include problematic ingredients.</p>

<h2>Passover: the exception that changes everything</h2>
<p>During Passover, extra attention is needed because many spirits are made from grains that constitute <a href="/articulos/jametz-pesaj">chametz</a>, so a specific "kosher for Passover" certification is required during that time of year. This especially affects whiskey (made from barley) and some grain vodkas, while potato- or sugar-cane-based spirits tend to have fewer additional restrictions for the holiday, though it\'s still worth confirming the specific certification before buying.</p>

<h2>Frequently asked questions</h2>
<p><strong>Is every whiskey without visible kosher certification automatically unfit?</strong><br>
Not necessarily, but without verification there is no way to confirm whether it was aged in non-kosher wine barrels or contains problematic additives. When in doubt, look for a recognized certified brand.</p>
<p><strong>Can the alcohol itself (ethanol) be non-kosher?</strong><br>
Ethanol as a molecule has no kashrut issue; the concern is always about the origin of the fermented raw material and any additives introduced after distillation.</p>
<p><strong>Can cocktails made at a regular bar be considered kosher if all the ingredients are?</strong><br>
It depends on each person\'s criteria: beyond the ingredients, whether the bar uses the same equipment for preparations with non-kosher ingredients also comes into play, similar to the issue raised in <a href="/articulos/comer-kosher-restaurante">eating kosher at a non-certified restaurant</a>.</p>

<h2>Related reading</h2>
<p>To understand why wine needs such strict supervision, which also affects wine-based liqueurs, see <a href="/articulos/vino-kosher">kosher wine</a>. And for the exception that allows serving wine without restrictions, see <a href="/articulos/vino-mevushal">mevushal wine</a>.</p>',
            ],
            'comer-kosher-restaurante' => [
                'title' => 'How to eat kosher at a non-certified restaurant',
                'excerpt' => 'Traveling or eating out without a kosher restaurant nearby doesn\'t mean breaking your diet. There are options to stay within the rules.',
                'content' => '<p>A certified kosher restaurant isn\'t always available, especially when traveling or living in cities with little community infrastructure. Even so, there are strategies to stay within kashrut at regular restaurants, and not all of them mean going hungry.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/comer-kosher-restaurante.jpg" alt="Without a certified restaurant nearby, there are still ways to stay within kashrut." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Without a certified restaurant nearby, there are still ways to stay within kashrut. Photo: Sohail1308 via <a href="https://commons.wikimedia.org/wiki/File%3AAl_Fanar_Restaurant.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>The safest options, from strictest to most flexible</h2>
<ul>
<li><strong>Bottled and sealed beverages:</strong> water, sodas and juices in their original packaging generally present no problems, no matter where they are bought.</li>
<li><strong>Raw fruits and vegetables:</strong> with no cooking or complex handling, these are usually the safest option almost anywhere in the world.</li>
<li><strong>Vegetarian or vegan options:</strong> by removing meat and dairy from the plate, the risk is greatly reduced, though it is still necessary to check for hidden ingredients (meat broth in a vegetable stir-fry, butter in a puree, animal-based sauces).</li>
<li><strong>Fish with fins and scales:</strong> at simple-cuisine restaurants, plain grilled fish without sauces can be a reasonable alternative for those following a more flexible approach, as long as it is not cooked alongside shellfish or non-kosher meat on the same equipment — this ultimately depends on each person\'s and community\'s criteria.</li>
</ul>
<p>What almost no opinion allows is meat or poultry from a non-certified restaurant, no matter how the dish looks: both the animal\'s origin and the slaughter method (<a href="/articulos/shejita-sacrificio-kosher">shechita</a>) come into play, and neither can be verified by sight.</p>

<h2>A real example of how this plays out in practice</h2>
<p>We once traveled on vacation to Mar del Plata with the kids still small, and as we came in along Constitución Avenue, my wife remembered we\'d forgotten the crackers. In winter, in that part of the country, finding kosher bread or crackers is practically impossible. What were we going to give the kids? At home we didn\'t usually eat certain packaged snacks, but given the emergency we turned to the Ajdut Kosher list, a guide to approved supermarket-shelf products published by the certifier. We looked through the approved brands and found some Traviata-style crackers that were on the list. That saved us.</p>
<p>The takeaway: when you travel, having your trusted certifier\'s list of approved products on hand is worth its weight in gold. A huge number of everyday supermarket products are kosher even without a big seal printed on the package, and knowing that list opens up options where it seemed like there were none. Most certifiers today publish these lists online or through an app, so it is worth saving one to your phone before traveling rather than searching for it once you actually need it.</p>

<h2>Asking in the kitchen: what actually helps</h2>
<p>If you are going to eat something prepared on the spot, some questions help more than others. Asking a waiter "is this kosher?" rarely works if they don\'t know what the word means. It is more useful to ask something concrete: was this oil used to fry something else before, is the same grill used for meat, is there butter or cream in the preparation? The more specific the question, the more reliable the answer.</p>

<h2>Different levels of strictness, and why they vary</h2>
<p>Each person and community has a different threshold for what is considered acceptable outside a certified restaurant. Some families only eat packaged and sealed products, no exceptions. Others accept certain simple preparations (a salad, a tea) at regular restaurants, but not elaborate dishes. There is no single correct answer: it depends on family custom and which rabbi each person follows. When in doubt about a specific case, it is best to ask beforehand, before a trip, rather than improvise on the spot.</p>

<h2>Frequently asked questions</h2>
<p><strong>Is a vegan restaurant automatically acceptable?</strong><br>
Not necessarily. Having no animal-derived ingredients does not resolve everything: shared equipment and the issue of <a href="/articulos/bishul-akum">bishul akum</a> (food cooked entirely by a non-Jew) are still relevant depending on the opinion followed. We cover this in more detail in <a href="/articulos/kashrut-y-veganismo">kashrut and veganism</a>.</p>
<p><strong>What do I do if I travel to a city with no Jewish community at all?</strong><br>
Prioritize packaged and sealed items (with visible certification) you brought with you or can find at a supermarket, plus raw fruits and vegetables. Many travelers keep a stock of certified non-perishables for these situations.</p>
<p><strong>Do delivery apps show whether a place is kosher?</strong><br>
Some certifiers have their own apps or online listings of certified restaurants in their region, which tend to be more reliable than trusting a generic delivery app\'s description of a place.</p>

<h2>Related reading</h2>
<p>To understand what makes a place genuinely certified, see <a href="/articulos/simbolos-certificacion-kosher">kosher certification symbols</a>. And if the goal is to set up your own kitchen so you rely less on eating out, see <a href="/articulos/armar-cocina-kosher">how to set up a kosher kitchen from scratch</a>.</p>',
            ],
            'simbolos-certificacion-kosher' => [
                'title' => 'The most common kosher certification symbols',
                'excerpt' => 'OU, OK, Star-K, KSA... there are dozens of kosher certification symbols around the world. We help you recognize the most common ones.',
                'content' => '<p>When a product goes through the kosher certification process, the certifying agency authorizes the use of a symbol (hechsher) on the packaging that lets it be identified at a glance. There are hundreds of certifiers worldwide, but some are especially well known for their global reach, and learning to recognize them saves a lot of time at the store.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/simbolos-certificacion-kosher.jpg" alt="A hechsher: the kashrut certificate a rabbinical agency grants to a business or product." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">A hechsher: the kashrut certificate a rabbinical agency grants to a business or product. Photo: Utilisateur:Djampa - User:Djampa via <a href="https://commons.wikimedia.org/wiki/File%3AHechsher_Safed_Rabbinate.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>The most widely recognized symbols worldwide</h2>
<ul>
<li><strong>OU (Orthodox Union):</strong> a "U" inside a circle. It is probably the most recognized kosher symbol worldwide, based in the United States, appearing on tens of thousands of products.</li>
<li><strong>OK Kosher Certification:</strong> a "K" inside a circle, another major U.S. agency, strong in industrial products and additives.</li>
<li><strong>Star-K:</strong> a star with a "K" in the center, based in Baltimore.</li>
<li><strong>KSA (Kosher Supervision of America):</strong> a certifier with a strong presence in industrial products and flavorings.</li>
<li><strong>Badatz:</strong> a seal used by several rabbinic courts in Israel, associated with very high standards of strictness (there are several distinct Badatz bodies, not a single organization).</li>
<li><strong>Local certifiers:</strong> in countries like Argentina, Brazil or Mexico there are local community certifiers (such as each kehilah\'s Va\'ad Hakashrut) with their own seals, generally recognized only within that community or country.</li>
</ul>

<h2>The letters that accompany the symbol</h2>
<p>Besides the symbol, many labels include an additional letter: "D" (dairy), "M" (meat), "Pareve" (neutral) or "DE" (dairy equipment, made on dairy equipment but without direct dairy ingredients). This last distinction matters: a "DE" product is not suitable to eat right after meat, even though it contains no dairy ingredients. Knowing these symbols greatly speeds up shopping, especially when traveling to countries where you don\'t speak the local language.</p>

<h2>A hechsher is not a one-time paperwork step: it is constant supervision</h2>
<p>It\'s worth clarifying something people don\'t always understand: a hechsher isn\'t a one-time paperwork step that stays valid forever. It\'s active, ongoing supervision. We know of a case involving a bakery in Buenos Aires that held certification from a community agency. At one point the supervising rabbi noticed something odd and sent people to check things out, almost like a detective. They found the bakery was buying dulce de leche without supervision, when it was only supposed to use Chalav Yisrael products (dairy made under Jewish supervision). They warned the owner and asked him to fix it. Not long after, unaware he was still being watched closely, he turned up buying regular, uncertified cheese, and that was the last straw: his supervision was pulled. It was all handled quietly, with no scandal, simply by no longer certifying the place.</p>
<p>The takeaway for consumers is clear: the seal is worth something because someone is genuinely checking, all the time, behind it. That\'s why it\'s worth trusting recognized certifiers and, when you come across an unfamiliar symbol, asking within the community before assuming a product is kosher.</p>

<h2>Why some communities only accept certain seals</h2>
<p>Not every rabbi or community accepts the same hechsherim. Some criteria are more flexible and trust a wide range of recognized certifiers; others, especially stricter communities, only trust a handful of seals whose standards they know in depth. It is not unusual to see "reliable hechsher lists" that each community publishes for its members. When in doubt about whether a particular seal is accepted, the simplest approach is to ask the congregation\'s rabbi directly.</p>

<h2>Frequently asked questions</h2>
<p><strong>Can a product with no symbol at all still be kosher?</strong><br>
Technically yes, if all its ingredients are kosher and it never touched non-kosher equipment, but without certification there is no way to verify that from the outside. That is exactly why the hechsher exists.</p>
<p><strong>Is every symbol with a "K" trustworthy?</strong><br>
No. Unlike "OU" or "OK," a plain letter "K" is not trademarked by any specific agency, so any manufacturer can print it without it meaning real supervision. Be cautious of a standalone "K" with no identifiable agency behind it.</p>
<p><strong>Do symbols mean the same thing in every country?</strong><br>
The symbol\'s design is usually consistent since it is trademarked by the certifier, but the level of trust each community places in it varies by country and religious stream.</p>

<h2>Related reading</h2>
<p>To understand the difference between "D," "M" and "Pareve" in detail, see <a href="/articulos/que-significa-pareve">what pareve means</a>. And if you want to read a full label beyond the symbol, see <a href="/articulos/como-leer-etiqueta-kosher">how to read a kosher product label</a>.</p>',
            ],
            'que-significa-pareve' => [
                'title' => 'Pareve: what it means and why it\'s so common on labels',
                'excerpt' => 'Pareve is one of the most repeated words in kosher labeling. We explain what it means and why it\'s so valued.',
                'content' => '<p>"Pareve" (also spelled parve) describes foods that are neither meat nor dairy: fruits, vegetables, eggs, fish, grains and most products made without dairy or meat ingredients of animal origin.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/que-significa-pareve.jpg" alt="Fresh fruit and vegetables are pareve by nature: they go with both meat and dairy." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Fresh fruit and vegetables are pareve by nature: they go with both meat and dairy. Photo: PattayaPatrol via <a href="https://commons.wikimedia.org/wiki/File%3ADFC_2197_A_colorful_assortment_of_fresh_fruits_and_vegetables_-_apples_mango_dragon_fruit_kiwis_limes_bananas_and_more_-_arranged_on_a_wooden_crate.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>The great advantage of a pareve product is its flexibility: it can be freely combined with both meat and dairy meals, without creating any kashrut conflict. That is why many food companies actively develop pareve versions of products that traditionally contain dairy — such as chocolate, margarine or cream substitutes — to expand their market.</p>
<p>It\'s worth noting a nuance: a food can be pareve by ingredients but lose that status if it was made on equipment that also processes dairy or meat, depending on possible traces left behind. That is why certification doesn\'t just review ingredients, but also the production equipment and cleaning processes between batches.</p>
<p>Some common examples of pareve products: olive oil, dry pasta without egg, most breads (although some contain butter and become dairy), unprocessed nuts, and soft drinks. Always checking the label is the only sure way to confirm it, since the recipe can vary between brands or even between presentations of the same brand.</p>',
            ],
            'shejita-sacrificio-kosher' => [
                'title' => 'Shechita: the kosher slaughter method',
                'excerpt' => 'For the meat of a kosher animal to be fit for consumption, it must be slaughtered using a specific ritual method called shechita.',
                'content' => '<p>Shechita is the Jewish ritual slaughter method, performed by a shochet (a trained and certified slaughterer) using an extremely sharp, nick-free knife, designed specifically to make a quick, precise cut across the animal\'s throat, severing the trachea and esophagus in a single continuous motion.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/shejita-sacrificio-kosher.svg" alt="The chalef is checked before and after each animal: a single nick invalidates the procedure." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">The chalef is checked before and after each animal: a single nick invalidates the procedure. Illustration: KosherMap.</figcaption>
</figure>
<p>The goal of this method is to minimize the animal\'s suffering and produce an almost instantaneous loss of consciousness. The shochet inspects the knife before and after each slaughter to make sure it has no imperfection, however small, since any irregularity invalidates the procedure.</p>
<p>A relative of ours works in shechita, which gives a sense of just how obsessive the oversight is in practice. The chalaf (the knife) isn\'t only checked by the shochet before and after each animal: there is also a supervisor who inspects every knife, day after day, with an extremely strict standard. An imperfection that isn\'t even visible to the naked eye is enough to take a knife out of service. It is an environment of total seriousness and precision, where the moment anything out of the ordinary comes up, the first move is to stop everything and put safety first.</p>
<p>After shechita, an inspection (bedika) of the animal\'s internal organs is performed, especially the lungs, to rule out diseases or adhesions that would invalidate the meat as kosher. Only animals that pass this inspection are considered fit.</p>
<p>Additionally, the meat must go through a salting process (kashering) to extract the blood, since the Torah forbids consuming blood. This is done by soaking the meat in water, salting it and letting it rest before rinsing it again — a process that today is usually carried out by the certified butcher shop or slaughterhouse itself before the product reaches the consumer.</p>',
            ],
            'glatt-kosher' => [
                'title' => 'Glatt kosher: how is it different from regular kosher',
                'excerpt' => 'The term "glatt" appears frequently in kosher butcher shops and restaurants. We explain what level of strictness it represents.',
                'content' => '<p>"Glatt" means "smooth" in Yiddish and originally referred specifically to the condition of an animal\'s lungs after <a href="/articulos/shejita-sacrificio-kosher">shechita</a>: if the lungs had no adhesions (sirchah), the animal was considered "glatt," the highest level of certainty that the meat is kosher beyond doubt.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/glatt-kosher.jpg" alt="A kosher meat restaurant in Jerusalem. &quot;Glatt&quot; indicates the strictest inspection standard." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">A kosher meat restaurant in Jerusalem. "Glatt" indicates the strictest inspection standard. Photo: brionv from San Francisco, United States via <a href="https://commons.wikimedia.org/wiki/File%3AJerusalem_MMMM_MEAT_%286036353902%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 2.0.</figcaption>
</figure>

<h2>The technical origin: the animal\'s lungs</h2>
<p>During the post-shechita inspection, one of the key steps is checking the animal\'s lungs for adhesions (sirchot), which can indicate a disease or perforation that would invalidate the animal as kosher. When the lung appears completely smooth, with no adhesions at all, the animal is classified as "glatt." If it has minor adhesions that an expert examiner (bodek) determines can be removed without leaving a perforation, the animal may still qualify as kosher under the standard criterion, though not as "glatt."</p>

<h2>How it went from a technical term to a general strictness label</h2>
<p>Over time, especially in Ashkenazi communities in the United States, the term "glatt kosher" colloquially expanded to describe an overall stricter standard throughout a food\'s entire production chain, not just lung inspection. Today it is common to see "glatt kosher" on restaurant and product labels to indicate they meet the most demanding criteria possible, even in contexts where the original technical term (an animal\'s lungs) doesn\'t even apply.</p>

<h2>Is standard kosher "less valid"?</h2>
<p>It is important to note that a "kosher" product without the "glatt" label is not less halachically valid: it simply follows a different certification standard, generally accepted by the vast majority of communities and rabbinic authorities. Choosing between standard kosher and glatt kosher usually depends on family or community custom, rather than an objective difference in validity. Many families who don\'t follow glatt daily still choose it for special occasions, like a wedding or a major holiday.</p>
<p>For poultry and fish, the concept of "glatt" technically does not apply in the same way, since birds don\'t undergo the same kind of lung inspection, although colloquially it is sometimes used to indicate a generally more rigorous level of supervision.</p>

<h2>Frequently asked questions</h2>
<p><strong>Is all glatt kosher meat more expensive?</strong><br>
Generally yes, because the selection process is stricter and a higher percentage of animals are rejected, which reduces the available supply relative to demand.</p>
<p><strong>Is a restaurant that says "kosher" without specifying "glatt" being dishonest?</strong><br>
No, it is simply being precise: it follows the standard kosher criterion, which is fully valid for the vast majority of communities.</p>
<p><strong>Does "glatt" guarantee that other rules, like bishul akum, were also followed?</strong><br>
Not automatically. "Glatt" refers specifically to the meat inspection level; other rules like <a href="/articulos/bishul-akum">bishul akum</a> are verified separately as part of the overall certification process.</p>

<h2>Related reading</h2>
<p>To understand the full process behind kosher meat, from slaughter to inspection, see <a href="/articulos/shejita-sacrificio-kosher">shechita: the kosher slaughter method</a>. And to recognize the seals that certify all of this on a label, see <a href="/articulos/simbolos-certificacion-kosher">kosher certification symbols</a>.</p>',
            ],
            'como-leer-etiqueta-kosher' => [
                'title' => 'How to read a kosher product label',
                'excerpt' => 'Beyond the certification symbol, kosher labels contain key information about whether a product is suitable for your table.',
                'content' => '<p>Properly reading a kosher label goes beyond looking for the certification symbol. There are several elements worth always checking, and learning to read them fully avoids surprises at the table.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/como-leer-etiqueta-kosher.jpg" alt="An Israeli product label: beyond the seal, check the category and the ingredients." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">An Israeli product label: beyond the seal, check the category and the ingredients. Photo: Chenspec via <a href="https://commons.wikimedia.org/wiki/File%3AList_of_ingredients_of_food_products_in_Israel_02.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>The four things worth checking, in order</h2>
<ul>
<li><strong>The certifier\'s symbol:</strong> identifies which agency supervised the product. It\'s important to recognize trustworthy certifiers (covered in <a href="/articulos/simbolos-certificacion-kosher">kosher certification symbols</a>), since not every symbol in the world has the same level of rigor.</li>
<li><strong>The category:</strong> "Dairy" or "D," "Meat" or "M," "Pareve" (neutral), or "Fish" (which in many traditions is treated as a category separate from meat). This letter determines what other foods the product can be combined with.</li>
<li><strong>"Kosher for Passover":</strong> an additional indication needed during the holiday, distinct from the regular kosher certification used the rest of the year. A product can be kosher year-round but not fit for Passover if it contains any derivative of the five grains.</li>
<li><strong>Certification date or code:</strong> some certifiers include a code or date to verify that the seal is still current, since recipes and factory processes can change, and certification can be lost without the packaging design changing at all.</li>
</ul>

<h2>The "DE" category: the detail most often overlooked</h2>
<p>Beyond "D," "M" and "Pareve," some labels include "DE" (dairy equipment): it means the product contains no dairy ingredients, but was made on the same equipment as dairy products. For practical purposes, many opinions treat a "DE" product more flexibly than a true "D" product, but not the same as pure pareve. It is worth understanding this difference in detail in <a href="/articulos/que-significa-pareve">what pareve means</a>.</p>

<h2>Why you shouldn\'t assume by elimination</h2>
<p>When a product has no visible certification but the ingredient list looks simple (for example, just water, salt and a vegetable), it can be tempting to assume it is kosher by elimination. The general recommendation from kashrut authorities is not to do that: many additives and industrial processes are not obvious at a glance, from enzymes used in processing to equipment shared with other products on the same production line.</p>

<h2>Frequently asked questions</h2>
<p><strong>Is a product with no category (D/M/Pareve) printed on it always pareve?</strong><br>
Don\'t assume it. The absence of a letter doesn\'t confirm anything on its own; if the label doesn\'t clarify the category, it\'s worth looking the product up directly in the certifier\'s database.</p>
<p><strong>Do barcode scanning apps replace the need to check the physical label?</strong><br>
They help a lot, but it\'s best to use them as a complement rather than a full replacement, especially with imported or recently manufactured products the database may not have updated yet.</p>
<p><strong>Do all countries require the kosher label to be in the local language?</strong><br>
No, it\'s common to find imported products with kosher information only in the language of origin (Hebrew, English), which makes it more valuable to recognize the symbols by sight instead of relying on the text.</p>

<h2>Related reading</h2>
<p>To recognize the most common seals at a glance, see <a href="/articulos/simbolos-certificacion-kosher">kosher certification symbols</a>. On KosherMap you can also search products by name or barcode and filter directly by certifier, category and type, so you don\'t have to rely solely on the physical label.</p>',
            ],
            'bishul-akum' => [
                'title' => 'Bishul Akum: why some cooked foods need Jewish supervision',
                'excerpt' => 'There is a specific category of rabbinic laws about food cooked by non-Jews, known as bishul akum. We explain what it\'s about.',
                'content' => '<p>Bishul akum (literally "cooking of a non-Jew") is a category of rabbinic laws that restricts the consumption of certain foods cooked entirely by a non-Jewish person, even if all the ingredients are kosher. The prohibition was established by the Talmudic sages, mainly to encourage social cohesion and prevent cultural assimilation between communities.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/bishul-akum.jpg" alt="Bishul akum governs food cooked entirely by a non-Jew, even when the ingredients are kosher." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Bishul akum governs food cooked entirely by a non-Jew, even when the ingredients are kosher. Photo: Seattle Municipal Archives from Seattle, WA via <a href="https://commons.wikimedia.org/wiki/File%3ACooks_in_kitchen%2C_1930_%2820981103874%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>

<h2>Which foods fall into this category (and which don\'t)</h2>
<p>This law does not apply to all foods: it is generally limited to foods considered "fit to serve at a king\'s table" (chashivut) and that are not eaten raw. That is why fruits, raw vegetables and most processed snacks do not fall into this category. The "chashivut" criterion is partly subjective and varies somewhat by community: an elaborate stew clearly falls under it, while a simply boiled vegetable is more debated among different rabbinic opinions.</p>

<h2>How it is resolved in a certified factory or restaurant</h2>
<p>There are two common ways to resolve the issue in an industrial production or certified restaurant context:</p>
<ul>
<li><strong>An observant Jew actively participates in the cooking process</strong>, for example by lighting the fire or the cooking equipment before the production process begins.</li>
<li><strong>Rabbinic supervision certifies</strong> that a Jewish representative was present when the cooking equipment was turned on during each production shift, even if the rest of the process is later handled by non-Jewish staff.</li>
</ul>
<p>This is one of the reasons why kosher certification of a food factory is not limited to reviewing ingredients: it also supervises processes, staff presence and operational protocols at the facility, which makes certifiers\' work much more complex than a simple ingredient checklist.</p>

<h2>Why this affects restaurants that aren\'t kosher</h2>
<p>This is one of the underlying reasons a dish cooked at a restaurant without certification raises doubts even when all the ingredients seem fit: beyond the meat\'s origin or shared equipment, who actually lit the fire and handled the cooking also comes into play. The topic is covered with concrete examples in <a href="/articulos/comer-kosher-restaurante">how to eat kosher at a non-certified restaurant</a>.</p>

<h2>Frequently asked questions</h2>
<p><strong>Does bishul akum also apply at home, with non-Jewish domestic staff?</strong><br>
Yes, the principle is the same regardless of where the cooking takes place; what changes is the practical solution, generally that a Jewish family member participates in lighting the fire.</p>
<p><strong>Does an electric oven with a timer solve the lighting problem?</strong><br>
It depends on the rabbinic opinion followed; some accept it as a form of the Jewish owner\'s "participation," others require a more direct act. When in doubt, consult the community rabbi.</p>
<p><strong>Does bishul akum have anything to do with koshering utensils?</strong><br>
They are separate categories: bishul akum is about who cooks, while koshering (like <a href="/articulos/hagala-utensilios-metal">hagalah</a>) is about what a utensil previously absorbed.</p>

<h2>Related reading</h2>
<p>To see how this principle applies in practice when eating out, see <a href="/articulos/comer-kosher-restaurante">how to eat kosher at a non-certified restaurant</a>. And for another category where who handles the product matters greatly, see <a href="/articulos/vino-kosher">kosher wine</a>.</p>',
            ],
            'vino-mevushal' => [
                'title' => 'Mevushal: kosher wine that can be served without restrictions',
                'excerpt' => 'Mevushal wine is a special category that allows it to be served at events without requiring that only Jews handle it.',
                'content' => '<p>As we saw when discussing <a href="/articulos/vino-kosher">kosher wine</a>, the general rule requires that only observant Jews handle the wine from production to serving. Mevushal wine ("boiled" or pasteurized) is a practical exception to this rule: once the wine goes through a heating process at a specific minimum temperature, it keeps its kosher status no matter who serves it afterward.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/vino-mevushal.jpg" alt="Mevushal wine is pasteurised, so it keeps its kosher status even when served by a non-Jew." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Mevushal wine is pasteurised, so it keeps its kosher status even when served by a non-Jew. Photo: misbehave via <a href="https://commons.wikimedia.org/wiki/File%3ABottle_%26_glass_of_red_Bordeaux_style_blend.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>

<h2>Why heat changes everything</h2>
<p>This category exists thanks to a halachic principle according to which wine altered by heat loses the ritual "dignity" that originally motivated the restriction, since historically that concern was aimed at wine\'s use in idolatrous ceremonies — something a boiled wine did not lend itself to in that context. Once it loses that status, it stops mattering who touches or serves it afterward: it can pass through non-Jewish hands without losing its kosher status.</p>

<h2>Where it is used most</h2>
<p>Mevushal wine is very popular for:</p>
<ul>
<li><strong>Catering and events:</strong> weddings, bar/bat mitzvahs and receptions where serving staff is not necessarily Jewish.</li>
<li><strong>Kosher restaurants open to the general public:</strong> lets any waiter serve the wine without special supervision at each table.</li>
<li><strong>Airlines and hotels</strong> that offer kosher options, where it would be impractical to guarantee only Jewish staff touches every bottle.</li>
<li><strong>Synagogues and large community events</strong>, where wine passes through many hands during a kiddush.</li>
</ul>

<h2>How it is pasteurized without ruining the flavor</h2>
<p>For a long time, boiling wine the traditional way affected the flavor considerably, and mevushal had a reputation for being lower quality. Today there are modern flash pasteurization techniques, where the wine is heated to the required minimum temperature for only a few seconds and cooled immediately, something that used to be much harder to achieve without ruining the flavor. This has greatly expanded the range of premium mevushal wines on the market, including labels from well-known wineries that previously only produced non-mevushal wine.</p>

<h2>Frequently asked questions</h2>
<p><strong>Is every kosher wine sold in supermarkets mevushal?</strong><br>
No, there are also non-mevushal kosher wines on the general market. Check the label or the certifier\'s website if this matters for your use (for example, if you\'ll serve it at an event with non-Jewish staff).</p>
<p><strong>Is mevushal lower quality than regular kosher wine?</strong><br>
Not necessarily anymore. With modern flash pasteurization techniques, many wineries produce premium-level mevushal wine indistinguishable in a blind tasting from the non-pasteurized version.</p>
<p><strong>Can a wine become mevushal after it has already been bottled?</strong><br>
No, the heating process must happen before or during production, under supervision, and is noted on the label or in the certifier\'s product listing.</p>

<h2>Related reading</h2>
<p>To understand why regular wine needs such strict supervision in the first place, see <a href="/articulos/vino-kosher">kosher wine: why it needs special supervision</a>. The topic also relates to <a href="/articulos/bishul-akum">bishul akum</a>, another category where who handles the food matters.</p>',
            ],
            'tevilat-kelim' => [
                'title' => 'Tevilat Kelim: the ritual immersion of new utensils',
                'excerpt' => 'Before using certain new kitchen utensils made by a non-Jewish manufacturer for the first time, there is a custom of immersing them in a mikveh.',
                'content' => '<p>Tevilat Kelim is the practice of immersing new kitchen utensils — metal or glass, bought from a non-Jewish manufacturer — in a mikveh (ritual bath) or a natural water source before using them for food for the first time.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/tevilat-kelim.jpg" alt="A mikveh, the ritual bath where new utensils are immersed before their first use." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">A mikveh, the ritual bath where new utensils are immersed before their first use. Photo: Stefan Walkowski via <a href="https://commons.wikimedia.org/wiki/File%3AMikveh.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>Which utensils need tevilah, and which don\'t</h2>
<p>This custom mainly applies to utensils that come into direct contact with food: pots, pans, cutlery, glass plates and cups. It usually does not apply to electric appliances (such as a toaster or a blender) nor to plastic or wooden utensils, although opinions vary depending on each community\'s tradition, so it is advisable to consult a rabbi about specific cases. The general criterion used to draw this line is similar to the one used for <a href="/articulos/hagala-utensilios-metal">hagalah</a>: the more direct the contact with food and the more durable the material, the more likely it needs immersion.</p>

<h2>The step-by-step process</h2>
<ol>
<li>Clean the utensil well, with no labels, seals or adhesives left that could stand between the water and the surface.</li>
<li>Check that there is no substance (grease, rust, packaging residue) acting as a barrier.</li>
<li>Fully immerse it in the mikveh\'s water, or in a fitting natural water source, while a blessing is recited (for utensils that require it according to tradition).</li>
<li>Once properly immersed, the utensil is ready for normal use, with no further steps needed.</li>
</ol>

<h2>Where to do it in practice</h2>
<p>Many community mikvehs have a specific time slot available for tevilat kelim, separate from personal ritual use, as well as detailed instructions on which materials require immersion and which do not. Some larger communities even have a separate pool dedicated exclusively to this use, to avoid mixing schedules with the mikveh used for personal ritual immersion.</p>

<h2>Frequently asked questions</h2>
<p><strong>Does a second-hand utensil need tevilah just like a new one?</strong><br>
Yes, if it was originally manufactured or sold by a non-Jewish party, the custom applies regardless of whether the utensil is new or used.</p>
<p><strong>What if I buy a utensil and there\'s no mikveh nearby?</strong><br>
Any natural water source that meets the halachic requirements can be used (a river, the sea), though in practice most people use the nearest community mikveh that has a time slot available for this.</p>
<p><strong>Do utensils bought from a Jewish manufacturer also need tevilah?</strong><br>
Generally not; the custom is specifically focused on utensils of non-Jewish origin.</p>

<h2>Related reading</h2>
<p>For other practical steps when equipping a kitchen from scratch, including this custom, see <a href="/articulos/armar-cocina-kosher">how to set up a kosher kitchen from scratch</a>. And for the method used to purify utensils that absorbed non-kosher flavor, see <a href="/articulos/hagala-utensilios-metal">hagalah: how to kosher metal utensils</a>.</p>',
            ],
            'armar-cocina-kosher' => [
                'title' => 'How to set up a kosher kitchen from scratch',
                'excerpt' => 'Starting to keep a kosher kitchen can seem overwhelming at first. We give you a practical guide to the first steps.',
                'content' => '<p>Setting up a kosher kitchen from scratch is a gradual process, and you don\'t need to figure it all out in one day. These are the most common steps families starting out follow:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/armar-cocina-kosher.jpg" alt="Setting up a kosher kitchen is a gradual process: it does not have to be done in one day." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Setting up a kosher kitchen is a gradual process: it does not have to be done in one day. Photo: MeRyan via <a href="https://commons.wikimedia.org/wiki/File%3AKitchen_with_island%2C_New_Orleans_2007.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>

<h2>The steps that come up most often</h2>
<ul>
<li><strong>Define the physical separation:</strong> decide which utensils, pots and dishware will be meat and which dairy. The most practical approach is usually to use different colors (for example, red for meat, blue for dairy) to avoid daily mix-ups.</li>
<li><strong>Separate work surfaces:</strong> cutting boards, dish towels and sponges must also be divided by category, ideally stored in different parts of the kitchen to avoid accidental mixing.</li>
<li><strong>Evaluate shared appliances:</strong> oven, microwave and dishwasher can be koshered between uses or, more simply, assigned to a single category from the start (for example, microwave only for pareve).</li>
<li><strong>Buy certified products:</strong> check the certification symbol on every purchase, until it becomes an automatic habit that doesn\'t require thinking about each time.</li>
<li><strong>Coordinate with a rabbi:</strong> especially to kosher items that were already in the kitchen before starting this process, and to resolve specific doubts about particular materials or utensils.</li>
<li><strong>Add tevilat kelim:</strong> new metal or glass utensils bought from a non-Jewish manufacturer need ritual immersion before their first use.</li>
</ul>

<h2>Go step by step: the order that makes the transition easiest</h2>
<p>A strategy many people just starting out use is to gradually incorporate the separation: first everyday utensils (pots, pans, cutlery), then table dishware, and finally appliances, which tend to be the most expensive and complex to resolve. There is no need to replace the entire kitchen at once, and many families take months to complete the transition without that being a halachic problem in itself: what matters is the intention to keep moving forward, not the speed.</p>

<h2>The budget: how to spread out the investment</h2>
<p>Setting up an entire kitchen from scratch can feel expensive if you think of it as a single expense. In practice, most families spread it out in small, spaced-out purchases: a set of pots this month, cutlery the next, and so on. Community shops and stores often have combos designed specifically for this, at more accessible prices than buying everything separately.</p>

<h2>Frequently asked questions</h2>
<p><strong>Do I need to throw out everything that was already in the kitchen before starting?</strong><br>
Not necessarily. Many utensils can be koshered depending on the material (see <a href="/articulos/hagala-utensilios-metal">hagalah</a>); only what can\'t be koshered, like most ceramics, needs to be replaced.</p>
<p><strong>Does the microwave need two separate units, one for meat and one for dairy?</strong><br>
It\'s not required: many families use a single microwave assigned to "pareve only," or kosher it between uses as described in <a href="/articulos/kasherizar-horno">how to kosher an oven</a> (the same principle applies).</p>
<p><strong>How long does the transition typically take?</strong><br>
It varies a lot depending on each family\'s budget and pace, but it\'s not unusual for it to take anywhere from several months to a year to comfortably complete every step.</p>

<h2>Related reading</h2>
<p>For the detail of the ritual immersion of new utensils, see <a href="/articulos/tevilat-kelim">tevilat kelim</a>. And to understand the method used to kosher what you already had at home, see <a href="/articulos/hagala-utensilios-metal">hagalah: how to kosher metal utensils</a>.</p>',
            ],
            'certificaciones-kosher-mundo' => [
                'title' => 'Differences between kosher certifications around the world',
                'excerpt' => 'Not all kosher certifiers follow exactly the same criteria. Knowing these differences helps you choose products with confidence.',
                'content' => '<p>Although the fundamental principles of kashrut are universal, there are hundreds of certifying agencies in the world, and each can have slightly different criteria on specific topics — for example, what level of supervision it requires for <a href="/articulos/bishul-akum">bishul akum</a>, or how it handles certain chemical additives whose origin is hard to trace.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/certificaciones-kosher-mundo.svg" alt="The principles of kashrut are universal, but each region has its own certifying agencies." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">The principles of kashrut are universal, but each region has its own certifying agencies. Illustration: KosherMap.</figcaption>
</figure>

<h2>How certification varies by region</h2>
<ul>
<li><strong>United States:</strong> home to the largest industrial certifiers (OU, OK, Star-K, Kof-K), with highly standardized processes for mass export. A product certified there tends to reach dozens of countries in almost the same form.</li>
<li><strong>Israel:</strong> the Rabbanut (chief rabbinate) offers official state certification, while organizations like the Badatz maintain additional standards considered stricter by certain communities, sometimes resulting in two levels of certification on the same product.</li>
<li><strong>Europe:</strong> certifiers like the Beth Din of various cities (London, Paris, Zurich) supervise both local production and imports, adapting to markets with less industrial scale than the United States.</li>
<li><strong>Latin America:</strong> each community usually has its own local Va\'ad Hakashrut (for example, in Buenos Aires, São Paulo or Mexico City), which certifies both local products and restaurants, generally with a scope limited to its own city or country.</li>
</ul>

<h2>Why there isn\'t just one single agency</h2>
<p>Unlike other quality seals, there is no single central authority for kashrut worldwide. Each certifier answers to its own chain of supervising rabbis, and its recognition is built over time, based on the trust other communities and rabbis place in it. That is why a certifier can be highly respected in its country of origin and practically unknown elsewhere, without that meaning it is any less serious.</p>

<h2>What to do with a seal you don\'t recognize</h2>
<p>For the consumer, the most important thing is to learn to recognize the active certifiers in their region (covered in more detail in <a href="/articulos/simbolos-certificacion-kosher">kosher certification symbols</a>). And when in doubt about an unfamiliar symbol, consult the community rabbi or look into the agency\'s reputation before trusting a product. Most large certifiers publish public lists of certified products on their websites, which is especially useful when buying imported goods.</p>

<h2>Frequently asked questions</h2>
<p><strong>Is a product certified in one country automatically accepted in another?</strong><br>
Not always. It depends on mutual recognition between certifiers and on each community\'s criteria; some families only trust a limited list of agencies, regardless of where the product comes from.</p>
<p><strong>Is there a single global list of trustworthy certifiers?</strong><br>
There isn\'t one official list, but many regional rabbinic organizations publish their own lists of agencies they recognize, which serve as a practical reference.</p>
<p><strong>Why do some products carry two or three different seals?</strong><br>
This usually happens when the manufacturer wants to reach markets or communities with different trust criteria, and seeks approval from several recognized agencies in each region.</p>

<h2>Related reading</h2>
<p>To recognize the most common symbols at a glance, see <a href="/articulos/simbolos-certificacion-kosher">the most common kosher certification symbols</a>. And to understand all the information a label can carry beyond the seal, see <a href="/articulos/como-leer-etiqueta-kosher">how to read a kosher product label</a>.</p>',
            ],
            'queso-kosher-cuajo' => [
                'title' => 'Kosher cheese: why it needs special rennet',
                'excerpt' => 'Cheese is one of the dairy products with the most kosher restrictions, mainly because of the origin of the rennet used to make it.',
                'content' => '<p>Rennet is the enzyme traditionally used to coagulate milk and separate the whey in cheesemaking. The problem from a kashrut standpoint is that traditional rennet is extracted from a calf\'s stomach, and for it to be fit, that animal must have been slaughtered via <a href="/articulos/shejita-sacrificio-kosher">shechita</a> — something that almost never happens in conventional cheese manufacturing.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/queso-kosher-cuajo.jpg" alt="Cheese needs specific certification because of the origin of the rennet that curdles the milk." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Cheese needs specific certification because of the origin of the rennet that curdles the milk. Photo: Daderot via <a href="https://commons.wikimedia.org/wiki/File%3ACheese_display%2C_Cambridge_MA_-_DSC05391.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>

<h2>Why "regular" cheese usually isn\'t kosher by elimination</h2>
<p>That is why practically all "regular" cheese on the market, even if made only with milk and rennet, is not kosher without specific certification, since the rennet\'s origin cannot be verified by sight. Neither the color, taste, nor texture of the cheese changes based on the type of rennet used, so there is no way to tell them apart without information from the manufacturer.</p>

<h2>The three alternatives manufacturers use</h2>
<p>Options used by kosher cheese manufacturers include:</p>
<ul>
<li><strong>Kosher animal rennet:</strong> extracted from animals slaughtered according to shechita and under rabbinic supervision throughout the chain, from slaughter to enzyme extraction.</li>
<li><strong>Microbial rennet:</strong> produced through fermentation of fungi or bacteria, with no animal origin, increasingly common in both industrial and kosher cheeses, since it also solves the problem for vegetarian consumers.</li>
<li><strong>Plant-based rennet:</strong> extracted from certain plants (such as thistle), traditionally used in some specific artisanal cheese varieties, especially on the Iberian Peninsula.</li>
</ul>

<h2>Gvinat Yisrael: a criterion that goes beyond rennet</h2>
<p>Besides rennet, there is another relevant factor: many communities require that the cheese be made under constant Jewish supervision (Gvinat Yisrael) to consider it fully kosher, an additional criterion beyond a simple ingredient analysis, closer to the principle behind <a href="/articulos/bishul-akum">bishul akum</a>: correct ingredients aren\'t enough, who supervises the process matters too.</p>

<h2>Frequently asked questions</h2>
<p><strong>Are vegan or "dairy-free" cheeses automatically kosher?</strong><br>
Not necessarily. Even though they avoid the animal rennet problem, you still need to check the rest of the ingredients and the equipment used, just like with any other industrial product.</p>
<p><strong>Does packaged shredded cheese carry the same risk as a whole block?</strong><br>
Yes, and sometimes more: the shredding and packaging process can introduce other ingredients (anti-caking agents, preservatives) that also require verification.</p>
<p><strong>How do I know if a cheese uses microbial or animal rennet with no certification?</strong><br>
Without visible certification there is no reliable way to know from the label; that is why buying cheese with a recognized seal is the safest way to avoid mistakes.</p>

<h2>Related reading</h2>
<p>To understand the slaughter method that determines whether animal rennet is fit, see <a href="/articulos/shejita-sacrificio-kosher">shechita: the kosher slaughter method</a>. And for another equally debated animal-derived ingredient, see <a href="/articulos/gelatina-kosher">kosher gelatin: the halachic debate</a>.</p>',
            ],
            'huevos-kosher' => [
                'title' => 'Kosher eggs: what to check before using them',
                'excerpt' => 'Eggs are pareve and generally kosher, but there is a mandatory checking step before cooking them.',
                'content' => '<p>Eggs from kosher birds (such as chickens) are, in principle, pareve and fit for consumption. However, before using an egg, tradition requires checking that it contains no blood spots in the yolk, since an egg with blood is considered unfit for consumption.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/huevos-kosher.jpg" alt="Eggs are pareve, but they must be checked for blood spots before use." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Eggs are pareve, but they must be checked for blood spots before use. Photo: Evan-Amos via <a href="https://commons.wikimedia.org/wiki/File%3A6-Pack-Chicken-Eggs.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>

<h2>The checking procedure</h2>
<p>The procedure is simple: when cracking the egg, visually check the yolk (and sometimes the white) against the light, looking for red spots or marks. It\'s best to crack each egg into a separate container before adding it to a larger mix, precisely so it can be discarded without ruining the rest of the preparation. If blood is found, the egg is discarded entirely; if the yolk is clean, the egg is fit to use normally.</p>

<h2>Why this happens in some eggs</h2>
<p>A blood spot usually originates from a ruptured blood vessel during the egg\'s formation inside the hen, and has nothing to do with whether the egg is fertilized or with any health issue in the bird. It is more common in eggs from older hens, though it can appear in any egg with no predictable pattern, which is why the check is always done, not just "when it looks suspicious."</p>

<h2>Other facts about eggs and kashrut</h2>
<ul>
<li>The shell and white generally don\'t carry the same risk as the yolk, although custom varies by community: some check both parts with equal care.</li>
<li>Eggs from non-kosher birds (such as ostrich or certain birds of prey) are also not fit, regardless of the presence of blood, since the underlying issue is the bird\'s origin, not the blood itself.</li>
<li>Industrial products containing egg (such as pasta or mayonnaise) generally go through a quality control process that automatically detects eggs with blood via candling, but still require certification to guarantee that control was done under the correct criteria.</li>
</ul>

<h2>Frequently asked questions</h2>
<p><strong>Do quail or duck eggs also need to be checked?</strong><br>
Yes, the same criterion applies to eggs from any kosher bird, not just chicken eggs.</p>
<p><strong>Can an egg with a tiny blood spot be saved by removing just that part?</strong><br>
Opinions vary depending on tradition and the size of the spot; some allow removing only the affected point, others require discarding the whole egg. When in doubt, it\'s best to follow the stricter opinion or consult a rabbi.</p>
<p><strong>Do kosher-certified eggs already come checked from the factory?</strong><br>
It doesn\'t replace the check at home: certification guarantees the bird\'s origin and the industrial process, but checking the yolk for blood remains the cook\'s responsibility, except for industrial products that already went through certified candling.</p>

<h2>Related reading</h2>
<p>It is one of the simplest habits to incorporate into a daily kosher kitchen: checking each egg as soon as it is cracked, before mixing it with the rest of the ingredients. For other everyday practical steps, see <a href="/articulos/armar-cocina-kosher">how to set up a kosher kitchen from scratch</a>. And for another pareve food with its own checking rules, see <a href="/articulos/insectos-frutas-verduras">insects in fruits and vegetables</a>.</p>',
            ],
            'pescado-kosher-aletas-escamas' => [
                'title' => 'Kosher fish: fins and scales, the basic rules',
                'excerpt' => 'Unlike meat, kosher fish does not require shechita, but it must meet a specific physical criterion.',
                'content' => '<p>The Torah sets a relatively simple rule for identifying kosher fish: it must have both fins (snapir) and scales (kaskeset) visible to the naked eye. This combination is present in the vast majority of freshwater and saltwater fish commonly eaten, such as salmon, tuna, hake, trout and mackerel.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/pescado-kosher-aletas-escamas.jpg" alt="To be kosher, a fish must have visible fins and scales." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">To be kosher, a fish must have visible fins and scales. Photo: IndayLiburan via <a href="https://commons.wikimedia.org/wiki/File%3AFish_stalls_at_Valencia_Public_Market_01.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>What is excluded from kashrut</h2>
<ul>
<li><strong>All shellfish</strong> (shrimp, prawns, crab, mussels, oysters): they lack both fins and scales under any criterion.</li>
<li><strong>Octopus and squid:</strong> also fail to meet the criterion, having neither true fins nor scales.</li>
<li><strong>Shark and monkfish:</strong> they lack true scales according to most halachic opinions, though the topic has been debated for years among different rabbinic authorities.</li>
<li><strong>Eel:</strong> lacks visible scales, and is excluded from the kosher criterion.</li>
<li><strong>Swordfish:</strong> its status has historically been debated among different rabbinic authorities, and not every certifier treats it the same way.</li>
</ul>

<h2>Why kosher fish is simpler to prepare than meat</h2>
<p>An important difference from meat: kosher fish does not require <a href="/articulos/shejita-sacrificio-kosher">shechita</a> or a blood-removal salting process, which greatly simplifies its preparation. However, in many traditions — especially Ashkenazi — fish is treated as a category separate from meat and dairy, avoiding combining it with meat in the same dish (although it does not require the same strict utensil separation that applies between meat and milk).</p>

<h2>How to verify fish when buying it</h2>
<p>When buying fresh fish, it\'s worth verifying that it retains skin with visible scales, since some filleting removes the skin entirely, making verification difficult. That is why many kosher fishmongers leave an identifiable patch of skin on the cut, usually a strip near the tail, specifically so the species can be confirmed without doubt.</p>

<h2>Frequently asked questions</h2>
<p><strong>Is caviar kosher?</strong><br>
It depends on the fish of origin: sturgeon caviar is a debated topic (due to the sturgeon\'s atypical scales), while roe from other fish with clear fins and scales is generally accepted without issue.</p>
<p><strong>Is certification needed to buy fresh, whole fish at the fish market?</strong><br>
It isn\'t mandatory if you can verify the species yourself by its physical characteristics, though for processed products (skinless fillets, canned goods) it is worth looking for certification.</p>
<p><strong>Why is fish kept separate from meat if both are fit on their own?</strong><br>
It is a custom, not a prohibition of the same category as meat and dairy; it originated from a historical health concern that no longer applies, but it is maintained as tradition in many communities.</p>

<h2>Related reading</h2>
<p>To understand the process required for meat from mammals and poultry, see <a href="/articulos/shejita-sacrificio-kosher">shechita: the kosher slaughter method</a>. And for another animal-derived ingredient with its own rules, see <a href="/articulos/gelatina-kosher">kosher gelatin: the halachic debate</a>.</p>',
            ],
            'frutos-secos-contaminacion-cruzada' => [
                'title' => 'Nuts and kashrut: cross-contamination risks',
                'excerpt' => 'Nuts are naturally pareve, but industrial processing can introduce kashrut risks that are not obvious.',
                'content' => '<p>Almonds, walnuts, peanuts and most nuts are, in their raw, natural form, pareve foods with no kashrut restrictions. The problem appears when they enter the industrial processing chain, where they can mix with other products on the same production lines.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/frutos-secos-contaminacion-cruzada.jpg" alt="Raw they are pareve without restrictions; the risk appears in industrial processing." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Raw they are pareve without restrictions; the risk appears in industrial processing. Photo: Famartin via <a href="https://commons.wikimedia.org/wiki/File%3A2021-01-06_12_15_43_Cranberry_trail_mix_with_cranberries%2C_peanuts%2C_raisins%2C_walnuts%2C_almonds%2C_sunflower_seeds%2C_pepitas_in_the_Franklin_Farm_section_of_Oak_Hill%2C_Fairfax_County%2C_Virginia.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>The most common risks</h2>
<ul>
<li><strong>Dairy flavorings:</strong> nuts "roasted with butter" or coated in milk chocolate are no longer pareve, and move into the dairy category, with all the mixing restrictions that implies.</li>
<li><strong>Shared lines:</strong> a factory may process pareve nuts on the same equipment later used for dairy or meat-derived products, generating non-kosher traces if there is no certified cleaning between batches, something no ingredient label reveals on its own.</li>
<li><strong>Cooking oils:</strong> some fried nuts use oils shared with other non-kosher products, without it affecting the final flavor.</li>
<li><strong>Glazes and coatings:</strong> "candied" nuts or those with a sweet coating may contain <a href="/articulos/gelatina-kosher">gelatin</a> or other animal-derived ingredients in the glaze.</li>
</ul>

<h2>Why this is more common than it seems</h2>
<p>Many nut factories process dozens of different varieties and presentations at the same plant, to make the most of the same equipment: the line that today roasts plain almonds may have processed a milk-chocolate-coated batch yesterday. Without certified cleaning between batches (or without lines dedicated exclusively to pareve products), enough residue remains for the product to no longer be considered pareve under a strict criterion.</p>

<h2>How to reduce the risk in practice</h2>
<p>That is why, although a raw, unprocessed nut bought in bulk from a trustworthy source or in its original shell almost never presents problems, industrial products (nut mixes, flavored snacks, granola bars) should always be checked for certification, without assuming they are automatically kosher just because the main ingredient is. This applies just as strongly to products marketed as "natural" or "additive-free": those labels say nothing about shared equipment.</p>

<h2>Frequently asked questions</h2>
<p><strong>Are unbranded bulk nuts riskier than packaged ones?</strong><br>
They can be, since there is no way to trace the origin or process; it\'s best to buy them from trustworthy stores or prefer certified brands.</p>
<p><strong>Does peanut butter carry the same risk?</strong><br>
Yes, and sometimes more, since it often includes additional oils and stabilizers that also require verification, beyond the peanuts themselves.</p>
<p><strong>Do home-activated or soaked nuts change the analysis at all?</strong><br>
No, if the original nut was raw and not industrially processed, soaking it at home introduces no new kashrut risk.</p>

<h2>Related reading</h2>
<p>For another ingredient where industrial processing is the key issue, see <a href="/articulos/gelatina-kosher">kosher gelatin: the halachic debate</a>. And to understand how to read all the relevant information on a label, see <a href="/articulos/como-leer-etiqueta-kosher">how to read a kosher product label</a>.</p>',
            ],
            'kashrut-y-veganismo' => [
                'title' => 'Kashrut and veganism: is eating vegan the same as eating kosher?',
                'excerpt' => 'Many people assume a vegan product is automatically kosher. The reality is more nuanced.',
                'content' => '<p>It\'s a common confusion: if a product contains no animal-derived ingredient, it would seem logical to assume it is automatically kosher. However, kashrut is not based solely on ingredients, but also on production processes, the equipment used and, in some cases, who supervises production.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kashrut-y-veganismo.jpg" alt="A vegan product is not automatically kosher: kashrut also looks at processes and equipment." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">A vegan product is not automatically kosher: kashrut also looks at processes and equipment. Photo: HaJunkiyada via <a href="https://commons.wikimedia.org/wiki/File%3ALiat_Portal_for_Foodie_Disorder_-_Cauliflower_from_SF_farmers%27_market.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>Cases where a vegan product may not be kosher</h2>
<ul>
<li><strong>Shared equipment:</strong> a vegan factory may use the same production line that previously processed meat or dairy products, without the certified cleaning kashrut requires between batches, a problem similar to what\'s seen in <a href="/articulos/frutos-secos-contaminacion-cruzada">nuts and cross-contamination</a>.</li>
<li><strong>Wine and derivatives:</strong> a vegan wine (without animal-derived fining agents) still requires the entire production process to be in the hands of observant Jews to be kosher, as explained in <a href="/articulos/vino-kosher">kosher wine</a>.</li>
<li><strong>Insects:</strong> certain dyes (such as carmine, of animal origin, extracted from an insect) are forbidden in kosher but are sometimes labeled vegan-friendly by mistake or under different vegan certification standards.</li>
<li><strong>Bishul akum:</strong> a vegan food cooked entirely by a non-Jewish person may fall under this restriction, depending on how the product is classified (see <a href="/articulos/bishul-akum">bishul akum</a>).</li>
</ul>

<h2>Why vegan and kosher certifications don\'t fully overlap</h2>
<p>Vegan certifications focus almost exclusively on ingredient origin: no meat, dairy, egg, honey or animal derivatives of any kind. Kashrut looks at that too, but also reviews the full process: what equipment was used, who was present at certain cooking stages, and whether there was contact with non-kosher products anywhere along the chain. These are verification systems with different goals, even though they overlap on many products.</p>

<h2>The other direction: kosher isn\'t always vegan</h2>
<p>And the reverse is also true: many pareve kosher products end up being vegan, because "pareve" already excludes meat and dairy. But a product can be kosher and not vegan with no contradiction at all: a yogurt with kosher dairy certification, for example, is perfectly valid for kashrut and not suitable for vegans.</p>

<h2>Frequently asked questions</h2>
<p><strong>Is there a certification that combines both criteria into a single seal?</strong><br>
Some certifiers offer combined indications ("Kosher Pareve" alongside a vegan seal from the same manufacturer), but these are two separate verification processes, not a single procedure.</p>
<p><strong>Does a 100% vegan restaurant need a separate kosher certification to be trustworthy?</strong><br>
Yes, if it seeks to be considered kosher: the fact that every dish is vegan doesn\'t automatically resolve the equipment issue or who is doing the cooking.</p>
<p><strong>Is honey vegan or kosher?</strong><br>
Honey is kosher (a notable exception, since it comes from an insect but halacha permits it), but it is not vegan, since most vegan certifications exclude any animal-derived product, including insects.</p>

<h2>Related reading</h2>
<p>To understand why wine needs such particular supervision, even when vegan, see <a href="/articulos/vino-kosher">kosher wine</a>. And on the cross-contamination risk in seemingly simple products, see <a href="/articulos/frutos-secos-contaminacion-cruzada">nuts and kashrut</a>.</p>',
            ],
            'separar-la-jala' => [
                'title' => 'How to separate challah',
                'excerpt' => 'Separating challah is a specific commandment that applies when kneading dough in large quantities, rooted in the Temple offerings.',
                'content' => '<p>Separating challah (hafrashat challah) is a biblical commandment that originally required giving a portion of bread dough to the priests (kohanim) of the Temple in Jerusalem. After the Temple\'s destruction, the practice changed: today, instead of being given away, the separated portion is burned or discarded respectfully.</p>

<h2>When this mitzvah applies</h2>
<p>This mitzvah applies when kneading a significant amount of dough made with one of the five grains (wheat, barley, oats, rye or spelt) — the exact minimum amount (generally around 1.2 kg of flour) varies depending on which halachic opinion is followed. Below that amount, separating is not obligatory, though some customs do it anyway without the blessing.</p>

<h2>The step-by-step process</h2>
<ol>
<li>Knead the bread dough normally, until it reaches the required minimum amount.</li>
<li>Separate a small portion (traditionally olive-sized or larger, depending on custom).</li>
<li>Recite the corresponding blessing before separating the portion, if the amount of dough reaches the minimum that requires it.</li>
<li>Burn the separated portion (wrapped in aluminum foil, in the oven) or discard it in a way that it is not used for regular consumption.</li>
</ol>
<p>This practice is why many certified industrial kosher bakeries separate challah as part of their production process, and why many Jewish women and families do it at home whenever they bake bread or challah for Shabbat in sufficient quantity.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/jala-shabat.jpg" alt="Braided challah ready for Shabbat, with the cup of wine and the salt. The cloth covering it is part of the Shabbat table custom." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Braided challah ready for Shabbat, with the cup of wine and the salt. The cloth covering it is part of the Shabbat table custom. Photo: HaJunkiyada via <a href="https://commons.wikimedia.org/wiki/File:Liat_Portal_for_Foodie_Disorder_-_Challah_for_Shabbat_with_wine_and_salt.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>A family ritual, beyond the technical requirement</h2>
<p>In many homes this becomes a special moment. In ours, for instance, the girls always try to reach the minimum amount of dough specifically so they can separate the challah with a blessing, since doing it with the berachah carries extra meaning. Beyond the technical requirement, it ends up being a family ritual built around the oven.</p>
<p>Over time, many families turn this moment into an almost weekly custom, especially when baking bread or challah for Shabbat: the older girls in the house take charge of calculating the amount of flour to make sure they reach the minimum, and the moment of separating the portion with the blessing becomes a fixed part of the Friday routine.</p>

<h2>Frequently asked questions</h2>
<p><strong>Do I need to separate challah if I only knead a small amount, for a small family?</strong><br>
If the minimum amount of flour isn\'t reached, separation isn\'t obligatory according to halacha, though some families do it anyway, without the blessing, as a custom.</p>
<p><strong>Can challah be separated from dough that isn\'t specifically for bread (for example, pastries)?</strong><br>
Yes, the mitzvah applies to any dough made with the five grains that reaches the minimum amount, not just traditional bread.</p>
<p><strong>What happens if I forget to separate the challah before baking?</strong><br>
It can be separated even after the bread is baked, though it\'s best to do it beforehand; consult a rabbi if this specific doubt comes up.</p>

<h2>Related reading</h2>
<p>If you want to go deeper, the <a href="https://oukosher.org/the-kosher-primer/" target="_blank" rel="noopener">Orthodox Union\'s guide</a> covers the laws of hafrashat challah with sources, and you can check the <a href="https://en.wikipedia.org/wiki/Challah" target="_blank" rel="noopener">Wikipedia entry on challah</a> for historical context and regional variations of the bread. On other holidays and food customs in the Jewish calendar, see <a href="/articulos/calendario-judio-festividades-alimentacion">the Jewish calendar and holidays</a>.</p>',
            ],
            'calendario-judio-festividades-alimentacion' => [
                'title' => 'The Jewish calendar and the holidays that affect kosher eating',
                'excerpt' => 'Several Jewish holidays have their own food customs, beyond the general rules of kashrut.',
                'content' => '<p>Besides the kashrut rules that apply all year, the Jewish calendar brings holidays with their own food customs, and knowing them helps explain why certain products come and go from store shelves at certain times of year.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/calendario-judio-festividades-alimentacion.jpg" alt="Apple, honey and pomegranate: the symbols of Rosh Hashanah, one of the festivals with its own food customs." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Apple, honey and pomegranate: the symbols of Rosh Hashanah, one of the festivals with its own food customs. Photo: Gilabrand via <a href="https://commons.wikimedia.org/wiki/File%3ASymbols_of_Rosh_Hashana.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>

<h2>A tour through each holiday</h2>
<ul>
<li><strong>Rosh Hashanah:</strong> it is customary to eat apple with honey to symbolize a sweet year, and to avoid bitter or sour foods at the festive table. It is also common to eat pomegranate, symbolically counting its seeds as the merits wished for the new year.</li>
<li><strong>Yom Kippur:</strong> a full 25-hour fast day, with no food or drink, except for specific medical exceptions. The meal before the fast (seudah mafseket) is usually hearty but simple, avoiding very salty foods that cause thirst.</li>
<li><strong>Sukkot:</strong> it is customary to eat in a temporary outdoor hut (sukkah) throughout the holiday week, recreating the temporary dwellings from the exodus from Egypt.</li>
<li><strong>Hanukkah:</strong> a tradition of eating foods fried in oil (such as sufganiyot, filled doughnuts, and latkes, potato pancakes) commemorating the miracle of the oil that lasted eight days.</li>
<li><strong>Purim:</strong> hamantaschen (Haman\'s ears), filled triangular pastries, are prepared, and it is customary to share gift baskets of food (mishloach manot) with friends and family, plus a festive meal (seudah) in the afternoon.</li>
<li><strong>Passover:</strong> the holiday with the most dietary restrictions, centered on the prohibition of <a href="/articulos/jametz-pesaj">chametz</a>, with matzah as the central food of the whole week.</li>
<li><strong>Shavuot:</strong> a custom of eating dairy foods, with dishes like cheesecake and blintzes (cheese-filled pancakes) taking center stage, commemorating the giving of the Torah.</li>
</ul>

<h2>Why this affects what you see on shelves</h2>
<p>That is why products like matzah, sufganiyot or kosher-for-Passover wine appear with much greater availability on shelves and in stores right before each holiday, and often disappear entirely the rest of the year. For anyone unfamiliar with the Jewish calendar, this can seem erratic; in reality it follows a fairly predictable annual cycle once you know the dates.</p>

<h2>The lunar calendar: why the dates "move"</h2>
<p>The Jewish calendar is lunisolar, which makes holidays fall on different dates of the Gregorian calendar each year, though always around the same season (Passover always in the Northern Hemisphere\'s spring, for example). It\'s worth checking the specific Jewish calendar for each year instead of memorizing a fixed date.</p>

<h2>Frequently asked questions</h2>
<p><strong>Do all these holidays have the same level of dietary restriction as Passover?</strong><br>
No, Passover is the strictest in terms of what can and cannot be eaten or owned. The rest have specific food customs, but not the same total prohibition on certain products.</p>
<p><strong>Are there holidays where you can eat chametz even close to Passover?</strong><br>
Yes, outside the specific Passover period there is no chametz restriction; the prohibition only applies during the days of that holiday.</p>
<p><strong>Why don\'t fish or meat have a holiday associated with them like dairy does with Shavuot?</strong><br>
There\'s no single reason; the custom of eating dairy on Shavuot simply has specific historical and symbolic roots that weren\'t replicated the same way with other foods on other holidays.</p>

<h2>Related reading</h2>
<p>To go deeper into the holiday with the most dietary rules of the year, see <a href="/articulos/jametz-pesaj">chametz: what it is and how it is removed before Passover</a>. And for the dishware many families set aside specifically for that time, see <a href="/articulos/vajilla-para-pesaj">Passover dishware</a>.</p>',
            ],
            'errores-comunes-empezar-comer-kosher' => [
                'title' => 'Common mistakes when starting to eat kosher',
                'excerpt' => 'Adopting kashrut for the first time is a learning process. We go over the most frequent mistakes to avoid from the start.',
                'content' => '<p>Starting to keep a kosher diet is a process that takes time, and it\'s normal to make mistakes at first. We go over the most common ones so they\'re easier to avoid from the start.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/errores-comunes-empezar-comer-kosher.jpg" alt="When starting out, the most common mistake is assuming a product is kosher without looking for certification." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">When starting out, the most common mistake is assuming a product is kosher without looking for certification. Photo: Nenad Stojkovic via <a href="https://commons.wikimedia.org/wiki/File%3ACorn_in_a_shopping_trolley._%2851964905166%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>

<h2>The most frequent mistakes</h2>
<ul>
<li><strong>Assuming "natural" or "preservative-free" means kosher:</strong> a product\'s marketing has no direct relation to its kashrut status. You should always look for certification, no matter how simple the ingredient list looks.</li>
<li><strong>Not checking products that seem obviously pareve:</strong> snacks, baked goods and candy sometimes contain dairy ingredients or <a href="/articulos/gelatina-kosher">gelatin</a> that aren\'t obvious from the product name.</li>
<li><strong>Mixing meat and dairy utensils by accident:</strong> at first it\'s easy to forget the separation; labeling or using different colors helps a lot during the transition, as detailed in <a href="/articulos/armar-cocina-kosher">how to set up a kosher kitchen from scratch</a>.</li>
<li><strong>Not checking leafy vegetables for insects:</strong> a step many people new to kashrut are completely unaware of, and one that applies especially to leafy greens.</li>
<li><strong>Trusting unknown or unclear certifications:</strong> not every symbol on a package is a real kosher certification; some are quality seals unrelated to kashrut. It\'s worth learning to tell them apart.</li>
<li><strong>Not asking:</strong> many doubts are resolved quickly with a question to the community rabbi or someone with more experience, instead of guessing or defaulting to the strictest (or most lenient) option just in case.</li>
</ul>

<h2>Why it\'s worth going step by step</h2>
<p>One of the quietest mistakes is trying to solve everything at once: overhauling the whole kitchen, memorizing every rule and giving up eating out the same day you decide to start. In practice, those who sustain the change long-term tend to move in stages, prioritizing what\'s cooked at home first and leaving more complex decisions, like eating at restaurants or hosting events, for later.</p>

<h2>Frequently asked questions</h2>
<p><strong>Do I need a go-to certifier from day one?</strong><br>
It isn\'t essential, but it helps a lot: choosing one or two recognized certifiers as your reference simplifies purchasing decisions from the start, instead of evaluating every seal separately.</p>
<p><strong>How long does it take to feel comfortable with the basic rules?</strong><br>
It varies a lot from person to person, but most people report feeling more confident after a few months of consistent practice, especially once checking labels becomes an automatic habit.</p>
<p><strong>Is there a mistake that\'s especially hard to fix later?</strong><br>
Repeatedly mixing meat and dairy utensils can complicate the kitchen more long-term than other mistakes, since it may require koshering or replacing pieces; it\'s worth resolving the physical separation as soon as possible.</p>

<h2>Related reading</h2>
<p>The most important thing is to understand that the transition doesn\'t need to be perfect from day one. Most Jewish communities value the gradual learning process, and there are many resources — including certifiers, rabbis and tools like KosherMap — to support that journey. For the first concrete steps, see <a href="/articulos/armar-cocina-kosher">how to set up a kosher kitchen from scratch</a>, and to know what to look for on every label, see <a href="/articulos/como-leer-etiqueta-kosher">how to read a kosher product label</a>.</p>',
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/pulgon-en-hoja.jpg" alt="Um pulgão sobre uma folha, ampliado. Na verdura real mede de um a três milímetros: apenas um pontinho escuro, por isso passa despercebido." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Um pulgão sobre uma folha, ampliado. Na verdura real mede de um a três milímetros: apenas um pontinho escuro, por isso passa despercebido. Foto: WikiPedant via <a href="https://commons.wikimedia.org/wiki/File:Aphid_on_leaf05.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Verduras de folha como alface, espinafre, brócolis e couve-flor são as que exigem mais cuidado, porque pulgões e outros insetos pequenos se escondem entre as folhas e são difíceis de detectar a olho nu. A técnica habitual inclui separar as folhas, lavá-las individualmente sob um jato de água e revisá-las contra a luz.</p>
<ul>
<li><strong>Verduras de folha:</strong> separar folha por folha e lavar sob água corrente, revisando os dois lados.</li>
<li><strong>Couve-flor e brócolis:</strong> deixar de molho em água com um pouco de sabão ou vinagre para que os insetos se desprendam dos ramalhetes.</li>
<li><strong>Morangos e framboesas:</strong> deixar de molho em água salgada ou com vinagre antes de enxaguar.</li>
<li><strong>Leguminosas secas (lentilhas, grão-de-bico):</strong> espalhar sobre uma superfície clara e revisar antes de cozinhar.</li>
</ul>
<p>E que fique claro que isso não é tão fácil quanto parece. Em nossa casa, revisar as verduras sempre coube a uma pessoa em especial. Com os anos, ele começou a precisar de óculos, e um dia viu um pontinho preto em uma folha. Convencido de que não era nada, perguntou à filha o que era aquilo. E a filha respondeu: não está vendo que é um bicho, dá para ver as perninhas? Desde esse dia, ele não revisa uma única folha sem os óculos postos. O ensinamento é simples: revisar bem exige boa luz e boa visão, e na dúvida, um segundo olhar nunca é demais.</p>
<p>Muitas certificadoras kosher oferecem guias específicos e ilustrados de como revisar cada tipo de verdura conforme a região onde foi cultivada, já que a prevalência de insetos varia conforme o clima e o método de cultivo. Hoje também existem verduras "pré-revisadas" ou cultivadas sob supervisão específica para minimizar a infestação, o que simplifica muito o processo na cozinha do dia a dia.</p>',
            ],
            'carne-y-leche' => [
                'title' => 'Carne e leite: por que não se misturam',
                'excerpt' => 'A separação entre carne e leite é um dos pilares mais conhecidos do kashrut. Explicamos sua origem, seu alcance e como se aplica na prática.',
                'content' => '<p>A proibição de misturar carne e leite se baseia em um versículo que aparece três vezes na Torá: "Não cozinharás um cabrito no leite de sua mãe". A tradição oral interpretou essa frase como uma proibição tripla: não cozinhar, não comer e não obter nenhum benefício de uma mistura de carne e leite.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/carne-y-leche.jpg" alt="Uma cozinha kosher observante mantém dois conjuntos completos de panelas e utensílios, um para carne e outro para laticínios." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Uma cozinha kosher observante mantém dois conjuntos completos de panelas e utensílios, um para carne e outro para laticínios. Foto: PattayaPatrol via <a href="https://commons.wikimedia.org/wiki/File%3ADFC_2431_A_cook_flips_food_in_a_hot_pan_while_working_in_a_compact_well-used_kitchen_filled_with_pots_utensils_and_shelves_of_ingredients.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Na prática, isso significa que os alimentos se dividem em três categorias: <strong>cárneos</strong> (carne e derivados), <strong>lácteos</strong> (leite e derivados) e <strong>pareve</strong> (neutros, como frutas, verduras, ovos e peixe, que não são nem carne nem leite).</p>
<p>Uma cozinha kosher observante mantém utensílios, panelas, pratos e até lava-louças separados para carne e para leite, já que o calor e o uso repetido podem transferir sabores e partículas entre superfícies. Além disso, exige-se um tempo de espera entre comer carne e comer laticínios — que varia conforme o costume familiar, geralmente entre uma e seis horas — enquanto de laticínios para carne basta enxaguar a boca e comer algo neutro.</p>
<p>Essa separação é a razão pela qual muitos rótulos de produtos trazem as letras "D" (dairy/lácteo), "M" (meat/cárneo) ou "Pareve" junto ao símbolo de certificação: permite ao consumidor saber de imediato em que categoria o produto se enquadra antes de combiná-lo com outros alimentos.</p>',
            ],
            'kasherizar-horno' => [
                'title' => 'Como casherizar um forno',
                'excerpt' => 'Quando um forno foi usado com alimentos não kosher, ou se quer mudar de uso cárneo para lácteo, existe um processo específico para torná-lo apto.',
                'content' => '<p>Casherizar um forno é necessário em várias situações: ao comprar uma casa com um forno usado anteriormente de forma não kosher, ao querer mudar o uso de um forno (por exemplo, de cárneo para lácteo) ou antes de Pessach, quando é preciso eliminar todo vestígio de chametz.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kasherizar-horno.jpg" alt="O libun, a kasherização do forno por calor intenso, exige limpeza total prévia e 24 horas sem uso." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">O libun, a kasherização do forno por calor intenso, exige limpeza total prévia e 24 horas sem uso. Foto: Brandon Carson from San Carlos, USA via <a href="https://commons.wikimedia.org/wiki/File%3ADouble_oven.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>

<h2>Por que o forno precisa de um método tão drástico</h2>
<p>O interior de um forno atinge temperaturas muito altas a cada uso, então qualquer resíduo absorvido é considerado "cozido" num nível muito mais profundo do que numa panela. Por isso um método mais suave como a hagalá (imersão em água fervente) não basta aqui: o forno precisa de libun, autolimpeza por calor seco intenso, que segue o mesmo princípio de "como absorveu, assim expele" usado nos utensílios de metal, só que aplicado pelo fogo em vez da água.</p>

<h2>O procedimento passo a passo</h2>
<ol>
<li>Limpar bem o forno, eliminando toda sujeira e resíduo visível de comida, incluindo a borracha da porta e qualquer canto onde se acumule gordura.</li>
<li>Não usar o forno durante 24 horas antes da casherização.</li>
<li>Ligar o forno na temperatura mais alta possível (idealmente usando a função de autolimpeza, se o forno tiver) durante pelo menos uma hora, até que as superfícies internas fiquem incandescentes.</li>
</ol>

<h2>Grades, bandejas e superfícies de vidro</h2>
<p>As grades e bandejas metálicas geralmente podem ser casherizadas separadamente por imersão em água fervente (hagalá), enquanto as superfícies de vidro ou esmalte geralmente exigem libun por serem materiais que absorvem mais. O vidro, em particular, é um dos materiais mais debatidos no kashrut: algumas opiniões o consideram não absorvente, outras exigem o mesmo calor intenso que o metal.</p>

<h2>Fornos que não suportam o libun</h2>
<p>Alguns fornos modernos, especialmente os com componentes de plástico, painéis eletrônicos sensíveis ao toque ou revestimentos antiaderentes especiais, não foram feitos para suportar a temperatura exigida pelo libun e podem se danificar ou até virar um risco de incêndio. Nesses casos, é melhor trocar o forno para um começo kosher do zero do que forçar um método de casherização para o qual o aparelho não foi projetado.</p>

<h2>Perguntas frequentes</h2>
<p><strong>O ciclo de autolimpeza por si só já conta como libun?</strong><br>
Na maioria das opiniões, sim, já que atinge a temperatura exigida, mas vale confirmar que o ciclo cobre todas as superfícies internas, incluindo a porta.</p>
<p><strong>Preciso casherizar o forno toda vez que cozinho algo não kosher por engano?</strong><br>
Se foi um evento isolado, o mais seguro é consultar um rabino sobre a situação específica, já que a resposta pode depender do alimento, da temperatura e do tempo que ficou dentro.</p>
<p><strong>Posso casherizar um forno para uso cárneo e lácteo ao mesmo tempo?</strong><br>
Muitas famílias preferem destinar o forno a uma única categoria desde o início, ou usar bandejas e forros separados para cada uso, em vez de casherizá-lo repetidamente entre usos.</p>

<h2>Para continuar lendo</h2>
<p>Para o micro-ondas, que usa um método diferente baseado em vapor em vez de calor seco, veja <a href="/articulos/kasherizar-microondas">como casherizar um micro-ondas</a>. Para a lava-louças, um dos aparelhos mais debatidos entre autoridades rabínicas, veja <a href="/articulos/kasherizar-lavavajillas">como casherizar uma lava-louças</a>.</p>',
            ],
            'kasherizar-microondas' => [
                'title' => 'Como casherizar um micro-ondas',
                'excerpt' => 'O micro-ondas tem um processo de casherização diferente do forno tradicional, porque cozinha com vapor e não com calor seco.',
                'content' => '<p>Diferente do forno convencional, o micro-ondas aquece os alimentos gerando vapor em seu interior, o que muda o método de casherização recomendado pela maioria das autoridades halájicas.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/microondas-cocina.jpg" alt="A cavidade do micro-ondas acumula respingos sobretudo no teto, que é onde menos se olha ao limpar." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">A cavidade do micro-ondas acumula respingos sobretudo no teto, que é onde menos se olha ao limpar. Foto: Tomwsulcer via <a href="https://commons.wikimedia.org/wiki/File:Stove_oven_combination_and_microwave_oven.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kasherizar-lavavajillas.jpg" alt="A lava-louças é um dos eletrodomésticos mais discutidos: filtros e aspersores retêm resíduos em alta temperatura." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">A lava-louças é um dos eletrodomésticos mais discutidos: filtros e aspersores retêm resíduos em alta temperatura. Foto: Myke2020 via <a href="https://commons.wikimedia.org/wiki/File%3ACountertop_dishwasher_7882.JPG" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>

<h2>Por que gera tanto debate entre as autoridades rabínicas</h2>
<p>Diferente de um forno ou um conjunto de panelas, a lava-louças tem dezenas de frestas escondidas, um filtro que retém partículas de comida diretamente e braços aspersores que recirculam água quente carregada de resíduos durante todo o ciclo. Essa combinação faz com que algumas autoridades a tratem com mais rigor do que quase qualquer outro aparelho, e algumas desaconselham usar a mesma unidade para cárneo e lácteo, mesmo em dias diferentes.</p>

<h2>O procedimento para quem permite</h2>
<ul>
<li>Limpeza profunda de filtros, braços aspersores e juntas de borracha, removendo à mão toda partícula visível.</li>
<li>Não usar a lava-louças durante 24 horas antes de casherizá-la.</li>
<li>Rodar um ciclo completo vazio, na temperatura mais alta possível, idealmente com um produto de limpeza forte.</li>
<li>Em algumas comunidades, recomenda-se usar cestos ou bandejas separadas e intercambiáveis para cárneo e lácteo, em vez de casherizar o aparelho inteiro entre usos.</li>
</ul>

<h2>A alternativa que evita o problema pela raiz</h2>
<p>Muitas famílias contornam o debate usando cestos removíveis, um para cárneo e outro para lácteo, que entram e saem da mesma máquina. Como a comida nunca toca diretamente as paredes ou o filtro da máquina, algumas opiniões rabínicas consideram essa solução muito mais simples do que a casherização repetida.</p>

<h2>Diferenças entre comunidades</h2>
<p>Os costumes variam bastante nesse tema — algumas comunidades sefarditas e asquenazitas diferem notavelmente na rigidez especificamente sobre lava-louças. É um dos casos em que mais vale a pena consultar diretamente o rabino da congregação antes de definir como organizar a cozinha, em vez de assumir que a mesma regra da hagalá para panelas também se aplica aqui.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Uma lava-louças com ciclo de higienização em alta temperatura é automaticamente mais fácil de casherizar?</strong><br>
Pode ajudar a atingir a temperatura exigida mais rápido, mas o debate é menos sobre temperatura e mais sobre a estrutura interna da máquina e como o resíduo de comida circula dentro dela.</p>
<p><strong>Os cestos de plástico da lava-louças precisam de casherização separada?</strong><br>
As peças de plástico costumam receber um tratamento diferente do interior metálico; pergunte ao seu rabino sobre o modelo específico se isso surgir.</p>
<p><strong>Usar cestos separados resolve totalmente a questão para todas as comunidades?</strong><br>
Não universalmente — algumas opiniões mais rigorosas ainda preferem uma máquina totalmente dedicada por categoria. Vale confirmar o critério que sua comunidade segue.</p>

<h2>Para continuar lendo</h2>
<p>Para o método de casherização usado em panelas, frigideiras e talheres, veja <a href="/articulos/hagala-utensilios-metal">hagalá: como casherizar utensílios de metal</a>. E para o forno, que segue a mesma lógica de fundo através do calor seco, veja <a href="/articulos/kasherizar-horno">como casherizar um forno</a>.</p>',
            ],
            'hagala-utensilios-metal' => [
                'title' => 'Como casherizar utensílios de metal (hagalá)',
                'excerpt' => 'A hagalá é o método tradicional de imersão em água fervente para casherizar panelas, talheres e outros utensílios metálicos.',
                'content' => '<p>A hagalá é um dos métodos de casherização mais antigos e é usada principalmente em utensílios de metal que foram aquecidos diretamente com fogo ou líquido fervente, como panelas, talheres, frigideiras (sem revestimento antiaderente) e algumas outras peças de cozinha.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/hagala-utensilios-metal.jpg" alt="A hagalá consiste em submergir o utensílio em água fervente: como absorveu, assim expele." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">A hagalá consiste em submergir o utensílio em água fervente: como absorveu, assim expele. Foto: W.carter via <a href="https://commons.wikimedia.org/wiki/File%3ASteam-boiling_green_asparagus.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>

<h2>O princípio por trás do método</h2>
<p>O princípio é simples de lembrar: "como absorveu, assim expele" (<em>kevol\'o kach polto</em>). Se um utensílio absorveu sabor não kosher (ou cárneo/lácteo) por meio de líquido fervente, ele se purifica da mesma maneira: sendo submerso em água fervente. É o mesmo princípio por trás da casherização de um <a href="/articulos/kasherizar-horno">forno</a>, aplicado por um método diferente conforme o tipo de calor que cada superfície absorveu.</p>

<h2>O procedimento passo a passo</h2>
<ol>
<li>Limpar bem o utensílio, sem restos de ferrugem, comida grudada ou sujeira incrustada. Qualquer resíduo que fique no meio bloqueia o contato direto com a água e invalida o processo naquele ponto.</li>
<li>Esperar 24 horas sem usar o utensílio antes da hagalá. Esse período faz com que o sabor absorvido seja considerado "estragado" e mais fácil de expelir.</li>
<li>Ferver uma panela grande de água até atingir fervura plena, num recipiente diferente do que será casherizado.</li>
<li>Submergir completamente o utensílio na água fervente, garantindo que todas as suas superfícies — incluindo bordas e reentrâncias — entrem em contato com a água nessa temperatura.</li>
<li>Retirá-lo com um instrumento que não tenha estado em contato com comida não kosher, e enxaguá-lo imediatamente em água fria.</li>
</ol>

<h2>Utensílios que esse método não resolve</h2>
<p>Utensílios com cabo de madeira ou plástico, ou com peças coladas com adesivos que não resistem à água fervente, geralmente não são adequados para hagalá e precisam de outro método, ou simplesmente não podem ser casherizados. Frigideiras antiaderentes (teflon) também não costumam ser casherizadas por hagalá, já que o revestimento é danificado pelo calor e o próprio processo de casherização acabaria estragando a superfície de qualquer jeito. Para vidro e louças, o critério é diferente: veja <a href="/articulos/vajilla-para-pesaj">louças para Pessach</a>, material por material.</p>

<h2>Perguntas frequentes</h2>
<p><strong>A hagalá serve para qualquer utensílio que teve contato com comida não kosher?</strong><br>
Nem sempre. Se o utensílio teve contato com fogo direto (como uma grelha), o método correto é outro: o libun, que exige aquecer o metal até ficar incandescente ou até que uma palha o queime ao tocar. A hagalá é especificamente para absorção por líquido.</p>
<p><strong>Quantas vezes o mesmo utensílio pode ser casherizado?</strong><br>
Não há um limite fixo de reutilização do método em si, desde que o utensílio esteja em bom estado. O que importa a cada vez é respeitar o período de 24 horas sem uso prévio.</p>
<p><strong>Preciso de um rabino presente para fazer a hagalá em casa?</strong><br>
Não é obrigatório para o procedimento doméstico habitual, mas diante de dúvidas sobre um utensílio específico convém consultar antes.</p>

<h2>Para continuar lendo</h2>
<p>Se o utensílio em questão for um forno ou micro-ondas, esses casos têm suas próprias regras: veja <a href="/articulos/kasherizar-horno">como casherizar o forno</a> e <a href="/articulos/kasherizar-microondas">como casherizar o micro-ondas</a>. Para a lava-louças, que gera mais debate entre autoridades rabínicas, veja <a href="/articulos/kasherizar-lavavajillas">como casherizar a lava-louças</a>.</p>',
            ],
            'vajilla-para-pesaj' => [
                'title' => 'Louças para Pessach: tudo o que você precisa saber',
                'excerpt' => 'Durante Pessach valem regras mais estritas do que o resto do ano quanto a utensílios de cozinha, devido à proibição de chametz.',
                'content' => '<p>Pessach é a festividade com as regras alimentares mais estritas do calendário judaico, porque além das normas habituais de kashrut, soma-se a proibição total de consumir ou possuir <a href="/articulos/jametz-pesaj">chametz</a> (produtos fermentados de cinco grãos: trigo, cevada, aveia, centeio e espelta).</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/vajilla-para-pesaj.jpg" alt="Muitas famílias têm um jogo de louça exclusivo para Pessach, guardado no resto do ano." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Muitas famílias têm um jogo de louça exclusivo para Pessach, guardado no resto do ano. Foto: Silar via <a href="https://commons.wikimedia.org/wiki/File%3A020210817_135854_Seder_plates%2C_brass_plate%2C_%C4%86miel%C3%B3w_porcelain%2C_19th-early_20th_century%2C_Category%2C_Passover_in_Galicia.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>Por que o chametz do resto do ano é o problema</h2>
<p>O problema é que o chametz pode ter estado em contato com panelas, pratos e talheres durante todo o ano: toda vez que se cozinhou massa, se assou pão ou se serviu biscoitos, esses utensílios absorveram restos de chametz. No resto do ano isso não é problema, porque o chametz em si é kosher. Mas durante Pessach, ter utensílios que possam "liberar" esse sabor absorvido entra em conflito com a proibição total da festividade.</p>

<h2>A opção mais simples: louças à parte</h2>
<p>Por isso muitas famílias optam por ter um conjunto de louças separado, exclusivo para Pessach, guardado o resto do ano em caixas ou num armário específico: é a opção mais simples, e que evita ter que casherizar todo ano. Com o tempo, muitas famílias montam essas louças aos poucos, comprando ou herdando peças, até ter um conjunto completo dedicado só a esses oito dias do ano.</p>

<h2>O que pode ser casherizado, material por material</h2>
<p>Quem não tem louças separadas para Pessach pode casherizar certos utensílios por meio da <a href="/articulos/hagala-utensilios-metal">hagalá</a>, embora nem todos:</p>
<ul>
<li><strong>Metal sem revestimento</strong> (panelas, talheres): geralmente apto para hagalá, seguindo o mesmo procedimento usado no resto do ano.</li>
<li><strong>Vidro</strong>: conforme o costume, alguns consideram que uma boa lavagem basta, outros exigem imersão; vale consultar o critério da própria comunidade.</li>
<li><strong>Cerâmica e porcelana</strong>: em geral, não podem ser casherizadas para Pessach sob nenhuma opinião, porque o material poroso retém o que absorveu de forma permanente; deve-se usar um conjunto à parte.</li>
<li><strong>Plástico e borracha</strong>: a maioria das opiniões não permite casherizá-los, embora haja exceções pontuais conforme o uso que tiveram.</li>
</ul>

<h2>Perguntas frequentes</h2>
<p><strong>Preciso comprar louças novas para Pessach todos os anos?</strong><br>
Não, uma vez montado o conjunto ele é reutilizado ano após ano, guardado o resto do tempo. Só é preciso repor peças quebradas ou somar novas se a família crescer.</p>
<p><strong>Os eletrodomésticos também precisam de um conjunto à parte?</strong><br>
Depende do aparelho. Alguns, como a torradeira, em geral não podem ser casherizados e vale ter uma exclusiva para Pessach; outros, como o forno, têm um procedimento específico detalhado em <a href="/articulos/kasherizar-horno">como casherizar o forno</a>.</p>
<p><strong>Com quanto tempo de antecedência devo começar a preparar as louças?</strong><br>
Vale começar com folga, pelo menos uma semana antes, para ter tempo de casherizar sem pressa os utensílios que permitem isso e resolver qualquer dúvida com o rabino ou a certificadora local antes do início da festividade.</p>

<h2>Para continuar lendo</h2>
<p>Para entender exatamente quais produtos precisam sair da cozinha antes da festividade, veja <a href="/articulos/jametz-pesaj">chametz: o que é e como se elimina</a>. E para saber o que fazer com as panelas e frigideiras usadas no dia a dia, veja <a href="/articulos/hagala-utensilios-metal">como casherizar utensílios de metal</a>.</p>',
            ],
            'jametz-pesaj' => [
                'title' => 'Chametz: o que é e como se elimina antes de Pessach',
                'excerpt' => 'O chametz é o alimento fermentado proibido durante Pessach. Conhecer quais produtos o contêm é fundamental para preparar a festividade.',
                'content' => '<p>Chametz é qualquer produto elaborado com um dos cinco grãos — trigo, cevada, aveia, centeio ou espelta — que entrou em contato com água e fermentou (cresceu) por mais de 18 minutos sem ser assado. Isso inclui pão, cerveja, a maioria das massas, biscoitos e uma enorme quantidade de produtos industrializados que usam esses grãos como ingrediente ou derivado.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/jametz-pesaj.jpg" alt="Durante Pessach vigora a proibição total de comer e de possuir chametz." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Durante Pessach vigora a proibição total de comer e de possuir chametz. Foto: Ministry of Information Photo Division Photographer via <a href="https://commons.wikimedia.org/wiki/File%3AAllied_Forces_Celebrate_Passover-_Jewish_Traditions_in_Wartime_Britain%2C_April_1944_D19336.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>

<h2>Não é só comê-lo: possuí-lo também é proibido</h2>
<p>A Torá proíbe não só comer chametz durante Pessach, mas também possuí-lo, mesmo guardado sem intenção de comê-lo. Por isso, nas semanas anteriores à festividade, as famílias judaicas fazem uma limpeza profunda da casa (bedikat chametz) para eliminar qualquer resto de pão, farinha ou produtos com chametz de armários, carros, bolsas e qualquer canto onde possa ter caído uma migalha. Essa limpeza costuma começar vários dias antes, cômodo por cômodo, e se completa na noite anterior a Pessach com uma busca ritual final.</p>

<h2>O que fazer com o chametz que não dá para jogar fora</h2>
<p>Para o chametz que não pode ou não convém jogar fora, como bebidas caras ou produtos difíceis de repor, existe a opção de "vendê-lo" simbolicamente a uma pessoa não judia por meio de um contrato chamado <em>mechirat chametz</em>, geralmente coordenado pelo rabino da comunidade, muitas vezes online nas semanas anteriores à festividade. O chametz vendido fica guardado fechado e à parte durante a festividade, fisicamente presente em casa mas legalmente "vendido", e é "recomprado" automaticamente ao final de Pessach, sem que o dono precise fazer nenhum trâmite adicional.</p>

<h2>A busca ritual e a queima final</h2>
<p>Na noite anterior a Pessach, realiza-se a bedikat chametz, a busca ritual de chametz pela casa, geralmente com uma vela, uma pena e uma colher de madeira. É comum que, antes de começar, algum familiar esconda de propósito alguns pedacinhos de pão envoltos em papel para que a busca não termine em zero (um costume difundido em muitas comunidades). Na manhã seguinte, o que foi encontrado é queimado num ritual chamado biur chametz, acompanhado de uma declaração que anula qualquer resto que possa ter ficado sem ser encontrado.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Aveia e centeio estão realmente proibidos, ou só o trigo?</strong><br>
Os cinco grãos (trigo, cevada, aveia, centeio e espelta) estão todos incluídos na proibição de chametz quando fermentam com água. Arroz e milho não fazem parte desse grupo, embora em algumas comunidades (principalmente asquenazitas) sejam evitados por um costume à parte chamado kitniyot, diferente da proibição de chametz em si.</p>
<p><strong>O que acontece se eu encontrar chametz em casa durante Pessach?</strong><br>
Deve ser removido imediatamente: guardado fechado até o fim da festividade (se já estava incluído na venda simbólica) ou descartado se não estava.</p>
<p><strong>A venda de chametz tem algum custo?</strong><br>
Geralmente é feita sem custo ou com uma contribuição simbólica à sinagoga que coordena o trâmite; não é uma venda real em termos econômicos, mas um mecanismo legal dentro da halachá.</p>

<h2>Para continuar lendo</h2>
<p>Para entender o que acontece com as panelas e pratos que estiveram em contato com chametz durante o ano, veja <a href="/articulos/vajilla-para-pesaj">louças para Pessach</a>. E para saber mais sobre o calendário e o que se come em cada festividade, veja <a href="/articulos/calendario-judio-festividades-alimentacion">o calendário judaico e a alimentação</a>.</p>',
            ],
            'vino-kosher' => [
                'title' => 'Vinho kosher: por que precisa de supervisão especial',
                'excerpt' => 'O vinho tem um status particular na halachá: para ser kosher, deve ser elaborado e manipulado exclusivamente por judeus observantes.',
                'content' => '<p>Há um tempo conseguimos visitar uma vinícola em Mendoza que produz vinho kosher, e ver o processo de perto ajuda a entender por que o vinho tem regras tão diferentes das de qualquer outro alimento. Com quase qualquer outro produto, basta que os ingredientes e o processo cumpram certos requisitos. Com o vinho não: a halachá exige que toda pessoa que o toque durante a elaboração, desde que a uva entra até o engarrafamento, seja judia e observante. O motivo é histórico: o vinho era usado em rituais de idolatria, e daí vem a restrição.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/barricas-roble-vino.jpg" alt="Sala de barricas de uma vinícola (imagem ilustrativa, não é a vinícola de Mendoza que visitamos). Numa vinícola kosher cada barrica leva também um lacre que certifica que ninguém alheio à supervisão a abriu." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Sala de barricas de uma vinícola (imagem ilustrativa, não é a vinícola de Mendoza que visitamos). Numa vinícola kosher cada barrica leva também um lacre que certifica que ninguém alheio à supervisão a abriu. Foto: Subhashish Panigrahi via <a href="https://commons.wikimedia.org/wiki/File:Oak_barrels_used_for_aging_of_wine_in_a_cellar_at_Grover_Zampa_Vineyard,_Doddaballapura,_Karnataka,_India.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Na prática, isso monta uma divisão de tarefas bem particular. Quem entende de vinho é o enólogo, que em geral não é judeu (o goy), e é quem indica o que fazer em cada etapa. Mas quem efetivamente movimenta o vinho, abre as torneiras e faz tudo o que envolve tocar o produto é sempre um judeu observante (o iehudi). O especialista dirige, o iehudi executa, e tudo sob supervisão rabínica constante.</p>
<p>O vinho vai descansando em ambientes diferentes conforme a etapa, e parte dele fica em barris de carvalho, que podem ser reutilizados umas três vezes antes de perder suas propriedades. Cada barril é lacrado, e esse lacre não é um detalhe menor: é a garantia de que ninguém de fora tocou o conteúdo. Contaram-nos um caso que mostra até onde vai a exigência. Em um barril de milhares de litros, notaram que faltava o lacre justamente no ponto em que deveria estar fechado: estava aberto. O Rabino de Buenos Aires teve que vir verificar a situação pessoalmente, e determinou que aquele vinho havia ficado sem supervisão. Resultado: aqueles milhares de litros já não podiam ser vendidos como kosher.</p>
<p>Existe também uma categoria especial, o <strong>vinho mevushal</strong> ("fervido"), que é vinho pasteurizado a uma temperatura específica. Uma vez que um vinho é mevushal, ele mantém seu status kosher mesmo que depois seja servido ou tocado por uma pessoa não judia. Por isso é tão prático para eventos, restaurantes e buffês, onde não há como controlar quem pega cada garrafa.</p>
<p>Hoje existem vinhos kosher de boa qualidade em quase todas as regiões vinícolas do mundo. Mendoza é um polo importante na Argentina, e também se produz no Chile, na França, na Espanha, na Itália e, claro, em Israel, certificados pelas principais agências rabínicas.</p>',
            ],
            'gelatina-kosher' => [
                'title' => 'Gelatina kosher: o debate halájico',
                'excerpt' => 'A gelatina é um dos ingredientes mais debatidos no mundo do kashrut, porque sua origem animal pode comprometer seu status.',
                'content' => '<p>A gelatina tradicional é obtida fervendo ossos, pele e tecido conectivo de animais — geralmente vacas ou porcos — até extrair o colágeno. Isso traz dois problemas do ponto de vista do kashrut: a origem do animal (é uma espécie kosher?) e o método de processamento (o animal foi abatido segundo a <a href="/articulos/shejita-sacrificio-kosher">shechitá</a>?).</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/gelatina-kosher.jpg" alt="Doces e sobremesas são os produtos onde mais aparece a gelatina, o ingrediente mais debatido do kashrut." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Doces e sobremesas são os produtos onde mais aparece a gelatina, o ingrediente mais debatido do kashrut. Foto: Sakurai Midori via <a href="https://commons.wikimedia.org/wiki/File%3ASweets_Offering_for_Obon.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>

<h2>O debate de fundo: a transformação muda o status?</h2>
<p>Durante décadas, diferentes autoridades rabínicas debateram se a gelatina, ao passar por um processo químico tão transformador, muda de status halájico, um conceito chamado <em>panim chadashot</em> (literalmente "rosto novo", transformação total). Algumas opiniões mais permissivas sustentaram que o processo é tão radical — de osso e pele a um pó cristalino sem sabor, cor nem textura reconhecível do animal original — que o produto final já não é considerado carne em sentido halájico. A maioria das certificadoras kosher mainstream, no entanto, não aceita essa posição para gelatina de origem não kosher, e exige que a origem do colágeno seja autorizada desde o início.</p>

<h2>As alternativas que evitam o problema pela raiz</h2>
<p>Por isso, hoje a grande maioria dos produtos com certificação kosher que requerem gelatina (balas, sobremesas, cápsulas de medicamentos, marshmallows) usa uma destas alternativas:</p>
<ul>
<li><strong>Gelatina de peixe kosher:</strong> extraída de espécies com barbatanas e escamas, sem o problema de origem que a gelatina de mamíferos tem.</li>
<li><strong>Gelatina bovina de animais abatidos segundo a shechitá:</strong> resolve o problema desde a origem, embora costume ser mais cara que a alternativa convencional.</li>
<li><strong>Substitutos vegetais</strong> como ágar-ágar (extraído de algas) ou pectina (de frutas), que evitam o debate por completo por não terem origem animal.</li>
</ul>
<p>Quando um produto traz o selo de uma certificadora reconhecida, já não é preciso investigar a origem da gelatina: a certificação garante que esse ponto já foi verificado como parte do processo de certificação.</p>

<h2>Onde a gelatina aparece sem que você perceba</h2>
<p>Além de doces e sobremesas óbvias, a gelatina aparece em lugares menos evidentes: cápsulas de medicamentos e suplementos, alguns iogurtes e sobremesas lácteas para dar textura, vinhos e cervejas (usada como clarificante no processo de filtragem) e certos produtos de panificação industrial. Por isso vale sempre revisar o rótulo, e não assumir que um produto "sem sabor de gelatina" não a contém.</p>

<h2>Perguntas frequentes</h2>
<p><strong>A gelatina vegetal (ágar-ágar) é sempre kosher?</strong><br>
Pela origem (algas), sim, mas como com qualquer produto vale verificar que não tenha sido processada com equipamento compartilhado com ingredientes não kosher.</p>
<p><strong>Por que algumas marcas de balas kosher têm textura diferente das convencionais?</strong><br>
Porque muitas usam gelatina de peixe ou substitutos vegetais em vez da gelatina bovina ou suína padrão, o que pode mudar levemente a textura final.</p>
<p><strong>Um produto rotulado só como "gelatina", sem mais detalhes, pode ser considerado não kosher?</strong><br>
Sem certificação visível, não há como saber com segurança pelo rótulo; o mais seguro é procurar um selo de certificadora reconhecida.</p>

<h2>Para continuar lendo</h2>
<p>Para entender o método de abate que determina se a carne ou o colágeno de origem animal é apto, veja <a href="/articulos/shejita-sacrificio-kosher">shechitá: o método de abate kosher</a>. E para outro ingrediente igualmente debatido, veja <a href="/articulos/queso-kosher-cuajo">queijo kosher e o coalho</a>.</p>',
            ],
            'alcohol-bebidas-espirituosas' => [
                'title' => 'Álcool e bebidas espirituosas: o que é preciso para serem kosher',
                'excerpt' => 'Whisky, vodca, rum e outros destilados costumam ser kosher por natureza, mas há exceções importantes a se considerar.',
                'content' => '<p>A maioria dos destilados — whisky, vodca, rum, gin — é elaborada a partir de grãos, batata ou cana-de-açúcar, ingredientes que por si só não apresentam problemas de kashrut. Por isso, muitos destilados simples são kosher sem necessidade de certificação especial, desde que não sejam adicionados sabores, corantes ou aditivos de origem não kosher.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/alcohol-bebidas-espirituosas.jpg" alt="Muitos destilados são kosher pelos ingredientes, mas o envelhecimento em barris de vinho pode mudar seu status." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Muitos destilados são kosher pelos ingredientes, mas o envelhecimento em barris de vinho pode mudar seu status. Foto: 4028mdk09 via <a href="https://commons.wikimedia.org/wiki/File%3ABarbestand.JPG" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>

<h2>Os pontos que merecem atenção</h2>
<ul>
<li><strong>Envelhecimento em barris de vinho ou xerez:</strong> alguns whiskies e runs envelhecem em barris que antes continham vinho não kosher, e esse contato pode afetar seu status, de forma parecida ao que acontece com os <a href="/articulos/vino-kosher">barris de vinho kosher</a>, que só podem ser reutilizados um número limitado de vezes.</li>
<li><strong>Aromatizantes e aditivos:</strong> licores com sabor de creme, chocolate ou frutas costumam incluir ingredientes (corantes, essências, estabilizantes) que requerem verificação caso a caso.</li>
<li><strong>Bebidas à base de vinho</strong> (como vermute ou alguns licores): herdam todas as restrições do vinho kosher, incluindo a necessidade de supervisão rabínica durante toda a elaboração.</li>
<li><strong>Cerveja:</strong> geralmente kosher por seus ingredientes básicos (água, cevada, lúpulo, levedura), exceto variantes com aromatizantes especiais ou processos de filtragem com clarificantes de origem animal.</li>
<li><strong>Licores de creme:</strong> por levarem laticínios, além da análise de ingredientes é preciso considerar as regras de mistura com comidas cárneas.</li>
</ul>

<h2>Por que vodca e gin costumam ser mais simples</h2>
<p>Os destilados neutros como vodca ou gin base costumam gerar menos dúvidas que whisky ou rum, porque em geral não passam por envelhecimento em madeira e seus ingredientes são mais diretos: água, grão ou batata fermentados e destilados várias vezes. O gin é um caso intermediário, porque recebe botânicos (zimbro, cítricos, especiarias) durante a destilação, e aí sim vale revisar se esses aditivos não incluem ingredientes problemáticos.</p>

<h2>Pessach: a exceção que muda tudo</h2>
<p>Durante Pessach, além disso, é preciso prestar atenção especial porque muitos destilados são elaborados com grãos que constituem <a href="/articulos/jametz-pesaj">chametz</a>, por isso é necessária uma certificação específica "kosher para Pessach" nessa época do ano. Isso afeta especialmente o whisky (feito de cevada) e alguns vodcas de grão, enquanto destilados de batata ou cana-de-açúcar costumam ter menos restrições adicionais para a festividade, embora convenha confirmar a certificação específica antes de comprar.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Todo whisky sem certificação kosher visível é automaticamente inapto?</strong><br>
Não necessariamente, mas sem verificação não há como confirmar se envelheceu em barris de vinho não kosher ou se leva aditivos problemáticos. Na dúvida, procure uma marca com certificação reconhecida.</p>
<p><strong>O álcool em si (o etanol) pode não ser kosher?</strong><br>
O etanol como molécula não tem problema de kashrut; a questão sempre passa pela origem da matéria-prima fermentada e pelos aditivos somados após a destilação.</p>
<p><strong>Coquetéis preparados num bar comum podem ser considerados kosher se todos os ingredientes forem?</strong><br>
Depende do critério de cada pessoa: além dos ingredientes, entra em jogo se o bar usa o mesmo equipamento para preparos com ingredientes não kosher, um tema parecido com o que se coloca em <a href="/articulos/comer-kosher-restaurante">como comer kosher num restaurante não certificado</a>.</p>

<h2>Para continuar lendo</h2>
<p>Para entender por que o vinho precisa de supervisão tão rigorosa, algo que também afeta licores à base de vinho, veja <a href="/articulos/vino-kosher">vinho kosher</a>. E sobre a exceção que permite servir vinho sem restrições, veja <a href="/articulos/vino-mevushal">vinho mevushal</a>.</p>',
            ],
            'comer-kosher-restaurante' => [
                'title' => 'Como comer kosher em um restaurante não certificado',
                'excerpt' => 'Viajar ou sair para comer sem um restaurante kosher por perto não significa quebrar a dieta. Há opções para se manter dentro das normas.',
                'content' => '<p>Nem sempre há um restaurante com certificação kosher disponível, especialmente ao viajar ou viver em cidades com pouca infraestrutura comunitária. Mesmo assim, existem estratégias para se manter dentro do kashrut em restaurantes comuns, e nem todas significam ficar sem comer.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/comer-kosher-restaurante.jpg" alt="Sem um restaurante certificado por perto, há estratégias para se manter dentro do kashrut." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Sem um restaurante certificado por perto, há estratégias para se manter dentro do kashrut. Foto: Sohail1308 via <a href="https://commons.wikimedia.org/wiki/File%3AAl_Fanar_Restaurant.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>As opções mais seguras, da mais rigorosa à mais flexível</h2>
<ul>
<li><strong>Bebidas engarrafadas e lacradas:</strong> água, refrigerantes e sucos na embalagem original geralmente não apresentam nenhum problema, não importa onde sejam comprados.</li>
<li><strong>Frutas e verduras cruas:</strong> sem cozimento nem manipulação complexa, costumam ser a opção mais segura em quase qualquer lugar do mundo.</li>
<li><strong>Opções vegetarianas ou veganas:</strong> ao eliminar carne e laticínios do prato, reduz-se muito o risco, embora ainda seja necessário perguntar sobre ingredientes escondidos (caldo de carne num refogado, manteiga num purê, molhos à base animal).</li>
<li><strong>Peixe com barbatanas e escamas:</strong> em restaurantes de cozinha simples, um peixe grelhado sem molhos pode ser uma alternativa razoável para quem segue um critério mais flexível, desde que não seja cozido junto com frutos do mar ou carne não kosher no mesmo equipamento — isso já depende do critério de cada pessoa e comunidade.</li>
</ul>
<p>O que quase nenhuma opinião permite é carne ou aves de um restaurante não certificado, não importa a aparência do prato: entram em jogo tanto a origem do animal quanto o método de abate (<a href="/articulos/shejita-sacrificio-kosher">shechitá</a>), algo que não se pode verificar a olho nu.</p>

<h2>Um exemplo real de como isso se resolve na prática</h2>
<p>Uma vez viajamos de férias para Mar del Plata com os filhos ainda pequenos, e chegando pela Avenida Constitución minha esposa se lembrou de que tinha esquecido os biscoitos. No inverno, naquela região, conseguir pão ou biscoitos kosher é praticamente impossível. O que dávamos para as crianças? Em casa não costumávamos comer certos salgadinhos de pacote, mas diante da urgência recorremos à lista da Ajdut Kosher, um guia de produtos de prateleira aprovados publicado pela certificadora. Procuramos entre as marcas permitidas e encontramos uns biscoitos tipo Traviatas que estavam na lista. Isso nos salvou.</p>
<p>A lição: quando você viaja, ter em mãos a lista de produtos aprovados da sua certificadora de confiança vale ouro. Muitíssimos produtos comuns de supermercado são kosher mesmo sem trazer um selo grande impresso na embalagem, e conhecer essa lista abre opções onde parecia não haver nenhuma. Hoje a maioria das certificadoras publica essas listas online ou em aplicativos, por isso vale a pena salvar uma no celular antes de viajar, não procurar só na hora do aperto.</p>

<h2>Perguntar na cozinha: o que realmente ajuda</h2>
<p>Se você vai comer algo preparado na hora, algumas perguntas ajudam mais do que outras. Perguntar a um garçom "isso é kosher?" raramente funciona se ele não sabe o que a palavra significa. É mais útil perguntar algo concreto: esse óleo já fritou outra coisa antes?, a chapa também é usada para carne?, tem manteiga ou creme no preparo? Quanto mais específica a pergunta, mais confiável a resposta.</p>

<h2>Os diferentes níveis de rigor, e por que variam tanto</h2>
<p>Cada pessoa e cada comunidade tem um limiar diferente sobre o que é considerado aceitável fora de um restaurante certificado. Algumas famílias só comem produtos embalados e lacrados de fábrica, sem exceção. Outras aceitam certos preparos simples (uma salada, um chá) em restaurantes comuns, mas não pratos elaborados. Não há uma única resposta correta: depende do costume familiar e do rabino que cada um segue. Na dúvida sobre um caso específico, o mais recomendável é consultar antes de uma viagem, não improvisar na hora.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Um restaurante vegano é automaticamente aceitável?</strong><br>
Não necessariamente. Não ter ingredientes de origem animal não resolve tudo: o equipamento compartilhado e a questão do <a href="/articulos/bishul-akum">bishul akum</a> (comida cozida inteiramente por alguém não judeu) ainda são relevantes conforme a opinião seguida. Desenvolvemos isso em <a href="/articulos/kashrut-y-veganismo">kashrut e veganismo</a>.</p>
<p><strong>O que faço se viajo para uma cidade sem nenhuma comunidade judaica?</strong><br>
Priorizar o que é embalado e lacrado (com certificação visível) que você trouxe ou conseguiu num supermercado, e frutas/verduras cruas. Muitos viajantes levam uma reserva de produtos não perecíveis certificados para esses casos.</p>
<p><strong>Os aplicativos de delivery mostram se um lugar é kosher?</strong><br>
Algumas certificadoras têm seus próprios aplicativos ou listas online dos restaurantes certificados da região, que costumam ser mais confiáveis do que confiar na descrição do estabelecimento num aplicativo de delivery genérico.</p>

<h2>Para continuar lendo</h2>
<p>Para entender o que torna um lugar realmente certificado, veja <a href="/articulos/simbolos-certificacion-kosher">símbolos de certificação kosher</a>. E se o assunto é montar sua própria cozinha para depender menos de comer fora, veja <a href="/articulos/armar-cocina-kosher">como montar uma cozinha kosher do zero</a>.</p>',
            ],
            'simbolos-certificacion-kosher' => [
                'title' => 'Símbolos de certificação kosher mais comuns',
                'excerpt' => 'OU, OK, Star-K, KSA... existem dezenas de símbolos de certificação kosher no mundo. Ajudamos você a reconhecer os mais usados.',
                'content' => '<p>Quando um produto passa pelo processo de certificação kosher, a agência certificadora autoriza o uso de um símbolo (hechsher) na embalagem que permite identificá-lo rapidamente. Existem centenas de certificadoras no mundo, mas algumas são especialmente conhecidas por seu alcance global, e aprender a reconhecê-las economiza muito tempo nas compras.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/simbolos-certificacion-kosher.jpg" alt="Um hechsher: o certificado de kashrut que uma agência rabínica concede a um estabelecimento ou produto." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Um hechsher: o certificado de kashrut que uma agência rabínica concede a um estabelecimento ou produto. Foto: Utilisateur:Djampa - User:Djampa via <a href="https://commons.wikimedia.org/wiki/File%3AHechsher_Safed_Rabbinate.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>Os símbolos mais reconhecidos mundialmente</h2>
<ul>
<li><strong>OU (Orthodox Union):</strong> um "U" dentro de um círculo. É provavelmente o símbolo kosher mais reconhecido mundialmente, sediado nos Estados Unidos e presente em dezenas de milhares de produtos.</li>
<li><strong>OK Kosher Certification:</strong> um "K" dentro de um círculo, outra das grandes agências americanas, muito forte em produtos industriais e aditivos.</li>
<li><strong>Star-K:</strong> uma estrela com um "K" no centro, sediada em Baltimore.</li>
<li><strong>KSA (Kosher Supervision of America):</strong> certificadora com forte presença em produtos industriais e aromatizantes.</li>
<li><strong>Badatz:</strong> selo utilizado por vários tribunais rabínicos em Israel, associado a padrões de rigor muito altos (existem vários Badatz distintos, não uma única organização).</li>
<li><strong>Certificadoras locais:</strong> em países como Argentina, Brasil ou México existem certificadoras comunitárias locais (como o Vaad Hakashrut de cada kehilá) com seus próprios selos, geralmente reconhecidos só dentro dessa comunidade ou país.</li>
</ul>

<h2>As letras que acompanham o símbolo</h2>
<p>Além do símbolo, muitos rótulos incluem uma letra adicional: "D" (dairy/lácteo), "M" (meat/cárneo), "Pareve" (neutro) ou "DE" (dairy equipment, elaborado em equipamento lácteo mas sem ingredientes lácteos diretos). Essa última distinção é importante: um produto "DE" não é apto para consumir logo após carne, mesmo sem conter laticínios como ingrediente. Conhecer esses símbolos agiliza muito as compras, principalmente ao viajar para países onde não se domina o idioma local.</p>

<h2>Um hechsher não é um trâmite único: é supervisão constante</h2>
<p>Vale esclarecer algo que nem sempre se entende: um hechsher não é um trâmite que se faz uma vez e vale para sempre. É uma supervisão ativa e constante. Conhecemos o caso de uma padaria de Buenos Aires que tinha a certificação de uma agência comunitária. Em determinado momento o rabino supervisor notou movimentos estranhos e mandou gente para investigar, quase como um detetive. Descobriram que ela estava comprando doce de leite sem supervisão, quando deveria usar apenas produtos Jalav Israel (laticínios feitos sob supervisão judaica). Avisaram e pediram para corrigir. Pouco depois, sem saber que estava sendo acompanhada de perto, apareceu comprando queijo comum sem certificação, e essa foi a gota d\'água: retiraram a supervisão. Tudo foi tratado com discrição, sem escândalo, simplesmente deixando de certificar o local.</p>
<p>A lição para o consumidor é clara: o selo vale porque por trás dele existe alguém controlando de verdade, o tempo todo. Por isso vale a pena confiar em certificadoras reconhecidas e, diante de um símbolo desconhecido, perguntar na comunidade antes de presumir que um produto é kosher.</p>

<h2>Por que algumas comunidades só aceitam certos selos</h2>
<p>Nem todo rabino ou comunidade aceita os mesmos hechsherim. Alguns critérios são mais flexíveis e confiam numa ampla gama de certificadoras reconhecidas; outros, especialmente comunidades mais rigorosas, só confiam num punhado específico de selos cujos padrões conhecem a fundo. Não é raro ver "listas de hechsherim confiáveis" que cada comunidade publica para seus membros. Na dúvida sobre se um selo específico é aceito, o mais simples é perguntar diretamente ao rabino da congregação.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Um produto sem nenhum símbolo pode ser kosher mesmo assim?</strong><br>
Tecnicamente sim, se todos os ingredientes forem kosher e não houver contato com equipamento não kosher, mas sem certificação não há como verificar isso de fora. É exatamente por isso que o hechsher existe.</p>
<p><strong>Todos os símbolos com um "K" são confiáveis?</strong><br>
Não. Diferente de "OU" ou "OK", a letra "K" isolada não é registrada como marca por nenhuma agência específica, então qualquer fabricante pode imprimi-la sem que signifique supervisão real. Vale desconfiar de um "K" solto sem agência identificável.</p>
<p><strong>Os símbolos significam a mesma coisa em todos os países?</strong><br>
O desenho do símbolo costuma ser consistente porque é registrado pela certificadora, mas o nível de reconhecimento que cada comunidade dá a ele varia conforme o país e a corrente religiosa.</p>

<h2>Para continuar lendo</h2>
<p>Para entender a diferença entre "D", "M" e "Pareve" em detalhe, veja <a href="/articulos/que-significa-pareve">o que significa pareve</a>. E se você quer ler um rótulo completo além do símbolo, veja <a href="/articulos/como-leer-etiqueta-kosher">como ler um rótulo kosher</a>.</p>',
            ],
            'que-significa-pareve' => [
                'title' => 'Pareve: o que significa e por que é tão comum nos rótulos',
                'excerpt' => 'Pareve é uma das palavras mais repetidas na rotulagem kosher. Explicamos o que significa e por que é tão valorizada.',
                'content' => '<p>"Pareve" (também escrito parve) descreve os alimentos que não são nem cárneos nem lácteos: frutas, verduras, ovos, peixe, grãos e a maioria dos produtos elaborados sem ingredientes de origem animal láctea ou cárnea.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/que-significa-pareve.jpg" alt="Frutas e verduras frescas são parve por natureza: combinam tanto com carne quanto com laticínios." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Frutas e verduras frescas são parve por natureza: combinam tanto com carne quanto com laticínios. Foto: PattayaPatrol via <a href="https://commons.wikimedia.org/wiki/File%3ADFC_2197_A_colorful_assortment_of_fresh_fruits_and_vegetables_-_apples_mango_dragon_fruit_kiwis_limes_bananas_and_more_-_arranged_on_a_wooden_crate.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>A grande vantagem de um produto pareve é sua flexibilidade: pode se combinar livremente tanto com refeições cárneas quanto lácteas, sem gerar nenhum conflito de kashrut. Por isso, muitas indústrias alimentícias buscam ativamente desenvolver versões pareve de produtos que tradicionalmente levam laticínios — como chocolate, margarina ou substitutos de creme — para ampliar seu mercado.</p>
<p>É importante esclarecer um detalhe: um alimento pode ser pareve pelos ingredientes, mas perder esse status se foi elaborado em equipamento que também processa laticínios ou carne, dependendo dos traços que possam restar. Por isso a certificação não analisa apenas ingredientes, mas também o equipamento de produção e os processos de limpeza entre lotes.</p>
<p>Alguns exemplos comuns de produtos pareve: azeite de oliva, massa seca sem ovo, a maioria dos pães (embora alguns levem manteiga e passem a ser lácteos), castanhas sem processamento, e refrigerantes. Revisar sempre o rótulo é a única forma certeira de confirmar, já que a receita pode variar entre marcas ou até entre apresentações da mesma marca.</p>',
            ],
            'shejita-sacrificio-kosher' => [
                'title' => 'Shechitá: o método de abate kosher',
                'excerpt' => 'Para que a carne de um animal kosher seja apta para consumo, deve ser abatida segundo um método ritual específico chamado shechitá.',
                'content' => '<p>A shechitá é o método de abate ritual judaico, realizado por um shochet (abatedor capacitado e certificado) usando uma faca extremamente afiada e sem mossas, projetada especificamente para produzir um corte rápido e preciso na garganta do animal, seccionando a traqueia e o esôfago em um único movimento contínuo.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/shejita-sacrificio-kosher.svg" alt="O chalef é revisado antes e depois de cada animal: uma única falha invalida o procedimento." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">O chalef é revisado antes e depois de cada animal: uma única falha invalida o procedimento. Ilustração: KosherMap.</figcaption>
</figure>
<p>O objetivo desse método é minimizar o sofrimento do animal e produzir uma perda de consciência praticamente instantânea. O shochet inspeciona a faca antes e depois de cada abate para garantir que não tenha nenhuma imperfeição, por mínima que seja, já que qualquer irregularidade invalida o procedimento.</p>
<p>Um parente da nossa equipe trabalha em shechitá, e isso dá uma ideia de o quanto o controle é obsessivo na prática. O chalaf (a faca) não é revisado apenas pelo shochet antes e depois de cada animal: existe também um supervisor que controla todas as facas, dia após dia, com um critério extremamente rigoroso. Uma imperfeição que nem se percebe a olho nu já é suficiente para tirar uma faca de uso. É um ambiente de máxima seriedade e precisão, onde diante de qualquer situação fora do comum, a primeira atitude é parar tudo e priorizar a segurança.</p>
<p>Após a shechitá, é feita uma inspeção (bedika) dos órgãos internos do animal, especialmente os pulmões, para descartar doenças ou aderências que invalidariam a carne como kosher. Apenas os animais que passam nessa inspeção são considerados aptos.</p>
<p>Além disso, a carne deve passar por um processo de salga (kashering) para extrair o sangue, já que a Torá proíbe consumir sangue. Isso é feito deixando a carne de molho em água, salgando-a e deixando-a descansar antes de enxaguá-la novamente — um processo que hoje em dia geralmente é realizado pelo próprio açougue ou frigorífico certificado antes de o produto chegar ao consumidor.</p>',
            ],
            'glatt-kosher' => [
                'title' => 'Glatt kosher: que diferença há para o kosher comum',
                'excerpt' => 'O termo "glatt" aparece frequentemente em açougues e restaurantes kosher. Explicamos que nível de rigor representa.',
                'content' => '<p>"Glatt" significa "liso" em iídiche e originalmente se referia especificamente ao estado dos pulmões de um animal após a <a href="/articulos/shejita-sacrificio-kosher">shechitá</a>: se os pulmões não apresentassem nenhuma aderência (sircá), o animal era considerado "glatt", o nível mais alto de certeza de que a carne é kosher sem dúvida alguma.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/glatt-kosher.jpg" alt="Um restaurante de carne kosher em Jerusalém. &quot;Glatt&quot; indica o padrão de inspeção mais exigente." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Um restaurante de carne kosher em Jerusalém. "Glatt" indica o padrão de inspeção mais exigente. Foto: brionv from San Francisco, United States via <a href="https://commons.wikimedia.org/wiki/File%3AJerusalem_MMMM_MEAT_%286036353902%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 2.0.</figcaption>
</figure>

<h2>A origem técnica: os pulmões do animal</h2>
<p>Durante a inspeção pós-shechitá, um dos passos-chave é revisar os pulmões do animal em busca de aderências (sircot), que podem indicar uma doença ou perfuração que invalidaria o animal como kosher. Quando o pulmão aparece completamente liso, sem nenhuma aderência, o animal é classificado como "glatt". Se tem aderências menores que um examinador experiente (bodek) considera que podem ser removidas sem deixar perfuração, o animal ainda pode se qualificar como kosher sob o critério padrão, embora não como "glatt".</p>

<h2>Como passou de termo técnico a marca de rigor geral</h2>
<p>Com o tempo, especialmente em comunidades asquenazitas dos Estados Unidos, o termo "glatt kosher" se estendeu coloquialmente para descrever um padrão geral de maior rigor em toda a cadeia de produção de um alimento, não só na inspeção de pulmões. Hoje é comum ver "glatt kosher" em rótulos de restaurantes e produtos para indicar que cumprem os critérios mais exigentes possíveis, mesmo em contextos onde o termo técnico original (os pulmões do animal) nem se aplica.</p>

<h2>O kosher comum é "menos válido"?</h2>
<p>É importante destacar que um produto "kosher" sem o rótulo "glatt" não é menos válido halajicamente: simplesmente segue um padrão de certificação diferente, geralmente aceito pela ampla maioria das comunidades e autoridades rabínicas. A escolha entre kosher padrão e glatt kosher costuma depender do costume familiar ou comunitário, mais do que de uma diferença objetiva de validade. Muitas famílias que não seguem glatt no dia a dia escolhem mesmo assim para ocasiões especiais, como um casamento ou uma festividade importante.</p>
<p>No caso de aves e peixes, o conceito de "glatt" tecnicamente não se aplica da mesma forma, já que as aves não passam pelo mesmo tipo de inspeção pulmonar, embora coloquialmente às vezes seja usado para indicar um nível de supervisão mais rigoroso em geral.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Toda carne glatt kosher é mais cara?</strong><br>
Em geral sim, porque o processo de seleção é mais rigoroso e uma porcentagem maior de animais é descartada, o que reduz a oferta disponível em relação à demanda.</p>
<p><strong>Um restaurante que diz "kosher" sem especificar "glatt" está sendo desonesto?</strong><br>
Não, está simplesmente sendo preciso: segue o critério kosher padrão, plenamente válido para a grande maioria das comunidades.</p>
<p><strong>"Glatt" garante que outras regras, como bishul akum, também foram seguidas?</strong><br>
Não automaticamente. "Glatt" se refere especificamente ao nível de inspeção da carne; outras regras como <a href="/articulos/bishul-akum">bishul akum</a> são verificadas separadamente como parte do processo geral de certificação.</p>

<h2>Para continuar lendo</h2>
<p>Para entender o processo completo por trás da carne kosher, do abate à inspeção, veja <a href="/articulos/shejita-sacrificio-kosher">shechitá: o método de abate kosher</a>. E para reconhecer os selos que certificam tudo isso no rótulo, veja <a href="/articulos/simbolos-certificacion-kosher">símbolos de certificação kosher</a>.</p>',
            ],
            'como-leer-etiqueta-kosher' => [
                'title' => 'Como ler um rótulo de produto kosher',
                'excerpt' => 'Além do símbolo de certificação, os rótulos kosher contêm informações-chave para saber se um produto é apto para sua mesa.',
                'content' => '<p>Ler corretamente um rótulo kosher vai além de procurar o símbolo de certificação. Há vários elementos que vale a pena sempre revisar, e aprender a lê-los por completo evita surpresas à mesa.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/como-leer-etiqueta-kosher.jpg" alt="O rótulo de um produto israelense: além do selo, vale olhar a categoria e os ingredientes." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">O rótulo de um produto israelense: além do selo, vale olhar a categoria e os ingredientes. Foto: Chenspec via <a href="https://commons.wikimedia.org/wiki/File%3AList_of_ingredients_of_food_products_in_Israel_02.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>Os quatro dados que vale a pena procurar, em ordem</h2>
<ul>
<li><strong>O símbolo da certificadora:</strong> identifica qual agência supervisionou o produto. É importante reconhecer certificadoras confiáveis (detalhadas em <a href="/articulos/simbolos-certificacion-kosher">símbolos de certificação kosher</a>), já que nem todos os símbolos do mundo têm o mesmo nível de exigência.</li>
<li><strong>A categoria:</strong> "Dairy" ou "D" (lácteo), "Meat" ou "M" (cárneo), "Pareve" (neutro), ou "Fish" (peixe, que em muitas tradições é tratado como categoria separada da carne). Essa letra determina com quais outras comidas o produto pode ser combinado.</li>
<li><strong>"Kosher para Pessach":</strong> indicação adicional necessária durante a festividade, diferente da certificação kosher habitual do resto do ano. Um produto pode ser kosher o ano todo, mas não apto para Pessach se contiver algum derivado dos cinco grãos.</li>
<li><strong>Data ou código de certificação:</strong> algumas certificadoras incluem um código ou data para verificar se o selo continua válido, já que receitas e processos de fábrica podem mudar, e uma certificação pode ser perdida sem que o design da embalagem mude.</li>
</ul>

<h2>A categoria "DE": o detalhe mais esquecido</h2>
<p>Além de "D", "M" e "Pareve", alguns rótulos incluem "DE" (dairy equipment): significa que o produto não leva ingredientes lácteos, mas foi elaborado no mesmo equipamento que produtos lácteos. Para efeitos práticos, muitas opiniões tratam um produto "DE" de forma mais flexível que um "D" real, mas não como se fosse pareve puro. Vale entender essa diferença em detalhe em <a href="/articulos/que-significa-pareve">o que significa pareve</a>.</p>

<h2>Por que não convém assumir por eliminação</h2>
<p>Quando um produto não tem certificação visível mas a lista de ingredientes parece simples (por exemplo, só água, sal e um vegetal), a tentação é assumir que é kosher por eliminação. A recomendação geral das autoridades de kashrut é não fazer isso: muitos aditivos e processos industriais não são evidentes à primeira vista, desde enzimas usadas no processamento até equipamento compartilhado com outros produtos na mesma linha de produção.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Um produto sem categoria (D/M/Pareve) impressa é sempre pareve?</strong><br>
Não assuma isso. A ausência de uma letra não confirma nada por si só; se o rótulo não esclarece a categoria, vale procurar o produto diretamente no banco de dados da certificadora.</p>
<p><strong>Aplicativos de leitura de código de barras substituem a necessidade de revisar o rótulo físico?</strong><br>
Ajudam muito, mas convém usá-los como complemento, não como substituto total, principalmente com produtos importados ou de fabricação recente que o banco de dados ainda não atualizou.</p>
<p><strong>Todos os países exigem que o rótulo kosher esteja no idioma local?</strong><br>
Não, é comum encontrar produtos importados com a informação kosher só no idioma de origem (hebraico, inglês), o que torna mais valioso reconhecer os símbolos de memória em vez de depender do texto.</p>

<h2>Para continuar lendo</h2>
<p>Para reconhecer de relance os selos mais comuns do mundo, veja <a href="/articulos/simbolos-certificacion-kosher">símbolos de certificação kosher</a>. No KosherMap você também pode buscar produtos por nome ou código de barras e filtrar diretamente por certificadora, categoria e tipo, para não depender só do rótulo físico.</p>',
            ],
            'bishul-akum' => [
                'title' => 'Bishul Akum: por que alguns alimentos cozidos precisam de supervisão judaica',
                'excerpt' => 'Existe uma categoria de leis específica sobre alimentos cozinhados por não judeus, conhecida como bishul akum. Explicamos do que se trata.',
                'content' => '<p>Bishul akum (literalmente "cozimento de um não judeu") é uma categoria de leis rabínicas que restringe o consumo de certos alimentos cozinhados inteiramente por uma pessoa não judia, mesmo que todos os ingredientes sejam kosher. A proibição foi estabelecida pelos sábios talmúdicos, principalmente para fomentar a coesão social e evitar a assimilação cultural entre comunidades.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/bishul-akum.jpg" alt="Bishul akum regula alimentos cozidos inteiramente por um não judeu, mesmo com ingredientes kosher." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Bishul akum regula alimentos cozidos inteiramente por um não judeu, mesmo com ingredientes kosher. Foto: Seattle Municipal Archives from Seattle, WA via <a href="https://commons.wikimedia.org/wiki/File%3ACooks_in_kitchen%2C_1930_%2820981103874%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>

<h2>Que alimentos entram nessa categoria (e quais não)</h2>
<p>Essa lei não se aplica a todos os alimentos: geralmente se limita a alimentos considerados "dignos de servir na mesa de um rei" (chashivut) e que não se comem crus. Por isso, frutas, verduras cruas e a maioria dos lanches industrializados não entram nessa categoria. O critério de "chashivut" é em parte subjetivo e varia conforme a comunidade: um guisado elaborado claramente entra, enquanto um vegetal simplesmente fervido gera mais debate entre as diferentes opiniões rabínicas.</p>

<h2>Como se resolve numa fábrica ou restaurante certificado</h2>
<p>Há duas formas habituais de resolver o problema em um contexto de produção industrial ou restaurantes certificados:</p>
<ul>
<li><strong>Que um judeu observante participe ativamente do processo de cozimento</strong>, por exemplo acendendo o fogo ou o equipamento antes de começar o processo de produção.</li>
<li><strong>Que a supervisão rabínica certifique</strong> que um representante judeu esteve presente durante o acendimento dos equipamentos de cocção em cada turno de produção, mesmo que o resto do processo seja depois conduzido por pessoal não judeu.</li>
</ul>
<p>Essa é uma das razões pelas quais a certificação kosher de uma fábrica de alimentos não se limita a revisar ingredientes: também supervisiona processos, presença de pessoal e protocolos operacionais do estabelecimento, o que torna o trabalho das certificadoras muito mais complexo do que uma simples lista de checagem de insumos.</p>

<h2>Por que isso afeta restaurantes que não são kosher</h2>
<p>Essa é uma das razões de fundo pelas quais um prato preparado num restaurante sem certificação gera dúvidas mesmo quando todos os ingredientes parecem aptos: além da origem da carne ou do equipamento compartilhado, entra em jogo quem efetivamente acendeu o fogo e conduziu o cozimento. O tema é desenvolvido com exemplos concretos em <a href="/articulos/comer-kosher-restaurante">como comer kosher em um restaurante não certificado</a>.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Bishul akum também se aplica em casa, com funcionários domésticos não judeus?</strong><br>
Sim, o princípio é o mesmo independentemente de onde se cozinha; o que muda é a solução prática, geralmente algum membro judeu da família participando do acendimento.</p>
<p><strong>Um forno elétrico com timer resolve o problema do acendimento?</strong><br>
Depende da opinião rabínica seguida; algumas aceitam como forma de "participação" do dono judeu, outras exigem um ato mais direto. Na dúvida, consulte o rabino da comunidade.</p>
<p><strong>Bishul akum tem alguma relação com a casherização de utensílios?</strong><br>
São categorias distintas: bishul akum trata de quem cozinha, enquanto a casherização (como a <a href="/articulos/hagala-utensilios-metal">hagalá</a>) trata do que um utensílio absorveu anteriormente.</p>

<h2>Para continuar lendo</h2>
<p>Para ver como esse princípio se aplica na prática ao comer fora, veja <a href="/articulos/comer-kosher-restaurante">como comer kosher em um restaurante não certificado</a>. E para outra categoria onde importa muito quem manipula o produto, veja <a href="/articulos/vino-kosher">vinho kosher</a>.</p>',
            ],
            'vino-mevushal' => [
                'title' => 'Mevushal: vinho kosher que pode ser servido sem restrições',
                'excerpt' => 'O vinho mevushal é uma categoria especial que permite servi-lo em eventos sem necessidade de que só judeus o manipulem.',
                'content' => '<p>Como vimos ao falar sobre <a href="/articulos/vino-kosher">vinho kosher</a>, a regra geral exige que só judeus observantes manipulem o vinho desde a elaboração até o serviço. O vinho mevushal ("fervido" ou pasteurizado) é uma exceção prática a essa regra: uma vez que o vinho passa por um processo de aquecimento a uma temperatura mínima específica, ele mantém seu status kosher independentemente de quem o sirva depois.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/vino-mevushal.jpg" alt="O vinho mevushal é pasteurizado, por isso mantém seu status kosher mesmo servido por um não judeu." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">O vinho mevushal é pasteurizado, por isso mantém seu status kosher mesmo servido por um não judeu. Foto: misbehave via <a href="https://commons.wikimedia.org/wiki/File%3ABottle_%26_glass_of_red_Bordeaux_style_blend.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>

<h2>Por que o calor muda tudo</h2>
<p>Essa categoria existe graças a um princípio halájico segundo o qual o vinho que foi alterado por meio de calor perde a "dignidade" ritual que originalmente motivou a restrição, já que historicamente essa preocupação visava o uso do vinho em cerimônias idólatras — algo que um vinho fervido não se prestava a fazer nesse contexto. Ao perder esse status, deixa de importar quem o toca ou serve depois do processo: pode passar por mãos não judias sem perder seu status kosher.</p>

<h2>Onde é mais usado</h2>
<p>O vinho mevushal é muito popular em:</p>
<ul>
<li><strong>Bufês e eventos:</strong> casamentos, bar/bat mitzvás e recepções onde a equipe de serviço não é necessariamente judia.</li>
<li><strong>Restaurantes kosher abertos ao público geral:</strong> permite que qualquer garçom sirva o vinho sem supervisão especial em cada mesa.</li>
<li><strong>Companhias aéreas e hotéis</strong> que oferecem opções kosher, onde seria impraticável garantir que só pessoal judeu toque cada garrafa.</li>
<li><strong>Sinagogas e grandes eventos comunitários</strong>, onde o vinho circula por muitas mãos durante um kidush.</li>
</ul>

<h2>Como é pasteurizado sem estragar o sabor</h2>
<p>Durante muito tempo, ferver o vinho da forma tradicional afetava bastante o sabor, e o mevushal tinha fama de ser de qualidade inferior. Hoje existem técnicas modernas de pasteurização rápida (flash pasteurization), onde o vinho é aquecido à temperatura mínima exigida só por alguns segundos e resfriado imediatamente, algo que antes era muito mais difícil de conseguir sem estragar o sabor. Isso ampliou muito a oferta de vinhos mevushal premium disponíveis no mercado, incluindo rótulos de vinícolas reconhecidas que antes só produziam vinho não mevushal.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Todo vinho kosher vendido em supermercados é mevushal?</strong><br>
Não, também existem vinhos kosher não mevushal no mercado geral. Vale revisar o rótulo ou o site da certificadora se isso for relevante para seu uso (por exemplo, se for servir num evento com equipe não judia).</p>
<p><strong>O mevushal tem qualidade inferior ao vinho kosher comum?</strong><br>
Já não necessariamente. Com as técnicas modernas de pasteurização rápida, muitas vinícolas produzem mevushal de nível premium indistinguível numa degustação às cegas da versão não pasteurizada.</p>
<p><strong>Um vinho pode se tornar mevushal depois de engarrafado?</strong><br>
Não, o processo de aquecimento precisa ser feito antes ou durante a elaboração, sob supervisão, e fica indicado no rótulo ou na ficha do produto da certificadora.</p>

<h2>Para continuar lendo</h2>
<p>Para entender por que o vinho comum precisa de supervisão tão rigorosa, veja <a href="/articulos/vino-kosher">vinho kosher: por que precisa de supervisão especial</a>. O tema também se relaciona com <a href="/articulos/bishul-akum">bishul akum</a>, outra categoria onde importa quem manipula a comida.</p>',
            ],
            'tevilat-kelim' => [
                'title' => 'Tevilat Kelim: a imersão ritual de utensílios novos',
                'excerpt' => 'Antes de usar pela primeira vez certos utensílios de cozinha fabricados por não judeus, existe o costume de submergi-los em um mikvê.',
                'content' => '<p>Tevilat Kelim é a prática de submergir utensílios de cozinha novos — de metal ou vidro, comprados de um fabricante não judeu — em um mikvê (banho ritual) ou uma fonte de água natural antes de usá-los pela primeira vez para alimentos.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/tevilat-kelim.jpg" alt="Uma mikvê, o banho ritual onde se imergem os utensílios novos antes do primeiro uso." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Uma mikvê, o banho ritual onde se imergem os utensílios novos antes do primeiro uso. Foto: Stefan Walkowski via <a href="https://commons.wikimedia.org/wiki/File%3AMikveh.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>Quais utensílios precisam de tevilá e quais não</h2>
<p>Esse costume se aplica principalmente a utensílios que entram em contato direto com a comida: panelas, frigideiras, talheres, pratos de vidro e copos. Geralmente não se aplica a utensílios elétricos (como uma torradeira ou liquidificador) nem a utensílios de plástico ou madeira, embora as opiniões variem conforme a tradição de cada comunidade, por isso é recomendável consultar um rabino sobre casos específicos. O critério geral usado para essa distinção é parecido com o que determina o que precisa de <a href="/articulos/hagala-utensilios-metal">hagalá</a>: quanto mais direto o contato com a comida e mais durável o material, mais provável que precise de imersão.</p>

<h2>O processo passo a passo</h2>
<ol>
<li>Limpar bem o utensílio, sem restos de etiquetas, lacres ou adesivos que possam se interpor entre a água e a superfície.</li>
<li>Verificar que não haja nenhuma substância (gordura, ferrugem, resíduo de embalagem) atuando como barreira.</li>
<li>Submergir completamente na água do mikvê, ou numa fonte de água natural apropriada, enquanto se recita uma bênção (no caso de utensílios que a exigem conforme a tradição).</li>
<li>Uma vez imerso corretamente, o utensílio já está pronto para ser usado normalmente, sem nenhum passo adicional.</li>
</ol>

<h2>Onde fazer isso na prática</h2>
<p>Muitos mikvês comunitários têm um horário específico habilitado para tevilat kelim, separado do uso ritual pessoal, assim como instruções detalhadas sobre quais materiais requerem imersão e quais não. Algumas comunidades maiores têm até uma piscina separada, dedicada exclusivamente a esse uso, para não misturar os horários com o mikvê de uso ritual pessoal.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Um utensílio de segunda mão precisa de tevilá igual a um novo?</strong><br>
Sim, se foi fabricado ou vendido originalmente por alguém não judeu, o costume se aplica independentemente de o utensílio ser novo ou usado.</p>
<p><strong>E se eu comprar um utensílio e não tiver um mikvê por perto?</strong><br>
Pode-se usar qualquer fonte de água natural que cumpra os requisitos halájicos (um rio, o mar), embora na prática a maioria recorra ao mikvê comunitário mais próximo que tenha horário habilitado para isso.</p>
<p><strong>Utensílios comprados de um fabricante judeu também precisam de tevilá?</strong><br>
Em geral não; o costume se concentra especificamente em utensílios de origem não judia.</p>

<h2>Para continuar lendo</h2>
<p>Para outros passos práticos ao equipar uma cozinha do zero, incluindo esse costume, veja <a href="/articulos/armar-cocina-kosher">como montar uma cozinha kosher do zero</a>. E para o método usado para purificar utensílios que absorveram sabor não kosher, veja <a href="/articulos/hagala-utensilios-metal">hagalá: como casherizar utensílios de metal</a>.</p>',
            ],
            'armar-cocina-kosher' => [
                'title' => 'Como montar uma cozinha kosher do zero',
                'excerpt' => 'Começar a manter uma cozinha kosher pode parecer assustador no início. Damos um guia prático dos primeiros passos.',
                'content' => '<p>Montar uma cozinha kosher do zero é um processo gradual, e não é preciso resolver tudo em um dia. Estes são os passos mais comuns que famílias iniciantes seguem:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/armar-cocina-kosher.jpg" alt="Montar uma cozinha kosher é um processo gradual: não é preciso resolver tudo em um dia." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Montar uma cozinha kosher é um processo gradual: não é preciso resolver tudo em um dia. Foto: MeRyan via <a href="https://commons.wikimedia.org/wiki/File%3AKitchen_with_island%2C_New_Orleans_2007.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>

<h2>Os passos mais comuns</h2>
<ul>
<li><strong>Definir a separação física:</strong> estabelecer quais utensílios, panelas e louças serão cárneos e quais lácteos. O mais prático costuma ser usar cores diferentes (por exemplo, vermelho para carne, azul para leite) para evitar confusões diárias.</li>
<li><strong>Separar as superfícies de trabalho:</strong> tábuas de corte, panos de prato e esponjas também devem ser divididos por categoria, idealmente guardados em lugares diferentes da cozinha para evitar misturas acidentais.</li>
<li><strong>Avaliar eletrodomésticos compartilhados:</strong> forno, micro-ondas e lava-louças podem ser casherizados entre usos ou, mais simples, designados a uma só categoria desde o início (por exemplo, micro-ondas só para pareve).</li>
<li><strong>Comprar produtos certificados:</strong> revisar o símbolo de certificação em cada compra, até que vire um hábito automático, sem precisar pensar nisso toda vez.</li>
<li><strong>Coordenar com um rabino:</strong> especialmente para casherizar itens que já estavam na cozinha antes de começar esse processo, e resolver dúvidas pontuais sobre materiais ou utensílios específicos.</li>
<li><strong>Somar tevilat kelim:</strong> utensílios novos de metal ou vidro comprados de um fabricante não judeu precisam de imersão ritual antes do primeiro uso.</li>
</ul>

<h2>Ir aos poucos: a ordem que mais facilita a transição</h2>
<p>Uma estratégia muito usada por quem está começando é incorporar a separação aos poucos: primeiro os utensílios de uso diário (panelas, frigideiras, talheres), depois as louças de mesa, e finalmente os eletrodomésticos, que costumam ser o mais caro e complexo de resolver. Não é necessário trocar toda a cozinha de uma vez, e muitas famílias levam meses para completar a transição sem que isso seja um problema halájico em si: o que importa é a intenção de seguir avançando, não a velocidade.</p>

<h2>O orçamento: como distribuir o investimento</h2>
<p>Montar uma cozinha completa do zero pode parecer caro se pensado como um gasto único. Na prática, a maioria das famílias distribui em compras pequenas e espaçadas: um jogo de panelas este mês, talheres no próximo, e assim por diante. Bazares e lojas da comunidade costumam ter combos pensados especificamente para isso, com preços mais acessíveis do que comprar tudo separadamente.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Preciso jogar fora tudo o que já havia na cozinha antes de começar?</strong><br>
Não necessariamente. Muitos utensílios podem ser casherizados conforme o material (veja <a href="/articulos/hagala-utensilios-metal">hagalá</a>); só o que não admite casherização, como a maioria da cerâmica, precisa ser substituído.</p>
<p><strong>O micro-ondas precisa de duas unidades, uma para carne e outra para lácteo?</strong><br>
Não é obrigatório: muitas famílias usam um único micro-ondas designado "só para pareve", ou o casherizam entre usos conforme detalhado em <a href="/articulos/kasherizar-horno">como casherizar o forno</a> (o princípio é semelhante).</p>
<p><strong>Quanto tempo leva em média para completar a transição?</strong><br>
Varia muito conforme o orçamento e o ritmo de cada família, mas não é incomum levar entre vários meses e um ano para completar todos os passos com tranquilidade.</p>

<h2>Para continuar lendo</h2>
<p>Para o detalhe da imersão ritual de utensílios novos, veja <a href="/articulos/tevilat-kelim">tevilat kelim</a>. E para entender o método usado para casherizar o que você já tinha em casa, veja <a href="/articulos/hagala-utensilios-metal">hagalá: como casherizar utensílios de metal</a>.</p>',
            ],
            'certificaciones-kosher-mundo' => [
                'title' => 'Diferenças entre as certificações kosher ao redor do mundo',
                'excerpt' => 'Nem todas as certificadoras kosher seguem exatamente os mesmos critérios. Conhecer essas diferenças ajuda a escolher produtos com confiança.',
                'content' => '<p>Embora os princípios fundamentais do kashrut sejam universais, existem centenas de agências certificadoras no mundo, e cada uma pode ter critérios ligeiramente diferentes sobre temas específicos — por exemplo, qual nível de supervisão exige para <a href="/articulos/bishul-akum">bishul akum</a>, ou como aborda certos aditivos químicos cuja origem é difícil de rastrear.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/certificaciones-kosher-mundo.svg" alt="Os princípios do kashrut são universais, mas cada região tem suas próprias agências certificadoras." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Os princípios do kashrut são universais, mas cada região tem suas próprias agências certificadoras. Ilustração: KosherMap.</figcaption>
</figure>

<h2>Como a certificação varia por região</h2>
<ul>
<li><strong>Estados Unidos:</strong> tem as maiores certificadoras a nível industrial (OU, OK, Star-K, Kof-K), com processos muito padronizados para exportação em massa. Um mesmo produto certificado ali costuma chegar praticamente igual a dezenas de países.</li>
<li><strong>Israel:</strong> o Rabanut (rabinato) oferece certificação oficial estatal, enquanto organizações como o Badatz mantêm padrões adicionais considerados mais estritos por certas comunidades, às vezes gerando dois níveis de certificação sobre um mesmo produto.</li>
<li><strong>Europa:</strong> certificadoras como o Beth Din de várias cidades (Londres, Paris, Zurique) supervisionam tanto a produção local quanto importações, adaptando-se a mercados com menor escala industrial que os Estados Unidos.</li>
<li><strong>América Latina:</strong> cada comunidade costuma ter seu Vaad Hakashrut local (por exemplo, em Buenos Aires, São Paulo ou Cidade do México), que certifica tanto produtos locais quanto restaurantes, geralmente com alcance limitado à sua própria cidade ou país.</li>
</ul>

<h2>Por que não existe uma única agência</h2>
<p>Diferente de outros selos de qualidade, não existe uma autoridade central única para o kashrut em nível mundial. Cada certificadora responde à sua própria cadeia de rabinos supervisores, e seu reconhecimento se constrói com o tempo, com base na confiança que outras comunidades e rabinos depositam nela. Por isso, uma certificadora pode ser muito respeitada em seu país de origem e praticamente desconhecida em outro, sem que isso signifique que seja menos séria.</p>

<h2>O que fazer diante de um selo que você não reconhece</h2>
<p>Para o consumidor, o mais importante é aprender a reconhecer as certificadoras ativas em sua região (detalhadas em <a href="/articulos/simbolos-certificacion-kosher">símbolos de certificação kosher</a>). E, em caso de dúvida sobre um símbolo desconhecido, consultar o rabino da comunidade ou pesquisar a reputação da agência antes de confiar em um produto. A maioria das grandes certificadoras publica listas públicas de produtos certificados em seus sites, algo útil especialmente ao comprar produtos importados.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Um produto certificado em um país é automaticamente aceito em outro?</strong><br>
Nem sempre. Depende do reconhecimento mútuo entre certificadoras e do critério de cada comunidade; algumas famílias só confiam numa lista limitada de agências, independentemente de onde o produto venha.</p>
<p><strong>Existe uma lista global única de certificadoras confiáveis?</strong><br>
Não existe uma lista oficial única, mas muitas organizações rabínicas regionais publicam suas próprias listas de agências que reconhecem, que servem como referência prática.</p>
<p><strong>Por que alguns produtos têm dois ou três selos diferentes?</strong><br>
Costuma acontecer quando o fabricante quer alcançar mercados ou comunidades com critérios de confiança diferentes, e busca a aprovação de várias agências reconhecidas em cada região.</p>

<h2>Para continuar lendo</h2>
<p>Para reconhecer os símbolos mais comuns de relance, veja <a href="/articulos/simbolos-certificacion-kosher">os símbolos de certificação kosher mais comuns</a>. E para entender toda a informação que um rótulo pode trazer além do selo, veja <a href="/articulos/como-leer-etiqueta-kosher">como ler um rótulo de produto kosher</a>.</p>',
            ],
            'queso-kosher-cuajo' => [
                'title' => 'Queijo kosher: por que precisa de coalho especial',
                'excerpt' => 'O queijo é um dos produtos lácteos com mais restrições kosher, principalmente pela origem do coalho usado para elaborá-lo.',
                'content' => '<p>O coalho (rennet) é a enzima usada tradicionalmente para coagular o leite e separar o soro na elaboração do queijo. O problema do ponto de vista do kashrut é que o coalho tradicional é extraído do estômago de bezerros, e para ser apto, esse animal deve ter sido abatido por meio da <a href="/articulos/shejita-sacrificio-kosher">shechitá</a> — algo que na indústria queijeira convencional quase nunca acontece.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/queso-kosher-cuajo.jpg" alt="O queijo precisa de certificação específica pela origem do coalho, a enzima que coagula o leite." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">O queijo precisa de certificação específica pela origem do coalho, a enzima que coagula o leite. Foto: Daderot via <a href="https://commons.wikimedia.org/wiki/File%3ACheese_display%2C_Cambridge_MA_-_DSC05391.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>

<h2>Por que o queijo "comum" quase nunca é kosher por eliminação</h2>
<p>Por isso, praticamente todo o queijo "comum" do mercado, mesmo feito só com leite e coalho, não é kosher sem certificação específica, já que a origem do coalho não pode ser verificada a olho nu. Nem a cor, o sabor nem a textura do queijo mudam conforme o tipo de coalho usado, então não há como distingui-los sem informação do fabricante.</p>

<h2>As três alternativas que os fabricantes usam</h2>
<p>As opções usadas pelos fabricantes de queijo kosher incluem:</p>
<ul>
<li><strong>Coalho animal kosher:</strong> extraído de animais abatidos segundo a shechitá e sob supervisão rabínica em toda a cadeia, do abate à extração da enzima.</li>
<li><strong>Coalho microbiano:</strong> produzido por fermentação de fungos ou bactérias, sem origem animal, cada vez mais comum tanto em queijos industriais quanto kosher, já que também resolve o problema para consumidores vegetarianos.</li>
<li><strong>Coalho vegetal:</strong> extraído de certas plantas (como o cardo), usado tradicionalmente em algumas variedades específicas de queijos artesanais, sobretudo na Península Ibérica.</li>
</ul>

<h2>Gvinat Yisrael: um critério que vai além do coalho</h2>
<p>Além do coalho, há outro fator relevante: muitas comunidades exigem que o queijo seja elaborado sob supervisão judaica constante (Gvinat Yisrael) para considerá-lo plenamente kosher, um critério adicional à simples análise de ingredientes, que se aproxima do princípio por trás do <a href="/articulos/bishul-akum">bishul akum</a>: não basta ter os insumos corretos, quem supervisiona o processo também importa.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Queijos veganos ou "sem laticínios" são automaticamente kosher?</strong><br>
Não necessariamente. Embora evitem o problema do coalho animal, ainda é preciso revisar o restante dos ingredientes e o equipamento usado, igual a qualquer outro produto industrializado.</p>
<p><strong>Queijo ralado embalado tem o mesmo risco que um bloco inteiro?</strong><br>
Sim, e às vezes mais: o processo de ralar e embalar pode somar outros ingredientes (antiaglomerantes, conservantes) que também requerem verificação.</p>
<p><strong>Como sei se um queijo usa coalho microbiano ou animal sem certificação?</strong><br>
Sem certificação visível não há forma confiável de saber pelo rótulo; por isso comprar queijo com um selo reconhecido é a maneira mais segura de evitar erros.</p>

<h2>Para continuar lendo</h2>
<p>Para entender o método de abate que determina se o coalho animal é apto, veja <a href="/articulos/shejita-sacrificio-kosher">shechitá: o método de abate kosher</a>. E para outro ingrediente de origem animal igualmente debatido, veja <a href="/articulos/gelatina-kosher">gelatina kosher: o debate halájico</a>.</p>',
            ],
            'huevos-kosher' => [
                'title' => 'Ovos kosher: o que revisar antes de usá-los',
                'excerpt' => 'Os ovos são pareve e geralmente kosher, mas existe um passo de revisão obrigatório antes de cozinhá-los.',
                'content' => '<p>Os ovos de aves kosher (como a galinha) são, em princípio, pareve e aptos para consumo. No entanto, antes de usar um ovo, a tradição exige revisar que não contenha manchas de sangue na gema, já que um ovo com sangue é considerado não apto para consumo.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/huevos-kosher.jpg" alt="Os ovos são parve, mas devem ser verificados quanto a manchas de sangue antes do uso." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Os ovos são parve, mas devem ser verificados quanto a manchas de sangue antes do uso. Foto: Evan-Amos via <a href="https://commons.wikimedia.org/wiki/File%3A6-Pack-Chicken-Eggs.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>

<h2>O procedimento de verificação</h2>
<p>O procedimento é simples: ao quebrar o ovo, revisa-se visualmente a gema (e às vezes a clara) contra a luz, procurando pontos vermelhos ou manchas. Convém quebrar cada ovo num recipiente separado antes de somá-lo a uma mistura maior, justamente para poder descartá-lo sem estragar o resto do preparo. Se for encontrado sangue, o ovo é descartado por completo; se a gema estiver limpa, o ovo é apto para usar normalmente.</p>

<h2>Por que isso acontece em alguns ovos</h2>
<p>A mancha de sangue costuma se originar da ruptura de um vaso sanguíneo durante a formação do ovo dentro da galinha, e não tem relação com o ovo estar fertilizado nem com nenhum problema de saúde da ave. É mais comum em ovos de galinhas mais velhas, embora possa aparecer em qualquer ovo sem padrão previsível, por isso a verificação é feita sempre, não só "quando parece suspeito".</p>

<h2>Outros dados sobre ovos e kashrut</h2>
<ul>
<li>A casca e a clara geralmente não apresentam o mesmo risco que a gema, embora o costume varie conforme a comunidade: algumas revisam ambas as partes com o mesmo cuidado.</li>
<li>Ovos de aves não kosher (como avestruz ou certas aves de rapina) também não são aptos, independentemente da presença de sangue, já que o problema de fundo é a origem da ave, não o sangue em si.</li>
<li>Produtos industrializados com ovo (como massas ou maionese) geralmente passam por um processo de controle de qualidade que detecta automaticamente ovos com sangue por ovoscopia, mas ainda exigem certificação para garantir que esse controle foi feito sob os critérios corretos.</li>
</ul>

<h2>Perguntas frequentes</h2>
<p><strong>Preciso revisar também ovos de codorna ou de pata?</strong><br>
Sim, o mesmo critério se aplica a ovos de qualquer ave kosher, não só aos de galinha.</p>
<p><strong>Um ovo com uma manchinha bem pequena pode ser salvo removendo só essa parte?</strong><br>
As opiniões variam conforme a tradição e o tamanho da mancha; algumas permitem remover só o ponto afetado, outras exigem descartar o ovo inteiro. Na dúvida, convém seguir o critério mais rigoroso ou consultar um rabino.</p>
<p><strong>Ovos com certificação kosher já vêm revisados de fábrica?</strong><br>
Isso não substitui a revisão caseira: a certificação garante a origem da ave e o processo industrial, mas revisar a gema quanto a sangue continua sendo responsabilidade de quem cozinha, exceto em produtos industrializados que já passaram por ovoscopia certificada.</p>

<h2>Para continuar lendo</h2>
<p>É um dos hábitos mais simples de incorporar em uma cozinha kosher diária: revisar cada ovo assim que é quebrado, antes de misturá-lo com o restante dos ingredientes. Para outros passos práticos do dia a dia, veja <a href="/articulos/armar-cocina-kosher">como montar uma cozinha kosher do zero</a>. E sobre outro alimento pareve com regras próprias de verificação, veja <a href="/articulos/insectos-frutas-verduras">insetos em frutas e verduras</a>.</p>',
            ],
            'pescado-kosher-aletas-escamas' => [
                'title' => 'Peixe kosher: barbatanas e escamas, as regras básicas',
                'excerpt' => 'Diferente da carne, o peixe kosher não requer shechitá, mas deve cumprir um critério físico específico.',
                'content' => '<p>A Torá estabelece uma regra relativamente simples para identificar peixe kosher: deve ter tanto barbatanas (snapir) quanto escamas (kaskeset) visíveis a olho nu. Essa combinação está presente na grande maioria dos peixes de água doce e salgada consumidos habitualmente, como salmão, atum, merluza, truta e cavala.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/pescado-kosher-aletas-escamas.jpg" alt="Para ser kosher, um peixe deve ter barbatanas e escamas visíveis." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Para ser kosher, um peixe deve ter barbatanas e escamas visíveis. Foto: IndayLiburan via <a href="https://commons.wikimedia.org/wiki/File%3AFish_stalls_at_Valencia_Public_Market_01.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>O que fica excluído do kashrut</h2>
<ul>
<li><strong>Todos os frutos do mar</strong> (camarão, lagostim, caranguejo, mexilhão, ostra): não têm nem barbatanas nem escamas sob nenhum critério.</li>
<li><strong>Polvo e lula:</strong> também não cumprem o critério, por não terem nem barbatanas nem escamas verdadeiras.</li>
<li><strong>Tubarão e tamboril:</strong> carecem de escamas verdadeiras segundo a maioria das opiniões halájicas, embora o tema tenha sido debatido por anos entre diferentes autoridades rabínicas.</li>
<li><strong>Enguia:</strong> carece de escamas visíveis, e fica fora do critério kosher.</li>
<li><strong>Peixe-espada:</strong> seu status é objeto de debate histórico entre diferentes autoridades rabínicas, e nem toda certificadora o trata da mesma forma.</li>
</ul>

<h2>Por que o peixe kosher é mais simples de preparar que a carne</h2>
<p>Uma diferença importante em relação à carne: o peixe kosher não requer <a href="/articulos/shejita-sacrificio-kosher">shechitá</a> nem um processo de salga para extrair sangue, o que simplifica bastante seu preparo. No entanto, em muitas tradições — especialmente asquenazitas — o peixe é tratado como uma categoria separada da carne e dos laticínios, evitando combiná-lo com carne no mesmo prato (embora não exija a mesma separação estrita de utensílios que rege entre carne e leite).</p>

<h2>Como verificar o peixe na hora de comprar</h2>
<p>Ao comprar peixe fresco, vale verificar se ele mantém a pele com escamas visíveis, já que algumas filetagens removem completamente a pele, dificultando a verificação. Por isso muitas peixarias kosher deixam uma porção de pele identificável no corte, geralmente uma tira perto da cauda, especificamente para que a espécie possa ser confirmada sem dúvida.</p>

<h2>Perguntas frequentes</h2>
<p><strong>O caviar é kosher?</strong><br>
Depende do peixe de origem: o caviar de esturjão é um tema debatido (pelas escamas atípicas do esturjão), enquanto ovas de outros peixes com barbatanas e escamas claras costumam ser aceitas sem problema.</p>
<p><strong>Preciso de certificação para comprar peixe fresco inteiro na peixaria?</strong><br>
Não é obrigatório se você mesmo puder verificar a espécie por suas características físicas, embora para produtos processados (filés sem pele, conservas) valha a pena procurar certificação.</p>
<p><strong>Por que se evita misturar peixe com carne se ambos são aptos separadamente?</strong><br>
É um costume, não uma proibição da mesma categoria que carne e leite; se originou de uma preocupação de saúde histórica que já não se aplica, mas se mantém como tradição em muitas comunidades.</p>

<h2>Para continuar lendo</h2>
<p>Para entender o processo que a carne de mamíferos e aves realmente precisa, veja <a href="/articulos/shejita-sacrificio-kosher">shechitá: o método de abate kosher</a>. E sobre outro ingrediente de origem animal com regras próprias, veja <a href="/articulos/gelatina-kosher">gelatina kosher: o debate halájico</a>.</p>',
            ],
            'frutos-secos-contaminacion-cruzada' => [
                'title' => 'Castanhas e kashrut: riscos de contaminação cruzada',
                'excerpt' => 'As castanhas são naturalmente pareve, mas o processamento industrial pode introduzir riscos de kashrut que não são evidentes.',
                'content' => '<p>Amêndoas, nozes, amendoim e a maioria das castanhas são, em sua forma crua e natural, alimentos pareve sem restrições de kashrut. O problema aparece quando entram na cadeia de processamento industrial, onde podem se misturar com outros produtos nas mesmas linhas de produção.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/frutos-secos-contaminacion-cruzada.jpg" alt="Crus são parve sem restrições; o risco aparece no processamento industrial." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Crus são parve sem restrições; o risco aparece no processamento industrial. Foto: Famartin via <a href="https://commons.wikimedia.org/wiki/File%3A2021-01-06_12_15_43_Cranberry_trail_mix_with_cranberries%2C_peanuts%2C_raisins%2C_walnuts%2C_almonds%2C_sunflower_seeds%2C_pepitas_in_the_Franklin_Farm_section_of_Oak_Hill%2C_Fairfax_County%2C_Virginia.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>

<h2>Os riscos mais comuns</h2>
<ul>
<li><strong>Aromatizantes lácteos:</strong> castanhas "tostadas com manteiga" ou com cobertura de chocolate ao leite deixam de ser pareve e passam para a categoria de lácteos, com todas as restrições de mistura que isso implica.</li>
<li><strong>Linhas compartilhadas:</strong> uma fábrica pode processar castanhas pareve no mesmo equipamento onde depois processa produtos com leite ou derivados cárneos, gerando traços não kosher se não houver limpeza certificada entre lotes, algo que nenhum rótulo de ingredientes revela por si só.</li>
<li><strong>Óleos de cozimento:</strong> algumas castanhas fritas usam óleos compartilhados com outros produtos não kosher, sem que isso se note no sabor final.</li>
<li><strong>Glaceados e coberturas:</strong> castanhas "carameladas" ou com cobertura doce podem conter <a href="/articulos/gelatina-kosher">gelatina</a> ou outros ingredientes de origem animal no glaceado.</li>
</ul>

<h2>Por que isso é mais comum do que parece</h2>
<p>Muitas fábricas de castanhas processam dezenas de variedades e apresentações diferentes na mesma planta, para aproveitar o mesmo equipamento: a linha que hoje torra amêndoas naturais pode ter processado ontem um lote com cobertura de chocolate ao leite. Sem limpeza certificada entre lotes (ou sem linhas dedicadas exclusivamente a produtos pareve), sobram traços suficientes para que o produto deixe de ser considerado pareve sob um critério rigoroso.</p>

<h2>Como minimizar o risco na prática</h2>
<p>Uma castanha crua e sem processamento, comprada a granel de uma fonte confiável ou em sua casca original, quase nunca apresenta problemas. Mas os produtos industrializados (mix de castanhas, lanches aromatizados, barras de cereal) sempre devem ser verificados quanto à certificação, sem assumir que são automaticamente kosher só porque o ingrediente principal é. Isso vale igualmente para produtos vendidos como "naturais" ou "sem aditivos": esses rótulos não dizem nada sobre o equipamento compartilhado.</p>

<h2>Perguntas frequentes</h2>
<p><strong>Castanhas a granel sem marca são mais arriscadas que as embaladas?</strong><br>
Podem ser, porque não há como rastrear a origem ou o processo; convém comprá-las em comércios de confiança ou preferir marcas com certificação visível.</p>
<p><strong>A manteiga de amendoim tem o mesmo risco?</strong><br>
Sim, e às vezes mais, porque costuma levar óleos e estabilizantes adicionais que também requerem verificação, além do amendoim em si.</p>
<p><strong>Castanhas ativadas ou deixadas de molho em casa mudam a análise?</strong><br>
Não, se a castanha de origem era crua e não processada industrialmente, deixá-la de molho em casa não introduz nenhum risco novo de kashrut.</p>

<h2>Para continuar lendo</h2>
<p>Para outro ingrediente onde o processamento industrial é a chave do problema, veja <a href="/articulos/gelatina-kosher">gelatina kosher: o debate halájico</a>. E para entender como ler todas as informações relevantes em um rótulo, veja <a href="/articulos/como-leer-etiqueta-kosher">como ler um rótulo de produto kosher</a>.</p>',
            ],
            'kashrut-y-veganismo' => [
                'title' => 'Kashrut e veganismo: comer vegano é o mesmo que comer kosher?',
                'excerpt' => 'Muitas pessoas assumem que um produto vegano é automaticamente kosher. A realidade é mais sutil.',
                'content' => '<p>É uma confusão comum: se um produto não contém nenhum ingrediente de origem animal, pareceria lógico assumir que é automaticamente kosher. No entanto, o kashrut não se baseia apenas nos ingredientes, mas também nos processos de elaboração, no equipamento utilizado e, em alguns casos, em quem supervisiona a produção.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kashrut-y-veganismo.jpg" alt="Um produto vegano não é automaticamente kosher: o kashrut também observa processos e equipamentos." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Um produto vegano não é automaticamente kosher: o kashrut também observa processos e equipamentos. Foto: HaJunkiyada via <a href="https://commons.wikimedia.org/wiki/File%3ALiat_Portal_for_Foodie_Disorder_-_Cauliflower_from_SF_farmers%27_market.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/jala-shabat.jpg" alt="Chalá trançada pronta para o Shabat, com a taça de vinho e o sal. O pano que a cobre faz parte do costume da mesa de Shabat." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Chalá trançada pronta para o Shabat, com a taça de vinho e o sal. O pano que a cobre faz parte do costume da mesa de Shabat. Foto: HaJunkiyada via <a href="https://commons.wikimedia.org/wiki/File:Liat_Portal_for_Foodie_Disorder_-_Challah_for_Shabbat_with_wine_and_salt.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Essa mitzvá se aplica ao sovar uma quantidade significativa de massa feita com um dos cinco grãos (trigo, cevada, aveia, centeio ou espelta) — a quantidade mínima exata (geralmente em torno de 1,2 kg de farinha) varia conforme a opinião halájica seguida.</p>
<p>O processo básico é:</p>
<ul>
<li>Sovar a massa de pão normalmente, até atingir a quantidade mínima exigida.</li>
<li>Separar uma pequena porção (tradicionalmente do tamanho de uma azeitona ou maior, conforme o costume).</li>
<li>Recitar a bênção correspondente antes de separar a porção.</li>
<li>Queimar a porção separada (envolta em papel alumínio, no forno) ou descartá-la de forma que não seja usada para consumo regular.</li>
</ul>
<p>Essa prática é a razão pela qual muitas padarias kosher industriais certificadas separam chalá como parte de seu processo de produção, e pela qual muitas mulheres e famílias judaicas a realizam em casa sempre que assam pão ou chalá para Shabat em quantidade suficiente.</p>
<p>Em muitas casas isso é vivido como um momento especial. Na nossa, por exemplo, as meninas sempre tentam chegar à quantidade mínima de massa justamente para poder separar a chalá com berachá, já que fazer isso com a bênção tem um valor a mais. Além do gesto técnico, acaba sendo um ritual familiar em torno do forno.</p>',
            ],
            'calendario-judio-festividades-alimentacion' => [
                'title' => 'O calendário judaico e as festividades que afetam a alimentação kosher',
                'excerpt' => 'Várias festividades judaicas têm costumes alimentares específicos, além das regras gerais do kashrut.',
                'content' => '<p>Além das normas de kashrut válidas o ano todo, o calendário judaico traz festividades com costumes alimentares próprios que vale a pena conhecer:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/calendario-judio-festividades-alimentacion.jpg" alt="Maçã, mel e romã: os símbolos de Rosh Hashaná, uma das festas com costumes alimentares próprios." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Maçã, mel e romã: os símbolos de Rosh Hashaná, uma das festas com costumes alimentares próprios. Foto: Gilabrand via <a href="https://commons.wikimedia.org/wiki/File%3ASymbols_of_Rosh_Hashana.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/errores-comunes-empezar-comer-kosher.jpg" alt="Ao começar, o erro mais comum é presumir que um produto é kosher sem procurar a certificação." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Ao começar, o erro mais comum é presumir que um produto é kosher sem procurar a certificação. Foto: Nenad Stojkovic via <a href="https://commons.wikimedia.org/wiki/File%3ACorn_in_a_shopping_trolley._%2851964905166%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/pulgon-en-hoja.jpg" alt="Un puceron sur une feuille, agrandi. Sur le légume réel il mesure un à trois millimètres : à peine un point sombre, d&#039;où la facilité de le manquer." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Un puceron sur une feuille, agrandi. Sur le légume réel il mesure un à trois millimètres : à peine un point sombre, d\'où la facilité de le manquer. Photo: WikiPedant via <a href="https://commons.wikimedia.org/wiki/File:Aphid_on_leaf05.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Les légumes-feuilles comme la laitue, les épinards, le brocoli et le chou-fleur demandent le plus de vigilance, car les pucerons et autres petits insectes se cachent entre les feuilles et sont difficiles à repérer à l\'œil nu. La technique habituelle consiste à séparer les feuilles, à les laver individuellement sous l\'eau courante et à les examiner à contre-jour.</p>
<ul>
<li><strong>Légumes-feuilles :</strong> séparer feuille par feuille et laver à l\'eau courante, en vérifiant les deux côtés.</li>
<li><strong>Chou-fleur et brocoli :</strong> faire tremper dans l\'eau avec un peu de savon ou de vinaigre pour détacher les insectes des bouquets.</li>
<li><strong>Fraises et framboises :</strong> tremper dans de l\'eau salée ou vinaigrée avant de rincer.</li>
<li><strong>Légumineuses sèches (lentilles, pois chiches) :</strong> étaler sur une surface claire et vérifier avant cuisson.</li>
</ul>
<p>Et il faut être honnête, ce n\'est pas aussi simple que ça en a l\'air. Chez nous, c\'est toujours la même personne qui se chargeait de vérifier les légumes. Avec les années, il a commencé à avoir besoin de lunettes, et un jour il a repéré un petit point noir sur une feuille. Convaincu que ce n\'était rien, il a demandé à sa fille ce que c\'était. Elle lui a répondu : tu ne vois pas que c\'est une bestiole, on voit même ses petites pattes ? Depuis ce jour, il ne vérifie plus une seule feuille sans ses lunettes. La leçon est simple : bien vérifier demande une bonne lumière et une bonne vue, et en cas de doute, un second regard ne fait jamais de mal.</p>
<p>De nombreux organismes de certification cachère proposent des guides illustrés détaillant comment vérifier chaque type de légume selon la région où il a été cultivé, car la prévalence des insectes varie selon le climat et la méthode de culture. Aujourd\'hui, il existe aussi des légumes "pré-vérifiés" ou cultivés sous supervision spécifique pour minimiser l\'infestation, ce qui simplifie beaucoup le processus au quotidien.</p>',
            ],
            'carne-y-leche' => [
                'title' => 'Viande et lait : pourquoi ils ne se mélangent pas',
                'excerpt' => 'La séparation entre viande et lait est l\'un des piliers les plus connus de la cacherout. Nous expliquons son origine, sa portée et son application pratique.',
                'content' => '<p>L\'interdiction de mélanger viande et lait repose sur un verset qui apparaît trois fois dans la Torah : "Tu ne cuiras pas un chevreau dans le lait de sa mère". La tradition orale a interprété cette phrase comme une triple interdiction : ne pas cuire, ne pas manger et ne tirer aucun bénéfice d\'un mélange de viande et de lait.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/carne-y-leche.jpg" alt="Une cuisine casher observante garde deux jeux complets d&#039;ustensiles, un pour la viande et un pour les produits laitiers." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Une cuisine casher observante garde deux jeux complets d\'ustensiles, un pour la viande et un pour les produits laitiers. Photo: PattayaPatrol via <a href="https://commons.wikimedia.org/wiki/File%3ADFC_2431_A_cook_flips_food_in_a_hot_pan_while_working_in_a_compact_well-used_kitchen_filled_with_pots_utensils_and_shelves_of_ingredients.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>En pratique, cela signifie que les aliments se divisent en trois catégories : <strong>carnés</strong> (viande et dérivés), <strong>lactés</strong> (lait et dérivés) et <strong>parve</strong> (neutres, comme les fruits, légumes, œufs et poisson, qui ne sont ni viande ni lait).</p>
<p>Une cuisine cachère pratiquante conserve des ustensiles, casseroles, assiettes et même lave-vaisselle séparés pour la viande et pour le lait, car la chaleur et l\'usage répété peuvent transférer saveurs et particules entre surfaces. De plus, un temps d\'attente est exigé entre manger de la viande et manger des produits laitiers — variable selon les coutumes familiales, généralement entre une et six heures — tandis que passer du lait à la viande nécessite seulement de se rincer la bouche et de manger quelque chose de neutre.</p>
<p>Cette séparation explique pourquoi tant d\'étiquettes de produits portent les lettres "D" (dairy/lacté), "M" (meat/carné) ou "Pareve" à côté du symbole de certification : cela permet au consommateur de savoir immédiatement dans quelle catégorie se situe un produit avant de le combiner avec d\'autres aliments.</p>',
            ],
            'kasherizar-horno' => [
                'title' => 'Comment cachériser un four',
                'excerpt' => 'Lorsqu\'un four a été utilisé avec des aliments non cachères, ou pour le faire passer d\'un usage carné à laitier, il existe un processus spécifique pour le rendre apte.',
                'content' => '<p>Cachériser un four est nécessaire dans plusieurs situations : lors de l\'achat d\'une maison avec un four utilisé précédemment de façon non cachère, pour changer l\'usage d\'un four (par exemple, de carné à laitier) ou avant Pessah, lorsqu\'il faut éliminer toute trace de hametz.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kasherizar-horno.jpg" alt="Le libun, la cachérisation du four par chaleur intense, exige un nettoyage complet et 24 heures sans utilisation." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Le libun, la cachérisation du four par chaleur intense, exige un nettoyage complet et 24 heures sans utilisation. Photo: Brandon Carson from San Carlos, USA via <a href="https://commons.wikimedia.org/wiki/File%3ADouble_oven.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/microondas-cocina.jpg" alt="Les éclaboussures s&#039;accumulent surtout au plafond de la cavité du micro-ondes, l&#039;endroit qu&#039;on regarde le moins en nettoyant." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Les éclaboussures s\'accumulent surtout au plafond de la cavité du micro-ondes, l\'endroit qu\'on regarde le moins en nettoyant. Photo: Tomwsulcer via <a href="https://commons.wikimedia.org/wiki/File:Stove_oven_combination_and_microwave_oven.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kasherizar-lavavajillas.jpg" alt="Le lave-vaisselle est l&#039;un des appareils les plus discutés : ses filtres et bras retiennent des résidus à haute température." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Le lave-vaisselle est l\'un des appareils les plus discutés : ses filtres et bras retiennent des résidus à haute température. Photo: Myke2020 via <a href="https://commons.wikimedia.org/wiki/File%3ACountertop_dishwasher_7882.JPG" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/hagala-utensilios-metal.jpg" alt="La hagalah consiste à immerger l&#039;ustensile dans l&#039;eau bouillante : comme il a absorbé, ainsi il expulse." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">La hagalah consiste à immerger l\'ustensile dans l\'eau bouillante : comme il a absorbé, ainsi il expulse. Photo: W.carter via <a href="https://commons.wikimedia.org/wiki/File%3ASteam-boiling_green_asparagus.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/vajilla-para-pesaj.jpg" alt="Beaucoup de familles ont un service de vaisselle réservé à Pessah, rangé le reste de l&#039;année." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Beaucoup de familles ont un service de vaisselle réservé à Pessah, rangé le reste de l\'année. Photo: Silar via <a href="https://commons.wikimedia.org/wiki/File%3A020210817_135854_Seder_plates%2C_brass_plate%2C_%C4%86miel%C3%B3w_porcelain%2C_19th-early_20th_century%2C_Category%2C_Passover_in_Galicia.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/jametz-pesaj.jpg" alt="Pendant Pessah, il est totalement interdit de consommer et même de posséder du hametz." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Pendant Pessah, il est totalement interdit de consommer et même de posséder du hametz. Photo: Ministry of Information Photo Division Photographer via <a href="https://commons.wikimedia.org/wiki/File%3AAllied_Forces_Celebrate_Passover-_Jewish_Traditions_in_Wartime_Britain%2C_April_1944_D19336.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>
<p>La Torah interdit non seulement de manger du hametz pendant Pessah, mais aussi d\'en posséder. C\'est pourquoi, dans les semaines précédant la fête, les familles juives effectuent un grand nettoyage de la maison (bedikat hametz) pour éliminer tout reste de pain, de farine ou de produits avec hametz des placards, voitures, sacs et tout coin où une miette aurait pu tomber.</p>
<p>Pour le hametz qui ne peut ou ne doit pas être jeté (par exemple, des produits coûteux ou difficiles à remplacer), il existe l\'option de le "vendre" symboliquement à une personne non juive par un contrat appelé <em>mekhirat hametz</em>, généralement coordonné par le rabbin de la communauté. Le hametz vendu est gardé fermé et à part pendant la fête et est "racheté" automatiquement à la fin de Pessah.</p>
<p>La nuit précédant Pessah, une recherche rituelle du hametz est effectuée dans toute la maison (bedikat hametz), généralement avec une bougie, une plume et une cuillère en bois, suivie de la combustion de ce qui a été trouvé (bi\'our hametz) le lendemain matin.</p>',
            ],
            'vino-kosher' => [
                'title' => 'Le vin cachère : pourquoi il nécessite une supervision spéciale',
                'excerpt' => 'Le vin a un statut particulier en halakha : pour être cachère, il doit être élaboré et manipulé exclusivement par des juifs pratiquants.',
                'content' => '<p>Il y a quelque temps, nous avons pu visiter un domaine à Mendoza qui produit du vin cachère, et voir le processus de près aide à comprendre pourquoi le vin a des règles si différentes de celles de tout autre aliment. Pour presque n\'importe quel autre produit, il suffit que les ingrédients et le processus respectent certaines exigences. Pas pour le vin : la halakha exige que toute personne le touchant pendant son élaboration, depuis l\'arrivée du raisin jusqu\'à la mise en bouteille, soit juive et pratiquante. La raison est historique : le vin était utilisé dans des rituels d\'idolâtrie, et c\'est de là que vient la restriction.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/barricas-roble-vino.jpg" alt="Chai à barriques d&#039;un domaine (image d&#039;illustration, ce n&#039;est pas le domaine de Mendoza que nous avons visité). Dans un domaine casher, chaque fût porte aussi un sceau certifiant que personne d&#039;étranger à la supervision ne l&#039;a ouvert." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Chai à barriques d\'un domaine (image d\'illustration, ce n\'est pas le domaine de Mendoza que nous avons visité). Dans un domaine casher, chaque fût porte aussi un sceau certifiant que personne d\'étranger à la supervision ne l\'a ouvert. Photo: Subhashish Panigrahi via <a href="https://commons.wikimedia.org/wiki/File:Oak_barrels_used_for_aging_of_wine_in_a_cellar_at_Grover_Zampa_Vineyard,_Doddaballapura,_Karnataka,_India.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>En pratique, cela crée une répartition des tâches assez particulière. Celui qui s\'y connaît en vin, c\'est l\'œnologue, qui en général n\'est pas juif (le goy), et c\'est lui qui indique quoi faire à chaque étape. Mais celui qui manipule effectivement le vin, ouvre les robinets et fait tout ce qui implique de toucher le produit, est toujours un juif pratiquant (le yehoudi). L\'expert dirige, le yehoudi exécute, et tout cela sous supervision rabbinique constante.</p>
<p>Le vin repose dans différents espaces selon l\'étape, et une partie est conservée dans des fûts de chêne, réutilisables environ trois fois avant de perdre leurs propriétés. Chaque fût est scellé, et ce sceau n\'est pas un détail mineur : c\'est la garantie que personne d\'extérieur n\'a touché le contenu. On nous a raconté un cas qui montre jusqu\'où va cette exigence. Dans un fût de plusieurs milliers de litres, on a remarqué qu\'il manquait le sceau exactement là où il aurait dû être fermé : il était ouvert. Le rabbin de Buenos Aires a dû venir vérifier la situation en personne, et il a statué que ce vin était resté sans supervision. Résultat : ces milliers de litres ne pouvaient plus être vendus comme cachères.</p>
<p>Il existe aussi une catégorie spéciale, le <strong>vin mevoushal</strong> ("bouilli"), un vin pasteurisé à une température précise. Une fois mevoushal, le vin conserve son statut cachère même s\'il est ensuite servi ou touché par une personne non juive. C\'est pourquoi il est si pratique pour les événements, restaurants et traiteurs, où l\'on ne peut pas contrôler qui prend chaque bouteille.</p>
<p>Aujourd\'hui, il existe de bons vins cachères de qualité dans presque toutes les régions viticoles du monde. Mendoza est un pôle important en Argentine, et on en produit aussi au Chili, en France, en Espagne, en Italie et bien sûr en Israël, certifiés par les principales agences rabbiniques.</p>',
            ],
            'gelatina-kosher' => [
                'title' => 'La gélatine cachère : le débat halakhique',
                'excerpt' => 'La gélatine est l\'un des ingrédients les plus débattus dans le monde de la cacherout, car son origine animale peut compromettre son statut.',
                'content' => '<p>La gélatine traditionnelle s\'obtient en faisant bouillir des os, de la peau et du tissu conjonctif d\'animaux — généralement des vaches ou des porcs — jusqu\'à en extraire le collagène. Cela pose deux problèmes du point de vue de la cacherout : l\'origine de l\'animal (est-ce une espèce cachère ?) et la méthode de transformation (l\'animal a-t-il été abattu selon la shehita ?).</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/gelatina-kosher.jpg" alt="Bonbons et desserts sont les produits où l&#039;on trouve le plus la gélatine, l&#039;ingrédient le plus débattu de la cacherout." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Bonbons et desserts sont les produits où l\'on trouve le plus la gélatine, l\'ingrédient le plus débattu de la cacherout. Photo: Sakurai Midori via <a href="https://commons.wikimedia.org/wiki/File%3ASweets_Offering_for_Obon.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/alcohol-bebidas-espirituosas.jpg" alt="Beaucoup de spiritueux sont cachers par leurs ingrédients, mais le vieillissement en fûts de vin peut changer leur statut." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Beaucoup de spiritueux sont cachers par leurs ingrédients, mais le vieillissement en fûts de vin peut changer leur statut. Photo: 4028mdk09 via <a href="https://commons.wikimedia.org/wiki/File%3ABarbestand.JPG" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/comer-kosher-restaurante.jpg" alt="Sans restaurant certifié à proximité, il existe des stratégies pour rester dans le cadre de la cacherout." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Sans restaurant certifié à proximité, il existe des stratégies pour rester dans le cadre de la cacherout. Photo: Sohail1308 via <a href="https://commons.wikimedia.org/wiki/File%3AAl_Fanar_Restaurant.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<ul>
<li><strong>Options végétariennes ou véganes :</strong> en éliminant viande et produits laitiers du plat, le risque diminue beaucoup, bien qu\'il faille toujours vérifier les ingrédients (bouillon de viande, beurre, sauces à base animale).</li>
<li><strong>Fruits et légumes crus :</strong> sans cuisson ni manipulation complexe, ils constituent souvent une option sûre presque partout.</li>
<li><strong>Poisson à nageoires et écailles :</strong> dans les restaurants à cuisine simple, un poisson grillé sans sauce peut être une alternative raisonnable pour ceux suivant un critère plus flexible (à condition qu\'il ne soit pas cuisiné avec des fruits de mer ou de la viande non cachère sur le même équipement, selon le critère de chacun).</li>
<li><strong>Boissons en bouteille et scellées :</strong> eau, sodas et jus dans leur emballage d\'origine ne posent généralement pas de problème.</li>
</ul>
<p>Voici un exemple bien concret de la façon dont on gère ça en pratique. Une fois, nous sommes partis en vacances à Mar del Plata avec les enfants encore petits, et en arrivant par l\'avenue Constitución, ma femme s\'est souvenue qu\'elle avait oublié les biscuits. En hiver, dans cette région, trouver du pain ou des biscuits cachères est pratiquement impossible. Qu\'est-ce qu\'on donnait aux enfants ? Chez nous, on n\'avait pas l\'habitude de manger certains snacks emballés, mais face à l\'urgence, on s\'est tourné vers la liste d\'Ajdut Kosher, un guide des produits de supermarché approuvés publié par le certificateur. On a cherché parmi les marques autorisées et on est tombé sur des biscuits type Traviatas qui figuraient sur la liste. Ça nous a sauvés.</p>
<p>La leçon : quand on voyage, avoir sous la main la liste des produits approuvés de son certificateur de confiance vaut de l\'or. Énormément de produits courants de supermarché sont cachères même sans grand sceau imprimé sur l\'emballage, et connaître cette liste ouvre des options là où il semblait n\'y en avoir aucune.</p>
<p>Chaque personne et chaque communauté a un niveau de rigueur différent quant à ce qui est considéré acceptable en dehors d\'un restaurant certifié (certains ne mangent que des produits emballés et scellés, d\'autres acceptent certaines préparations simples). En cas de doute, il est recommandé de consulter le rabbin de la congrégation sur le critère à suivre.</p>',
            ],
            'simbolos-certificacion-kosher' => [
                'title' => 'Les symboles de certification cachère les plus courants',
                'excerpt' => 'OU, OK, Star-K, KSA... il existe des dizaines de symboles de certification cachère dans le monde. Nous vous aidons à reconnaître les plus utilisés.',
                'content' => '<p>Lorsqu\'un produit passe par le processus de certification cachère, l\'agence certificatrice autorise l\'utilisation d\'un symbole (hekhsher) sur l\'emballage permettant de l\'identifier d\'un coup d\'œil. Il existe des centaines de certificateurs dans le monde, mais certains sont particulièrement connus pour leur portée mondiale.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/simbolos-certificacion-kosher.jpg" alt="Un hekhsher : le certificat de cacherout qu&#039;une agence rabbinique accorde à un établissement ou un produit." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Un hekhsher : le certificat de cacherout qu\'une agence rabbinique accorde à un établissement ou un produit. Photo: Utilisateur:Djampa - User:Djampa via <a href="https://commons.wikimedia.org/wiki/File%3AHechsher_Safed_Rabbinate.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<ul>
<li><strong>OU (Orthodox Union) :</strong> un "U" dans un cercle. C\'est probablement le symbole cachère le plus reconnu mondialement, basé aux États-Unis.</li>
<li><strong>OK Kosher Certification :</strong> un "K" dans un cercle, une autre grande agence américaine.</li>
<li><strong>Star-K :</strong> une étoile avec un "K" au centre.</li>
<li><strong>KSA (Kosher Supervision of America) :</strong> certificateur avec une forte présence dans les produits industriels.</li>
<li><strong>Badatz :</strong> sceau utilisé par plusieurs tribunaux rabbiniques en Israël, associé à des normes de rigueur très élevées.</li>
<li><strong>Certificateurs locaux :</strong> dans des pays comme l\'Argentine, le Brésil ou le Mexique, il existe des certificateurs communautaires locaux (comme le Va\'ad Hakashrut de chaque kehila) avec leurs propres sceaux.</li>
</ul>
<p>Outre le symbole, de nombreuses étiquettes incluent une lettre supplémentaire : "D" (dairy/lacté), "M" (meat/carné), "Pareve" (neutre) ou "DE" (dairy equipment, élaboré sur équipement laitier mais sans ingrédients laitiers directs). Connaître ces symboles facilite grandement les achats, surtout en voyageant dans des pays dont on ne maîtrise pas la langue.</p>
<p>Il faut préciser quelque chose qu\'on ne comprend pas toujours : un hekhsher n\'est pas une formalité qu\'on fait une fois et qui reste valable pour toujours. C\'est une supervision active et constante. Nous connaissons le cas d\'une boulangerie de Buenos Aires qui avait la certification d\'une agence communautaire. À un moment, le rabbin superviseur a remarqué des mouvements suspects et a envoyé des gens vérifier, presque comme un détective. Ils ont découvert qu\'elle achetait du dulce de leche sans supervision, alors qu\'elle devait utiliser uniquement des produits Halav Israël (produits laitiers élaborés sous supervision juive). On l\'a avertie et on lui a demandé de corriger. Peu après, sans savoir qu\'elle était surveillée de près, elle s\'est mise à acheter du fromage ordinaire sans certification, et ç\'a été la goutte de trop : sa supervision lui a été retirée. Tout a été géré discrètement, sans scandale, simplement en cessant de certifier l\'établissement.</p>
<p>La leçon pour le consommateur est claire : le sceau a de la valeur parce que derrière, quelqu\'un contrôle vraiment, tout le temps. C\'est pourquoi il vaut mieux faire confiance aux certificateurs reconnus et, face à un symbole inconnu, se renseigner dans la communauté avant de supposer qu\'un produit est cachère.</p>',
            ],
            'que-significa-pareve' => [
                'title' => 'Parve : que signifie ce terme si fréquent sur les étiquettes',
                'excerpt' => 'Parve est l\'un des mots les plus répétés dans l\'étiquetage cachère. Nous expliquons sa signification et pourquoi il est tant valorisé.',
                'content' => '<p>"Parve" (aussi orthographié pareve) décrit les aliments qui ne sont ni carnés ni laitiers : fruits, légumes, œufs, poisson, céréales et la plupart des produits élaborés sans ingrédients d\'origine animale laitière ou carnée.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/que-significa-pareve.jpg" alt="Fruits et légumes frais sont parvé par nature : ils se combinent avec la viande comme avec le lait." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Fruits et légumes frais sont parvé par nature : ils se combinent avec la viande comme avec le lait. Photo: PattayaPatrol via <a href="https://commons.wikimedia.org/wiki/File%3ADFC_2197_A_colorful_assortment_of_fresh_fruits_and_vegetables_-_apples_mango_dragon_fruit_kiwis_limes_bananas_and_more_-_arranged_on_a_wooden_crate.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Le grand avantage d\'un produit parve est sa flexibilité : il peut se combiner librement avec des repas tant carnés que laitiers, sans générer aucun conflit de cacherout. C\'est pourquoi de nombreuses industries alimentaires cherchent activement à développer des versions parve de produits traditionnellement laitiers — comme le chocolat, la margarine ou les substituts de crème — pour élargir leur marché.</p>
<p>Il est important de préciser une nuance : un aliment peut être parve par ses ingrédients, mais perdre ce statut s\'il a été élaboré sur un équipement traitant aussi des produits laitiers ou carnés, selon les traces pouvant subsister. C\'est pourquoi la certification n\'analyse pas seulement les ingrédients, mais aussi l\'équipement de production et les processus de nettoyage entre les lots.</p>
<p>Quelques exemples courants de produits parve : huile d\'olive, pâtes sèches sans œuf, la plupart des pains (bien que certains contiennent du beurre et deviennent laitiers), fruits secs non transformés, et boissons gazeuses. Toujours vérifier l\'étiquette est le seul moyen sûr de le confirmer, car la recette peut varier selon les marques ou même selon les présentations d\'une même marque.</p>',
            ],
            'shejita-sacrificio-kosher' => [
                'title' => 'La shehita : la méthode d\'abattage cachère',
                'excerpt' => 'Pour que la viande d\'un animal cachère soit apte à la consommation, elle doit être abattue selon une méthode rituelle spécifique appelée shehita.',
                'content' => '<p>La shehita est la méthode d\'abattage rituel juif, réalisée par un shohet (abatteur formé et certifié) utilisant un couteau extrêmement aiguisé et sans ébréchure, conçu spécifiquement pour produire une coupe rapide et précise sur la gorge de l\'animal, sectionnant la trachée et l\'œsophage en un seul mouvement continu.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/shejita-sacrificio-kosher.svg" alt="Le halef est vérifié avant et après chaque animal : une seule brèche invalide la procédure." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Le halef est vérifié avant et après chaque animal : une seule brèche invalide la procédure. Illustration : KosherMap.</figcaption>
</figure>
<p>L\'objectif de cette méthode est de minimiser la souffrance de l\'animal et de produire une perte de conscience pratiquement instantanée. Le shohet inspecte le couteau avant et après chaque abattage pour s\'assurer qu\'il n\'a aucune imperfection, aussi minime soit-elle, car toute irrégularité invalide la procédure.</p>
<p>Un membre de notre entourage travaille dans la shehita, ce qui donne une idée du caractère obsessionnel du contrôle en pratique. Le halaf (le couteau) n\'est pas seulement inspecté par le shohet avant et après chaque animal : il y a aussi un superviseur qui contrôle tous les couteaux, jour après jour, avec un critère extrêmement strict. Une imperfection à peine perceptible à l\'œil nu suffit à mettre un couteau hors service. C\'est un environnement d\'une rigueur et d\'une précision absolues, où face à toute situation inhabituelle, le premier réflexe est de tout arrêter et de faire de la sécurité la priorité.</p>
<p>Après la shehita, une inspection (bedika) des organes internes de l\'animal est effectuée, en particulier les poumons, pour écarter les maladies ou adhérences qui invalideraient la viande comme cachère. Seuls les animaux passant cette inspection sont considérés aptes.</p>
<p>De plus, la viande doit subir un processus de salage (kashering) pour extraire le sang, car la Torah interdit de consommer du sang. Cela se fait en faisant tremper la viande dans l\'eau, en la salant et en la laissant reposer avant de la rincer à nouveau — un processus généralement réalisé aujourd\'hui par la boucherie ou l\'abattoir certifié lui-même avant que le produit n\'atteigne le consommateur.</p>',
            ],
            'glatt-kosher' => [
                'title' => 'Glatt cachère : quelle différence avec le cachère ordinaire',
                'excerpt' => 'Le terme "glatt" apparaît fréquemment dans les boucheries et restaurants cachères. Nous expliquons le niveau de rigueur qu\'il représente.',
                'content' => '<p>"Glatt" signifie "lisse" en yiddish et désignait à l\'origine spécifiquement l\'état des poumons d\'un animal après la shehita : si les poumons ne présentaient aucune adhérence (sircha), l\'animal était considéré "glatt", le niveau le plus élevé de certitude que la viande est cachère sans aucun doute.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/glatt-kosher.jpg" alt="Un restaurant de viande casher à Jérusalem. « Glatt » désigne le standard d&#039;inspection le plus strict." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Un restaurant de viande casher à Jérusalem. « Glatt » désigne le standard d\'inspection le plus strict. Photo: brionv from San Francisco, United States via <a href="https://commons.wikimedia.org/wiki/File%3AJerusalem_MMMM_MEAT_%286036353902%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 2.0.</figcaption>
</figure>
<p>Avec le temps, particulièrement dans les communautés ashkénazes des États-Unis, le terme "glatt kosher" s\'est étendu familièrement pour décrire un standard général de plus grande rigueur dans toute la chaîne de production d\'un aliment, pas seulement dans l\'inspection des poumons. Aujourd\'hui, il est courant de voir "glatt kosher" sur les étiquettes de restaurants et de produits pour indiquer qu\'ils respectent les critères les plus exigeants possibles.</p>
<p>Il est important de souligner qu\'un produit "cachère" sans l\'étiquette "glatt" n\'est pas moins valide halakhiquement : il suit simplement un standard de certification différent, généralement accepté par la grande majorité des communautés. Le choix entre cachère standard et glatt kosher dépend généralement de la coutume familiale ou communautaire, plus que d\'une différence objective de validité.</p>
<p>Pour la volaille et le poisson, le concept de "glatt" ne s\'applique techniquement pas de la même manière que pour les mammifères, bien qu\'il soit parfois utilisé familièrement pour indiquer un niveau de supervision plus rigoureux en général.</p>',
            ],
            'como-leer-etiqueta-kosher' => [
                'title' => 'Comment lire une étiquette de produit cachère',
                'excerpt' => 'Au-delà du symbole de certification, les étiquettes cachères contiennent des informations clés pour savoir si un produit convient à votre table.',
                'content' => '<p>Bien lire une étiquette cachère va au-delà de chercher le symbole de certification. Plusieurs éléments méritent toujours d\'être vérifiés :</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/como-leer-etiqueta-kosher.jpg" alt="L&#039;étiquette d&#039;un produit israélien : au-delà du sceau, il faut regarder la catégorie et les ingrédients." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">L\'étiquette d\'un produit israélien : au-delà du sceau, il faut regarder la catégorie et les ingrédients. Photo: Chenspec via <a href="https://commons.wikimedia.org/wiki/File%3AList_of_ingredients_of_food_products_in_Israel_02.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/bishul-akum.jpg" alt="Le bichoul akoum régit les aliments cuits entièrement par un non-Juif, même avec des ingrédients cachers." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Le bichoul akoum régit les aliments cuits entièrement par un non-Juif, même avec des ingrédients cachers. Photo: Seattle Municipal Archives from Seattle, WA via <a href="https://commons.wikimedia.org/wiki/File%3ACooks_in_kitchen%2C_1930_%2820981103874%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/vino-mevushal.jpg" alt="Le vin mevouchal est pasteurisé : il garde son statut casher même servi par un non-Juif." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Le vin mevouchal est pasteurisé : il garde son statut casher même servi par un non-Juif. Photo: misbehave via <a href="https://commons.wikimedia.org/wiki/File%3ABottle_%26_glass_of_red_Bordeaux_style_blend.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/tevilat-kelim.jpg" alt="Un mikvé, le bain rituel où l&#039;on immerge les ustensiles neufs avant leur première utilisation." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Un mikvé, le bain rituel où l\'on immerge les ustensiles neufs avant leur première utilisation. Photo: Stefan Walkowski via <a href="https://commons.wikimedia.org/wiki/File%3AMikveh.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Cette coutume s\'applique principalement aux ustensiles en contact direct avec la nourriture : casseroles, poêles, couverts, assiettes en verre et verres. Elle ne s\'applique généralement pas aux appareils électriques (comme un grille-pain ou un mixeur) ni aux ustensiles en plastique ou en bois, bien que les avis varient selon la tradition de chaque communauté, c\'est pourquoi il est recommandé de consulter un rabbin pour des cas spécifiques.</p>
<p>Le processus en lui-même est simple : l\'ustensile est bien nettoyé (sans restes d\'étiquettes, scellés ou adhésifs), immergé complètement dans l\'eau du mikvé pendant que l\'on récite une bénédiction, et il est ensuite prêt à être utilisé normalement.</p>
<p>De nombreux mikvaot communautaires ont un horaire spécifique réservé à la tevilat kelim, séparé de l\'usage rituel personnel, ainsi que des instructions détaillées sur les matériaux nécessitant l\'immersion et ceux qui n\'en ont pas besoin. C\'est l\'une de ces pratiques qui, bien que paraissant un détail mineur, fait partie intégrante de la façon dont de nombreuses familles juives pratiquantes équipent leur cuisine.</p>',
            ],
            'armar-cocina-kosher' => [
                'title' => 'Comment organiser une cuisine cachère à partir de zéro',
                'excerpt' => 'Commencer à tenir une cuisine cachère peut sembler accablant au début. Voici un guide pratique des premiers pas.',
                'content' => '<p>Organiser une cuisine cachère à partir de zéro est un processus graduel, et il n\'est pas nécessaire de tout résoudre en un jour. Voici les étapes les plus courantes suivies par les familles qui débutent :</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/armar-cocina-kosher.jpg" alt="Aménager une cuisine casher est un processus graduel : inutile de tout régler en un jour." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Aménager une cuisine casher est un processus graduel : inutile de tout régler en un jour. Photo: MeRyan via <a href="https://commons.wikimedia.org/wiki/File%3AKitchen_with_island%2C_New_Orleans_2007.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/certificaciones-kosher-mundo.svg" alt="Les principes de la cacherout sont universels, mais chaque région a ses propres agences de certification." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Les principes de la cacherout sont universels, mais chaque région a ses propres agences de certification. Illustration : KosherMap.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/queso-kosher-cuajo.jpg" alt="Le fromage exige une certification spécifique à cause de l&#039;origine de la présure qui coagule le lait." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Le fromage exige une certification spécifique à cause de l\'origine de la présure qui coagule le lait. Photo: Daderot via <a href="https://commons.wikimedia.org/wiki/File%3ACheese_display%2C_Cambridge_MA_-_DSC05391.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/huevos-kosher.jpg" alt="Les œufs sont parvé, mais il faut les vérifier pour des taches de sang avant utilisation." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Les œufs sont parvé, mais il faut les vérifier pour des taches de sang avant utilisation. Photo: Evan-Amos via <a href="https://commons.wikimedia.org/wiki/File%3A6-Pack-Chicken-Eggs.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/pescado-kosher-aletas-escamas.jpg" alt="Pour être casher, un poisson doit avoir des nageoires et des écailles visibles." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Pour être casher, un poisson doit avoir des nageoires et des écailles visibles. Photo: IndayLiburan via <a href="https://commons.wikimedia.org/wiki/File%3AFish_stalls_at_Valencia_Public_Market_01.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/frutos-secos-contaminacion-cruzada.jpg" alt="Crus, ils sont parvé sans restriction ; le risque apparaît lors de la transformation industrielle." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Crus, ils sont parvé sans restriction ; le risque apparaît lors de la transformation industrielle. Photo: Famartin via <a href="https://commons.wikimedia.org/wiki/File%3A2021-01-06_12_15_43_Cranberry_trail_mix_with_cranberries%2C_peanuts%2C_raisins%2C_walnuts%2C_almonds%2C_sunflower_seeds%2C_pepitas_in_the_Franklin_Farm_section_of_Oak_Hill%2C_Fairfax_County%2C_Virginia.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kashrut-y-veganismo.jpg" alt="Un produit végane n&#039;est pas automatiquement casher : la cacherout regarde aussi procédés et équipements." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Un produit végane n\'est pas automatiquement casher : la cacherout regarde aussi procédés et équipements. Photo: HaJunkiyada via <a href="https://commons.wikimedia.org/wiki/File%3ALiat_Portal_for_Foodie_Disorder_-_Cauliflower_from_SF_farmers%27_market.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/jala-shabat.jpg" alt="Halla tressée prête pour Chabbat, avec la coupe de vin et le sel. Le linge qui la couvre fait partie de la coutume de la table de Chabbat." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Halla tressée prête pour Chabbat, avec la coupe de vin et le sel. Le linge qui la couvre fait partie de la coutume de la table de Chabbat. Photo: HaJunkiyada via <a href="https://commons.wikimedia.org/wiki/File:Liat_Portal_for_Foodie_Disorder_-_Challah_for_Shabbat_with_wine_and_salt.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Cette mitsva s\'applique lors du pétrissage d\'une quantité significative de pâte faite avec l\'une des cinq céréales (blé, orge, avoine, seigle ou épeautre) — la quantité minimale exacte (généralement autour de 1,2 kg de farine) varie selon l\'avis halakhique suivi.</p>
<p>Le processus de base est :</p>
<ul>
<li>Pétrir la pâte à pain normalement, jusqu\'à atteindre la quantité minimale requise.</li>
<li>Prélever une petite portion (traditionnellement de la taille d\'une olive ou plus, selon la coutume).</li>
<li>Réciter la bénédiction correspondante avant de prélever la portion.</li>
<li>Brûler la portion prélevée (enveloppée dans du papier aluminium, au four) ou la jeter d\'une manière qu\'elle ne soit pas utilisée pour une consommation régulière.</li>
</ul>
<p>Cette pratique explique pourquoi de nombreuses boulangeries cachères industrielles certifiées prélèvent la halla dans le cadre de leur processus de production, et pourquoi de nombreuses femmes et familles juives la réalisent à la maison chaque fois qu\'elles font cuire du pain ou de la halla pour Chabbat en quantité suffisante.</p>
<p>Dans de nombreux foyers, ce moment est vécu comme quelque chose de spécial. Chez nous, par exemple, les filles essaient toujours d\'atteindre la quantité minimale de pâte précisément pour pouvoir prélever la halla avec une bénédiction, car le faire avec la berakha a une valeur supplémentaire. Au-delà du geste technique, cela finit par devenir un rituel familial autour du four.</p>',
            ],
            'calendario-judio-festividades-alimentacion' => [
                'title' => 'Le calendrier juif et les fêtes qui affectent l\'alimentation cachère',
                'excerpt' => 'Plusieurs fêtes juives ont des coutumes alimentaires spécifiques, au-delà des règles générales de la cacherout.',
                'content' => '<p>Outre les règles de cacherout valables toute l\'année, le calendrier juif apporte des fêtes avec des coutumes alimentaires propres qu\'il vaut la peine de connaître :</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/calendario-judio-festividades-alimentacion.jpg" alt="Pomme, miel et grenade : les symboles de Roch Hachana, l&#039;une des fêtes aux coutumes alimentaires propres." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Pomme, miel et grenade : les symboles de Roch Hachana, l\'une des fêtes aux coutumes alimentaires propres. Photo: Gilabrand via <a href="https://commons.wikimedia.org/wiki/File%3ASymbols_of_Rosh_Hashana.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>
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

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/errores-comunes-empezar-comer-kosher.jpg" alt="Au début, l&#039;erreur la plus fréquente est de supposer qu&#039;un produit est casher sans chercher la certification." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Au début, l\'erreur la plus fréquente est de supposer qu\'un produit est casher sans chercher la certification. Photo: Nenad Stojkovic via <a href="https://commons.wikimedia.org/wiki/File%3ACorn_in_a_shopping_trolley._%2851964905166%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
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

    private static function he(): array
    {
        return [
            'insectos-frutas-verduras' => [
                'title' => 'חרקים בפירות וירקות: איך לבדוק אותם כראוי',
                'excerpt' => 'התורה אוסרת אכילת חרקים, ולכן בדיקת פירות וירקות לפני אכילתם היא חלק מרכזי בשמירת מטבח כשר.',
                'content' => '<p>איסור אכילת שרצים הוא מהחמורים שבתורה: בניגוד לאיסורי מאכל אחרים, אכילת חרק אחד יכולה להיחשב כעבירות מרובות. לכן בדיקת פירות וירקות לפני בישול או הגשה היא שלב הכרחי במטבח כשר.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/pulgon-en-hoja.jpg" alt="כנימת עלה בהגדלה. על הירק עצמו היא מילימטר עד שלושה: כמעט נקודה כהה בלבד, ולכן קל כל כך לפספס אותה." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">כנימת עלה בהגדלה. על הירק עצמו היא מילימטר עד שלושה: כמעט נקודה כהה בלבד, ולכן קל כל כך לפספס אותה. צילום: WikiPedant דרך <a href="https://commons.wikimedia.org/wiki/File:Aphid_on_leaf05.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>ירקות עלים כמו חסה, תרד, ברוקולי וכרובית דורשים את הזהירות הרבה ביותר, כי כנימות וחרקים קטנים אחרים מסתתרים בין העלים וקשה לאתרם בעין בלתי מזוינת. הטכניקה המקובלת כוללת הפרדת העלים, שטיפתם בנפרד תחת זרם מים ובדיקתם כנגד האור.</p>
<ul>
<li><strong>ירקות עלים:</strong> להפריד עלה עלה ולשטוף תחת מים זורמים, תוך בדיקת שני הצדדים.</li>
<li><strong>כרובית וברוקולי:</strong> להשרות במים עם מעט סבון או חומץ כדי שהחרקים יתנתקו מהפרחים.</li>
<li><strong>תותים ופטל:</strong> להשרות במי מלח או חומץ לפני השטיפה.</li>
<li><strong>קטניות יבשות (עדשים, חומוס):</strong> לפזר על משטח בהיר ולבדוק לפני הבישול.</li>
</ul>
<p>וחשוב להיות כנים: זה לא כל כך פשוט כמו שזה נשמע. אצלנו בבית, בדיקת הירקות תמיד נפלה על אדם מסוים במשפחה. עם השנים הוא התחיל להזדקק למשקפיים, ויום אחד הבחין בנקודה שחורה קטנה על עלה. בטוח שזה כלום, הוא שאל את בתו מה זה. והיא ענתה לו: אתה לא רואה שזה חרק, רואים לו את הרגליים הקטנות? מאז אותו יום הוא לא בודק עלה אחד בלי המשקפיים. הלקח פשוט: בדיקה טובה דורשת אור טוב וראייה טובה, ובמקרה של ספק, מבט נוסף אף פעם לא מזיק.</p>
<p>כשרויות רבות מציעות מדריכים מפורטים ומאוירים כיצד לבדוק כל סוג ירק בהתאם לאזור הגידול, מאחר ושכיחות החרקים משתנה לפי האקלים ושיטת הגידול. כיום קיימים גם ירקות "בדוקים מראש" או מגודלים תחת השגחה מיוחדת כדי לצמצם את ההדבקה, מה שמפשט מאוד את התהליך במטבח היומיומי.</p>',
            ],
            'carne-y-leche' => [
                'title' => 'בשר וחלב: למה אסור לערבב',
                'excerpt' => 'הפרדת בשר וחלב היא אחד היסודות המוכרים ביותר של הכשרות. נסביר את מקורה, היקפה ויישומה בפועל.',
                'content' => '<p>איסור בישול בשר וחלב יחד מבוסס על פסוק שמופיע שלוש פעמים בתורה: "לא תבשל גדי בחלב אמו". התורה שבעל פה פירשה משפט זה כאיסור משולש: לא לבשל, לא לאכול ולא ליהנות מתערובת בשר וחלב.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/carne-y-leche.jpg" alt="מטבח כשר מקפיד על שני סטים נפרדים של כלים: אחד לבשרי ואחד לחלבי." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">מטבח כשר מקפיד על שני סטים נפרדים של כלים: אחד לבשרי ואחד לחלבי. צילום: PattayaPatrol דרך <a href="https://commons.wikimedia.org/wiki/File%3ADFC_2431_A_cook_flips_food_in_a_hot_pan_while_working_in_a_compact_well-used_kitchen_filled_with_pots_utensils_and_shelves_of_ingredients.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>בפועל, זה אומר שהמזון מתחלק לשלוש קטגוריות: <strong>בשרי</strong> (בשר ונגזרותיו), <strong>חלבי</strong> (חלב ונגזרותיו) ו<strong>פרווה</strong> (ניטרלי, כמו פירות, ירקות, ביצים ודגים, שאינם בשר ואינם חלב).</p>
<p>מטבח כשר שומר כלים, סירים, צלחות ואפילו מדיח כלים נפרדים לבשר ולחלב, מכיוון שחום ושימוש חוזר עלולים להעביר טעמים וחלקיקים בין משטחים. בנוסף, נדרש זמן המתנה בין אכילת בשר לאכילת חלבי — המשתנה לפי מנהג המשפחה, בדרך כלל בין שעה לשש שעות — בעוד שמחלבי לבשרי מספיק לשטוף את הפה ולאכול משהו ניטרלי.</p>
<p>הפרדה זו היא הסיבה שעל תוויות מוצרים רבות מופיעות האותיות "D" (חלבי), "M" (בשרי) או "Pareve" (פרווה) לצד סמל הכשרות: זה מאפשר לצרכן לדעת מיד לאיזו קטגוריה שייך המוצר לפני שילובו עם מאכלים אחרים.</p>',
            ],
            'kasherizar-horno' => [
                'title' => 'איך מכשירים תנור',
                'excerpt' => 'כאשר תנור שימש למאכלים לא כשרים, או רוצים להעביר אותו משימוש בשרי לחלבי, קיים תהליך ספציפי להפיכתו לכשר.',
                'content' => '<p>הכשרת תנור נחוצה במספר מצבים: בקניית בית עם תנור שהיה בשימוש לא כשר בעבר, כאשר רוצים לשנות את ייעוד התנור (למשל מבשרי לחלבי), או לפני פסח, כאשר יש להסיר כל שמץ חמץ.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kasherizar-horno.jpg" alt="ליבון התנור בחום גבוה מחייב ניקוי יסודי מראש ו-24 שעות ללא שימוש." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">ליבון התנור בחום גבוה מחייב ניקוי יסודי מראש ו-24 שעות ללא שימוש. צילום: Brandon Carson from San Carlos, USA דרך <a href="https://commons.wikimedia.org/wiki/File%3ADouble_oven.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<p>השיטה המסורתית לתנורים נקראת <em>ליבון</em> (ניקוי עצמי בחום עז) וכוללת:</p>
<ul>
<li>ניקוי יסודי של התנור, הסרת כל לכלוך ושאריות מזון נראות לעין.</li>
<li>אי שימוש בתנור במשך 24 שעות לפני ההכשרה.</li>
<li>הפעלת התנור בטמפרטורה הגבוהה ביותר האפשרית (רצוי באמצעות פונקציית הניקוי העצמי, אם קיימת) למשך שעה לפחות.</li>
</ul>
<p>רשתות ותבניות מתכת ניתן בדרך כלל להכשיר בנפרד באמצעות הגעלה במים רותחים, בעוד שמשטחי זכוכית או אמייל בדרך כלל דורשים ליבון מאחר שהם חומרים סופגים יותר.</p>
<p>חשוב להתייעץ עם רב לפני הכשרת תנור מסוים, מכיוון שההליך המדויק עשוי להשתנות בהתאם לחומר, לדגם ולמנהג של כל קהילה. תנורים מודרניים מסוימים עם ציפויים מיוחדים עלולים שלא להתאים לליבון בטמפרטורה גבוהה, כדאי לבדוק את הוראות היצרן.</p>',
            ],
            'kasherizar-microondas' => [
                'title' => 'איך מכשירים מיקרוגל',
                'excerpt' => 'למיקרוגל יש תהליך הכשרה שונה מתנור רגיל, מכיוון שהוא מבשל באמצעות אדים ולא בחום יבש.',
                'content' => '<p>בניגוד לתנור רגיל, המיקרוגל מחמם מזון על ידי יצירת אדים בפנים, מה שמשנה את שיטת ההכשרה המומלצת על ידי רוב הפוסקים.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/microondas-cocina.jpg" alt="התזות מצטברות בעיקר בתקרת תא המיקרוגל, המקום שהכי פחות מסתכלים בו בזמן ניקוי." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">התזות מצטברות בעיקר בתקרת תא המיקרוגל, המקום שהכי פחות מסתכלים בו בזמן ניקוי. צילום: Tomwsulcer דרך <a href="https://commons.wikimedia.org/wiki/File:Stove_oven_combination_and_microwave_oven.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>
<p>התהליך הנפוץ ביותר כולל:</p>
<ul>
<li>ניקוי יסודי של הפנים, הסרת כל חלקיק מזון נראה לעין, כולל הצלחת המסתובבת והדפנות.</li>
<li>אי שימוש במיקרוגל במשך 24 שעות לפני ההכשרה.</li>
<li>הנחת כלי מים בתוך המיקרוגל והפעלתו עד שהמים רותחים ויוצרים מספיק אדים לכסות את כל המשטחים הפנימיים, כולל הדלת.</li>
<li>לתת לאדים לפעול על הדפנות במשך מספר דקות.</li>
</ul>
<p>משפחות רבות בוחרות גם להשתמש תמיד במכסה או ניילון מתאים למיקרוגל בעת חימום מזון, ולייעד את המכשיר לשימוש יחיד (בשרי, חלבי או פרווה) כדי להימנע מהכשרתו שוב ושוב. מיקרוגלים עם פונקציית גריל או קונבקציה עשויים לדרוש תהליך נוסף בדומה לתנור רגיל עבור פונקציה זו. כמו בכל הכשרה, מומלץ להתייעץ עם רב לגבי הדגם והחומר הספציפיים של המכשיר.</p>',
            ],
            'kasherizar-lavavajillas' => [
                'title' => 'איך מכשירים מדיח כלים',
                'excerpt' => 'משפחות רבות משתמשות במדיח הכלים לכלים בשריים וחלביים במחזורים נפרדים. נספר מה נדרש כדי להכשיר אותו.',
                'content' => '<p>מדיח הכלים מציב אתגר מיוחד מכיוון שדפנותיו הפנימיות, המסננים וזרועות ההתזה נמצאים במגע מתמיד עם שאריות מזון בטמפרטורה גבוהה, מה שעלול לספוג טעמים בעקביות רבה יותר ממכשירים אחרים.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kasherizar-lavavajillas.jpg" alt="המדיח הוא מהמכשירים השנויים במחלוקת: המסננים והזרועות אוגרים שאריות בחום גבוה." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">המדיח הוא מהמכשירים השנויים במחלוקת: המסננים והזרועות אוגרים שאריות בחום גבוה. צילום: Myke2020 דרך <a href="https://commons.wikimedia.org/wiki/File%3ACountertop_dishwasher_7882.JPG" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>
<p>לכן, פוסקים רבים מחמירים יותר לגבי הכשרת מדיחי כלים מאשר מכשירים אחרים, וחלקם ממליצים שלא להשתמש בו לשתי הקטגוריות (בשרי וחלבי) כלל, אפילו בימים שונים. אלו המתירים, בדרך כלל דורשים:</p>
<ul>
<li>ניקוי יסודי של מסננים, זרועות התזה ואטמי גומי.</li>
<li>אי שימוש במדיח במשך 24 שעות לפני ההכשרה.</li>
<li>הפעלת מחזור שלם ריק, בטמפרטורה הגבוהה ביותר האפשרית, רצוי עם חומר ניקוי חזק.</li>
<li>בקהילות מסוימות, מומלץ להשתמש בסלסילות או מגשים נפרדים וניתנים להחלפה לבשרי ולחלבי, במקום להכשיר את כל המכשיר בין השימושים.</li>
</ul>
<p>מכיוון שהמנהגים משתנים מאוד בנושא זה — קהילות ספרדיות ואשכנזיות מסוימות נבדלות בצורה ניכרת — זהו אחד המקרים שכדאי במיוחד להתייעץ ישירות עם רב הקהילה לפני שמחליטים כיצד לארגן את המטבח.</p>',
            ],
            'hagala-utensilios-metal' => [
                'title' => 'איך מכשירים כלי מתכת (הגעלה)',
                'excerpt' => 'הגעלה היא השיטה המסורתית של טבילה במים רותחים להכשרת סירים, סכו"ם וכלי מתכת אחרים.',
                'content' => '<p>הגעלה היא אחת משיטות ההכשרה העתיקות ביותר ומשמשת בעיקר בכלי מתכת שחוממו ישירות באש או בנוזל רותח, כמו סירים, סכו"ם, מחבתות (ללא ציפוי אנטי-דביק) וכלי מטבח נוספים.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/hagala-utensilios-metal.jpg" alt="הגעלה היא הטבלת הכלי במים רותחים: כבולעו כך פולטו." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">הגעלה היא הטבלת הכלי במים רותחים: כבולעו כך פולטו. צילום: W.carter דרך <a href="https://commons.wikimedia.org/wiki/File%3ASteam-boiling_green_asparagus.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>
<p>העיקרון מאחורי ההגעלה הוא "כבולעו כך פולטו": אם כלי ספג טעם לא כשר (או בשרי/חלבי) באמצעות נוזל רותח, הוא מיטהר באותה הדרך, על ידי טבילה במים רותחים.</p>
<p>ההליך הבסיסי הוא:</p>
<ul>
<li>לנקות את הכלי ביסודיות, ללא חלודה, מזון דבוק או לכלוך מוצק.</li>
<li>להמתין 24 שעות ללא שימוש בכלי לפני ההגעלה.</li>
<li>להרתיח סיר גדול של מים עד לרתיחה מלאה.</li>
<li>לטבול את הכלי במלואו במים הרותחים, כך שכל משטחיו יבואו במגע עם המים בטמפרטורה זו.</li>
<li>להוציאו בעזרת כלי שלא היה במגע עם מזון לא כשר, ולשטוף אותו במים קרים.</li>
</ul>
<p>כלים עם ידית מעץ או פלסטיק, או עם חלקים מודבקים בדבק שאינו עמיד במים רותחים, בדרך כלל אינם מתאימים להגעלה ודורשים שיטה אחרת, או שפשוט אינם ניתנים להכשרה. מחבתות אנטי-דביקות (טפלון) גם אינן מוכשרות בדרך כלל בהגעלה, מכיוון שהציפוי ניזוק מהחום.</p>',
            ],
            'vajilla-para-pesaj' => [
                'title' => 'כלים לפסח: כל מה שצריך לדעת',
                'excerpt' => 'בפסח חלים כללים מחמירים יותר מכל השנה לגבי כלי מטבח, בשל איסור החמץ.',
                'content' => '<p>פסח הוא החג עם כללי הכשרות המחמירים ביותר בלוח השנה היהודי, מכיוון שבנוסף לכללי הכשרות הרגילים, מתווסף איסור מוחלט על אכילת או החזקת חמץ (מוצרים מותססים מחמשת מיני הדגן: חיטה, שעורה, שיבולת שועל, שיפון וכוסמין).</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/vajilla-para-pesaj.jpg" alt="משפחות רבות מחזיקות מערכת כלים ייעודית לפסח, שמאוחסנת כל השנה." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">משפחות רבות מחזיקות מערכת כלים ייעודית לפסח, שמאוחסנת כל השנה. צילום: Silar דרך <a href="https://commons.wikimedia.org/wiki/File%3A020210817_135854_Seder_plates%2C_brass_plate%2C_%C4%86miel%C3%B3w_porcelain%2C_19th-early_20th_century%2C_Category%2C_Passover_in_Galicia.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>מכיוון שהחמץ עשוי היה להיות במגע עם סירים, צלחות וסכו"ם לאורך כל השנה, משפחות רבות בוחרות להחזיק מערכת כלים נפרדת, ייעודית לפסח, המאוחסנת שאר השנה. זוהי האפשרות הפשוטה ביותר והיא נמנעת מהצורך להכשיר מדי שנה.</p>
<p>למי שאין כלים נפרדים לפסח, ניתן להכשיר כלים מסוימים:</p>
<ul>
<li><strong>מתכת ללא ציפוי</strong> (סירים, סכו"ם): בדרך כלל מתאים להגעלה.</li>
<li><strong>זכוכית</strong>: לפי המנהג, יש הסוברים שדי בשטיפה טובה, ויש הדורשים טבילה.</li>
<li><strong>קרמיקה וחרסינה</strong>: בדרך כלל אינן ניתנות להכשרה לפסח ויש להשתמש בערכה נפרדת.</li>
<li><strong>פלסטיק וגומי</strong>: רוב הדעות אינן מתירות הכשרתם.</li>
</ul>
<p>לפני פסח כדאי להתייעץ עם מדריך ההכשרה הספציפי של הקהילה או הכשרות המקומית, מכיוון שהמועדים והשיטות המדויקות עשויים להשתנות בהתאם לסוג החומר ולשימוש שנעשה בכלי במהלך השנה.</p>',
            ],
            'jametz-pesaj' => [
                'title' => 'חמץ: מהו וכיצד מסלקים אותו לפני פסח',
                'excerpt' => 'חמץ הוא המאכל המותסס האסור בפסח. הכרת המוצרים שמכילים אותו היא המפתח להכנת החג.',
                'content' => '<p>חמץ הוא כל מוצר העשוי מאחד מחמשת מיני הדגן — חיטה, שעורה, שיבולת שועל, שיפון או כוסמין — שבא במגע עם מים ותסס (תפח) במשך יותר מ-18 דקות מבלי להיאפות. זה כולל לחם, בירה, רוב סוגי הפסטה, עוגיות וכמות עצומה של מוצרים תעשייתיים המשתמשים בדגנים אלה כמרכיב או נגזרת.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/jametz-pesaj.jpg" alt="בפסח חל איסור מוחלט גם לאכול וגם להחזיק חמץ." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">בפסח חל איסור מוחלט גם לאכול וגם להחזיק חמץ. צילום: Ministry of Information Photo Division Photographer דרך <a href="https://commons.wikimedia.org/wiki/File%3AAllied_Forces_Celebrate_Passover-_Jewish_Traditions_in_Wartime_Britain%2C_April_1944_D19336.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>
<p>התורה אוסרת לא רק לאכול חמץ בפסח, אלא גם להחזיק בו. לכן, בשבועות שלפני החג, משפחות יהודיות עורכות ניקיון יסודי של הבית (בדיקת חמץ) כדי לסלק כל שריד של לחם, קמח או מוצרי חמץ מהארונות, מהרכבים, מהתיקים ומכל פינה שאליה הייתה עשויה ליפול פירור.</p>
<p>עבור חמץ שאי אפשר או לא כדאי לזרוק (למשל, מוצרים יקרים או קשים להחלפה), קיימת האפשרות "למכור" אותו באופן סמלי לאדם לא יהודי באמצעות חוזה הנקרא <em>מכירת חמץ</em>, המתואם בדרך כלל על ידי רב הקהילה. החמץ הנמכר נשמר סגור ובנפרד במהלך החג ו"נקנה בחזרה" אוטומטית בתום פסח.</p>
<p>בלילה שלפני פסח מתבצע חיפוש טקסי אחר חמץ בכל הבית (בדיקת חמץ), בדרך כלל עם נר, נוצה וכף עץ, ולאחריו שריפת מה שנמצא (ביעור חמץ) למחרת בבוקר.</p>',
            ],
            'vino-kosher' => [
                'title' => 'יין כשר: למה הוא דורש השגחה מיוחדת',
                'excerpt' => 'ליין מעמד מיוחד בהלכה: כדי שיהיה כשר, הוא חייב להיוצר ולהיות מטופל באופן בלעדי על ידי יהודים שומרי מצוות.',
                'content' => '<p>לפני זמן מה הזדמן לנו לבקר ביקב במנדוסה שמייצר יין כשר, וראיית התהליך מקרוב עוזרת להבין למה ליין יש כללים כה שונים משאר המאכלים. כמעט בכל מוצר אחר מספיק שהמרכיבים והתהליך יעמדו בדרישות מסוימות. ביין לא: ההלכה דורשת שכל אדם הנוגע בו במהלך הייצור, מרגע קטיף הענבים ועד הבקבוק, יהיה יהודי ושומר מצוות. הסיבה היסטורית: היין שימש בטקסי עבודה זרה, ומשם מקור ההגבלה.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/barricas-roble-vino.jpg" alt="חדר חביות ביקב (תמונת המחשה, לא היקב במנדוסה שבו ביקרנו). ביקב כשר כל חבית נושאת גם חותם המעיד שאיש מחוץ להשגחה לא פתח אותה." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">חדר חביות ביקב (תמונת המחשה, לא היקב במנדוסה שבו ביקרנו). ביקב כשר כל חבית נושאת גם חותם המעיד שאיש מחוץ להשגחה לא פתח אותה. צילום: Subhashish Panigrahi דרך <a href="https://commons.wikimedia.org/wiki/File:Oak_barrels_used_for_aging_of_wine_in_a_cellar_at_Grover_Zampa_Vineyard,_Doddaballapura,_Karnataka,_India.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>בפועל, זה יוצר חלוקת עבודה מיוחדת למדי. מי שמבין ביין הוא היקבן, שבדרך כלל אינו יהודי (הגוי), והוא זה שמנחה מה לעשות בכל שלב. אבל מי שבפועל מזיז את היין, פותח את הברזים ועושה כל מה שכרוך במגע עם המוצר, הוא תמיד יהודי שומר מצוות (היהודי). המומחה מכוון, היהודי מבצע, והכול תחת השגחה רבנית מתמדת.</p>
<p>היין נח בסביבות שונות בהתאם לשלב, וחלק ממנו נשמר בחביות אלון, שניתן לעשות בהן שימוש חוזר כשלוש פעמים לפני שהן מאבדות את תכונותיהן. כל חבית חתומה, והחותם הזה אינו פרט שולי: הוא הערובה לכך שאיש מבחוץ לא נגע בתוכן. סיפרו לנו על מקרה שממחיש עד כמה רחוקה הדרישה. בחבית של אלפי ליטרים שמו לב שהחותם חסר בדיוק בנקודה שהייתה אמורה להיות סגורה: היא הייתה פתוחה. הרב מבואנוס איירס נאלץ להגיע לבדוק את המצב באופן אישי, וקבע שהיין נותר ללא השגחה. התוצאה: אלפי הליטרים ההם כבר לא יכלו להימכר ככשרים.</p>
<p>קיימת גם קטגוריה מיוחדת, <strong>יין מבושל</strong>, שהוא יין שעבר פסטור בטמפרטורה מסוימת. ברגע שיין הופך למבושל, הוא שומר על מעמדו הכשר גם אם לאחר מכן אדם לא יהודי מגיש או נוגע בו. לכן הוא כה שימושי לאירועים, מסעדות וקייטרינג, שבהם אין דרך לשלוט מי אוחז בכל בקבוק.</p>
<p>כיום יש יין כשר באיכות טובה כמעט בכל אזורי הגפן בעולם. מנדוסה היא מוקד חשוב בארגנטינה, ויין כשר מיוצר גם בצ\'ילה, צרפת, ספרד, איטליה וכמובן ישראל, המוסמכים על ידי הכשרויות המובילות.</p>',
            ],
            'gelatina-kosher' => [
                'title' => 'ג\'לטין כשר: המחלוקת ההלכתית',
                'excerpt' => 'הג\'לטין הוא אחד המרכיבים השנויים ביותר במחלוקת בעולם הכשרות, מכיוון שמקורו מן החי עלול לפגוע במעמדו.',
                'content' => '<p>ג\'לטין מסורתי מופק מהרתחת עצמות, עור ורקמת חיבור של בעלי חיים — בדרך כלל פרות או חזירים — עד להפקת הקולגן. זה מעלה שתי בעיות מבחינת הכשרות: מקור החיה (האם זו חיה כשרה?) ושיטת העיבוד (האם החיה נשחטה לפי דיני השחיטה?).</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/gelatina-kosher.jpg" alt="ממתקים וקינוחים הם המוצרים שבהם מופיעה הכי הרבה ג&#039;לטין, הרכיב השנוי ביותר במחלוקת." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">ממתקים וקינוחים הם המוצרים שבהם מופיעה הכי הרבה ג\'לטין, הרכיב השנוי ביותר במחלוקת. צילום: Sakurai Midori דרך <a href="https://commons.wikimedia.org/wiki/File%3ASweets_Offering_for_Obon.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>
<p>במשך עשורים, פוסקים שונים התווכחו האם הג\'לטין, לאחר מעבר תהליך כימי כה משמעותי, משנה את מעמדו ההלכתי (מושג הנקרא <em>פנים חדשות</em>, או שינוי מהותי מוחלט). דעות מקלות יותר טענו שהתהליך כה רדיקלי עד שהמוצר הסופי כבר אינו נחשב לבשר במובן ההלכתי; עם זאת, רוב הכשרויות המקובלות אינן מקבלות עמדה זו לגבי ג\'לטין ממקור לא כשר.</p>
<p>לכן, כיום רוב המוצרים המוסמכים כשר הדורשים ג\'לטין (ממתקים, קינוחים, כמוסות תרופות, מרשמלו) משתמשים בחלופות מוסמכות:</p>
<ul>
<li>ג\'לטין דגים כשר.</li>
<li>ג\'לטין בקר מבעלי חיים שנשחטו לפי דיני השחיטה.</li>
<li>תחליפים צמחיים כמו אגר-אגר או פקטין, המונעים את המחלוקת לחלוטין.</li>
</ul>
<p>כאשר מוצר נושא חותם של כשרות מוכרת, כבר אין צורך לחקור את מקור הג\'לטין: ההכשר מבטיח שנקודה זו כבר נבדקה.</p>',
            ],
            'alcohol-bebidas-espirituosas' => [
                'title' => 'אלכוהול ומשקאות חריפים: מה נדרש כדי שיהיו כשרים',
                'excerpt' => 'וויסקי, וודקה, רום ומשקאות חריפים אחרים בדרך כלל כשרים מטבעם, אך יש חריגים חשובים שכדאי לדעת.',
                'content' => '<p>רוב המשקאות החריפים — וויסקי, וודקה, רום, ג\'ין — מיוצרים מדגנים, תפוחי אדמה או קנה סוכר, מרכיבים שכשלעצמם אינם מעוררים בעיות כשרות. לכן משקאות חריפים פשוטים רבים כשרים ללא צורך בהכשר מיוחד, כל עוד לא נוספים להם טעמים, צבעי מאכל או תוספים ממקור לא כשר.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/alcohol-bebidas-espirituosas.jpg" alt="משקאות רבים כשרים מצד המרכיבים, אך יישון בחביות יין עלול לשנות את מעמדם." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">משקאות רבים כשרים מצד המרכיבים, אך יישון בחביות יין עלול לשנות את מעמדם. צילום: 4028mdk09 דרך <a href="https://commons.wikimedia.org/wiki/File%3ABarbestand.JPG" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>
<p>עם זאת, ישנן נקודות חשובות לתשומת לב:</p>
<ul>
<li><strong>הזדקנות בחביות יין או שרי:</strong> וויסקי ורום מסוימים מתיישנים בחביות שהכילו בעבר יין לא כשר, מה שעלול לפגוע במעמדם.</li>
<li><strong>תוספי טעם וריח:</strong> ליקרים בטעם קרם, שוקולד או פירות כוללים לעתים קרובות מרכיבים הדורשים בדיקה.</li>
<li><strong>משקאות על בסיס יין</strong> (כמו וורמוט או ליקרים מסוימים): יורשים את כל מגבלות היין הכשר, כולל הצורך בהשגחה רבנית בייצורם.</li>
<li><strong>בירה:</strong> בדרך כלל כשרה בזכות מרכיביה הבסיסיים (מים, שעורה, כשות, שמרים), למעט סוגים עם טעמים מיוחדים.</li>
</ul>
<p>בפסח יש לשים לב במיוחד מכיוון שמשקאות חריפים רבים מיוצרים מדגנים המהווים חמץ, ולכן נדרש הכשר ספציפי "כשר לפסח" בתקופה זו של השנה.</p>',
            ],
            'comer-kosher-restaurante' => [
                'title' => 'איך לאכול כשר במסעדה לא מוסמכת',
                'excerpt' => 'נסיעה או יציאה לאכול ללא מסעדה כשרה בקרבת מקום אינה אומרת לוותר על הדיאטה. יש אפשרויות להישאר במסגרת הכללים.',
                'content' => '<p>לא תמיד יש מסעדה עם הכשר זמינה, במיוחד בנסיעות או במגורים בערים עם תשתית קהילתית מועטה. בכל זאת, קיימות אסטרטגיות להישאר בגדרי הכשרות במסעדות רגילות.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/comer-kosher-restaurante.jpg" alt="גם ללא מסעדה מוכשרת בסביבה, יש דרכים להישאר בגדרי הכשרות." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">גם ללא מסעדה מוכשרת בסביבה, יש דרכים להישאר בגדרי הכשרות. צילום: Sohail1308 דרך <a href="https://commons.wikimedia.org/wiki/File%3AAl_Fanar_Restaurant.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<ul>
<li><strong>אפשרויות צמחוניות או טבעוניות:</strong> בהסרת בשר וחלב מהצלחת, הסיכון פוחת משמעותית, אם כי עדיין יש צורך לבדוק מרכיבים (מרק בשר, חמאה, רטבים על בסיס מן החי).</li>
<li><strong>פירות וירקות חיים:</strong> ללא בישול או טיפול מורכב, בדרך כלל מהווים אפשרות בטוחה כמעט בכל מקום.</li>
<li><strong>דג עם סנפיר וקשקשת:</strong> במסעדות בישול פשוט, דג על הגריל ללא רטבים יכול להיות אלטרנטיבה סבירה למי שנוקט בגישה גמישה יותר (כל עוד הוא לא מבושל יחד עם פירות ים או בשר לא כשר על אותו ציוד, לפי שיקול דעתו של כל אחד).</li>
<li><strong>משקאות בבקבוקים אטומים:</strong> מים, משקאות מוגזים ומיצים באריזתם המקורית בדרך כלל אינם מעוררים בעיות.</li>
</ul>
<p>הנה דוגמה קונקרטית מאוד לאיך זה נפתר בפועל. פעם נסענו לחופשה לְמַר דֶל פְּלָטָה עם הילדים עוד קטנים, ובהגיענו דרך שדרות קונסטיטוסיון אשתי נזכרה ששכחה את הביסקוויטים. בחורף, באזור הזה, למצוא לחם או ביסקוויטים כשרים כמעט בלתי אפשרי. מה נתנו לילדים? בבית לא היינו רגילים לאכול חטיפים ארוזים מסוימים, אבל מול הדחיפות פנינו לרשימה של Ajdut Kosher, מדריך מוצרי מדף מאושרים שהכשרות מפרסמת. חיפשנו בין המותגים המורשים ומצאנו ביסקוויטים מסוג טרוויאטה שהיו ברשימה. זה הציל אותנו.</p>
<p>המסקנה: כשנוסעים, כדאי מאוד להחזיק ביד את רשימת המוצרים המאושרים של הכשרות שסומכים עליה. המון מוצרי סופרמרקט רגילים כשרים גם בלי חותם גדול מודפס על האריזה, והיכרות עם הרשימה הזאת פותחת אפשרויות במקום שבו נראה שאין אף אחת.</p>
<p>לכל אדם ולכל קהילה רמת חומרה שונה לגבי מה שנחשב מקובל מחוץ למסעדה מוסמכת (חלקם אוכלים רק מוצרים ארוזים וחתומים, אחרים מקבלים הכנות פשוטות מסוימות). בעת ספק, מומלץ להתייעץ עם רב הקהילה לגבי הקריטריון שיש לעקוב אחריו.</p>',
            ],
            'simbolos-certificacion-kosher' => [
                'title' => 'הסמלים הנפוצים ביותר של הכשרות',
                'excerpt' => 'OU, OK, Star-K, KSA... קיימות עשרות סמלי הכשר בעולם. נעזור לכם לזהות את הנפוצים שבהם.',
                'content' => '<p>כשמוצר עובר את תהליך ההכשר, גוף הכשרות מאשר שימוש בסמל (הכשר) על האריזה המאפשר זיהוי במבט אחד. קיימות מאות כשרויות בעולם, אך חלקן מוכרות במיוחד בזכות היקפן העולמי.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/simbolos-certificacion-kosher.jpg" alt="הכשר: תעודת הכשרות שגוף רבני מעניק לעסק או למוצר." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">הכשר: תעודת הכשרות שגוף רבני מעניק לעסק או למוצר. צילום: Utilisateur:Djampa - User:Djampa דרך <a href="https://commons.wikimedia.org/wiki/File%3AHechsher_Safed_Rabbinate.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<ul>
<li><strong>OU (Orthodox Union):</strong> "U" בתוך עיגול. זהו ככל הנראה סמל הכשר המוכר ביותר בעולם, ובסיסו בארצות הברית.</li>
<li><strong>OK Kosher Certification:</strong> "K" בתוך עיגול, גוף גדול נוסף בארה"ב.</li>
<li><strong>Star-K:</strong> כוכב עם "K" במרכזו.</li>
<li><strong>KSA (Kosher Supervision of America):</strong> כשרות בעלת נוכחות חזקה במוצרים תעשייתיים.</li>
<li><strong>בד"ץ:</strong> חותם המשמש בתי דין רבניים שונים בישראל, המזוהה עם סטנדרטים גבוהים במיוחד של חומרה.</li>
<li><strong>כשרויות מקומיות:</strong> במדינות כמו ארגנטינה, ברזיל או מקסיקו קיימות כשרויות קהילתיות מקומיות (כמו ה-Va\'ad Hakashrut של כל קהילה) עם חותמות משלהן.</li>
</ul>
<p>מלבד הסמל, תוויות רבות כוללות אות נוספת: "D" (חלבי), "M" (בשרי), "Pareve" (פרווה) או "DE" (ציוד חלבי, מיוצר על ציוד חלבי אך ללא מרכיבים חלביים ישירים). הכרת סמלים אלה מאיצה מאוד את הקנייה, במיוחד בנסיעה למדינות שבהן לא שולטים בשפה המקומית.</p>
<p>כדאי להבהיר משהו שלא תמיד מובן: הכשר אינו הליך שעושים פעם אחת ונשאר בתוקף לתמיד. זו השגחה פעילה ומתמדת. אנחנו מכירים מקרה של מאפייה בבואנוס איירס שהחזיקה בהכשר של גוף קהילתי. באיזשהו שלב הרב המשגיח הבחין בתנועות חשודות ושלח אנשים לבדוק, כמעט כמו בלש. הם גילו שהיא קונה דולסה דה לצ\'ה בלי השגחה, כשהיא הייתה אמורה להשתמש רק במוצרי חלב ישראל (מוצרי חלב תחת השגחה יהודית). הזהירו אותה וביקשו לתקן. זמן קצר אחר כך, בלי לדעת שעוקבים אחריה מקרוב, היא נתפסה קונה גבינה רגילה ללא הכשר, וזה היה הקש ששבר את גב הגמל: ההשגחה הוסרה ממנה. הכול טופל בשקט, בלי שערורייה, פשוט הפסיקו להכשיר את המקום.</p>
<p>המסקנה עבור הצרכן ברורה: החותם שווה משהו כי מאחוריו יש מישהו שבודק ברצינות, כל הזמן. לכן כדאי לסמוך על כשרויות מוכרות, ומול סמל לא מוכר, לשאול בקהילה לפני שמניחים שמוצר כשר.</p>',
            ],
            'que-significa-pareve' => [
                'title' => 'פרווה: מה זה אומר ולמה זה כה נפוץ בתוויות',
                'excerpt' => 'פרווה היא אחת המילים הנפוצות ביותר בתיוג כשר. נסביר מה זה אומר ולמה זה כל כך מוערך.',
                'content' => '<p>"פרווה" מתאר מאכלים שאינם בשריים ואינם חלביים: פירות, ירקות, ביצים, דגים, דגנים ורוב המוצרים המיוצרים ללא מרכיבים מן החי חלביים או בשריים.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/que-significa-pareve.jpg" alt="פירות וירקות טריים הם פרווה מטבעם: אפשר לשלבם גם עם בשרי וגם עם חלבי." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">פירות וירקות טריים הם פרווה מטבעם: אפשר לשלבם גם עם בשרי וגם עם חלבי. צילום: PattayaPatrol דרך <a href="https://commons.wikimedia.org/wiki/File%3ADFC_2197_A_colorful_assortment_of_fresh_fruits_and_vegetables_-_apples_mango_dragon_fruit_kiwis_limes_bananas_and_more_-_arranged_on_a_wooden_crate.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>היתרון הגדול של מוצר פרווה הוא הגמישות שלו: ניתן לשלבו בחופשיות הן עם ארוחות בשריות והן עם ארוחות חלביות, מבלי ליצור כל בעיית כשרות. לכן, תעשיות מזון רבות שואפות באופן פעיל לפתח גרסאות פרווה למוצרים שמסורתית מכילים חלב — כמו שוקולד, מרגרינה או תחליפי שמנת — כדי להרחיב את שוקן.</p>
<p>חשוב להבהיר ניואנס: מאכל יכול להיות פרווה מבחינת מרכיביו, אך לאבד מעמד זה אם יוצר על ציוד המעבד גם חלב או בשר, בהתאם לשאריות שעלולות להישאר. לכן ההכשר אינו בודק רק מרכיבים, אלא גם את ציוד הייצור ותהליכי הניקוי בין אצוות.</p>
<p>דוגמאות נפוצות למוצרי פרווה: שמן זית, פסטה יבשה ללא ביצים, רוב סוגי הלחם (אם כי חלקם מכילים חמאה והופכים לחלביים), אגוזים שאינם מעובדים, ומשקאות מוגזים. בדיקת התווית תמיד היא הדרך הבטוחה היחידה לאישור, מאחר שהמתכון עשוי להשתנות בין מותגים או אפילו בין אריזות שונות של אותו מותג.</p>',
            ],
            'shejita-sacrificio-kosher' => [
                'title' => 'שחיטה: שיטת השחיטה הכשרה',
                'excerpt' => 'כדי שבשר בעל חיים כשר יהיה ראוי לאכילה, יש לשוחטו בשיטה טקסית ספציפית הנקראת שחיטה.',
                'content' => '<p>שחיטה היא שיטת השחיטה הטקסית היהודית, המבוצעת על ידי שוחט (איש מקצוע מוסמך ובדוק) באמצעות סכין חדה ביותר וללא פגמים, המתוכננת במיוחד לבצע חתך מהיר ומדויק בגרון בעל החיים, תוך חיתוך קנה הנשימה והוושט בתנועה רציפה אחת.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/shejita-sacrificio-kosher.svg" alt="החלף נבדק לפני ואחרי כל בהמה: פגימה אחת פוסלת את השחיטה." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">החלף נבדק לפני ואחרי כל בהמה: פגימה אחת פוסלת את השחיטה. איור: KosherMap.</figcaption>
</figure>
<p>מטרת שיטה זו היא לצמצם את סבל בעל החיים ולגרום לאובדן הכרה כמעט מיידי. השוחט בודק את הסכין לפני ואחרי כל שחיטה כדי לוודא שאין בה כל פגם, ולו הקטן ביותר, מכיוון שכל אי-סדירות פוסלת את ההליך.</p>
<p>קרוב משפחה מהצוות שלנו עובד בשחיטה, וזה נותן מושג עד כמה הבקרה אובססיבית בפועל. את החלף (הסכין) לא בודק רק השוחט לפני ואחרי כל בהמה: יש גם משגיח שבודק את כל הסכינים, יום אחר יום, בקריטריון קפדני ביותר. פגם שאפילו לא ניכר לעין בלתי מזוינת מספיק כדי להוציא סכין משימוש. זו סביבה של רצינות ודיוק מקסימליים, שבה מול כל מצב חריג, הצעד הראשון הוא לעצור הכול ולתת עדיפות לבטיחות.</p>
<p>לאחר השחיטה מתבצעת בדיקה של איברי הגוף הפנימיים, בעיקר הריאות, כדי לשלול מחלות או הידבקויות שיפסלו את הבשר ככשר. רק בעלי חיים שעוברים בדיקה זו נחשבים כשרים.</p>
<p>בנוסף, הבשר חייב לעבור תהליך ניקוי במלח (כשרות) להוצאת הדם, מכיוון שהתורה אוסרת אכילת דם. זה נעשה על ידי השריית הבשר במים, מליחתו והשהייתו לפני שטיפה נוספת — תהליך המתבצע כיום בדרך כלל על ידי האטליז או בית המטבחיים המוסמך עצמו לפני שהמוצר מגיע לצרכן.</p>',
            ],
            'glatt-kosher' => [
                'title' => 'גלאט כשר: מה ההבדל מכשר רגיל',
                'excerpt' => 'המונח "גלאט" מופיע לעתים קרובות באטליזים ובמסעדות כשרות. נסביר איזו רמת חומרה הוא מייצג.',
                'content' => '<p>"גלאט" פירושו "חלק" ביידיש, ומקורו התייחס באופן ספציפי למצב הריאות של בעל חיים לאחר השחיטה: אם הריאות לא הציגו כל הידבקות (סירכה), בעל החיים נחשב "גלאט", הרמה הגבוהה ביותר של ודאות שהבשר כשר ללא ספק.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/glatt-kosher.jpg" alt="מסעדה בשרית כשרה בירושלים. &quot;גלאט&quot; מציין את רמת הבדיקה המחמירה ביותר." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">מסעדה בשרית כשרה בירושלים. "גלאט" מציין את רמת הבדיקה המחמירה ביותר. צילום: brionv from San Francisco, United States דרך <a href="https://commons.wikimedia.org/wiki/File%3AJerusalem_MMMM_MEAT_%286036353902%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 2.0.</figcaption>
</figure>
<p>עם הזמן, במיוחד בקהילות אשכנזיות בארצות הברית, המונח "גלאט כשר" התרחב באופן עממי לתיאור סטנדרט כללי של חומרה רבה יותר לאורך כל שרשרת הייצור של מאכל, לא רק בבדיקת הריאות. כיום נפוץ לראות "גלאט כשר" על תוויות מסעדות ומוצרים כדי לציין שהם עומדים בקריטריונים המחמירים ביותר האפשריים.</p>
<p>חשוב להדגיש שמוצר "כשר" ללא התווית "גלאט" אינו פחות תקף הלכתית: הוא פשוט עוקב אחר סטנדרט הכשר שונה, המקובל בדרך כלל על ידי הרוב המכריע של הקהילות. הבחירה בין כשר רגיל לגלאט כשר תלויה בדרך כלל במנהג המשפחתי או הקהילתי, יותר מאשר בהבדל אובייקטיבי בתוקף.</p>
<p>במקרה של עופות ודגים, מושג ה"גלאט" טכנית אינו חל באותו אופן כמו ביונקים, אם כי בעגה היומיומית הוא משמש לעתים לציון רמת השגחה קפדנית יותר באופן כללי.</p>',
            ],
            'como-leer-etiqueta-kosher' => [
                'title' => 'איך לקרוא תווית של מוצר כשר',
                'excerpt' => 'מעבר לסמל ההכשר, תוויות כשרות מכילות מידע מרכזי כדי לדעת אם מוצר מתאים לשולחנכם.',
                'content' => '<p>קריאה נכונה של תווית כשרה חורגת מחיפוש סמל ההכשר. ישנם מספר רכיבים שכדאי תמיד לבדוק:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/como-leer-etiqueta-kosher.jpg" alt="תווית של מוצר ישראלי: מעבר לחותמת, כדאי לבדוק את הסיווג ואת רשימת הרכיבים." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">תווית של מוצר ישראלי: מעבר לחותמת, כדאי לבדוק את הסיווג ואת רשימת הרכיבים. צילום: Chenspec דרך <a href="https://commons.wikimedia.org/wiki/File%3AList_of_ingredients_of_food_products_in_Israel_02.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<ul>
<li><strong>סמל הכשרות:</strong> מזהה איזה גוף השגיח על המוצר. חשוב להכיר כשרויות אמינות, מכיוון שלא כל הסמלים בעולם נמצאים באותה רמת קפדנות.</li>
<li><strong>הקטגוריה:</strong> "Dairy" או "D" (חלבי), "Meat" או "M" (בשרי), "Pareve" (פרווה), או "Fish" (דג, שבמסורות רבות נחשב לקטגוריה נפרדת מבשר).</li>
<li><strong>"כשר לפסח":</strong> ציון נוסף הנדרש במהלך החג, השונה מההכשר הרגיל המשמש בשאר השנה.</li>
<li><strong>תאריך ההכשר:</strong> חלק מהכשרויות כוללות קוד או תאריך כדי לוודא שהחותם עדיין בתוקף, מכיוון שמתכונים ותהליכי ייצור עשויים להשתנות.</li>
</ul>
<p>כאשר למוצר אין הכשר נראה לעין אך רשימת המרכיבים נראית פשוטה (למשל, רק מים, מלח וירק), אנשים מסוימים בוחרים לחקור עוד, אך ההמלצה הכללית של רשויות הכשרות היא לא להניח שמוצר כשר רק בגלל מראהו הפשוט של מרכיביו: תוספים ותהליכים תעשייתיים רבים אינם ברורים במבט ראשון.</p>
<p>באתר KosherMap ניתן לחפש מוצרים לפי שם או ברקוד ולסנן ישירות לפי כשרות, קטגוריה וסוג, כדי לא להסתמך רק על התווית הפיזית.</p>',
            ],
            'bishul-akum' => [
                'title' => 'בישול עכו"ם: למה מאכלים מבושלים מסוימים דורשים השגחה יהודית',
                'excerpt' => 'קיימת קטגוריית הלכות ספציפית לגבי מאכלים שבושלו על ידי לא יהודים, הידועה כבישול עכו"ם. נסביר במה מדובר.',
                'content' => '<p>בישול עכו"ם (פשוטו כמשמעו "בישול של נוכרי") הוא קטגוריה של הלכות רבניות המגבילה את צריכתם של מאכלים מסוימים המבושלים כולם על ידי אדם לא יהודי, גם אם כל המרכיבים כשרים. האיסור נקבע על ידי חכמי התלמוד, בעיקר כדי לעודד לכידות חברתית ולמנוע התבוללות תרבותית.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/bishul-akum.jpg" alt="בישול עכו&quot;ם חל על מאכלים שבושלו כולם בידי נוכרי, גם כשהמרכיבים כשרים." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">בישול עכו"ם חל על מאכלים שבושלו כולם בידי נוכרי, גם כשהמרכיבים כשרים. צילום: Seattle Municipal Archives from Seattle, WA דרך <a href="https://commons.wikimedia.org/wiki/File%3ACooks_in_kitchen%2C_1930_%2820981103874%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<p>הלכה זו אינה חלה על כל המאכלים: היא מוגבלת בדרך כלל למאכלים הנחשבים "ראויים להגשה על שולחן מלך" (חשיבות) ושאינם נאכלים חיים. לכן פירות, ירקות חיים ורוב החטיפים התעשייתיים אינם נכללים בקטגוריה זו.</p>
<p>ישנן שתי דרכים נפוצות לפתור את הבעיה בהקשר של ייצור תעשייתי או מסעדות מוסמכות:</p>
<ul>
<li>שיהודי שומר מצוות ישתתף באופן פעיל בתהליך הבישול, למשל בהדלקת האש או ציוד הבישול.</li>
<li>שהשגחה רבנית תאשר שנציג יהודי היה נוכח בהדלקת ציוד הבישול בכל משמרת ייצור.</li>
</ul>
<p>זו אחת הסיבות לכך שהכשר של מפעל מזון אינו מסתכם בבדיקת מרכיבים בלבד: הוא גם משגיח על תהליכים, נוכחות צוות ופרוטוקולים תפעוליים במתקן, מה שהופך את עבודת הכשרויות למורכבת הרבה יותר מרשימת בדיקה פשוטה של חומרי גלם.</p>',
            ],
            'vino-mevushal' => [
                'title' => 'מבושל: יין כשר שניתן להגיש ללא הגבלות',
                'excerpt' => 'יין מבושל הוא קטגוריה מיוחדת המאפשרת להגישו באירועים מבלי לדרוש שרק יהודים יטפלו בו.',
                'content' => '<p>כפי שראינו בדיון על יין כשר, הכלל הכללי דורש שרק יהודים שומרי מצוות יטפלו ביין מהייצור ועד ההגשה. יין מבושל הוא חריג מעשי לכלל זה: לאחר שהיין עובר תהליך חימום בטמפרטורה מינימלית ספציפית, הוא שומר על מעמדו הכשר ללא קשר למי שיגיש אותו לאחר מכן.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/vino-mevushal.jpg" alt="יין מבושל עבר פסטור, ולכן שומר על כשרותו גם כשמוזג אותו נוכרי." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">יין מבושל עבר פסטור, ולכן שומר על כשרותו גם כשמוזג אותו נוכרי. צילום: misbehave דרך <a href="https://commons.wikimedia.org/wiki/File%3ABottle_%26_glass_of_red_Bordeaux_style_blend.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<p>קטגוריה זו קיימת הודות לעיקרון הלכתי לפיו יין שעבר שינוי באמצעות חום מאבד את ה"כבוד" הטקסי שהניע במקור את ההגבלה, מכיוון שהיסטורית החשש התמקד בשימוש ביין בטקסי עבודה זרה — דבר שיין מבושל אינו מתאים לו בהקשר זה.</p>
<p>יין מבושל פופולרי מאוד עבור:</p>
<ul>
<li>קייטרינג ואירועים שבהם צוות ההגשה אינו בהכרח יהודי.</li>
<li>מסעדות כשרות הפתוחות לציבור הרחב.</li>
<li>חברות תעופה ובתי מלון המציעים אפשרויות כשרות.</li>
</ul>
<p>כיום קיימות טכניקות מודרניות של פסטור מהיר המאפשרות ייצור יין מבושל באיכות גבוהה, דבר שהיסטורית היה קשה יותר להשיג מבלי לפגוע בטעם היין. הדבר הרחיב מאוד את היצע יינות המבושל הפרימיום הזמינים בשוק.</p>',
            ],
            'tevilat-kelim' => [
                'title' => 'טבילת כלים: הטבילה הטקסית של כלים חדשים',
                'excerpt' => 'לפני שימוש לראשונה בכלי מטבח חדשים מסוימים שיוצרו על ידי לא יהודי, קיים המנהג לטבול אותם במקווה.',
                'content' => '<p>טבילת כלים היא הנוהג של טבילת כלי מטבח חדשים — ממתכת או זכוכית, שנקנו מיצרן לא יהודי — במקווה (אמבט טקסי) או במקור מים טבעי לפני השימוש בהם לראשונה למזון.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/tevilat-kelim.jpg" alt="מקווה: בו מטבילים כלים חדשים לפני השימוש הראשון." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">מקווה: בו מטבילים כלים חדשים לפני השימוש הראשון. צילום: Stefan Walkowski דרך <a href="https://commons.wikimedia.org/wiki/File%3AMikveh.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>מנהג זה חל בעיקר על כלים הבאים במגע ישיר עם המזון: סירים, מחבתות, סכו"ם, צלחות זכוכית וכוסות. הוא בדרך כלל אינו חל על מכשירי חשמל (כמו טוסטר או בלנדר) ולא על כלי פלסטיק או עץ, אם כי הדעות משתנות לפי מסורת כל קהילה, ולכן מומלץ להתייעץ עם רב לגבי מקרים ספציפיים.</p>
<p>התהליך עצמו פשוט: הכלי מנוקה היטב (ללא שאריות תוויות, חותמות או דבק), נטבל לחלוטין במי המקווה תוך אמירת ברכה, ולאחר מכן הוא מוכן לשימוש רגיל.</p>
<p>במקוואות קהילתיים רבים יש שעות ספציפיות המוקדשות לטבילת כלים, נפרדות מהשימוש הטקסי האישי, כמו גם הוראות מפורטות אילו חומרים דורשים טבילה ואילו לא. זו אחת הפרקטיקות שלמרות שנראית כפרט קטן, היא חלק בלתי נפרד מהאופן שבו משפחות יהודיות שומרות מצוות מציידות את מטבחן.</p>',
            ],
            'armar-cocina-kosher' => [
                'title' => 'איך להקים מטבח כשר מאפס',
                'excerpt' => 'התחלת ניהול מטבח כשר עשויה להיראות מכריעה בהתחלה. נספק מדריך מעשי לצעדים הראשונים.',
                'content' => '<p>הקמת מטבח כשר מאפס היא תהליך הדרגתי, ואין צורך לפתור הכל ביום אחד. אלו הצעדים הנפוצים ביותר שמשפחות מתחילות עוקבות אחריהם:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/armar-cocina-kosher.jpg" alt="הכשרת מטבח היא תהליך הדרגתי: אין צורך לסיים הכול ביום אחד." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">הכשרת מטבח היא תהליך הדרגתי: אין צורך לסיים הכול ביום אחד. צילום: MeRyan דרך <a href="https://commons.wikimedia.org/wiki/File%3AKitchen_with_island%2C_New_Orleans_2007.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<ul>
<li><strong>הגדרת ההפרדה הפיזית:</strong> לקבוע אילו כלים, סירים וכלי שולחן יהיו בשריים ואילו חלביים. הפתרון המעשי ביותר הוא בדרך כלל שימוש בצבעים שונים (למשל, אדום לבשר, כחול לחלב) כדי למנוע בלבול יומיומי.</li>
<li><strong>הפרדת משטחי עבודה:</strong> קרשי חיתוך, מגבות מטבח וספוגים חייבים גם הם להתחלק לפי קטגוריה.</li>
<li><strong>הערכת מכשירים משותפים:</strong> תנור, מיקרוגל ומדיח כלים ניתן להכשיר בין שימושים או, פשוט יותר, לייעד אותם לקטגוריה אחת מההתחלה (למשל, מיקרוגל לפרווה בלבד).</li>
<li><strong>קניית מוצרים מוסמכים:</strong> לבדוק את סמל ההכשר בכל קנייה, עד שזה הופך להרגל אוטומטי.</li>
<li><strong>תיאום עם רב:</strong> במיוחד להכשרת פריטים שכבר היו במטבח לפני תחילת תהליך זה.</li>
</ul>
<p>אסטרטגיה נפוצה מאוד בקרב מתחילים היא לשלב את ההפרדה בהדרגה: תחילה כלי השימוש היומיומי, לאחר מכן כלי השולחן, ולבסוף המכשירים החשמליים. אין צורך להחליף את כל המטבח בבת אחת, ומשפחות רבות נמשכות חודשים להשלים את המעבר מבלי שזו תהיה בעיה הלכתית כשלעצמה.</p>',
            ],
            'certificaciones-kosher-mundo' => [
                'title' => 'הבדלים בין הכשרויות ברחבי העולם',
                'excerpt' => 'לא כל הכשרויות עוקבות אחר אותם קריטריונים בדיוק. הכרת ההבדלים האלה עוזרת לבחור מוצרים בביטחון.',
                'content' => '<p>למרות שעקרונות היסוד של הכשרות הם אוניברסליים, קיימות מאות גופי הכשר בעולם, ולכל אחד עשויים להיות קריטריונים שונים מעט בנושאים ספציפיים — למשל, איזו רמת השגחה הוא דורש לבישול עכו"ם, או כיצד הוא מתייחס לתוספים כימיים מסוימים שמקורם קשה לאתר.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/certificaciones-kosher-mundo.svg" alt="עקרונות הכשרות אחידים, אך לכל אזור גופי הכשרה משלו." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">עקרונות הכשרות אחידים, אך לכל אזור גופי הכשרה משלו. איור: KosherMap.</figcaption>
</figure>
<p>כמה הבדלים נפוצים בין אזורים:</p>
<ul>
<li><strong>ארצות הברית:</strong> מארחת את הכשרויות התעשייתיות הגדולות ביותר (OU, OK, Star-K, Kof-K), עם תהליכים סטנדרטיים מאוד לייצוא המוני.</li>
<li><strong>ישראל:</strong> הרבנות הראשית מציעה הכשר ממלכתי רשמי, בעוד ארגונים כמו הבד"ץ שומרים על תקנים נוספים הנחשבים מחמירים יותר על ידי קהילות מסוימות.</li>
<li><strong>אירופה:</strong> כשרויות כמו בית הדין של ערים שונות (לונדון, פריז, ציריך) משגיחות הן על ייצור מקומי והן על ייבוא.</li>
<li><strong>אמריקה הלטינית:</strong> לכל קהילה יש בדרך כלל את ה-Va\'ad Hakashrut המקומי שלה (למשל, בבואנוס איירס, סאו פאולו או מקסיקו סיטי), המסמיך הן מוצרים מקומיים והן מסעדות.</li>
</ul>
<p>עבור הצרכן, הדבר החשוב ביותר הוא ללמוד לזהות את הכשרויות הפעילות באזורו, ובמקרה של ספק לגבי סמל לא מוכר, להתייעץ עם רב הקהילה או לחקור את מוניטין הגוף לפני שסומכים על מוצר. רוב הכשרויות הגדולות מפרסמות רשימות פומביות של מוצרים מוסמכים באתריהן.</p>',
            ],
            'queso-kosher-cuajo' => [
                'title' => 'גבינה כשרה: למה היא דורשת קיבה מיוחדת',
                'excerpt' => 'הגבינה היא אחד ממוצרי החלב עם ההגבלות הכשרות הרבות ביותר, בעיקר בשל מקור האנזים (קיבה) המשמש לייצורה.',
                'content' => '<p>הקיבה (rennet) היא האנזים המשמש באופן מסורתי לקרישת חלב ולהפרדת מי הגבינה בייצור גבינה. הבעיה מנקודת מבט הכשרות היא שהקיבה המסורתית מופקת מקיבת עגלים, וכדי שתהיה כשרה, אותה חיה חייבת להישחט בשיטת השחיטה הכשרה — דבר שכמעט אף פעם לא קורה בתעשיית הגבינות המקובלת.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/queso-kosher-cuajo.jpg" alt="גבינה מצריכה הכשר מיוחד בגלל מקור האנזים שמקריש את החלב." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">גבינה מצריכה הכשר מיוחד בגלל מקור האנזים שמקריש את החלב. צילום: Daderot דרך <a href="https://commons.wikimedia.org/wiki/File%3ACheese_display%2C_Cambridge_MA_-_DSC05391.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>
<p>לכן, כמעט כל הגבינה ה"רגילה" בשוק, גם אם עשויה רק מחלב וקיבה, אינה כשרה ללא הכשר ספציפי, מכיוון שמקור הקיבה אינו ניתן לאימות בעין.</p>
<p>האפשרויות שבהן משתמשים יצרני גבינה כשרה כוללות:</p>
<ul>
<li><strong>קיבה מן החי כשרה:</strong> מופקת מבעלי חיים שנשחטו לפי שחיטה ותחת השגחה רבנית לאורך כל השרשרת.</li>
<li><strong>קיבה מיקרוביאלית:</strong> מיוצרת בתסיסה, ללא מקור מן החי, נפוצה יותר ויותר בגבינות תעשייתיות וכשרות.</li>
<li><strong>קיבה צמחית:</strong> מופקת מצמחים מסוימים, המשמשת באופן מסורתי בזנים ספציפיים מסוימים של גבינות ביתיות.</li>
</ul>
<p>מעבר לקיבה, ישנו גורם רלוונטי נוסף: קהילות רבות דורשות שהגבינה תיוצר תחת השגחה יהודית מתמדת (גבינת ישראל) כדי להיחשב כשרה לחלוטין, קריטריון נוסף לניתוח המרכיבים הפשוט. לכן, קניית גבינה עם הכשר מוכר היא הדרך האמינה ביותר להימנע מטעויות.</p>',
            ],
            'huevos-kosher' => [
                'title' => 'ביצים כשרות: מה לבדוק לפני השימוש בהן',
                'excerpt' => 'ביצים הן פרווה ובדרך כלל כשרות, אך קיים שלב בדיקה חובה לפני בישולן.',
                'content' => '<p>ביצים מעופות כשרים (כמו תרנגולת) הן, בעיקרון, פרווה וראויות לאכילה. עם זאת, לפני השימוש בביצה, המסורת מחייבת לבדוק שאינה מכילה כתמי דם בחלמון, מכיוון שביצה עם דם נחשבת לא ראויה לאכילה.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/huevos-kosher.jpg" alt="ביצים הן פרווה, אך יש לבדוק אותן מדם לפני השימוש." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">ביצים הן פרווה, אך יש לבדוק אותן מדם לפני השימוש. צילום: Evan-Amos דרך <a href="https://commons.wikimedia.org/wiki/File%3A6-Pack-Chicken-Eggs.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>
<p>ההליך פשוט: בשבירת הביצה, בודקים חזותית את החלמון (ולעתים גם את החלבון) כנגד האור, ומחפשים נקודות אדומות או כתמים. אם נמצא דם, הביצה נזרקת לחלוטין; אם החלמון נקי, הביצה ראויה לשימוש רגיל.</p>
<p>כמה עובדות נוספות על ביצים וכשרות:</p>
<ul>
<li>הקליפה והחלבון בדרך כלל אינם מציגים את אותו הסיכון כמו החלמון, אם כי המנהג משתנה לפי הקהילה.</li>
<li>ביצים מעופות לא כשרים (כמו יען או עופות דורסים מסוימים) אינן ראויות גם הן, ללא קשר לנוכחות דם.</li>
<li>מוצרים תעשייתיים המכילים ביצה (כמו פסטה או מיונז) עוברים בדרך כלל תהליך בקרת איכות הכולל זיהוי אוטומטי של ביצים עם דם, אך עדיין דורשים הכשר כדי להבטיח שבקרה זו בוצעה כראוי.</li>
</ul>
<p>זהו אחד ההרגלים הפשוטים ביותר לשילוב במטבח כשר יומיומי: בדיקת כל ביצה ברגע שבירתה, לפני ערבובה עם שאר המרכיבים.</p>',
            ],
            'pescado-kosher-aletas-escamas' => [
                'title' => 'דג כשר: סנפירים וקשקשים, הכללים הבסיסיים',
                'excerpt' => 'בניגוד לבשר, דג כשר אינו דורש שחיטה, אך עליו לעמוד בקריטריון פיזי ספציפי.',
                'content' => '<p>התורה קובעת כלל פשוט יחסית לזיהוי דג כשר: עליו להיות בעל סנפיר וקשקשת הנראים לעין. שילוב זה קיים ברוב המכריע של דגי מים מתוקים ומלוחים הנאכלים בדרך כלל, כמו סלמון, טונה, דניס, פורל ומקרל.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/pescado-kosher-aletas-escamas.jpg" alt="כדי שדג יהיה כשר, עליו להיות בעל סנפיר וקשקשת נראים לעין." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">כדי שדג יהיה כשר, עליו להיות בעל סנפיר וקשקשת נראים לעין. צילום: IndayLiburan דרך <a href="https://commons.wikimedia.org/wiki/File%3AFish_stalls_at_Valencia_Public_Market_01.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>אינם נכללים בכשרות, בין היתר:</p>
<ul>
<li>כל פירות הים (שרימפס, לובסטר, סרטנים, צדפות, אוסטרות).</li>
<li>תמנון וקלמארי.</li>
<li>כריש ולוטה (חסרים קשקשים אמיתיים לפי רוב הדעות ההלכתיות).</li>
<li>צלופח (חסר קשקשים נראים לעין).</li>
<li>דג חרב (מעמדו שנוי במחלוקת היסטורית בין פוסקים שונים).</li>
</ul>
<p>הבדל חשוב לעומת בשר: דג כשר אינו דורש שחיטה או תהליך מליחה להוצאת דם, מה שמפשט מאוד את הכנתו. עם זאת, במסורות רבות — במיוחד אשכנזיות — דג נחשב לקטגוריה נפרדת מבשר וחלב, ונמנעים משילובו עם בשר באותה צלחת (אם כי אינו דורש את אותה הפרדה קפדנית של כלים החלה בין בשר לחלב).</p>
<p>בקניית דג טרי, כדאי לוודא שהוא שומר על העור עם קשקשים נראים לעין, מכיוון שבחלק מתהליכי הפילוט מסירים את העור לחלוטין, מה שמקשה על האימות. לכן דייגים כשרים רבים משאירים חתיכת עור ניתנת לזיהוי בחיתוך.</p>',
            ],
            'frutos-secos-contaminacion-cruzada' => [
                'title' => 'אגוזים וכשרות: סיכוני זיהום צולב',
                'excerpt' => 'אגוזים הם באופן טבעי פרווה, אך עיבוד תעשייתי עלול להכניס סיכוני כשרות שאינם ברורים.',
                'content' => '<p>שקדים, אגוזי מלך, בוטנים ורוב האגוזים הם, בצורתם החיה והטבעית, מאכלי פרווה ללא הגבלות כשרות. הבעיה מתעוררת כשהם נכנסים לשרשרת העיבוד התעשייתי, שם הם עלולים להתערבב עם מוצרים אחרים באותם קווי ייצור.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/frutos-secos-contaminacion-cruzada.jpg" alt="במצבם הגולמי הם פרווה ללא הגבלה; הסיכון מתחיל בעיבוד התעשייתי." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">במצבם הגולמי הם פרווה ללא הגבלה; הסיכון מתחיל בעיבוד התעשייתי. צילום: Famartin דרך <a href="https://commons.wikimedia.org/wiki/File%3A2021-01-06_12_15_43_Cranberry_trail_mix_with_cranberries%2C_peanuts%2C_raisins%2C_walnuts%2C_almonds%2C_sunflower_seeds%2C_pepitas_in_the_Franklin_Farm_section_of_Oak_Hill%2C_Fairfax_County%2C_Virginia.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>כמה סיכונים נפוצים:</p>
<ul>
<li><strong>תוספי טעם חלביים:</strong> אגוזים "קלויים בחמאה" או מצופים בשוקולד חלב כבר אינם פרווה.</li>
<li><strong>קווים משותפים:</strong> מפעל עשוי לעבד אגוזי פרווה על אותו ציוד שבו מעובדים מאוחר יותר מוצרים חלביים או נגזרות בשר, ויוצרים שאריות לא כשרות ללא ניקוי מוסמך בין האצוות.</li>
<li><strong>שמני בישול:</strong> אגוזים מטוגנים מסוימים משתמשים בשמנים המשותפים עם מוצרים לא כשרים אחרים.</li>
<li><strong>ציפויים וזיגוגים:</strong> אגוזים "מסוכרים" או עם ציפוי מתוק עשויים להכיל ג\'לטין או מרכיבים אחרים ממקור מן החי.</li>
</ul>
<p>לכן, למרות שאגוז חי ולא מעובד כמעט לעולם אינו מציג בעיות, מוצרים תעשייתיים (תערובות אגוזים, חטיפים בטעמים, חטיפי דגנים) תמיד צריכים להיבדק להכשר, מבלי להניח שהם כשרים אוטומטית רק כי המרכיב העיקרי כשר.</p>',
            ],
            'kashrut-y-veganismo' => [
                'title' => 'כשרות וטבעונות: האם אכילה טבעונית זהה לאכילה כשרה?',
                'excerpt' => 'אנשים רבים מניחים שמוצר טבעוני הוא אוטומטית כשר. המציאות מורכבת יותר.',
                'content' => '<p>זוהי טעות נפוצה: אם מוצר אינו מכיל כל מרכיב מן החי, נראה הגיוני להניח שהוא אוטומטית כשר. עם זאת, הכשרות אינה מבוססת רק על מרכיבים, אלא גם על תהליכי הייצור, הציוד המשמש, ובמקרים מסוימים, מי המשגיח על הייצור.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kashrut-y-veganismo.jpg" alt="מוצר טבעוני אינו כשר אוטומטית: הכשרות בוחנת גם תהליכים וציוד." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">מוצר טבעוני אינו כשר אוטומטית: הכשרות בוחנת גם תהליכים וציוד. צילום: HaJunkiyada דרך <a href="https://commons.wikimedia.org/wiki/File%3ALiat_Portal_for_Foodie_Disorder_-_Cauliflower_from_SF_farmers%27_market.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>כמה דוגמאות שבהן מוצר טבעוני עשוי שלא להיות כשר:</p>
<ul>
<li><strong>ציוד משותף:</strong> מפעל טבעוני עשוי להשתמש באותו קו ייצור שבו עובד בעבר מוצרי בשר או חלב, ללא הניקוי המוסמך שהכשרות דורשת בין אצוות.</li>
<li><strong>יין ונגזרותיו:</strong> יין טבעוני (ללא חומרי צלילה ממקור מן החי) עדיין דורש שכל תהליך הייצור יהיה בידי יהודים שומרי מצוות כדי שיהיה כשר.</li>
<li><strong>חרקים:</strong> צבעי מאכל מסוימים (כמו כרמין, ממקור מן החי) אסורים בכשרות אך לעתים מתויגים כמתאימים לטבעונים בטעות או לפי תקנים שונים של אישור טבעוני.</li>
<li><strong>בישול עכו"ם:</strong> מאכל טבעוני שבושל כולו על ידי אדם לא יהודי עלול ליפול תחת מגבלה זו, בהתאם לאופן סיווג המוצר.</li>
</ul>
<p>לעומת זאת, נכון גם שמוצרים כשרים רבים מסוג פרווה הם, למעשה, טבעוניים. אך ההקבלה אינה אוטומטית בשום כיוון: הבטוח ביותר הוא תמיד לחפש הכשר מפורש, במקום להניח ש"טבעוני" שווה ל"כשר".</p>',
            ],
            'separar-la-jala' => [
                'title' => 'איך מפרישים חלה',
                'excerpt' => 'הפרשת חלה היא מצווה ספציפית החלה בלישת בצק בכמויות גדולות, ומקורה בקרבנות בית המקדש.',
                'content' => '<p>הפרשת חלה היא מצווה מהתורה שבמקור דרשה להעניק חלק מבצק הלחם לכוהנים בבית המקדש בירושלים. לאחר חורבן בית המקדש, הנוהג השתנה: כיום, במקום להעניק, החלק המופרש נשרף או מושלך בכבוד.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/jala-shabat.jpg" alt="חלה קלועה מוכנה לשבת, לצד כוס היין והמלח. המפית המכסה אותה היא חלק ממנהג שולחן השבת." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">חלה קלועה מוכנה לשבת, לצד כוס היין והמלח. המפית המכסה אותה היא חלק ממנהג שולחן השבת. צילום: HaJunkiyada דרך <a href="https://commons.wikimedia.org/wiki/File:Liat_Portal_for_Foodie_Disorder_-_Challah_for_Shabbat_with_wine_and_salt.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>מצווה זו חלה בעת לישת כמות משמעותית של בצק העשוי מאחד מחמשת מיני הדגן (חיטה, שעורה, שיבולת שועל, שיפון או כוסמין) — הכמות המינימלית המדויקת (בדרך כלל סביב 1.2 ק"ג קמח) משתנה לפי הדעה ההלכתית הננקטת.</p>
<p>התהליך הבסיסי הוא:</p>
<ul>
<li>ללוש את בצק הלחם כרגיל, עד שמגיע לכמות המינימלית הנדרשת.</li>
<li>להפריש חלק קטן (מסורתית בגודל זית או יותר, בהתאם למנהג).</li>
<li>לברך את הברכה המתאימה לפני הפרשת החלק.</li>
<li>לשרוף את החלק המופרש (עטוף בנייר כסף, בתנור) או להשליכו באופן שלא ישמש לצריכה רגילה.</li>
</ul>
<p>נוהג זה הוא הסיבה שמאפיות כשרות תעשייתיות מוסמכות רבות מפרישות חלה כחלק מתהליך הייצור שלהן, והסיבה שנשים ומשפחות יהודיות רבות מבצעות זאת בבית בכל פעם שהן אופות לחם או חלה לשבת בכמות מספקת.</p>
<p>בהרבה בתים חווים את זה כרגע מיוחד. אצלנו, למשל, הבנות תמיד מנסות להגיע לכמות המינימלית של הבצק בדיוק כדי שיוכלו להפריש את החלה עם ברכה, כי עשיית זה עם הברכה נותנת ערך מוסף. מעבר למחווה הטכנית, זה הופך בסופו של דבר לטקס משפחתי סביב התנור.</p>',
            ],
            'calendario-judio-festividades-alimentacion' => [
                'title' => 'הלוח השנה היהודי והחגים המשפיעים על אכילה כשרה',
                'excerpt' => 'לחגים יהודיים רבים יש מנהגי אוכל ספציפיים, מעבר לכללי הכשרות הכלליים.',
                'content' => '<p>מלבד כללי הכשרות החלים כל השנה, הלוח השנה היהודי מביא חגים עם מנהגי אוכל משלהם שכדאי להכיר:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/calendario-judio-festividades-alimentacion.jpg" alt="תפוח, דבש ורימון: סימני ראש השנה, אחד החגים עם מנהגי אכילה משלו." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">תפוח, דבש ורימון: סימני ראש השנה, אחד החגים עם מנהגי אכילה משלו. צילום: Gilabrand דרך <a href="https://commons.wikimedia.org/wiki/File%3ASymbols_of_Rosh_Hashana.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>
<ul>
<li><strong>ראש השנה:</strong> נהוג לאכול תפוח בדבש לסמל שנה מתוקה, ולהימנע ממאכלים מרים או חמוצים על שולחן החג.</li>
<li><strong>יום כיפור:</strong> יום צום מלא של 25 שעות, ללא אוכל או שתייה, למעט חריגים רפואיים ספציפיים.</li>
<li><strong>סוכות:</strong> נהוג לאכול בסוכה זמנית בחוץ במשך כל שבוע החג.</li>
<li><strong>חנוכה:</strong> מסורת אכילת מאכלים מטוגנים בשמן (כמו סופגניות ולביבות) לזכר נס השמן.</li>
<li><strong>פורים:</strong> מכינים אוזני המן, מאפים משולשים ממולאים, ונהוג לחלוק משלוחי מנות עם חברים ומשפחה.</li>
<li><strong>פסח:</strong> החג עם מירב ההגבלות התזונתיות, המתמקד באיסור החמץ, כפי שראינו בפירוט.</li>
<li><strong>שבועות:</strong> מנהג אכילת מאכלים חלביים, עם מאכלים כמו עוגת גבינה ובלינצ\'ס (חביתיות ממולאות גבינה) בחזית.</li>
</ul>
<p>הכרת לוח שנה זה עוזרת להבין מדוע מוצרים מסוימים (כמו מצה, סופגניות או יין כשר לפסח) מופיעים בזמינות רבה יותר במדפים ובחנויות בתקופות מסוימות בשנה.</p>',
            ],
            'errores-comunes-empezar-comer-kosher' => [
                'title' => 'טעויות נפוצות כשמתחילים לאכול כשר',
                'excerpt' => 'אימוץ הכשרות לראשונה כרוך בתהליך למידה. נסקור את הטעויות הנפוצות ביותר כדי להימנע מהן מההתחלה.',
                'content' => '<p>תחילת קיום דיאטה כשרה היא תהליך שלוקח זמן, וטבעי לטעות בהתחלה. אלו כמה מהטעויות הנפוצות ביותר:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/errores-comunes-empezar-comer-kosher.jpg" alt="בתחילת הדרך, הטעות הנפוצה היא להניח שמוצר כשר בלי לחפש הכשר." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">בתחילת הדרך, הטעות הנפוצה היא להניח שמוצר כשר בלי לחפש הכשר. צילום: Nenad Stojkovic דרך <a href="https://commons.wikimedia.org/wiki/File%3ACorn_in_a_shopping_trolley._%2851964905166%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<ul>
<li><strong>הנחה ש"טבעי" או "ללא חומרים משמרים" משמעו כשר:</strong> השיווק של מוצר אינו קשור ישירות למעמדו ההכשרי. תמיד יש לחפש הכשר.</li>
<li><strong>אי בדיקת מוצרים שנראים פרווה באופן ברור:</strong> חטיפים, מאפים וממתקים מכילים לעתים מרכיבים חלביים או ג\'לטין שאינם ברורים משם המוצר.</li>
<li><strong>ערבוב כלים בשריים וחלביים בהיסח הדעת:</strong> בהתחלה קל לשכוח את ההפרדה; תיוג או שימוש בצבעים שונים מסייע מאוד במהלך המעבר.</li>
<li><strong>אי בדיקת ירקות עלים מפני חרקים:</strong> שלב שאנשים רבים החדשים לכשרות אינם מודעים לו כלל.</li>
<li><strong>הסתמכות על הכשרים לא מוכרים או לא ברורים:</strong> לא כל סמל על אריזה הוא הכשר אמיתי; חלקם הם חותמות איכות שאינן קשורות לכשרות.</li>
<li><strong>אי שאלה:</strong> ספקות רבים נפתרים במהירות באמצעות שאלה לרב הקהילה או למי שמנוסה יותר, במקום לנחש.</li>
</ul>
<p>הדבר החשוב ביותר הוא להבין שהמעבר אינו צריך להיות מושלם מהיום הראשון. רוב הקהילות היהודיות מעריכות את תהליך הלמידה ההדרגתי, וישנם משאבים רבים — כולל כשרויות, רבנים וכלים כמו KosherMap — לליווי הדרך הזו.</p>',
            ],
        ];
    }

    private static function ru(): array
    {
        return [
            'insectos-frutas-verduras' => [
                'title' => 'Насекомые во фруктах и овощах: как правильно их проверять',
                'excerpt' => 'Тора запрещает есть насекомых, поэтому проверка фруктов и овощей перед употреблением — важная часть соблюдения кошерной кухни.',
                'content' => '<p>Запрет на употребление насекомых (шерацим) — один из самых строгих в Торе: в отличие от других пищевых запретов, употребление одного насекомого может считаться сразу несколькими нарушениями. Поэтому проверка фруктов и овощей перед приготовлением или подачей — неизбежный шаг на кошерной кухне.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/pulgon-en-hoja.jpg" alt="Тля на листе крупным планом. На реальном овоще — один-три миллиметра: едва заметная тёмная точка, потому её так легко пропустить." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Тля на листе крупным планом. На реальном овоще — один-три миллиметра: едва заметная тёмная точка, потому её так легко пропустить. Фото: WikiPedant через <a href="https://commons.wikimedia.org/wiki/File:Aphid_on_leaf05.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Листовые овощи, такие как салат, шпинат, брокколи и цветная капуста, требуют наибольшей осторожности, потому что тля и другие мелкие насекомые прячутся между листьями и их трудно обнаружить невооружённым глазом. Обычная техника включает разделение листьев, индивидуальное мытьё под проточной водой и проверку на просвет.</p>
<ul>
<li><strong>Листовые овощи:</strong> разделять лист за листом и мыть под проточной водой, проверяя обе стороны.</li>
<li><strong>Цветная капуста и брокколи:</strong> замочить в воде с небольшим количеством мыла или уксуса, чтобы насекомые отделились от соцветий.</li>
<li><strong>Клубника и малина:</strong> замочить в солёной или уксусной воде перед полосканием.</li>
<li><strong>Сухие бобовые (чечевица, нут):</strong> рассыпать на светлой поверхности и проверить перед варкой.</li>
</ul>
<p>И надо честно признать: это не так просто, как звучит. У нас дома проверка овощей всегда доставалась одному конкретному человеку. С годами ему понадобились очки, и однажды он заметил маленькую чёрную точку на листе. Уверенный, что это ничего, он спросил у дочери, что это такое. А она ответила: разве не видно, что это жучок, у него даже лапки видны? С того дня он не проверяет ни один лист без очков. Урок прост: чтобы хорошо проверять, нужен хороший свет и хорошее зрение, а в случае сомнения лишний взгляд никогда не помешает.</p>
<p>Многие кошерные сертификаторы предлагают подробные иллюстрированные руководства по проверке каждого вида овощей в зависимости от региона выращивания, поскольку распространённость насекомых зависит от климата и метода земледелия. Сегодня также существуют "предварительно проверенные" овощи или выращенные под специальным надзором для минимизации заражения, что значительно упрощает процесс на повседневной кухне.</p>',
            ],
            'carne-y-leche' => [
                'title' => 'Мясо и молоко: почему их не смешивают',
                'excerpt' => 'Разделение мяса и молока — один из самых известных столпов кашрута. Объясняем его происхождение, рамки и практическое применение.',
                'content' => '<p>Запрет на смешивание мяса и молока основан на стихе, который трижды встречается в Торе: "Не вари козлёнка в молоке матери его". Устная традиция истолковала эту фразу как тройной запрет: не варить, не есть и не извлекать никакой пользы из смеси мяса и молока.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/carne-y-leche.jpg" alt="На кошерной кухне держат два полных набора посуды: отдельно для мясного и для молочного." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">На кошерной кухне держат два полных набора посуды: отдельно для мясного и для молочного. Фото: PattayaPatrol через <a href="https://commons.wikimedia.org/wiki/File%3ADFC_2431_A_cook_flips_food_in_a_hot_pan_while_working_in_a_compact_well-used_kitchen_filled_with_pots_utensils_and_shelves_of_ingredients.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>На практике это означает, что продукты делятся на три категории: <strong>мясные</strong> (мясо и производные), <strong>молочные</strong> (молоко и производные) и <strong>парве</strong> (нейтральные, например фрукты, овощи, яйца и рыба, которые не являются ни мясом, ни молоком).</p>
<p>Соблюдающая кошерную кухню семья держит отдельную посуду, кастрюли, тарелки и даже посудомоечные машины для мяса и для молока, поскольку тепло и многократное использование могут передавать вкусы и частицы между поверхностями. Кроме того, требуется время ожидания между употреблением мяса и молочных продуктов — оно варьируется в зависимости от семейного обычая, обычно от одного до шести часов — тогда как переход от молочного к мясному обычно требует лишь полоскания рта и употребления чего-то нейтрального.</p>
<p>Это разделение объясняет, почему на многих этикетках продуктов рядом с символом сертификации стоят буквы "D" (молочный), "M" (мясной) или "Pareve" (парве): это позволяет потребителю сразу понять, к какой категории относится продукт, прежде чем сочетать его с другими блюдами.</p>',
            ],
            'kasherizar-horno' => [
                'title' => 'Как откошеровать духовку',
                'excerpt' => 'Когда духовка использовалась с некошерной едой, или хотят перевести её с мясного использования на молочное, существует особый процесс, чтобы сделать её пригодной.',
                'content' => '<p>Откошерование духовки необходимо в нескольких ситуациях: при покупке дома с духовкой, ранее использовавшейся некошерным образом, при изменении назначения духовки (например, с мясного на молочное) или перед Песахом, когда нужно устранить все следы хамеца.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kasherizar-horno.jpg" alt="Либун — кошерование духовки сильным жаром — требует полной предварительной чистки и 24 часов без использования." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Либун — кошерование духовки сильным жаром — требует полной предварительной чистки и 24 часов без использования. Фото: Brandon Carson from San Carlos, USA через <a href="https://commons.wikimedia.org/wiki/File%3ADouble_oven.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<p>Традиционный метод для духовок называется <em>либун</em> (самоочистка интенсивным жаром) и состоит из:</p>
<ul>
<li>Тщательной очистки духовки, удаления всей видимой грязи и остатков пищи.</li>
<li>Неиспользования духовки в течение 24 часов перед откошерованием.</li>
<li>Включения духовки на максимально возможную температуру (в идеале с использованием функции самоочистки, если она есть) минимум на час.</li>
</ul>
<p>Металлические решётки и противни обычно можно откошеровать отдельно путём погружения в кипящую воду (агала), тогда как стеклянные или эмалированные поверхности обычно требуют либуна, так как это более впитывающие материалы.</p>
<p>Важно проконсультироваться с раввином перед откошерованием конкретной духовки, поскольку точная процедура может варьироваться в зависимости от материала, модели и обычая (минhag) каждой общины. Некоторые современные духовки со специальным покрытием могут не подходить для либуна при высокой температуре, поэтому стоит проверить инструкцию производителя.</p>',
            ],
            'kasherizar-microondas' => [
                'title' => 'Как откошеровать микроволновую печь',
                'excerpt' => 'У микроволновой печи процесс откошерования отличается от обычной духовки, потому что она готовит с помощью пара, а не сухого тепла.',
                'content' => '<p>В отличие от обычной духовки, микроволновая печь нагревает пищу, генерируя пар внутри, что меняет метод откошерования, рекомендуемый большинством галахических авторитетов.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/microondas-cocina.jpg" alt="Брызги скапливаются прежде всего на потолке камеры микроволновки — там, куда при уборке смотрят меньше всего." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Брызги скапливаются прежде всего на потолке камеры микроволновки — там, куда при уборке смотрят меньше всего. Фото: Tomwsulcer через <a href="https://commons.wikimedia.org/wiki/File:Stove_oven_combination_and_microwave_oven.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>
<p>Наиболее распространённый процесс включает:</p>
<ul>
<li>Тщательную очистку внутренней части, удаление всех видимых частиц пищи, включая вращающуюся тарелку и стенки.</li>
<li>Неиспользование микроволновки в течение 24 часов перед откошерованием.</li>
<li>Размещение ёмкости с водой внутри микроволновки и включение её до тех пор, пока вода не закипит и не образуется достаточно пара, чтобы покрыть все внутренние поверхности, включая дверцу.</li>
<li>Оставить пар воздействовать на стенки в течение нескольких минут.</li>
</ul>
<p>Многие семьи также предпочитают всегда использовать крышку или плёнку, подходящую для микроволновки, при разогреве пищи, и закрепить за прибором только одно использование (мясное, молочное или парве), чтобы избежать повторного откошерования. Микроволновки с функцией гриля или конвекции могут потребовать дополнительного процесса, аналогичного обычной духовке, для этой конкретной функции. Как и при любом откошеровании, рекомендуется проконсультироваться с раввином о конкретной модели и материале прибора.</p>',
            ],
            'kasherizar-lavavajillas' => [
                'title' => 'Как откошеровать посудомоечную машину',
                'excerpt' => 'Многие семьи используют посудомоечную машину для мясной и молочной посуды в разных циклах. Рассказываем, что нужно для её откошерования.',
                'content' => '<p>Посудомоечная машина представляет особую сложность, поскольку её внутренние стенки, фильтры и разбрызгивающие рычаги находятся в постоянном контакте с остатками пищи при высокой температуре, что может впитывать вкусы более стойко, чем другие приборы.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kasherizar-lavavajillas.jpg" alt="Посудомоечная машина — один из самых спорных приборов: фильтры и разбрызгиватели удерживают остатки при высокой температуре." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Посудомоечная машина — один из самых спорных приборов: фильтры и разбрызгиватели удерживают остатки при высокой температуре. Фото: Myke2020 через <a href="https://commons.wikimedia.org/wiki/File%3ACountertop_dishwasher_7882.JPG" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>
<p>По этой причине многие раввинские авторитеты более строги в отношении откошерования посудомоечных машин, чем других приборов, а некоторые прямо не рекомендуют использовать одну машину для обеих категорий (мясной и молочной), даже в разные дни. Те, кто это допускает, обычно требуют:</p>
<ul>
<li>Тщательной очистки фильтров, разбрызгивающих рычагов и резиновых уплотнителей.</li>
<li>Неиспользования посудомоечной машины в течение 24 часов перед откошерованием.</li>
<li>Запуска полного пустого цикла при максимально возможной температуре, в идеале с сильным чистящим средством.</li>
<li>В некоторых общинах рекомендуется использовать отдельные сменные корзины или лотки для мясного и молочного, вместо откошерования всей машины между использованиями.</li>
</ul>
<p>Поскольку обычаи в этом вопросе сильно различаются — некоторые сефардские и ашкеназские общины заметно расходятся, — это один из тех случаев, когда особенно стоит проконсультироваться напрямую с раввином общины, прежде чем решать, как организовать кухню.</p>',
            ],
            'hagala-utensilios-metal' => [
                'title' => 'Как откошеровать металлическую посуду (агала)',
                'excerpt' => 'Агала — традиционный метод погружения в кипящую воду для откошерования кастрюль, столовых приборов и других металлических предметов.',
                'content' => '<p>Агала — один из древнейших методов откошерования, используемый в основном для металлической посуды, которая нагревалась напрямую огнём или кипящей жидкостью, например кастрюли, столовые приборы, сковороды (без антипригарного покрытия) и некоторая другая кухонная утварь.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/hagala-utensilios-metal.jpg" alt="Агала — погружение утвари в кипяток: как впитало, так и извергает." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Агала — погружение утвари в кипяток: как впитало, так и извергает. Фото: W.carter через <a href="https://commons.wikimedia.org/wiki/File%3ASteam-boiling_green_asparagus.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>
<p>Принцип агалы: "как впитала, так и отдаёт": если посуда впитала некошерный (или мясной/молочный) вкус через кипящую жидкость, она очищается тем же способом — погружением в кипящую воду.</p>
<p>Базовая процедура такова:</p>
<ul>
<li>Тщательно очистить посуду, без следов ржавчины, прилипшей еды или въевшейся грязи.</li>
<li>Подождать 24 часа без использования посуды перед агалой.</li>
<li>Довести большую кастрюлю воды до сильного кипения.</li>
<li>Полностью погрузить посуду в кипящую воду, убедившись, что все её поверхности контактируют с водой при этой температуре.</li>
<li>Извлечь её инструментом, который не контактировал с некошерной едой, и ополоснуть холодной водой.</li>
</ul>
<p>Посуда с деревянной или пластиковой ручкой, или с деталями, приклеенными клеем, не выдерживающим кипящую воду, обычно не подходит для агалы и требует другого метода, либо вообще не может быть откошерована. Антипригарные сковороды (тефлон) также обычно не откошеровывают агалой, поскольку покрытие повреждается от тепла.</p>',
            ],
            'vajilla-para-pesaj' => [
                'title' => 'Посуда для Песаха: всё, что нужно знать',
                'excerpt' => 'Во время Песаха действуют более строгие правила, чем в остальное время года, в отношении кухонной посуды из-за запрета хамеца.',
                'content' => '<p>Песах — праздник с самыми строгими пищевыми правилами в еврейском календаре, потому что помимо обычных норм кашрута добавляется полный запрет на употребление или владение хамецом (ферментированными продуктами из пяти видов злаков: пшеницы, ячменя, овса, ржи и спельты).</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/vajilla-para-pesaj.jpg" alt="Многие семьи держат отдельный набор посуды только для Песаха, убранный весь остальной год." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Многие семьи держат отдельный набор посуды только для Песаха, убранный весь остальной год. Фото: Silar через <a href="https://commons.wikimedia.org/wiki/File%3A020210817_135854_Seder_plates%2C_brass_plate%2C_%C4%86miel%C3%B3w_porcelain%2C_19th-early_20th_century%2C_Category%2C_Passover_in_Galicia.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Поскольку хамец мог контактировать с кастрюлями, тарелками и приборами в течение всего года, многие семьи предпочитают иметь отдельный набор посуды, исключительно для Песаха, хранимый остальную часть года. Это самый простой вариант, избавляющий от необходимости откошеровывать посуду каждый год.</p>
<p>Те, у кого нет отдельной посуды для Песаха, могут откошеровать некоторые предметы:</p>
<ul>
<li><strong>Металл без покрытия</strong> (кастрюли, столовые приборы): обычно подходит для агалы.</li>
<li><strong>Стекло</strong>: согласно обычаю, одни считают достаточным тщательное мытьё, другие требуют погружения.</li>
<li><strong>Керамика и фарфор</strong>: как правило, не могут быть откошерованы для Песаха, и нужно использовать отдельный набор.</li>
<li><strong>Пластик и резина</strong>: большинство мнений не допускает их откошерования.</li>
</ul>
<p>Перед Песахом стоит проконсультироваться с конкретным руководством по откошерованию общины или местного сертификатора, поскольку сроки и точные методы могут варьироваться в зависимости от типа материала и использования предмета в течение года.</p>',
            ],
            'jametz-pesaj' => [
                'title' => 'Хамец: что это и как его устранить перед Песахом',
                'excerpt' => 'Хамец — это ферментированная пища, запрещённая во время Песаха. Знание того, какие продукты его содержат, важно для подготовки к празднику.',
                'content' => '<p>Хамец — это любой продукт, изготовленный из одного из пяти видов злаков — пшеницы, ячменя, овса, ржи или спельты, — который вступил в контакт с водой и ферментировался (поднялся) более 18 минут без выпекания. Это включает хлеб, пиво, большинство видов пасты, печенье и огромное количество промышленных продуктов, использующих эти злаки в качестве ингредиента или производного.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/jametz-pesaj.jpg" alt="В Песах действует полный запрет и на употребление, и на владение хамецем." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">В Песах действует полный запрет и на употребление, и на владение хамецем. Фото: Ministry of Information Photo Division Photographer через <a href="https://commons.wikimedia.org/wiki/File%3AAllied_Forces_Celebrate_Passover-_Jewish_Traditions_in_Wartime_Britain%2C_April_1944_D19336.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>
<p>Тора запрещает не только есть хамец во время Песаха, но и владеть им. Поэтому за недели до праздника еврейские семьи проводят глубокую уборку дома (бдикат хамец), чтобы удалить любые остатки хлеба, муки или продуктов с хамецом из шкафов, машин, сумок и любого уголка, куда могла упасть крошка.</p>
<p>Для хамеца, который нельзя или невыгодно выбросить (например, дорогие или труднозаменяемые продукты), существует возможность символически "продать" его нееврею через контракт, называемый <em>мехират хамец</em>, обычно координируемый раввином общины. Проданный хамец хранится закрытым и отдельно во время праздника и автоматически "выкупается обратно" по окончании Песаха.</p>
<p>В ночь перед Песахом проводится ритуальный поиск хамеца по всему дому (бдикат хамец), обычно со свечой, пером и деревянной ложкой, за которым следует сжигание найденного (биур хамец) на следующее утро.</p>',
            ],
            'vino-kosher' => [
                'title' => 'Кошерное вино: почему оно требует особого надзора',
                'excerpt' => 'У вина особый статус в галахе: чтобы быть кошерным, оно должно изготавливаться и обрабатываться исключительно соблюдающими евреями.',
                'content' => '<p>Некоторое время назад нам довелось побывать на винодельне в Мендосе, производящей кошерное вино, и увидеть процесс вблизи — это помогает понять, почему у вина такие непохожие на другие продукты правила. Почти с любым другим продуктом достаточно, чтобы ингредиенты и процесс соответствовали определённым требованиям. С вином не так: галаха требует, чтобы каждый, кто прикасается к нему в процессе производства — от момента, когда виноград поступает, до розлива в бутылки, — был соблюдающим евреем. Причина историческая: вино использовалось в идолопоклоннических ритуалах, отсюда и это ограничение.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/barricas-roble-vino.jpg" alt="Бочковый зал винодельни (иллюстративное фото, не та винодельня в Мендосе, где мы были). На кошерной винодельне каждая бочка ещё и опечатана — это гарантия, что её не открывал никто вне надзора." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Бочковый зал винодельни (иллюстративное фото, не та винодельня в Мендосе, где мы были). На кошерной винодельне каждая бочка ещё и опечатана — это гарантия, что её не открывал никто вне надзора. Фото: Subhashish Panigrahi через <a href="https://commons.wikimedia.org/wiki/File:Oak_barrels_used_for_aging_of_wine_in_a_cellar_at_Grover_Zampa_Vineyard,_Doddaballapura,_Karnataka,_India.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>На практике это создаёт довольно особое разделение труда. Тот, кто разбирается в вине — это винодел, который обычно не еврей (гой), и именно он указывает, что делать на каждом этапе. Но тот, кто фактически перемещает вино, открывает краны и делает всё, что связано с прикосновением к продукту, — всегда соблюдающий еврей (иегуди). Специалист руководит, иегуди исполняет, и всё это под постоянным раввинским надзором.</p>
<p>Вино отдыхает в разных условиях в зависимости от этапа, и часть его хранится в дубовых бочках, которые можно использовать повторно примерно три раза, прежде чем они теряют свои свойства. Каждая бочка опечатана, и эта печать — не мелочь: это гарантия того, что никто посторонний не прикасался к содержимому. Нам рассказали случай, показывающий, насколько далеко заходит это требование. В бочке на тысячи литров заметили, что печати не было именно там, где она должна была стоять: бочка оказалась открытой. Раввину Буэнос-Айреса пришлось лично приехать проверить ситуацию, и он постановил, что это вино осталось без надзора. Результат: те тысячи литров уже нельзя было продавать как кошерные.</p>
<p>Существует также особая категория — <strong>вино мевушаль</strong> ("варёное"), пастеризованное при определённой температуре. Как только вино становится мевушаль, оно сохраняет кошерный статус, даже если потом его подаёт или трогает нееврей. Поэтому оно так удобно для мероприятий, ресторанов и кейтеринга, где невозможно проконтролировать, кто берёт каждую бутылку.</p>
<p>Сегодня качественное кошерное вино есть почти во всех винодельческих регионах мира. Мендоса — важный центр в Аргентине, а кошерное вино также производится в Чили, Франции, Испании, Италии и, конечно, в Израиле, сертифицированное ведущими раввинскими агентствами.</p>',
            ],
            'gelatina-kosher' => [
                'title' => 'Кошерный желатин: галахический спор',
                'excerpt' => 'Желатин — один из самых обсуждаемых ингредиентов в мире кашрута, потому что его животное происхождение может поставить под угрозу его статус.',
                'content' => '<p>Традиционный желатин получают, вываривая кости, кожу и соединительную ткань животных — обычно коров или свиней — до извлечения коллагена. Это создаёт две проблемы с точки зрения кашрута: происхождение животного (кошерный ли это вид?) и метод обработки (была ли животное забито по шхите?).</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/gelatina-kosher.jpg" alt="Сладости и десерты — продукты, где чаще всего встречается желатин, самый спорный ингредиент кашрута." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Сладости и десерты — продукты, где чаще всего встречается желатин, самый спорный ингредиент кашрута. Фото: Sakurai Midori через <a href="https://commons.wikimedia.org/wiki/File%3ASweets_Offering_for_Obon.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>
<p>Десятилетиями различные раввинские авторитеты спорили о том, меняет ли желатин, проходя через столь радикальный химический процесс, свой галахический статус (концепция, называемая <em>паним хадашот</em>, или полная трансформация). Некоторые более снисходительные мнения утверждали, что процесс настолько радикален, что конечный продукт уже не считается мясом в галахическом смысле; однако большинство основных кошерных сертификаторов не принимают эту позицию в отношении желатина некошерного происхождения.</p>
<p>Поэтому сегодня подавляющее большинство продуктов с кошерной сертификацией, требующих желатин (конфеты, десерты, капсулы лекарств, маршмеллоу), используют сертифицированные альтернативы:</p>
<ul>
<li>Кошерный рыбный желатин.</li>
<li>Говяжий желатин от животных, забитых по шхите.</li>
<li>Растительные заменители, такие как агар-агар или пектин, полностью избегающие этого спора.</li>
</ul>
<p>Когда продукт имеет печать признанного сертификатора, больше нет необходимости исследовать происхождение желатина: сертификация гарантирует, что этот момент уже проверен.</p>',
            ],
            'alcohol-bebidas-espirituosas' => [
                'title' => 'Алкоголь и крепкие напитки: что нужно для того, чтобы они были кошерными',
                'excerpt' => 'Виски, водка, ром и другие крепкие напитки часто кошерны по своей природе, но есть важные исключения, которые стоит учитывать.',
                'content' => '<p>Большинство крепких напитков — виски, водка, ром, джин — изготавливаются из злаков, картофеля или сахарного тростника, ингредиентов, которые сами по себе не создают проблем кашрута. Поэтому многие простые крепкие напитки кошерны без необходимости специальной сертификации, при условии что не добавлены ароматизаторы, красители или добавки некошерного происхождения.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/alcohol-bebidas-espirituosas.jpg" alt="Многие дистилляты кошерны по составу, но выдержка в винных бочках может изменить их статус." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Многие дистилляты кошерны по составу, но выдержка в винных бочках может изменить их статус. Фото: 4028mdk09 через <a href="https://commons.wikimedia.org/wiki/File%3ABarbestand.JPG" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>
<p>Однако есть важные моменты, на которые стоит обратить внимание:</p>
<ul>
<li><strong>Выдержка в винных или хересных бочках:</strong> некоторые виски и ромы выдерживаются в бочках, ранее содержавших некошерное вино, что может поставить под угрозу их статус.</li>
<li><strong>Ароматизаторы и добавки:</strong> ликёры со вкусом крема, шоколада или фруктов часто включают ингредиенты, требующие проверки.</li>
<li><strong>Напитки на винной основе</strong> (например, вермут или некоторые ликёры): наследуют все ограничения кошерного вина, включая необходимость раввинского надзора при их производстве.</li>
<li><strong>Пиво:</strong> обычно кошерно благодаря своим базовым ингредиентам (вода, ячмень, хмель, дрожжи), за исключением вариантов со специальными ароматизаторами.</li>
</ul>
<p>Во время Песаха, кроме того, нужно проявлять особое внимание, поскольку многие крепкие напитки изготавливаются из злаков, составляющих хамец, поэтому в это время года требуется специальная сертификация "кошерно для Песаха".</p>',
            ],
            'comer-kosher-restaurante' => [
                'title' => 'Как питаться кошерно в несертифицированном ресторане',
                'excerpt' => 'Путешествие или поход в ресторан без кошерного заведения поблизости не означает нарушение диеты. Есть варианты остаться в рамках правил.',
                'content' => '<p>Сертифицированный кошерный ресторан не всегда доступен, особенно при путешествиях или проживании в городах со слабой общинной инфраструктурой. Тем не менее существуют стратегии оставаться в рамках кашрута в обычных ресторанах.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/comer-kosher-restaurante.jpg" alt="Даже когда рядом нет сертифицированного ресторана, есть способы соблюдать кашрут." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Даже когда рядом нет сертифицированного ресторана, есть способы соблюдать кашрут. Фото: Sohail1308 через <a href="https://commons.wikimedia.org/wiki/File%3AAl_Fanar_Restaurant.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<ul>
<li><strong>Вегетарианские или веганские варианты:</strong> убирая мясо и молочные продукты из блюда, риск значительно снижается, хотя всё равно необходимо проверять ингредиенты (мясной бульон, сливочное масло, соусы на животной основе).</li>
<li><strong>Сырые фрукты и овощи:</strong> без приготовления и сложной обработки они обычно являются безопасным вариантом практически везде.</li>
<li><strong>Рыба с плавниками и чешуёй:</strong> в ресторанах простой кухни рыба на гриле без соусов может быть разумной альтернативой для тех, кто придерживается более гибкого подхода (при условии, что она не готовится вместе с морепродуктами или некошерным мясом на том же оборудовании, в зависимости от критериев каждого человека).</li>
<li><strong>Бутилированные и запечатанные напитки:</strong> вода, газировка и соки в оригинальной упаковке обычно не вызывают проблем.</li>
</ul>
<p>Вот очень конкретный пример того, как это решается на практике. Однажды мы поехали в отпуск в Мар-дель-Плату с ещё маленькими детьми, и, подъезжая по проспекту Конститусьон, жена вспомнила, что забыла печенье. Зимой в тех краях найти кошерный хлеб или печенье практически невозможно. Что давать детям? Дома мы обычно не ели определённые упакованные снеки, но в этой крайней ситуации мы обратились к списку Ajdut Kosher — путеводителю по одобренным продуктам на полках супермаркетов, который публикует эта сертификация. Мы просмотрели разрешённые марки и нашли печенье вроде "Travitas", которое было в списке. Это нас спасло.</p>
<p>Вывод: когда путешествуешь, иметь под рукой список одобренных продуктов надёжной сертификации — это на вес золота. Огромное количество обычных супермаркетских продуктов кошерны, даже если на упаковке не напечатана большая печать, и знание этого списка открывает варианты там, где, казалось, их совсем нет.</p>
<p>У каждого человека и каждой общины свой уровень строгости относительно того, что считается приемлемым вне сертифицированного ресторана (некоторые едят только упакованные и запечатанные продукты, другие принимают определённые простые блюда). В случае сомнений рекомендуется проконсультироваться с раввином общины о том, какому критерию следовать.</p>',
            ],
            'simbolos-certificacion-kosher' => [
                'title' => 'Наиболее распространённые символы кошерной сертификации',
                'excerpt' => 'OU, OK, Star-K, KSA... в мире существуют десятки символов кошерной сертификации. Поможем вам распознать самые используемые.',
                'content' => '<p>Когда продукт проходит процесс кошерной сертификации, сертифицирующее агентство разрешает использовать символ (hechsher) на упаковке, позволяющий идентифицировать его с первого взгляда. В мире существуют сотни сертификаторов, но некоторые особенно известны своим глобальным охватом.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/simbolos-certificacion-kosher.jpg" alt="Гехшер — сертификат кашрута, который раввинское агентство выдаёт заведению или продукту." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Гехшер — сертификат кашрута, который раввинское агентство выдаёт заведению или продукту. Фото: Utilisateur:Djampa - User:Djampa через <a href="https://commons.wikimedia.org/wiki/File%3AHechsher_Safed_Rabbinate.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<ul>
<li><strong>OU (Orthodox Union):</strong> буква "U" в круге. Вероятно, самый узнаваемый кошерный символ в мире, базирующийся в США.</li>
<li><strong>OK Kosher Certification:</strong> буква "K" в круге, ещё одно крупное американское агентство.</li>
<li><strong>Star-K:</strong> звезда с буквой "K" в центре.</li>
<li><strong>KSA (Kosher Supervision of America):</strong> сертификатор с сильным присутствием в промышленных продуктах.</li>
<li><strong>Бадац:</strong> печать, используемая несколькими раввинскими судами в Израиле, ассоциируемая с очень высокими стандартами строгости.</li>
<li><strong>Местные сертификаторы:</strong> в таких странах, как Аргентина, Бразилия или Мексика, существуют местные общинные сертификаторы (например, Ваад ха-Кашрут каждой общины) со своими печатями.</li>
</ul>
<p>Помимо символа, многие этикетки включают дополнительную букву: "D" (молочный), "M" (мясной), "Pareve" (парве) или "DE" (изготовлено на молочном оборудовании, но без прямых молочных ингредиентов). Знание этих символов значительно ускоряет покупки, особенно при путешествиях в страны, язык которых не знаком.</p>
<p>Стоит пояснить то, что не всегда понимают: хехшер — это не разовая формальность, которая остаётся действительной навсегда. Это активный, постоянный надзор. Нам известен случай с пекарней в Буэнос-Айресе, у которой был сертификат общинного агентства. В какой-то момент раввин-надзиратель заметил странности и отправил людей проверить, почти как детектив. Выяснилось, что пекарня покупала дульсе де лече без надзора, хотя должна была использовать только продукты Халав Исраэль (молочные продукты, произведённые под еврейским надзором). Её предупредили и попросили исправить ситуацию. Вскоре, не зная, что за ней продолжают следить, она снова была замечена покупающей обычный сыр без сертификации, и это стало последней каплей: надзор с неё сняли. Всё было улажено без огласки, без скандала, просто перестав сертифицировать это заведение.</p>
<p>Вывод для потребителя ясен: печать имеет ценность, потому что за ней стоит кто-то, кто действительно проверяет, постоянно. Поэтому стоит доверять признанным сертификаторам и, встретив незнакомый символ, спрашивать в общине, прежде чем считать продукт кошерным.</p>',
            ],
            'que-significa-pareve' => [
                'title' => 'Парве: что это значит и почему так часто встречается на этикетках',
                'excerpt' => 'Парве — одно из самых часто повторяемых слов в кошерной маркировке. Объясняем, что это значит и почему так ценится.',
                'content' => '<p>"Парве" описывает продукты, которые не являются ни мясными, ни молочными: фрукты, овощи, яйца, рыба, злаки и большинство продуктов, изготовленных без молочных или мясных ингредиентов животного происхождения.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/que-significa-pareve.jpg" alt="Свежие фрукты и овощи парве по своей природе: сочетаются и с мясным, и с молочным." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Свежие фрукты и овощи парве по своей природе: сочетаются и с мясным, и с молочным. Фото: PattayaPatrol через <a href="https://commons.wikimedia.org/wiki/File%3ADFC_2197_A_colorful_assortment_of_fresh_fruits_and_vegetables_-_apples_mango_dragon_fruit_kiwis_limes_bananas_and_more_-_arranged_on_a_wooden_crate.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Главное преимущество продукта парве — его гибкость: его можно свободно сочетать как с мясными, так и с молочными блюдами, не создавая никакого конфликта кашрута. Поэтому многие пищевые компании активно разрабатывают версии парве продуктов, традиционно содержащих молочные компоненты — например, шоколад, маргарин или заменители сливок, — чтобы расширить свой рынок.</p>
<p>Важно прояснить нюанс: продукт может быть парве по ингредиентам, но потерять этот статус, если он изготовлен на оборудовании, которое также обрабатывает молочные продукты или мясо, в зависимости от возможных следов. Поэтому сертификация анализирует не только ингредиенты, но и производственное оборудование и процессы очистки между партиями.</p>
<p>Некоторые распространённые примеры продуктов парве: оливковое масло, сухие макароны без яиц, большинство видов хлеба (хотя некоторые содержат сливочное масло и становятся молочными), необработанные орехи и газированные напитки. Всегда проверять этикетку — единственный надёжный способ подтверждения, поскольку рецепт может меняться между брендами или даже между разными вариантами одного бренда.</p>',
            ],
            'shejita-sacrificio-kosher' => [
                'title' => 'Шхита: метод кошерного забоя',
                'excerpt' => 'Чтобы мясо кошерного животного было пригодно для употребления, оно должно быть забито согласно особому ритуальному методу, называемому шхита.',
                'content' => '<p>Шхита — это еврейский ритуальный метод забоя, выполняемый шохетом (обученным и сертифицированным резчиком) с использованием чрезвычайно острого ножа без зазубрин, специально разработанного для выполнения быстрого и точного разреза горла животного, рассекающего трахею и пищевод одним непрерывным движением.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/shejita-sacrificio-kosher.svg" alt="Халеф проверяют до и после каждого животного: одна зазубрина делает процедуру недействительной." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Халеф проверяют до и после каждого животного: одна зазубрина делает процедуру недействительной. Иллюстрация: KosherMap.</figcaption>
</figure>
<p>Цель этого метода — минимизировать страдания животного и вызвать практически мгновенную потерю сознания. Шохет проверяет нож до и после каждого забоя, чтобы убедиться в отсутствии каких-либо изъянов, даже самых незначительных, поскольку любая неровность делает процедуру недействительной.</p>
<p>Один из наших родственников работает в шхите, и это даёт представление о том, насколько дотошным бывает контроль на практике. Халаф (нож) проверяет не только шохет до и после каждого животного: есть ещё и надзиратель, который проверяет все ножи, день за днём, по чрезвычайно строгому критерию. Изъян, который даже не заметен невооружённым глазом, достаточен, чтобы вывести нож из использования. Это среда предельной серьёзности и точности, где при любой нестандартной ситуации первым делом всё останавливают и ставят безопасность на первое место.</p>
<p>После шхиты проводится осмотр (бдика) внутренних органов животного, особенно лёгких, чтобы исключить заболевания или спайки, которые сделали бы мясо некошерным. Только животные, прошедшие эту проверку, считаются пригодными.</p>
<p>Кроме того, мясо должно пройти процесс засолки (кашерование) для удаления крови, поскольку Тора запрещает употребление крови. Это делается путём замачивания мяса в воде, посолки и выдержки перед повторным ополаскиванием — процесс, который сегодня обычно выполняет сама сертифицированная мясная лавка или бойня до того, как продукт попадёт к потребителю.</p>',
            ],
            'glatt-kosher' => [
                'title' => 'Глатт кошер: чем отличается от обычного кошер',
                'excerpt' => 'Термин "глатт" часто встречается в кошерных мясных лавках и ресторанах. Объясняем, какой уровень строгости он представляет.',
                'content' => '<p>"Глатт" означает "гладкий" на идише и изначально относился конкретно к состоянию лёгких животного после шхиты: если лёгкие не имели спаек (сирха), животное считалось "глатт" — высшим уровнем уверенности в том, что мясо бесспорно кошерно.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/glatt-kosher.jpg" alt="Кошерный мясной ресторан в Иерусалиме. «Глатт» — самый строгий стандарт проверки." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Кошерный мясной ресторан в Иерусалиме. «Глатт» — самый строгий стандарт проверки. Фото: brionv from San Francisco, United States через <a href="https://commons.wikimedia.org/wiki/File%3AJerusalem_MMMM_MEAT_%286036353902%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 2.0.</figcaption>
</figure>
<p>Со временем, особенно в ашкеназских общинах США, термин "глатт кошер" разговорно расширился, чтобы описывать общий стандарт большей строгости на протяжении всей производственной цепочки продукта, а не только при осмотре лёгких. Сегодня обычно можно увидеть "глатт кошер" на этикетках ресторанов и продуктов, чтобы указать, что они соответствуют самым строгим возможным критериям.</p>
<p>Важно подчеркнуть, что "кошерный" продукт без маркировки "глатт" не менее действителен с галахической точки зрения: он просто следует другому стандарту сертификации, как правило, принятому подавляющим большинством общин. Выбор между стандартным кошер и глатт кошер обычно зависит от семейного или общинного обычая, а не от объективного различия в действительности.</p>
<p>В случае птицы и рыбы понятие "глатт" технически не применяется так же, как у млекопитающих, хотя в разговорной речи иногда используется для обозначения в целом более строгого уровня надзора.</p>',
            ],
            'como-leer-etiqueta-kosher' => [
                'title' => 'Как читать этикетку кошерного продукта',
                'excerpt' => 'Помимо символа сертификации, кошерные этикетки содержат ключевую информацию о том, подходит ли продукт для вашего стола.',
                'content' => '<p>Правильное чтение кошерной этикетки выходит за рамки поиска символа сертификации. Есть несколько элементов, которые всегда стоит проверять:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/como-leer-etiqueta-kosher.jpg" alt="Этикетка израильского продукта: кроме знака, стоит смотреть категорию и состав." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Этикетка израильского продукта: кроме знака, стоит смотреть категорию и состав. Фото: Chenspec через <a href="https://commons.wikimedia.org/wiki/File%3AList_of_ingredients_of_food_products_in_Israel_02.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<ul>
<li><strong>Символ сертификатора:</strong> определяет, какое агентство контролировало продукт. Важно распознавать надёжных сертификаторов, поскольку не все символы в мире имеют одинаковый уровень строгости.</li>
<li><strong>Категория:</strong> "Dairy" или "D" (молочный), "Meat" или "M" (мясной), "Pareve" (парве) или "Fish" (рыба, которая во многих традициях рассматривается как отдельная от мяса категория).</li>
<li><strong>"Кошерно для Песаха":</strong> дополнительное обозначение, необходимое во время праздника, отличное от обычной кошерной сертификации в остальное время года.</li>
<li><strong>Дата сертификации:</strong> некоторые сертификаторы включают код или дату, чтобы подтвердить, что печать всё ещё действительна, поскольку рецепты и заводские процессы могут меняться.</li>
</ul>
<p>Когда у продукта нет видимой сертификации, но список ингредиентов выглядит простым (например, только вода, соль и овощ), некоторые люди предпочитают исследовать дальше, но общая рекомендация авторитетов кашрута — не предполагать, что продукт кошерен только по простому виду его ингредиентов: многие добавки и промышленные процессы не очевидны на первый взгляд.</p>
<p>На KosherMap вы можете искать продукты по названию или штрих-коду и фильтровать напрямую по сертификатору, категории и типу, чтобы не зависеть только от физической этикетки.</p>',
            ],
            'bishul-akum' => [
                'title' => 'Бишуль акум: почему некоторые приготовленные продукты требуют еврейского надзора',
                'excerpt' => 'Существует особая категория раввинских законов о пище, приготовленной неевреями, известная как бишуль акум. Объясняем, в чём суть.',
                'content' => '<p>Бишуль акум (буквально "приготовление неевреем") — это категория раввинских законов, ограничивающих потребление определённых продуктов, полностью приготовленных неевреем, даже если все ингредиенты кошерны. Запрет был установлен талмудическими мудрецами, в основном для содействия социальной сплочённости и предотвращения культурной ассимиляции.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/bishul-akum.jpg" alt="Бишуль акум касается блюд, полностью приготовленных неевреем, даже если ингредиенты кошерны." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Бишуль акум касается блюд, полностью приготовленных неевреем, даже если ингредиенты кошерны. Фото: Seattle Municipal Archives from Seattle, WA через <a href="https://commons.wikimedia.org/wiki/File%3ACooks_in_kitchen%2C_1930_%2820981103874%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<p>Этот закон применяется не ко всем продуктам: обычно он ограничивается продуктами, считающимися "достойными подачи на царском столе" (хашивут) и не употребляемыми сырыми. Поэтому фрукты, сырые овощи и большинство промышленных закусок не подпадают под эту категорию.</p>
<p>Существует два распространённых способа решения этой проблемы в контексте промышленного производства или сертифицированных ресторанов:</p>
<ul>
<li>Соблюдающий еврей активно участвует в процессе приготовления, например, зажигая огонь или оборудование для приготовления.</li>
<li>Раввинский надзор подтверждает, что еврейский представитель присутствовал при включении кулинарного оборудования в каждую производственную смену.</li>
</ul>
<p>Это одна из причин, по которой кошерная сертификация пищевой фабрики не ограничивается проверкой ингредиентов: она также контролирует процессы, присутствие персонала и операционные протоколы предприятия, что делает работу сертификаторов гораздо более сложной, чем простой контрольный список сырья.</p>',
            ],
            'vino-mevushal' => [
                'title' => 'Мевушаль: кошерное вино, которое можно подавать без ограничений',
                'excerpt' => 'Мевушаль — особая категория вина, позволяющая подавать его на мероприятиях без требования, чтобы его трогали только евреи.',
                'content' => '<p>Как мы видели, говоря о кошерном вине, общее правило требует, чтобы только соблюдающие евреи трогали вино от производства до подачи. Вино мевушаль ("варёное" или пастеризованное) — практическое исключение из этого правила: после того как вино проходит процесс нагрева при определённой минимальной температуре, оно сохраняет свой кошерный статус независимо от того, кто его подаёт впоследствии.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/vino-mevushal.jpg" alt="Вино мевушаль пастеризовано, поэтому сохраняет кошерность, даже если его наливает нееврей." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Вино мевушаль пастеризовано, поэтому сохраняет кошерность, даже если его наливает нееврей. Фото: misbehave через <a href="https://commons.wikimedia.org/wiki/File%3ABottle_%26_glass_of_red_Bordeaux_style_blend.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<p>Эта категория существует благодаря галахическому принципу, согласно которому вино, изменённое теплом, теряет ритуальное "достоинство", изначально мотивировавшее ограничение, поскольку исторически это опасение было направлено на использование вина в идолопоклоннических церемониях — то, для чего варёное вино не годилось в этом контексте.</p>
<p>Вино мевушаль очень популярно для:</p>
<ul>
<li>Кейтеринга и мероприятий, где обслуживающий персонал не обязательно еврейский.</li>
<li>Кошерных ресторанов, открытых для широкой публики.</li>
<li>Авиакомпаний и отелей, предлагающих кошерные варианты.</li>
</ul>
<p>Сегодня существуют современные методы быстрой пастеризации (flash pasteurization), позволяющие производить высококачественное вино мевушаль, что исторически было труднее достичь без ущерба для вкуса вина. Это значительно расширило ассортимент премиальных вин мевушаль на рынке.</p>',
            ],
            'tevilat-kelim' => [
                'title' => 'Твилат келим: ритуальное погружение новой посуды',
                'excerpt' => 'Перед первым использованием некоторых новых кухонных предметов, изготовленных неевреем, существует обычай погружать их в микву.',
                'content' => '<p>Твилат келим — это практика погружения новой кухонной посуды — металлической или стеклянной, купленной у нееврейского производителя — в микву (ритуальную купель) или природный источник воды перед первым использованием для пищи.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/tevilat-kelim.jpg" alt="Миква — ритуальный бассейн, где новую утварь окунают перед первым использованием." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Миква — ритуальный бассейн, где новую утварь окунают перед первым использованием. Фото: Stefan Walkowski через <a href="https://commons.wikimedia.org/wiki/File%3AMikveh.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Этот обычай в основном применяется к посуде, находящейся в прямом контакте с пищей: кастрюлям, сковородам, столовым приборам, стеклянным тарелкам и стаканам. Обычно он не применяется к электроприборам (например, тостеру или блендеру) или к пластиковой или деревянной посуде, хотя мнения варьируются в зависимости от традиции каждой общины, поэтому рекомендуется консультироваться с раввином по конкретным случаям.</p>
<p>Сам процесс прост: посуда хорошо очищается (без остатков этикеток, пломб или клея), полностью погружается в воду миквы во время произнесения благословения, и после этого она готова к обычному использованию.</p>
<p>Во многих общинных миквах есть конкретное время, выделенное для твилат келим, отдельно от личного ритуального использования, а также подробные инструкции о том, какие материалы требуют погружения, а какие нет. Это одна из тех практик, которая, хотя и может показаться незначительной деталью, является неотъемлемой частью того, как многие соблюдающие еврейские семьи оснащают свою кухню.</p>',
            ],
            'armar-cocina-kosher' => [
                'title' => 'Как организовать кошерную кухню с нуля',
                'excerpt' => 'Начало соблюдения кошерной кухни поначалу может показаться сложным. Даём практическое руководство по первым шагам.',
                'content' => '<p>Организация кошерной кухни с нуля — это постепенный процесс, и не нужно решать всё за один день. Вот наиболее распространённые шаги, которым следуют начинающие семьи:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/armar-cocina-kosher.jpg" alt="Обустройство кошерной кухни — постепенный процесс: не нужно делать всё за один день." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Обустройство кошерной кухни — постепенный процесс: не нужно делать всё за один день. Фото: MeRyan через <a href="https://commons.wikimedia.org/wiki/File%3AKitchen_with_island%2C_New_Orleans_2007.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<ul>
<li><strong>Определить физическое разделение:</strong> установить, какая посуда, кастрюли и столовые приборы будут мясными, а какие молочными. Самый практичный подход — обычно использование разных цветов (например, красный для мяса, синий для молока), чтобы избежать ежедневной путаницы.</li>
<li><strong>Разделить рабочие поверхности:</strong> разделочные доски, кухонные полотенца и губки также должны быть разделены по категориям.</li>
<li><strong>Оценить общие приборы:</strong> духовку, микроволновку и посудомоечную машину можно откошеровывать между использованиями или, проще, изначально закрепить за одной категорией (например, микроволновку только для парве).</li>
<li><strong>Покупать сертифицированные продукты:</strong> проверять символ сертификации при каждой покупке, пока это не станет автоматической привычкой.</li>
<li><strong>Координировать с раввином:</strong> особенно для откошерования предметов, которые уже были на кухне до начала этого процесса.</li>
</ul>
<p>Стратегия, часто используемая начинающими, — постепенно внедрять разделение: сначала предметы повседневного использования, затем столовую посуду, и, наконец, бытовую технику. Не обязательно заменять всю кухню сразу, и многим семьям требуются месяцы для завершения перехода, и это само по себе не является галахической проблемой.</p>',
            ],
            'certificaciones-kosher-mundo' => [
                'title' => 'Различия между кошерными сертификациями по всему миру',
                'excerpt' => 'Не все кошерные сертификаторы следуют точно одинаковым критериям. Знание этих различий помогает уверенно выбирать продукты.',
                'content' => '<p>Хотя фундаментальные принципы кашрута универсальны, в мире существуют сотни сертифицирующих агентств, и у каждого могут быть несколько разные критерии по конкретным темам — например, какой уровень надзора требуется для бишуль акум, или как они подходят к определённым химическим добавкам, происхождение которых трудно отследить.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/certificaciones-kosher-mundo.svg" alt="Принципы кашрута универсальны, но в каждом регионе свои сертифицирующие агентства." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Принципы кашрута универсальны, но в каждом регионе свои сертифицирующие агентства. Иллюстрация: KosherMap.</figcaption>
</figure>
<p>Некоторые распространённые различия между регионами:</p>
<ul>
<li><strong>США:</strong> здесь находятся крупнейшие промышленные сертификаторы (OU, OK, Star-K, Kof-K), с очень стандартизированными процессами для массового экспорта.</li>
<li><strong>Израиль:</strong> Раввинат предлагает официальную государственную сертификацию, в то время как такие организации, как Бадац, поддерживают дополнительные стандарты, считающиеся более строгими определёнными общинами.</li>
<li><strong>Европа:</strong> такие сертификаторы, как Бейт-дин различных городов (Лондон, Париж, Цюрих), контролируют как местное производство, так и импорт.</li>
<li><strong>Латинская Америка:</strong> у каждой общины обычно есть свой местный Ваад ха-Кашрут (например, в Буэнос-Айресе, Сан-Паулу или Мехико), сертифицирующий как местные продукты, так и рестораны.</li>
</ul>
<p>Для потребителя самое важное — научиться распознавать активных сертификаторов в своём регионе и, в случае сомнений относительно незнакомого символа, проконсультироваться с раввином общины или изучить репутацию агентства, прежде чем доверять продукту. Большинство крупных сертификаторов публикуют на своих сайтах открытые списки сертифицированных продуктов.</p>',
            ],
            'queso-kosher-cuajo' => [
                'title' => 'Кошерный сыр: почему требуется особый сычуг',
                'excerpt' => 'Сыр — один из молочных продуктов с наибольшими кошерными ограничениями, в основном из-за происхождения сычуга, используемого для его изготовления.',
                'content' => '<p>Сычуг (rennet) — это фермент, традиционно используемый для свёртывания молока и отделения сыворотки при изготовлении сыра. Проблема с точки зрения кашрута в том, что традиционный сычуг извлекается из желудка телят, и чтобы быть пригодным, это животное должно быть забито по шхите (методу кошерного забоя) — то, что почти никогда не происходит в обычной сыродельной промышленности.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/queso-kosher-cuajo.jpg" alt="Сыру нужна отдельная сертификация из-за происхождения сычужного фермента." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Сыру нужна отдельная сертификация из-за происхождения сычужного фермента. Фото: Daderot через <a href="https://commons.wikimedia.org/wiki/File%3ACheese_display%2C_Cambridge_MA_-_DSC05391.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC0.</figcaption>
</figure>
<p>Поэтому практически весь "обычный" сыр на рынке, даже изготовленный только из молока и сычуга, не является кошерным без специальной сертификации, так как происхождение сычуга невозможно проверить визуально.</p>
<p>Варианты, используемые производителями кошерного сыра, включают:</p>
<ul>
<li><strong>Кошерный животный сычуг:</strong> извлечённый из животных, забитых по шхите и под раввинским надзором на протяжении всей цепочки.</li>
<li><strong>Микробный сычуг:</strong> производимый путём ферментации, без животного происхождения, всё чаще используемый в промышленных и кошерных сырах.</li>
<li><strong>Растительный сычуг:</strong> извлечённый из определённых растений, традиционно используемый в некоторых конкретных сортах кустарных сыров.</li>
</ul>
<p>Помимо сычуга, есть ещё один важный фактор: многие общины требуют, чтобы сыр изготавливался под постоянным еврейским надзором (Гвинат Исраэль), чтобы считать его полностью кошерным — дополнительный критерий помимо простого анализа ингредиентов. Поэтому покупка сыра с признанной сертификацией — самый надёжный способ избежать ошибок.</p>',
            ],
            'huevos-kosher' => [
                'title' => 'Кошерные яйца: что проверять перед использованием',
                'excerpt' => 'Яйца являются парве и обычно кошерны, но перед их приготовлением существует обязательный этап проверки.',
                'content' => '<p>Яйца кошерных птиц (например, курицы) в принципе являются парве и пригодны для употребления. Однако перед использованием яйца традиция требует проверить, что в желтке нет следов крови, поскольку яйцо с кровью считается непригодным для употребления.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/huevos-kosher.jpg" alt="Яйца парве, но перед использованием их нужно проверить на пятна крови." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Яйца парве, но перед использованием их нужно проверить на пятна крови. Фото: Evan-Amos через <a href="https://commons.wikimedia.org/wiki/File%3A6-Pack-Chicken-Eggs.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, Public domain.</figcaption>
</figure>
<p>Процедура проста: разбивая яйцо, желток (а иногда и белок) визуально проверяют на просвет, ища красные точки или пятна. Если обнаружена кровь, яйцо полностью выбрасывается; если желток чист, яйцо пригодно для обычного использования.</p>
<p>Некоторые дополнительные факты о яйцах и кашруте:</p>
<ul>
<li>Скорлупа и белок обычно не представляют такого же риска, как желток, хотя обычай варьируется в зависимости от общины.</li>
<li>Яйца некошерных птиц (например, страуса или некоторых хищных птиц) также непригодны, независимо от наличия крови.</li>
<li>Промышленные продукты с яйцом (например, паста или майонез) обычно проходят процесс контроля качества, включающий автоматическое обнаружение яиц с кровью, но всё равно требуют сертификации, чтобы гарантировать правильность этого контроля.</li>
</ul>
<p>Это одна из самых простых привычек, которую можно внедрить в ежедневную кошерную кухню: проверять каждое яйцо сразу после его разбивания, прежде чем смешивать его с остальными ингредиентами.</p>',
            ],
            'pescado-kosher-aletas-escamas' => [
                'title' => 'Кошерная рыба: плавники и чешуя, основные правила',
                'excerpt' => 'В отличие от мяса, кошерная рыба не требует шхиты, но должна соответствовать определённому физическому критерию.',
                'content' => '<p>Тора устанавливает относительно простое правило для определения кошерной рыбы: у неё должны быть как плавники (снапир), так и чешуя (каскесет), видимые невооружённым глазом. Это сочетание присутствует у подавляющего большинства пресноводных и морских рыб, обычно употребляемых в пищу, таких как лосось, тунец, хек, форель и скумбрия.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/pescado-kosher-aletas-escamas.jpg" alt="Чтобы рыба была кошерной, у неё должны быть видимые плавники и чешуя." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Чтобы рыба была кошерной, у неё должны быть видимые плавники и чешуя. Фото: IndayLiburan через <a href="https://commons.wikimedia.org/wiki/File%3AFish_stalls_at_Valencia_Public_Market_01.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Из кашрута исключаются, среди прочего:</p>
<ul>
<li>Все морепродукты (креветки, лангустины, крабы, мидии, устрицы).</li>
<li>Осьминог и кальмар.</li>
<li>Акула и морской чёрт (по мнению большинства галахических авторитетов, у них нет настоящей чешуи).</li>
<li>Угорь (не имеет видимой чешуи).</li>
<li>Меч-рыба (её статус исторически является предметом споров между разными раввинскими авторитетами).</li>
</ul>
<p>Важное отличие от мяса: кошерная рыба не требует ни шхиты, ни процесса засолки для удаления крови, что значительно упрощает её приготовление. Однако во многих традициях — особенно ашкеназских — рыбу рассматривают как категорию, отдельную от мяса и молочных продуктов, избегая сочетания её с мясом в одном блюде (хотя это не требует такого же строгого разделения посуды, как между мясом и молоком).</p>
<p>При покупке свежей рыбы стоит убедиться, что она сохраняет кожу с видимой чешуёй, поскольку при некоторых способах филетирования кожа полностью удаляется, что затрудняет проверку. Поэтому многие кошерные рыбные лавки оставляют идентифицируемый участок кожи на куске.</p>',
            ],
            'frutos-secos-contaminacion-cruzada' => [
                'title' => 'Орехи и кашрут: риски перекрёстного загрязнения',
                'excerpt' => 'Орехи естественно являются парве, но промышленная обработка может привнести риски кашрута, которые не очевидны.',
                'content' => '<p>Миндаль, грецкие орехи, арахис и большинство орехов в своём сыром, натуральном виде являются продуктами парве без ограничений кашрута. Проблема возникает, когда они попадают в цепочку промышленной обработки, где могут смешиваться с другими продуктами на тех же производственных линиях.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/frutos-secos-contaminacion-cruzada.jpg" alt="В сыром виде они парве без ограничений; риск возникает при промышленной переработке." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">В сыром виде они парве без ограничений; риск возникает при промышленной переработке. Фото: Famartin через <a href="https://commons.wikimedia.org/wiki/File%3A2021-01-06_12_15_43_Cranberry_trail_mix_with_cranberries%2C_peanuts%2C_raisins%2C_walnuts%2C_almonds%2C_sunflower_seeds%2C_pepitas_in_the_Franklin_Farm_section_of_Oak_Hill%2C_Fairfax_County%2C_Virginia.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Некоторые распространённые риски:</p>
<ul>
<li><strong>Молочные ароматизаторы:</strong> орехи, "поджаренные с маслом" или покрытые молочным шоколадом, уже не являются парве.</li>
<li><strong>Общие линии:</strong> завод может обрабатывать орехи парве на том же оборудовании, где впоследствии обрабатываются молочные продукты или мясные производные, создавая некошерные следы при отсутствии сертифицированной очистки между партиями.</li>
<li><strong>Масла для жарки:</strong> некоторые жареные орехи используют масла, общие с другими некошерными продуктами.</li>
<li><strong>Глазури и покрытия:</strong> "карамелизированные" орехи или с сладким покрытием могут содержать желатин или другие ингредиенты животного происхождения.</li>
</ul>
<p>Поэтому, хотя сырой, необработанный орех почти никогда не вызывает проблем, промышленные продукты (ореховые смеси, ароматизированные закуски, зерновые батончики) всегда следует проверять на сертификацию, не предполагая, что они автоматически кошерны только потому, что главный ингредиент таков.</p>',
            ],
            'kashrut-y-veganismo' => [
                'title' => 'Кашрут и веганство: одно ли и то же есть веганскую и кошерную пищу?',
                'excerpt' => 'Многие люди предполагают, что веганский продукт автоматически кошерен. Реальность сложнее.',
                'content' => '<p>Это распространённое заблуждение: если продукт не содержит никаких ингредиентов животного происхождения, казалось бы логичным предположить, что он автоматически кошерен. Однако кашрут основан не только на ингредиентах, но и на процессах производства, используемом оборудовании и, в некоторых случаях, на том, кто контролирует производство.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/kashrut-y-veganismo.jpg" alt="Веганский продукт не автоматически кошерный: кашрут смотрит и на процессы, и на оборудование." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Веганский продукт не автоматически кошерный: кашрут смотрит и на процессы, и на оборудование. Фото: HaJunkiyada через <a href="https://commons.wikimedia.org/wiki/File%3ALiat_Portal_for_Foodie_Disorder_-_Cauliflower_from_SF_farmers%27_market.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Некоторые примеры, когда веганский продукт может не быть кошерным:</p>
<ul>
<li><strong>Общее оборудование:</strong> веганская фабрика может использовать ту же производственную линию, на которой ранее обрабатывались мясные или молочные продукты, без сертифицированной очистки, которую кашрут требует между партиями.</li>
<li><strong>Вино и его производные:</strong> веганское вино (без осветлителей животного происхождения) всё равно требует, чтобы весь процесс производства находился в руках соблюдающих евреев, чтобы быть кошерным.</li>
<li><strong>Насекомые:</strong> определённые красители (например, кармин, животного происхождения) запрещены в кошерной пище, но иногда ошибочно маркируются как подходящие для веганов или по другим стандартам веганской сертификации.</li>
<li><strong>Бишуль акум:</strong> веганская еда, полностью приготовленная неевреем, может подпадать под это ограничение, в зависимости от классификации продукта.</li>
</ul>
<p>С другой стороны, верно и то, что многие кошерные продукты парве на самом деле являются веганскими. Но соответствие не является автоматическим ни в одном направлении: самое надёжное — всегда искать явную кошерную сертификацию, а не предполагать, что "веганский" равно "кошерный".</p>',
            ],
            'separar-la-jala' => [
                'title' => 'Как отделять халу',
                'excerpt' => 'Отделение халы — особая заповедь, применяемая при замешивании теста в больших количествах, корнями уходящая в приношения Храма.',
                'content' => '<p>Отделение халы (хафрашат хала) — библейская заповедь, изначально требовавшая отдавать часть теста для хлеба священникам (коэнам) Иерусалимского Храма. После разрушения Храма практика изменилась: сегодня вместо передачи отделённая часть сжигается или уважительно выбрасывается.</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/jala-shabat.jpg" alt="Плетёная хала, готовая к Шаббату, рядом бокал вина и соль. Салфетка, которой она накрыта, — часть обычая шаббатнего стола." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Плетёная хала, готовая к Шаббату, рядом бокал вина и соль. Салфетка, которой она накрыта, — часть обычая шаббатнего стола. Фото: HaJunkiyada через <a href="https://commons.wikimedia.org/wiki/File:Liat_Portal_for_Foodie_Disorder_-_Challah_for_Shabbat_with_wine_and_salt.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 4.0.</figcaption>
</figure>
<p>Эта мицва применяется при замешивании значительного количества теста из одного из пяти злаков (пшеницы, ячменя, овса, ржи или спельты) — точное минимальное количество (обычно около 1,2 кг муки) варьируется в зависимости от соблюдаемого галахического мнения.</p>
<p>Базовый процесс таков:</p>
<ul>
<li>Замесить тесто для хлеба как обычно, пока оно не достигнет требуемого минимального количества.</li>
<li>Отделить небольшую часть (традиционно размером с маслину или больше, в зависимости от обычая).</li>
<li>Произнести соответствующее благословение перед отделением части.</li>
<li>Сжечь отделённую часть (завёрнутую в алюминиевую фольгу, в духовке) или выбросить её так, чтобы она не использовалась для обычного потребления.</li>
</ul>
<p>Эта практика объясняет, почему многие сертифицированные промышленные кошерные пекарни отделяют халу как часть своего производственного процесса, и почему многие еврейские женщины и семьи делают это дома каждый раз, когда выпекают хлеб или халу на Шаббат в достаточном количестве.</p>
<p>Во многих домах это переживается как особый момент. У нас, например, девочки всегда стараются набрать минимальное количество теста именно для того, чтобы отделить халу с благословением, поскольку делать это с брахой имеет дополнительную ценность. Помимо технического жеста, это превращается в семейный ритуал вокруг духовки.</p>',
            ],
            'calendario-judio-festividades-alimentacion' => [
                'title' => 'Еврейский календарь и праздники, влияющие на кошерное питание',
                'excerpt' => 'У нескольких еврейских праздников есть свои собственные пищевые обычаи, помимо общих правил кашрута.',
                'content' => '<p>Помимо правил кашрута, действующих круглый год, еврейский календарь приносит праздники со своими собственными пищевыми обычаями, которые стоит знать:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/calendario-judio-festividades-alimentacion.jpg" alt="Яблоко, мёд и гранат — символы Рош ха-Шана, одного из праздников со своими пищевыми обычаями." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">Яблоко, мёд и гранат — символы Рош ха-Шана, одного из праздников со своими пищевыми обычаями. Фото: Gilabrand через <a href="https://commons.wikimedia.org/wiki/File%3ASymbols_of_Rosh_Hashana.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY-SA 3.0.</figcaption>
</figure>
<ul>
<li><strong>Рош а-Шана:</strong> принято есть яблоко с мёдом, символизируя сладкий год, и избегать горьких или кислых продуктов на праздничном столе.</li>
<li><strong>Йом Кипур:</strong> день полного 25-часового поста, без еды и питья, за исключением особых медицинских случаев.</li>
<li><strong>Суккот:</strong> принято есть в временной хижине под открытым небом (сукке) на протяжении всей недели праздника.</li>
<li><strong>Ханука:</strong> традиция есть жареную в масле пищу (например, суфганиёт, пончики с начинкой, и латкес, картофельные оладьи) в память о чуде с маслом.</li>
<li><strong>Пурим:</strong> готовят гоменташи (уши Амана), треугольные пирожки с начинкой, и принято делиться корзинами с едой (мишлоах манот) с друзьями и семьёй.</li>
<li><strong>Песах:</strong> праздник с наибольшим количеством пищевых ограничений, сосредоточенный на запрете хамеца, как мы уже подробно рассмотрели.</li>
<li><strong>Шавуот:</strong> обычай есть молочные продукты, с такими блюдами, как чизкейк и блинчики с сыром (блинцес) в качестве главных героев.</li>
</ul>
<p>Знание этого календаря помогает понять, почему определённые продукты (например, маца, суфганиёт или кошерное вино для Песаха) появляются в большем количестве на полках и в магазинах в определённые периоды года.</p>',
            ],
            'errores-comunes-empezar-comer-kosher' => [
                'title' => 'Распространённые ошибки при переходе на кошерное питание',
                'excerpt' => 'Освоение кашрута впервые подразумевает процесс обучения. Рассматриваем самые частые ошибки, чтобы избежать их с самого начала.',
                'content' => '<p>Начало соблюдения кошерной диеты — это процесс, требующий времени, и нормально совершать ошибки в начале. Вот некоторые из самых распространённых:</p>

<figure style="margin:1.5rem 0;">
  <img src="/images/articulos/errores-comunes-empezar-comer-kosher.jpg" alt="В начале самая частая ошибка — считать продукт кошерным, не поискав сертификацию." loading="lazy" style="width:100%;height:auto;border-radius:12px;display:block;">
  <figcaption style="font-size:0.8rem;color:#6b7280;margin-top:0.6rem;line-height:1.5;">В начале самая частая ошибка — считать продукт кошерным, не поискав сертификацию. Фото: Nenad Stojkovic через <a href="https://commons.wikimedia.org/wiki/File%3ACorn_in_a_shopping_trolley._%2851964905166%29.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, CC BY 2.0.</figcaption>
</figure>
<ul>
<li><strong>Предположение, что "натуральный" или "без консервантов" означает кошерный:</strong> маркетинг продукта не имеет прямого отношения к его статусу кашрута. Всегда нужно искать сертификацию.</li>
<li><strong>Не проверять продукты, которые кажутся явно парве:</strong> закуски, выпечка и сладости иногда содержат молочные ингредиенты или желатин, не очевидные из названия продукта.</li>
<li><strong>Случайно смешивать мясную и молочную посуду:</strong> в начале легко забыть о разделении; маркировка или использование разных цветов очень помогает во время перехода.</li>
<li><strong>Не проверять листовые овощи на наличие насекомых:</strong> шаг, о котором многие новички в кашруте совершенно не подозревают.</li>
<li><strong>Доверять неизвестным или неясным сертификациям:</strong> не все символы на упаковке являются настоящими кошерными сертификациями; некоторые из них — знаки качества, не связанные с кашрутом.</li>
<li><strong>Не спрашивать:</strong> многие сомнения быстро разрешаются с помощью консультации с раввином общины или с более опытным человеком, вместо того чтобы гадать.</li>
</ul>
<p>Самое важное — понять, что переход не обязан быть идеальным с первого дня. Большинство еврейских общин ценят постепенный процесс обучения, и существует множество ресурсов — включая сертификаторов, раввинов и инструменты, такие как KosherMap, — чтобы сопровождать этот путь.</p>',
            ],
        ];
    }
}
