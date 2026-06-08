# Checklist: KosherStatus (Entorno Local Trae + Laragon/XAMPP)

## 1. 🏗️ Configuración del Entorno Local
- [x] **Ajustar .env**: Cambiar el host de la DB a `127.0.0.1` y asegurarte de que el usuario y contraseña coincidan con los de tu MySQL local (Laragon por defecto usa `root` y contraseña vacía).
- [x] **Generar Key**: Ejecutar `php artisan key:generate` para evitar errores de encriptación (500 Internal Server Error).
- [x] **Conexión HeidiSQL**: Crear la sesión en HeidiSQL apuntando a `localhost` para gestionar `kosher_db` visualmente.
- [x] **Queue Driver**: Asegurar que `QUEUE_CONNECTION=database` esté en el `.env`.

## 2. ⚡ Procesamiento de Datos (Scraping)
- [x] **Migraciones**: Ejecutar `php artisan migrate` en la terminal de Trae para recrear las tablas de `products`, `brands` y `jobs`.
- [x] **Lanzar Scraper**: Ejecutar `php artisan scrape:ou`.
- [x] **Activar el Worker**: Abrir una terminal en Trae y dejar corriendo `php artisan queue:work`. Es vital para que los productos pasen de la tabla `jobs` a la tabla `products`.
- [x] **Optimización del Scraper**: Optimizar para usar Cliente Http de Laravel, User-Agent real y manejo de timeouts.
- [x] **Multi-origen**: Manejar identificadores únicos por producto y servidor de origen (display unificado).

## 3. 🔍 Desarrollo del Buscador (SearchController)
- [x] **Lógica "Cascade"**:
    1. Buscar en MySQL local.
    2. Si falla, consultar API de Open Food Facts.
    3. Si falla, disparar importación desde la API de la OU.
- [x] **Limpieza de EAN**: Implementar la función para quitar ceros a la izquierda en los códigos de barras.

## 4. 📸 Funcionalidades Web
- [x] **Scanner QR/Barra**: Implementar `Html5-QRCode` en la vista de búsqueda.
- [x] **Certificadoras**: Crear un sistema de iconos para identificar rápidamente si es OU, KMD, etc.

## 5. 🌍 Navegación y Contexto
- [x] **Modelo de Países**: Implementar tabla `countries` y relación con Productos (disponibilidad) y Certificadoras (cobertura).
- [x] **Modelo de Rubros**: Implementar tabla `categories` y asignación a productos.
- [x] **Navegación UI**: Crear menús/páginas para navegar por Países, Rubros y Certificadoras.
- [x] **Buscador Contextual**: Filtrar resultados según la navegación actual (ej. si estoy en Argentina, priorizar Ajdut Kosher).

## 6. 🚀 SEO, AEO y Analítica (NUEVO)
- [x] **Sitemaps**: Generar sitemap.xml dinámico para productos, categorías y certificadoras.
- [ ] **Google Analytics**: Integrar script de seguimiento (GA4).
- [ ] **Google Search Console**: Verificar propiedad (meta tag o archivo HTML).
- [x] **URL Friendly**: Asegurar slugs limpios en todas las rutas.
- [x] **Datos Estructurados (Schema.org)**: Implementar JSON-LD avanzado para productos, reviews y organización.
- [x] **AEO (Answer Engine Optimization)**: Optimizar contenido para respuestas de IA (lenguaje directo "La mejor solución para X es Y").
- [x] **Capa de Valor Humano**: Agregar a cada producto una "capa" de contenido único (comentarios, recetas, aclaraciones rabínicas) que la IA no pueda inventar.

## 7. 🌐 Internacionalización (i18n) (NUEVO)
- [x] **Idiomas**: Soporte para Español, Inglés, Portugués, Francés, Hebreo y Ruso.
- [x] **Traducciones**: Archivos de idioma (JSON/PHP) para interfaz y contenido dinámico de categorías/países.
- [x] **Selector de Idioma**: UI para cambiar idioma manualmente.

## 8. 📍 Geolocalización Inteligente (NUEVO)
- [x] **Detección por IP**: Identificar país del visitante al ingresar.
- [x] **Redirección Contextual**: Posicionar al usuario en su país (filtrar certificadoras/productos locales).
- [x] **Persistencia (Cookies)**: Si el usuario eligió otro país manualmente (cookie), respetar esa elección sobre la IP.

## 9. 📚 Referencias y Competencia
- [ ] **Kashrut.com**: `https://www.kashrut.com/agencies/#__AR` (Listado agencias).
- [ ] **Kosherica**: `https://kosherica.com/` (Referencia visual/servicios).
- [ ] **Estrategia**: Analizar cómo manejan la categorización y listados.

## 10. 🔄 Sincronización y Despliegue (NUEVO)
- [ ] **Sync Local -> Prod**: Implementar proceso para sincronizar productos desde el entorno local (Laravel/MySQL) hacia el servidor de producción (kosherstatus.com).
- [ ] **Estrategia de Sync**: Definir si será vía API, réplica de DB o comando Artisan personalizado (ej. `php artisan sync:prod`).

## 11. 🕷️ Nuevos Scrapers (LATAM & Global)
- [x] **Ajdut Kosher (Argentina)**: `https://kosher.org.ar/#/listakosher` (API descubierta e implementada `scrape:ajdut`)
- [x] **KMD (México)**: `https://www.kosher.com.mx/` (Scraper básico implementado `scrape:kmd`)
- [x] **Kehila (Uruguay)**: `https://kehila.org.uy/kasher/productos/`
- [x] **BDK (Brasil)**: `https://www.bdk.com.br/`
- [ ] **One Kosher (México/Global)**: `https://onekosher.com/`
- [x] **Kosher Chile**: `https://www.chilekosher.cl/`
- [x] **UK Kosher (Latinoamerica)**: `https://www.ukkosher.org/`

## Notas Adicionales
- Scrapear sitios con web y >400 productos.
- Sincronización entre localhost y producción.
