// Test rápido: una sola búsqueda, sin headless, muestra lo que encontró
const { chromium } = require('playwright');
const delay = (a, b) => new Promise(r => setTimeout(r, Math.floor(Math.random()*(b-a+1))+a));

async function main() {
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({ locale: 'es', userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36' });
    const page    = await context.newPage();

    await page.goto('https://www.google.com/maps/search/kosher+restaurant+Buenos+Aires+Argentina/@-34.6037,-58.3816,13z?hl=es', { waitUntil: 'domcontentloaded' });

    try { await page.waitForSelector('button[aria-label*="Aceptar"]', { timeout: 4000 }); await page.click('button[aria-label*="Aceptar"]'); } catch {}

    await page.waitForSelector('.Nv2PK', { timeout: 12000 });
    await delay(1500, 2000);

    const items = await page.locator('.Nv2PK').all();
    console.log(`Cards encontrados: ${items.length}`);

    // Probar solo el primer card
    const first = items[0];
    const href  = await first.locator('a.hfpxzc').getAttribute('href');
    const m1s   = href?.match(/!1s([^!]+)/);
    const cid   = 'cid:' + (m1s ? m1s[1] : 'fallback');

    console.log('href completo:', href?.startsWith('http') ? '(URL completa ✓)' : '(relativa)');
    console.log('CID:', cid);

    // Navegar al detalle (la parte que estaba rota)
    const url = href.startsWith('http') ? href : 'https://www.google.com' + href;
    console.log('Navegando a URL:', url.substring(0, 70) + '...');

    await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 20000 });
    await page.waitForSelector('h1', { timeout: 10000 });
    await delay(1000, 1500);

    const name    = await page.locator('h1.DUwDvf').first().innerText().catch(() => page.locator('h1').first().innerText().catch(() => 'N/A'));
    const address = await page.locator('[data-item-id="address"] .Io6YTe').first().innerText().catch(() => 'N/A');
    const phone   = await page.locator('[data-item-id*="phone"] .Io6YTe').first().innerText().catch(() => 'N/A');

    console.log('\n✓ Resultado:');
    console.log('  Nombre:', name);
    console.log('  Dir:   ', address);
    console.log('  Tel:   ', phone);

    await browser.close();
}

main().catch(e => { console.error('ERROR:', e.message); process.exit(1); });
