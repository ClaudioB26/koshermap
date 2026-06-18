<?php

return [

    'que_es_kosher' => [
        'title'       => 'What is Kosher?',
        'description' => 'Discover what it means for food to be kosher: categories, permitted animals, separation of meat and dairy, and rabbinic certification.',
        'intro'       => 'The word <strong>kosher</strong> (כשר in Hebrew) means "fit" or "proper." In the context of food, a kosher food is one that complies with the dietary laws established in the Torah and developed by rabbinic tradition over the centuries. These laws, collectively known as <em>kashrut</em>, are a central part of observant Jewish life.',
        'sections'    => [
            [
                'title' => 'The Three Fundamental Categories',
                'body'  => '<p>All kosher food belongs to one of three categories:</p>
<ul>
  <li><strong>Basar (בשר — meat):</strong> Meat from permitted mammals and poultry, slaughtered and prepared according to the laws of shechita (ritual slaughter). Includes beef, lamb, chicken, and turkey.</li>
  <li><strong>Chalav (חלב — dairy):</strong> Milk, cheese, butter, yogurt, and any derivative from kosher animals.</li>
  <li><strong>Pareve:</strong> Neutral foods that are neither meat nor dairy: fish with fins and scales, eggs, fruits, vegetables, grains, and legumes. Pareve foods can be eaten with either meat or dairy.</li>
</ul>
<p>One of the most important principles is that <strong>meat and dairy must never be mixed</strong>: not in preparation, not in cooking, and not when eating them together.</p>',
            ],
            [
                'title' => 'Permitted and Forbidden Animals',
                'body'  => '<p>The Torah establishes clear criteria to determine which animals are fit for consumption:</p>
<p><strong>Mammals:</strong> Only those with <em>split hooves</em> that also <em>chew their cud</em> are permitted. Cows, sheep, goats, and deer meet both requirements. Pigs have split hooves but do not chew their cud — they are forbidden. Rabbits chew their cud but lack split hooves — also forbidden.</p>
<p><strong>Poultry:</strong> The Torah lists forbidden birds (mainly birds of prey and scavengers). By tradition, chicken, turkey, duck, and goose are permitted. All must be slaughtered via shechita.</p>
<p><strong>Fish:</strong> Only those with both <em>fins</em> and <em>scales</em> are kosher. Salmon, tuna, hake, and carp are examples. Shellfish (shrimp, lobster, mussels, crabs), squid, and octopus are completely forbidden, as are scaleless fish like eel and catfish.</p>',
            ],
            [
                'title' => 'Separation of Meat and Dairy',
                'body'  => '<p>The prohibition against mixing meat with dairy originates from three biblical verses repeating: <em>"You shall not boil a young goat in its mother\'s milk"</em> (Exodus 23:19, 34:26; Deuteronomy 14:21).</p>
<p>In practice, this means:</p>
<ul>
  <li><strong>Separate utensils:</strong> Pots, pans, plates, cutlery, and countertops are kept separate for meat and dairy.</li>
  <li><strong>Waiting periods:</strong> After eating meat, one must wait before eating dairy. Ashkenazi custom requires 6 hours; Sephardic, 1 to 3 hours. After dairy, generally rinsing the mouth and a short wait suffice.</li>
  <li><strong>No mixing in the same meal:</strong> Chicken with cream or meat with cheese cannot be served on the same plate.</li>
</ul>',
            ],
            [
                'title' => 'Kosher Certification',
                'body'  => '<p>In the modern food industry, supervision by a certified rabbinic authority is essential. A kosher certified product carries a <strong>kashrut symbol</strong> (heksher) guaranteeing it was produced under continuous supervision and meets all standards.</p>
<p>Leading international certifying agencies include the <strong>OU</strong> (Orthodox Union, USA), <strong>OK Kosher</strong>, <strong>Star-K</strong>, and <strong>Kof-K</strong>. In Latin America, <strong>BDK Brasil</strong>, Argentina\'s <strong>CIK</strong>, Uruguay\'s <strong>Vaad</strong>, and others are recognized. In Israel, the main authorities are the <strong>Chief Rabbinate of Israel</strong> and the stricter <strong>Badatz</strong>.</p>',
            ],
            [
                'title' => 'Kosher Beyond Religion',
                'body'  => '<p>Many non-Jewish people choose kosher products for their own reasons:</p>
<ul>
  <li>People with <strong>dairy allergies</strong> trust that a "pareve" product contains no dairy.</li>
  <li>Vegetarians and vegans use the pareve symbol to ensure no hidden meat.</li>
  <li>Muslims sometimes consume kosher products when halal certification is unavailable.</li>
  <li>People seeking <strong>greater control over their food\'s origin</strong> and production process.</li>
</ul>
<p>Kosher certification implies detailed supervision of ingredients, processes, and equipment, making it a guarantee of traceability and care in production.</p>',
            ],
        ],
    ],

    'kashrut' => [
        'title'       => 'Laws of Kashrut — Kosher Laws in Detail',
        'description' => 'Learn the halachot (laws) of kashrut: shechita, melicha, meat-dairy separation, Pesach, insect checking, and more.',
        'intro'       => '<strong>Kashrut</strong> (כַּשְׁרוּת) is the body of Jewish dietary laws determining which foods are permitted, how they must be prepared, and how they may be combined. Its foundation is the Written Torah, and the Oral tradition (Talmud and subsequent rabbinic literature) details its practical application.',
        'sections'    => [
            [
                'title' => 'Sources in the Torah',
                'body'  => '<p>The main biblical sources of kashrut are found in <strong>Leviticus</strong> (chapter 11) and <strong>Deuteronomy</strong> (chapter 14), which establish:</p>
<ul>
  <li>Criteria for permitted mammals, birds, and fish.</li>
  <li>The prohibition of blood: "You shall not eat any blood" (Leviticus 7:26).</li>
  <li>The prohibition of mixing meat with milk (Exodus 23:19).</li>
</ul>',
            ],
            [
                'title' => 'Shechita — Ritual Slaughter',
                'body'  => '<p>Every kosher mammal and bird must be slaughtered through <strong>shechita</strong> (שְׁחִיטָה): a swift, precise, uninterrupted cut through the trachea and esophagus using a perfectly sharp, nick-free knife (<em>chalaf</em>). This method produces rapid blood loss and immediate death.</p>
<p>The slaughterer is called a <strong>shochet</strong> (שׁוֹחֵט) and must be a trained and certified observant Jew. After slaughter, the shochet inspects the knife (<em>bedikát ha-chalaf</em>) to confirm no irregularities.</p>
<p>Animals that die naturally, from disease, or are slaughtered by other means are <strong>nevela</strong> — forbidden regardless of whether they are from a permitted species.</p>',
            ],
            [
                'title' => 'Internal Inspection',
                'body'  => '<p>After slaughter, the shochet inspects the animal\'s internal organs — particularly the lungs — for adhesions, wounds, or abnormalities that would render the animal non-kosher (<em>treifa</em>).</p>
<ul>
  <li><strong>Kosher:</strong> If adhesions detach without leaving marks.</li>
  <li><strong>Glatt kosher:</strong> If the lungs are completely smooth, with no adhesions at all. This stricter standard is preferred by many communities.</li>
  <li><strong>Treifa:</strong> If there are perforations or serious lesions — the animal is not fit.</li>
</ul>',
            ],
            [
                'title' => 'Melicha — Blood Extraction',
                'body'  => '<p>The Torah forbids consuming blood. After slaughter, meat must undergo the <strong>melicha</strong> process to draw out the blood:</p>
<ol>
  <li><strong>Soaking:</strong> Meat is submerged in cold water for 30 minutes.</li>
  <li><strong>Salting:</strong> Covered with coarse salt for 1 hour, allowing the salt to absorb the blood.</li>
  <li><strong>Rinsing:</strong> Rinsed three times with cold water.</li>
</ol>
<p>Liver cannot be koshered with salt alone due to its high blood content — it must be broiled directly over fire.</p>',
            ],
            [
                'title' => 'Basar Bechalav — Meat with Milk',
                'body'  => '<p>The prohibition of mixing meat with dairy has three dimensions:</p>
<ul>
  <li><strong>Bishul:</strong> Forbidden to cook meat with dairy.</li>
  <li><strong>Achila:</strong> Forbidden to eat meat and dairy together.</li>
  <li><strong>Hana\'ah:</strong> Forbidden to derive benefit from the mixture (in certain interpretations).</li>
</ul>
<p>Utensils that have absorbed meat flavors cannot be used for dairy and vice versa. If accidentally mixed, a rabbi (posek) determines whether the utensil can be kashered or must be discarded, based on proportion and context.</p>',
            ],
            [
                'title' => 'Pesach — Special Kashrut for Passover',
                'body'  => '<p>During <strong>Pesach</strong> (Passover), commemorating the Exodus from Egypt, an additional prohibition applies: <strong>chametz</strong> (חָמֵץ) — any leavened food made from five grains: wheat, barley, oats, rye, and spelt.</p>
<ul>
  <li>Bread, pasta, beer, whiskey, and many processed products are forbidden.</li>
  <li>Kitchen utensils must be specific to Pesach, as others have "absorbed" chametz during the year.</li>
  <li>The home must be thoroughly cleaned before Pesach to remove all traces of chametz.</li>
  <li><strong>Matzah</strong> (unleavened bread) is eaten in its place.</li>
</ul>',
            ],
            [
                'title' => 'Checking Vegetables for Insects',
                'body'  => '<p>Insects are completely forbidden by the Torah. Checking (<em>bedikat tolaim</em>) fruits and vegetables is mandatory, as many can harbor aphids, thrips, or other tiny insects invisible to the naked eye.</p>
<p>The most problematic vegetables include lettuce, broccoli, cauliflower, basil, spinach, and raspberries. They must be carefully washed and inspected, or purchased pre-checked and certified.</p>',
            ],
        ],
    ],

    'judaismo' => [
        'title'       => 'Pillars of Judaism',
        'description' => 'Discover the foundations of Judaism: Torah, Shabbat, prayer, tzedakah, teshuvah, and the Jewish life cycle.',
        'intro'       => 'Judaism is one of the world\'s oldest religious traditions, with over 3,500 years of continuous history. More than a religion, it is a complete way of life encompassing spirituality, ethics, community, culture, and identity. Its fundamental pillars guide Jews in every aspect of daily existence.',
        'sections'    => [
            [
                'title' => 'The Torah — Source of Everything',
                'body'  => '<p>The <strong>Torah</strong> (תּוֹרָה) is the central sacred text of Judaism. In the strict sense, it refers to the Five Books of Moses (Genesis, Exodus, Leviticus, Numbers, and Deuteronomy). Broadly, it encompasses all Jewish teaching.</p>
<ul>
  <li><strong>Written Torah (Torah She-biKhtav):</strong> The Five Books of Moses, given at Mount Sinai. Also includes the Prophets (Nevi\'im) and Writings (Ketuvim) — together forming the <em>Tanakh</em>.</li>
  <li><strong>Oral Torah (Torah She-be\'al Peh):</strong> The interpretation and application of the Written Torah, transmitted orally through generations. Codified in the <em>Mishnah</em> (2nd century), the <em>Talmud</em> (4th–6th centuries), and subsequent halachic literature.</li>
</ul>',
            ],
            [
                'title' => 'Shabbat — The Sacred Day of Rest',
                'body'  => '<p><strong>Shabbat</strong> (שַׁבָּת) is the holy day of rest, beginning Friday at nightfall and ending Saturday night (when three stars appear). It is the only day of the week named in the Torah, and its observance is one of the most important commandments.</p>
<p>During Shabbat, 39 categories of "melacha" (creative work) are refrained from, including writing, kindling fire, cooking, carrying in public spaces, and similar activities. In the modern context, this includes electricity, computers, and automobiles.</p>
<p>Shabbat begins with the woman\'s candle lighting, the blessing over wine (<em>kiddush</em>) and braided bread (<em>challah</em>). It is celebrated with family meals, prayers, and Torah study.</p>',
            ],
            [
                'title' => 'Tefila — Prayer',
                'body'  => '<p><strong>Tefila</strong> (תְּפִלָּה — prayer) is one of Judaism\'s pillars. The observant Jew prays three times daily: <em>Shacharit</em> (morning), <em>Mincha</em> (afternoon), and <em>Arvit/Ma\'ariv</em> (evening), corresponding to the daily Temple sacrifices.</p>
<p>The central prayer is the <strong>Amidah</strong> (also called Shmoneh Esreh — the "eighteen blessings"), recited standing and silently. Other central elements are the <em>Shema Yisrael</em> (the declaration of monotheistic faith) and the <em>Kiddush Levanah</em> (blessing of the moon).</p>',
            ],
            [
                'title' => 'Tzedakah — Social Justice',
                'body'  => '<p><strong>Tzedakah</strong> (צְדָקָה) is commonly translated as "charity," but in Hebrew it literally means "justice." In Judaism, giving to those in need is not a voluntary act of generosity — it is a moral and religious obligation.</p>
<p>The Torah establishes <em>ma\'aser</em> (tithe): setting aside 10% of income to help others. The highest form of tzedakah enables the recipient to become self-sufficient — giving employment, an interest-free loan, or a business partnership.</p>',
            ],
            [
                'title' => 'Teshuvah — Return',
                'body'  => '<p><strong>Teshuvah</strong> (תְּשׁוּבָה — literally "return") is the process of repentance and spiritual renewal. It involves recognizing the wrong, feeling genuine remorse, repairing the damage caused (when possible), and committing not to repeat it.</p>
<p>The most intense period of teshuvah is the <strong>Yamim Noraim</strong> (Days of Awe): the ten days from Rosh Hashanah (Jewish New Year) to Yom Kippur (Day of Atonement). Yom Kippur is the holiest day of the Jewish calendar — a full fast day of continuous prayer dedicated to introspection and seeking forgiveness.</p>',
            ],
            [
                'title' => 'The Jewish Life Cycle',
                'body'  => '<ul>
  <li><strong>Brit Milah:</strong> Male circumcision on the eighth day, celebrating entry into the Jewish covenant.</li>
  <li><strong>Bar/Bat Mitzvah:</strong> At 13 (boy) or 12 (girl), the young person assumes responsibility for the commandments. Celebrated with public Torah reading.</li>
  <li><strong>Marriage (Kiddushin/Nisuin):</strong> Under the chuppah (bridal canopy), with the kiddushin (consecration) and nisuin (marriage proper).</li>
  <li><strong>Mourning (Aveilut):</strong> A compassionate, structured process: seven days (shiva), thirty days (shloshim), and a year of kaddish.</li>
</ul>',
            ],
            [
                'title' => 'The Jewish Calendar\'s Holidays',
                'body'  => '<ul>
  <li><strong>Rosh Hashanah:</strong> Jewish New Year. Shofar blowing, introspection, apple with honey.</li>
  <li><strong>Yom Kippur:</strong> Day of fasting and atonement.</li>
  <li><strong>Sukkot:</strong> Festival of Booths, gratitude for the harvest and 40 years in the desert.</li>
  <li><strong>Hanukkah:</strong> Festival of Lights. Commemorates the miracle of oil in the Temple.</li>
  <li><strong>Purim:</strong> Celebrates the salvation narrated in the Book of Esther.</li>
  <li><strong>Pesach:</strong> Passover. Commemorates the Exodus from Egypt. Seven/eight days without chametz.</li>
  <li><strong>Shavuot:</strong> Commemorates the giving of the Torah at Mount Sinai.</li>
</ul>',
            ],
        ],
    ],

    'etiqueta_kosher' => [
        'title'       => 'How to Read a Kosher Label',
        'description' => 'Learn to interpret kosher certification symbols: OU, OK, KF, BDK, and designations like Chalav Yisrael, Mehadrin, Pas Yisrael, and more.',
        'intro'       => 'Knowing how to read a kosher label is essential for those who follow a kosher diet. Certified products carry a small graphic symbol — called a <strong>heksher</strong> — indicating who supervised their production and what standards were applied.',
        'sections'    => [
            [
                'title' => 'Why Does the Heksher Matter?',
                'body'  => '<p>It is not enough that the listed ingredients appear "kosher": utensils, production lines, additives, release agents, and even machine lubricating oils can compromise a product\'s kosher status.</p>
<p>Certification by a rabbinic agency guarantees that an inspector (<em>mashgiach</em>) supervised production continuously or periodically, and that all aspects of the process comply with halachic requirements.</p>',
            ],
            [
                'title' => 'The Most Common Symbols',
                'body'  => '<ul>
  <li><strong>OU (Orthodox Union):</strong> The most globally recognized. OU alone = pareve; OU-D = dairy; OU-M = meat; OU-P = Kosher le-Pesach.</li>
  <li><strong>OK Kosher:</strong> American agency with broad global coverage.</li>
  <li><strong>Star-K:</strong> Based in Baltimore, highly recognized in the US.</li>
  <li><strong>Kof-K:</strong> Strong presence in industrial products.</li>
  <li><strong>BDK Brasil:</strong> Leading certifier in Brazil, highly respected in South America.</li>
  <li><strong>Badatz Eida Chareidis:</strong> One of Israel\'s strictest standards, recognized by the most observant communities.</li>
</ul>
<p>A lone "K" (without a registered agency symbol) is not reliable as a recognized certification, as any company can print a K without real supervision.</p>',
            ],
            [
                'title' => 'Additional Label Designations',
                'body'  => '<ul>
  <li><strong>Pareve / Pareve:</strong> Contains neither meat nor dairy. Can be eaten with both.</li>
  <li><strong>Dairy (D) / Chalav:</strong> Contains dairy derivatives.</li>
  <li><strong>Meat (M):</strong> Contains meat.</li>
  <li><strong>Chalav Yisrael (CY):</strong> Milk was milked under continuous Jewish supervision — stricter than Chalav Stam.</li>
  <li><strong>Bishul Yisrael:</strong> Cooking was initiated by a Jew.</li>
  <li><strong>Pas Yisrael:</strong> Bread/baked goods were prepared with Jewish participation in lighting the oven.</li>
  <li><strong>Mehadrin:</strong> Stricter level of observance, especially in Israel.</li>
  <li><strong>Glatt:</strong> In meats, indicates the animal\'s lungs were completely smooth.</li>
  <li><strong>Kosher le-Pesach (P):</strong> Fit for consumption during Passover.</li>
</ul>',
            ],
            [
                'title' => 'Practical Tips for Verification',
                'body'  => '<ul>
  <li>Look for the symbol on the front or back of the package — it is usually small (2–5 mm).</li>
  <li>Confirm the symbol belongs to a recognized agency.</li>
  <li>Check the designation (pareve/dairy/meat) to know if you can combine it with what you are eating.</li>
  <li>For doubts about a specific agency or product, consult your rabbi.</li>
  <li>Use apps like <strong>KosherMap</strong> to verify products by barcode.</li>
</ul>',
            ],
        ],
    ],

    'sobre_nosotros' => [
        'title'       => 'About Us — KosherMap',
        'description' => 'KosherMap is the global directory of kosher products and places, designed to help the Jewish community and all those who follow a kosher diet worldwide.',
        'intro'       => '<strong>KosherMap</strong> was born from a real need: finding certified kosher products and places (restaurants, synagogues, supermarkets) in different cities around the world, easily and reliably.',
        'sections'    => [
            [
                'title' => 'Our Mission',
                'body'  => '<p>Our mission is to make living kosher easier, wherever you are. Whether you travel, move to a new city, or simply want to expand your repertoire of certified products, KosherMap gives you the information you need.</p>
<ul>
  <li>An extensive catalog of <strong>certified kosher products</strong> with their certifiers, categories, and status (pareve, dairy, meat).</li>
  <li>A directory of <strong>kosher places</strong>: restaurants, synagogues, butcher shops, bakeries, hotels, and more — with Google Maps ratings, hours, and contact.</li>
  <li>Educational information about kashrut, certifying agencies, and Judaism.</li>
</ul>',
            ],
            [
                'title' => 'Global Coverage',
                'body'  => '<p>KosherMap covers multiple countries, with special focus on Spanish, Portuguese, English, French, Hebrew, and Russian-speaking communities. Our cities include Buenos Aires, São Paulo, New York, Tel Aviv, London, Paris, and many more — with coverage constantly expanding.</p>',
            ],
            [
                'title' => 'How to Contribute',
                'body'  => '<p>KosherMap grows thanks to the community. If you notice a missing place, product, or outdated information, you can:</p>
<ul>
  <li>Report a problem from each place or product page.</li>
  <li>Suggest a new kosher establishment from the places section.</li>
  <li>Contact us directly for improvements, corrections, or suggestions.</li>
</ul>',
            ],
        ],
    ],

    'contacto' => [
        'title'       => 'Contact',
        'description' => 'Contact the KosherMap team for questions, suggestions, error reports, or to propose new kosher places and products.',
        'intro'       => 'We are here to help. If you have questions, suggestions, want to report an error, or propose a new kosher place or product, write to us.',
        'sections'    => [
            [
                'title' => 'Contact Email',
                'body'  => '<p>You can write to us at: <a href="mailto:info@koshermap.org" class="text-blue-600 hover:underline">info@koshermap.org</a></p>
<p>We respond within 1 to 3 business days.</p>',
            ],
            [
                'title' => 'Report a Place or Product',
                'body'  => '<p>If you find incorrect information about a place or product on KosherMap, use the "Report" button on each individual page. Your report goes directly to our moderation team.</p>
<p>To suggest a new kosher establishment, visit the <a href="/places" class="text-blue-600 hover:underline">Places</a> section and use the "Add a place" button.</p>',
            ],
        ],
    ],

    'privacidad' => [
        'title'       => 'Privacy Policy',
        'description' => 'KosherMap privacy policy: what information we collect, how we use it, cookies, Google AdSense advertising, and your rights.',
        'intro'       => 'At <strong>KosherMap</strong> (koshermap.org) we are committed to protecting your privacy. This policy explains what information we collect, how we use it, and what your rights are.',
        'sections'    => [
            [
                'title' => 'Information We Collect',
                'body'  => '<ul>
  <li><strong>Usage information:</strong> Pages visited, searches performed, country of origin (automatically detected to personalize results).</li>
  <li><strong>Session cookies:</strong> To remember your preferred language and selected country.</li>
  <li><strong>Contact information:</strong> If you email us or use the report form, we store your email solely to respond to your inquiry.</li>
</ul>',
            ],
            [
                'title' => 'Cookies and Advertising',
                'body'  => '<p>KosherMap uses <strong>Google AdSense</strong> to display advertising. Google AdSense uses cookies to show relevant ads based on your prior visits to this and other websites. You can opt out of personalized advertising by visiting <a href="https://www.google.com/settings/ads" class="text-blue-600 hover:underline" target="_blank" rel="noopener">Google Ads Settings</a>.</p>
<p>We may also use <strong>Google Analytics</strong> to anonymously analyze site traffic and improve user experience. This information does not include personally identifiable data.</p>',
            ],
            [
                'title' => 'Use of Information',
                'body'  => '<ul>
  <li>Personalize results according to your country and language.</li>
  <li>Improve site content and functionality.</li>
  <li>Respond to user inquiries and reports.</li>
  <li>Display relevant advertising through Google AdSense.</li>
</ul>
<p><strong>We do not sell or share your personal information with third parties</strong> for commercial purposes.</p>',
            ],
            [
                'title' => 'Your Rights',
                'body'  => '<p>You have the right to request access to the data we hold about you, request correction or deletion of your data, and opt out of non-essential cookies. To exercise any of these rights, write to <a href="mailto:info@koshermap.org" class="text-blue-600 hover:underline">info@koshermap.org</a>.</p>',
            ],
            [
                'title' => 'Changes to This Policy',
                'body'  => '<p>We may update this privacy policy periodically. We will notify you of significant changes by posting the new version on this page. Last updated: <strong>June 2026</strong>.</p>',
            ],
        ],
    ],

];
