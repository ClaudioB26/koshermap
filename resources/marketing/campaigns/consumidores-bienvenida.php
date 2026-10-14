<?php

// Primera campaña para consumidores suscriptos: qué es KosherMap y cómo usarlo.
// Tono semi-informal. Una versión por idioma; se manda a la lista del idioma.
$url = 'https://koshermap.org';

return [
    'segment' => 'consumers',
    'locales' => [
        'es' => [
            'subject' => 'Bienvenido a KosherMap: así podés usarlo',
            'preheader' => 'Buscá productos kosher, escaneá códigos y resolvé dudas de kashrut.',
            'heading' => '¡Hola! Qué bueno tenerte por acá',
            'paragraphs' => [
                'KosherMap te ayuda a saber si un producto tiene certificación kosher y quién la otorga. Podés buscarlo por nombre o escanear el código de barras desde el celular.',
                'También tenemos guías cortas y claras sobre kashrut: qué significa parve, cómo leer una etiqueta, qué certificadoras existen en tu país y más.',
                'Una vez por mes te escribimos con novedades. Si no querés recibirlas, te das de baja con un clic al final de este mail.',
            ],
            'button' => ['Buscar un producto', $url . '/productos'],
            'footer' => 'KosherMap · koshermap.org',
            'unsubscribe' => 'Darme de baja',
        ],
        'en' => [
            'subject' => 'Welcome to KosherMap: here is how to use it',
            'preheader' => 'Search kosher products, scan barcodes and get kashrut answers.',
            'heading' => "Hi! Great to have you here",
            'paragraphs' => [
                'KosherMap helps you find out whether a product is kosher-certified and who certifies it. You can search by name or scan the barcode with your phone.',
                'We also have short, clear guides on kashrut: what pareve means, how to read a label, which certifiers exist in your country and more.',
                "Once a month we'll write to you with news. If you don't want them, you can unsubscribe with one click at the bottom of this email.",
            ],
            'button' => ['Search a product', $url . '/productos'],
            'footer' => 'KosherMap · koshermap.org',
            'unsubscribe' => 'Unsubscribe',
        ],
        'pt' => [
            'subject' => 'Boas-vindas ao KosherMap: veja como usar',
            'preheader' => 'Busque produtos kosher, escaneie códigos e tire dúvidas de kashrut.',
            'heading' => 'Oi! Que bom ter você por aqui',
            'paragraphs' => [
                'O KosherMap ajuda você a saber se um produto tem certificação kosher e quem a concede. Você pode buscar pelo nome ou escanear o código de barras pelo celular.',
                'Também temos guias curtos e claros sobre kashrut: o que significa pareve, como ler um rótulo, quais certificadoras existem no seu país e mais.',
                'Uma vez por mês escrevemos com novidades. Se não quiser recebê-las, cancele com um clique no final deste e-mail.',
            ],
            'button' => ['Buscar um produto', $url . '/productos'],
            'footer' => 'KosherMap · koshermap.org',
            'unsubscribe' => 'Cancelar assinatura',
        ],
        'fr' => [
            'subject' => 'Bienvenue sur KosherMap : voici comment l\'utiliser',
            'preheader' => 'Cherchez des produits casher, scannez les codes-barres et trouvez des réponses.',
            'heading' => 'Bonjour ! Ravis de vous avoir parmi nous',
            'paragraphs' => [
                'KosherMap vous aide à savoir si un produit est certifié casher et par qui. Vous pouvez le chercher par nom ou scanner le code-barres avec votre téléphone.',
                'Nous avons aussi des guides courts et clairs sur la cacherout : ce que signifie parvé, comment lire une étiquette, quels certificateurs existent dans votre pays, et plus.',
                'Une fois par mois, nous vous écrirons avec des nouveautés. Si vous ne souhaitez pas les recevoir, désabonnez-vous en un clic en bas de cet e-mail.',
            ],
            'button' => ['Chercher un produit', $url . '/productos'],
            'footer' => 'KosherMap · koshermap.org',
            'unsubscribe' => 'Se désabonner',
        ],
        'ru' => [
            'subject' => 'Добро пожаловать в KosherMap: как им пользоваться',
            'preheader' => 'Ищите кошерные продукты, сканируйте штрихкоды и получайте ответы по кашруту.',
            'heading' => 'Привет! Рады, что вы с нами',
            'paragraphs' => [
                'KosherMap помогает узнать, есть ли у продукта кошерная сертификация и кто её выдал. Искать можно по названию или отсканировать штрихкод телефоном.',
                'Ещё у нас есть короткие и понятные руководства по кашруту: что значит парве, как читать этикетку, какие сертификаторы работают в вашей стране и многое другое.',
                'Раз в месяц мы будем писать вам с новостями. Если не хотите их получать, отпишитесь одним кликом внизу письма.',
            ],
            'button' => ['Найти продукт', $url . '/productos'],
            'footer' => 'KosherMap · koshermap.org',
            'unsubscribe' => 'Отписаться',
        ],
        'he' => [
            'subject' => 'ברוכים הבאים ל-KosherMap: ככה משתמשים',
            'preheader' => 'חפשו מוצרים כשרים, סרקו ברקודים וקבלו תשובות בענייני כשרות.',
            'heading' => 'היי! שמחים שהצטרפתם',
            'paragraphs' => [
                'KosherMap עוזר לדעת אם למוצר יש תעודת כשרות ומי נתן אותה. אפשר לחפש לפי שם או לסרוק את הברקוד מהנייד.',
                'יש לנו גם מדריכים קצרים וברורים על כשרות: מה זה פרווה, איך קוראים תווית, אילו גופי כשרות יש במדינה שלכם ועוד.',
                'פעם בחודש נכתוב לכם עם חדשות. אם לא תרצו לקבל אותן, אפשר להסיר בלחיצה אחת בתחתית המייל.',
            ],
            'button' => ['חיפוש מוצר', $url . '/productos'],
            'footer' => 'KosherMap · koshermap.org',
            'unsubscribe' => 'להסרה מהרשימה',
        ],
    ],
];
