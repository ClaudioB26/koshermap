/**
 * Script de diagnóstico — corre con browser VISIBLE para ver qué pasa.
 * Uso: node scripts/test-scraper.cjs
 */
const { chromium } = require('playwright');

async function main() {
    console.log('Abriendo browser...');

    const browser = await chromium.launch({
        headless: false,  // visible para diagnóstico
        slowMo: 500,
    });

    const context = await browser.newContext({
        locale: 'es',
        userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
    });

    const page = await context.newPage();

    const url = 'https://www.google.com/maps/search/kosher+restaurant+Buenos+Aires+Argentina/@-34.6037,-58.3816,13z?hl=es';
    console.log('Navegando a:', url);

    await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });

    // Aceptar cookies si aparece
    try {
        await page.waitForSelector('button[aria-label*="Aceptar"], button[aria-label*="Accept"]', { timeout: 5000 });
        await page.click('button[aria-label*="Aceptar"], button[aria-label*="Accept all"]');
        console.log('Cookies aceptadas');
        await page.waitForTimeout(2000);
    } catch {
        console.log('No apareció banner de cookies');
    }

    // Esperar resultados
    console.log('Esperando resultados .Nv2PK...');
    try {
        await page.waitForSelector('.Nv2PK', { timeout: 15000 });
        const count = await page.locator('.Nv2PK').count();
        console.log(`✓ Encontrados ${count} resultados con selector .Nv2PK`);
    } catch {
        console.log('✗ Selector .Nv2PK no encontrado. Probando alternativas...');

        // Listar todos los divs con jsaction para diagnosticar
        const divs = await page.evaluate(() => {
            const els = document.querySelectorAll('[data-result-index]');
            return els.length;
        });
        console.log(`  data-result-index: ${divs} elementos`);

        // Mostrar el body para ver qué cargó
        const title = await page.title();
        console.log(`  Título de la página: ${title}`);
    }

    // Extraer y visitar el primer resultado
    try {
        const first = page.locator('.Nv2PK').first();
        let href = await first.locator('a.hfpxzc').getAttribute('href');
        const cid = await first.getAttribute('data-cid');
        const m1s = href?.match(/!1s([^!]+)/);
        const resolvedId = cid || (m1s ? m1s[1] : 'md5-fallback');

        console.log(`\nPrimer resultado:`);
        console.log(`  data-cid: ${cid}`);
        console.log(`  !1s ID:   ${m1s?.[1]}`);
        console.log(`  ID usado: ${resolvedId}`);
        console.log(`  href:     ${href?.substring(0, 100)}`);

        // Navegar al detalle
        const url = href.startsWith('http') ? href : 'https://www.google.com' + href;
        console.log(`\nNavegando al detalle: ${url.substring(0, 80)}...`);
        await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 20000 });
        await page.waitForSelector('h1', { timeout: 10000 });
        await page.waitForTimeout(2000);

        const name    = await page.locator('h1.DUwDvf').first().innerText().catch(() => page.locator('h1').first().innerText().catch(() => 'N/A'));
        const address = await page.locator('[data-item-id="address"] .Io6YTe').first().innerText().catch(() => 'N/A');
        const phone   = await page.locator('[data-item-id*="phone"] .Io6YTe').first().innerText().catch(() => 'N/A');
        const rating  = await page.locator('div.F7nice span.MW4etd').first().innerText().catch(() => 'N/A');

        console.log(`\n  Nombre:    ${name}`);
        console.log(`  Dirección: ${address}`);
        console.log(`  Teléfono:  ${phone}`);
        console.log(`  Rating:    ${rating}`);
    } catch (e) {
        console.log('Error:', e.message);
    }

    console.log('\nEsperando 5 segundos...');
    await page.waitForTimeout(5000);

    await browser.close();
    console.log('Listo.');
}

main().catch(e => { console.error(e); process.exit(1); });
