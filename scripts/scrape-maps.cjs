#!/usr/bin/env node
/**
 * Scraper de Google Maps usando Playwright.
 * Llamado desde PHP via: node scripts/scrape-maps.js '<json_args>'
 *
 * Args JSON: { lat, lng, cityName, countryName, locale }
 * Salida:    JSON array de lugares al stdout (PHP lo parsea).
 * Errores:   al stderr + exit code 1.
 *
 * NOTA SOBRE SELECTORES: Google Maps cambia su HTML con frecuencia.
 * Si el scraper deja de funcionar buscar con DevTools y actualizar los
 * bloques marcados con "// SELECTOR".
 */

const { chromium } = require('playwright');

// Delays en ms — suficiente para no gatillar rate limiting pero sin ser exagerado
const DELAY_BETWEEN_TERMS  = [2000, 4000];   // entre cada query de búsqueda
const DELAY_BETWEEN_PLACES = [1500, 3000];   // entre cada visita a detalle
const DELAY_AFTER_LOAD     = [700,  1200];   // tras cargar la página de detalle
const DELAY_SCROLL_STEP    = [500,  900];    // entre cada paso de scroll

const SEARCH_TERMS = [
    { query: 'kosher restaurant',  type: 'restaurant' },
    { query: 'kosher food',        type: 'restaurant' },
    { query: 'kosher bakery',      type: 'bakery' },
    { query: 'kosher bar',         type: 'bar' },
    { query: 'kosher ice cream',   type: 'ice_cream' },
    { query: 'kosher supermarket', type: 'supermarket' },
    { query: 'sinagoga',           type: 'temple' },
    { query: 'synagogue kosher',   type: 'temple' },
    { query: 'escuela judía',      type: 'school' },
    { query: 'cementerio judío',   type: 'cemetery' },
    { query: 'hebraica',           type: 'other' },
    { query: 'comunidad judía',    type: 'other' },
];

const delay = (min, max) =>
    new Promise(r => setTimeout(r, Math.floor(Math.random() * (max - min + 1)) + min));

const ts = () => new Date().toISOString().substring(11, 19); // HH:MM:SS
const log = (...parts) => process.stderr.write(`[${ts()}] ${parts.join(' ')}\n`);

async function acceptConsent(page) {
    try {
        // SELECTOR: botón de aceptar cookies de Google
        const btn = page.locator('button:has-text("Aceptar todo"), button:has-text("Accept all")').first();
        await btn.waitFor({ timeout: 4000 });
        log('Aceptando consent de cookies...');
        await btn.click();
        await delay(1000, 2000);
    } catch {
        // No apareció el banner — normal
    }
}

async function scrollResults(page) {
    try {
        // SELECTOR: panel lateral scrolleable de resultados
        const feed = page.locator('div[role="feed"]').first();
        await feed.waitFor({ timeout: 5000 });
        log('Scrolleando resultados (5 pasos)...');
        for (let i = 0; i < 5; i++) {
            await feed.evaluate(el => el.scrollTop += 900);
            await delay(...DELAY_SCROLL_STEP);
        }
    } catch {
        log('Sin feed scrolleable (pocos resultados o sin resultados)');
    }
}

async function extractCardLinks(page, hintType) {
    const cards = [];
    try {
        // SELECTOR: cada card de resultado en el panel
        const items = await page.locator('.Nv2PK').all();
        for (const item of items) {
            try {
                // SELECTOR: link principal del card
                const link = item.locator('a.hfpxzc').first();
                const href  = await link.getAttribute('href');
                if (!href || !href.includes('/maps/place/')) continue;

                // ID único: intentar data-cid, luego !1s del data param, luego md5 del href
                let cid = await item.getAttribute('data-cid');
                if (!cid) {
                    // URL formato: /maps/place/Name/data=...!1s0x95...!... → extraer el valor de !1s
                    const m1s = href.match(/!1s([^!]+)/);
                    if (m1s) {
                        cid = m1s[1];
                    } else {
                        const mCid = href.match(/[?&]cid=(\d+)/);
                        cid = mCid ? mCid[1] : require('crypto').createHash('md5').update(href).digest('hex');
                    }
                }

                // SELECTOR: texto de categoría debajo del nombre
                let categoryText = '';
                try {
                    categoryText = (await item.locator('.W4Efsd span').first().innerText()) || '';
                } catch {}

                cards.push({
                    href,
                    cid: 'cid:' + cid,
                    hintType,
                    categoryText: categoryText.toLowerCase(),
                });
            } catch {}
        }
    } catch (e) {
        process.stderr.write('extractCardLinks error: ' + e.message + '\n');
    }
    return cards;
}

async function fetchPlaceDetail(page, card) {
    try {
        // href ya viene como URL completa desde Google Maps
        const url = card.href.startsWith('http') ? card.href : 'https://www.google.com' + card.href;
        log(`  → Navegando a detalle: ${url.substring(0, 80)}...`);
        await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 20000 });
        log('  → domcontentloaded OK, esperando h1...');
        await page.waitForSelector('h1', { timeout: 10000 });
        log('  → h1 encontrado, extrayendo datos...');
        await delay(...DELAY_AFTER_LOAD);

        const T = { timeout: 3000 }; // timeout corto para selectores opcionales

        // SELECTOR: nombre del lugar
        const name = await page.locator('h1.DUwDvf').first().innerText(T).catch(
            () => page.locator('h1').first().innerText(T).catch(() => '')
        );
        if (!name) return null;

        // SELECTOR: dirección
        const address = await page.locator('[data-item-id="address"] .Io6YTe').first()
            .innerText(T).catch(() => null);

        // SELECTOR: teléfono
        const phone = await page.locator('[data-item-id*="phone"] .Io6YTe').first()
            .innerText(T).catch(() => null);

        // SELECTOR: sitio web
        const website = await page.locator('[data-item-id="authority"] a').first()
            .getAttribute('href', T).catch(() => null);

        // SELECTOR: rating numérico — intentar varios selectores conocidos
        let rating = null;
        const ratingText = await page.locator('div.F7nice span.MW4etd').first().innerText(T).catch(
            () => page.locator('span.ceNzKf').first().innerText(T).catch(
                () => page.locator('div.fontDisplayLarge').first().innerText(T).catch(() => null)
            )
        );
        if (ratingText) {
            rating = parseFloat(ratingText.replace(',', '.')) || null;
        } else {
            // Fallback: buscar cualquier span con un número decimal visible (4.2, 3,8...)
            const ratingFallback = await page.evaluate(() => {
                const spans = [...document.querySelectorAll('span')];
                const match = spans.find(el => /^\d[.,]\d$/.test(el.innerText?.trim()));
                return match ? match.innerText.trim() : null;
            }).catch(() => null);
            if (ratingFallback) rating = parseFloat(ratingFallback.replace(',', '.')) || null;
        }

        // SELECTOR: cantidad de reseñas
        let reviewsCount = 0;
        const reviewsText = await page.locator('div.F7nice span[aria-label]').nth(1)
            .getAttribute('aria-label', T).catch(() => null);
        if (reviewsText) {
            const m = reviewsText.match(/[\d.,]+/);
            reviewsCount = parseInt((m ? m[0] : '0').replace(/[.,]/g, ''), 10) || 0;
        }

        // SELECTOR: texto de categoría (ej: "Restaurante kosher")
        const categoryText = await page.locator('button.DkEaL').first().innerText(T).catch(() => '');

        // SELECTOR: horarios (usamos page.evaluate para no esperar por selector que puede no existir)
        const hours = [];
        try {
            const hoursEl = page.locator('[jsaction*="open_hours"] table tr');
            const count = await hoursEl.count().catch(() => 0);
            for (let i = 0; i < count; i++) {
                const text = await hoursEl.nth(i).innerText(T).catch(() => null);
                if (text) hours.push(text.trim());
            }
        } catch {}

        // SELECTOR: cerrado permanentemente
        const closedText = (await page.locator('span.ylH6lf').first().innerText(T).catch(() => '')).toLowerCase();
        const isClosed = closedText.includes('cerrado permanentemente') || closedText.includes('permanently closed');

        // Coordenadas desde la URL actual
        let lat = null, lng = null;
        const urlMatch = page.url().match(/@(-?\d+\.\d+),(-?\d+\.\d+),/);
        if (urlMatch) { lat = parseFloat(urlMatch[1]); lng = parseFloat(urlMatch[2]); }

        const placeType = resolveType(card.hintType, card.categoryText + ' ' + categoryText.toLowerCase());
        log(`  → Extraído: "${name.trim()}" [${placeType}] rating:${rating} reseñas:${reviewsCount}`);

        return {
            google_place_id:       card.cid,
            name:                  name.trim(),
            place_type:            placeType,
            address:               address || null,
            latitude:              lat,
            longitude:             lng,
            phone:                 phone || null,
            website:               website || null,
            google_rating:         rating,
            google_reviews_count:  reviewsCount,
            opening_hours:         hours.length ? hours : null,
            google_types:          [],
            google_photo_ref:      null,
            is_permanently_closed: isClosed,
        };
    } catch (e) {
        log('fetchPlaceDetail error: ' + e.message);
        return null;
    }
}

function resolveType(hintType, categoryText) {
    const keywords = {
        restaurant:    ['restaurante', 'restaurant', 'comida', 'food', 'delivery'],
        bar:           ['bar', 'pub', 'cervecería', 'brewery'],
        bakery:        ['panadería', 'bakery', 'pastelería'],
        confectionery: ['confitería', 'café', 'cafetería', 'cafe'],
        ice_cream:     ['heladería', 'heladeria', 'ice cream', 'gelato'],
        supermarket:   ['supermercado', 'supermarket', 'almacén', 'almacen', 'grocery', 'mercado'],
        temple:        ['sinagoga', 'synagogue', 'templo', 'temple', 'worship'],
        school:        ['escuela', 'school', 'colegio', 'instituto', 'universidad'],
        cemetery:      ['cementerio', 'cemetery'],
    };
    for (const [type, words] of Object.entries(keywords)) {
        if (words.some(w => categoryText.includes(w))) return type;
    }
    return hintType;
}

async function readStdin() {
    return new Promise((resolve) => {
        let data = '';
        if (process.stdin.isTTY) {
            // Corriendo desde terminal directo — usar argv como fallback
            resolve(process.argv[2] || '{}');
            return;
        }
        process.stdin.setEncoding('utf8');
        process.stdin.on('data', chunk => data += chunk);
        process.stdin.on('end', () => resolve(data.trim()));
    });
}

async function main() {
    const raw  = await readStdin();
    const args = JSON.parse(raw || '{}');
    const { lat, lng, cityName, countryName, locale = 'es', onlyFirstTerm = false } = args;

    if (!lat || !lng || !cityName) {
        log('ERROR: Faltan argumentos: lat, lng, cityName');
        process.exit(1);
    }

    const terms = onlyFirstTerm ? [SEARCH_TERMS[0]] : SEARCH_TERMS;
    log(`Iniciando scraping de "${cityName}" (${countryName}) — ${terms.length} término(s) de búsqueda`);
    log(`Coordenadas: ${lat}, ${lng} | locale: ${locale}`);

    const browser = await chromium.launch({
        headless: true,
        args: [
            '--no-sandbox',
            '--disable-blink-features=AutomationControlled',
            '--disable-dev-shm-usage',
        ],
    });

    const context = await browser.newContext({
        locale,
        userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
    });

    const page = await context.newPage();
    const allPlaces = [];
    const seenIds   = new Set();

    try {
        for (let i = 0; i < terms.length; i++) {
            const term = terms[i];
            log(`--- Término ${i + 1}/${terms.length}: "${term.query}" ---`);
            await delay(...DELAY_BETWEEN_TERMS);

            const query = `${term.query} ${cityName} ${countryName}`;
            const url   = `https://www.google.com/maps/search/${encodeURIComponent(query)}/@${lat},${lng},13z?hl=${locale}`;

            log(`Navegando a: ${url.substring(0, 100)}...`);
            await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });
            log('Página cargada (domcontentloaded)');

            await acceptConsent(page);
            await delay(1000, 2000);

            // Esperar resultados
            try {
                log('Esperando cards .Nv2PK...');
                await page.waitForSelector('.Nv2PK', { timeout: 12000 }); // SELECTOR
                log('Cards encontrados');
            } catch {
                log(`Sin resultados para: "${query}"`);
                continue;
            }

            await scrollResults(page);

            const cards = await extractCardLinks(page, term.type);
            log(`${cards.length} cards extraídos (${seenIds.size} ya vistos antes)`);

            let newCards = 0;
            for (const card of cards) {
                if (seenIds.has(card.cid)) continue;
                seenIds.add(card.cid);
                newCards++;

                log(`Procesando card ${newCards} de ${cards.length - (cards.length - newCards)}: cid=${card.cid}`);
                await delay(...DELAY_BETWEEN_PLACES);
                const detail = await fetchPlaceDetail(page, card);
                if (detail) {
                    allPlaces.push(detail);
                    log(`  → GUARDADO: "${detail.name}" [total acumulado: ${allPlaces.length}]`);
                } else {
                    log('  → Sin detalle (null), saltando');
                }
            }
            log(`Fin término "${term.query}": ${newCards} nuevos procesados. Total acumulado: ${allPlaces.length}`);
        }
    } finally {
        log('Cerrando browser...');
        await browser.close();
    }

    log(`Scraping finalizado. ${allPlaces.length} lugares en total. Escribiendo JSON al stdout...`);
    process.stdout.write(JSON.stringify(allPlaces));
    log('Listo.');
}

main().catch(e => {
    process.stderr.write('Error fatal: ' + e.message + '\n');
    process.exit(1);
});
