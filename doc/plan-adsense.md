# Plan de acción — Aprobación AdSense

**Estado:** 3 rechazos consecutivos por "Contenido de bajo valor" (último: jul 2026)
**Documento creado:** 31 jul 2026

---

## Diagnóstico con datos reales

Medido sobre la base de producción, no sobre impresiones:

| Métrica | Valor actual | Referencia esperada |
|---|---|---|
| Artículos publicados | 30 | ✅ Cantidad suficiente |
| **Promedio de palabras por artículo** | **247** | ❌ 800–1500 |
| **Artículos con menos de 300 palabras** | **25 de 30** | ❌ Debería ser 0 |
| **Artículos con imágenes** | **0** | ❌ Al menos 1 c/u |
| **Artículos con links externos a fuentes** | **0** | ❌ 2–3 c/u |
| **Artículos con links internos entre sí** | **0** | ❌ 2–4 c/u |
| **Fechas de publicación distintas** | **1 sola fecha (30/06/2026)** | ❌ Escalonadas |
| Artículo más largo | 425 palabras (`vino-kosher`) | — |
| Artículo más corto | 182 palabras (`kasherizar-microondas`) | — |

### La causa raíz

**El problema no es la cantidad de artículos, es la profundidad.** Con 247 palabras
promedio, cada artículo es una nota breve, no una guía. Para los sistemas de Google eso
sigue siendo "thin content" aunque el texto esté bien escrito y sea original.

Los 3 artículos más largos son justamente los que tienen anécdotas reales
(`vino-kosher` 425, `simbolos-certificacion-kosher` 414, `comer-kosher-restaurante` 391).
Eso valida el camino: **experiencia real = más contenido = más valor**.

Además, las 30 fechas idénticas son una señal fuerte de publicación automatizada en masa,
que es exactamente el patrón que Google marca como "scaled content abuse".

---

## Evaluación de las sugerencias recibidas

### Válidas y confirmadas con datos

| Sugerencia | Estado | Comentario |
|---|---|---|
| Links a fuentes de referencia | ✅ Válida | Confirmado: 0 artículos tienen links externos |
| Imágenes en artículos | ✅ Válida | Confirmado: 0 artículos tienen imágenes |
| Fechas de creación distintas | ✅ Válida | Confirmado: los 30 comparten fecha |
| Forzar indexación en Search Console | ✅ Válida | Los artículos fueron descubiertos pero no indexados |
| Consolidar/ampliar artículos cortos | ✅ **La más importante** | 25 de 30 están por debajo del mínimo razonable |

### Parcialmente válidas

- **"Falta de tráfico orgánico"** — AdSense no publica un mínimo de tráfico requerido, y no
  es algo que se pueda forzar. Correlaciona, pero no es una casilla que se pueda tildar.
  Se resuelve solo como consecuencia de indexar y tener contenido bueno.
- **"Sacar productos y lugares"** — Los productos ya están en `noindex` desde julio, así que
  Google no los evalúa para calidad. Sacar `/places` sería contraproducente: ya tiene intro,
  FAQ y sidebar de artículos. **No hace falta borrar nada más.**

### No aplican / son mitos

- **Redes sociales (Facebook, Instagram, GMB, Twitter)** — No influyen en la aprobación de
  AdSense. Google no las revisa como parte del proceso. Sirven para tráfico y marca, pero
  no mueven la aguja acá. **No priorizar antes de la aprobación.**
- **Páginas legales faltantes** — Ya están todas: `/privacidad` (incluye política de
  cookies), `/terminos`, `/contacto`, `/sobre-nosotros`. Nada pendiente.
- **Agregar 5 artículos más** — Sumar notas de 250 palabras **empeora** el promedio.
  Primero hay que llevar los 30 actuales a profundidad real. Los temas propuestos
  (revisar verduras/frutas, verduras en verano, prohibición de insectos) en realidad se
  solapan con `insectos-frutas-verduras` que ya existe: conviene **expandir ese** en vez de
  fragmentar en tres notas cortas.

---

## Plan de acción

### Fase 1 — Profundidad de contenido (prioridad máxima)

Objetivo: pasar de 247 a 800+ palabras promedio.

1. **Expandir los 30 artículos existentes**, empezando por los 25 que están bajo 300 palabras.
   Estructura sugerida por artículo:
   - Introducción con contexto o caso concreto
   - Desarrollo con subtítulos (`<h2>`) para escanear
   - Sección práctica (pasos, listas, ejemplos)
   - Preguntas frecuentes específicas del tema (2–3)
   - Cierre que conecte con otros artículos
2. **Priorizar los que puedan llevar experiencia real.** Las anécdotas ya incorporadas son
   el material más valioso del sitio. Vale la pena pedir más al usuario por tema.
3. **No agregar artículos nuevos** hasta que el promedio esté arriba de 800 palabras.

### Fase 2 — Señales de calidad editorial

4. **Imágenes**: al menos una por artículo, con `alt` descriptivo. Alternativas si no hay
   fotos propias: diagramas simples, fotos propias de productos/etiquetas (ideal para
   E-E-A-T, demuestra experiencia de primera mano), o bancos libres con licencia clara.
5. **Links externos**: 2–3 por artículo a fuentes de autoridad — certificadoras (OU,
   Ajdut, Star-K), textos halájicos, Wikipedia para términos. Con `rel="noopener"`.
6. **Links internos**: 2–4 por artículo a otros artículos del sitio. Ya existe la sección
   "Artículos relacionados" al pie, pero faltan links contextuales dentro del texto.
7. **Escalonar fechas de publicación**: distribuir los `created_at` a lo largo de varios
   meses en vez de una sola fecha. Es un cambio de datos, no de contenido.

### Fase 3 — Indexación

8. **Search Console → Inspección de URLs → Solicitar indexación** para los artículos
   principales (5–6 por día, hay cuota diaria).
9. **Verificar avance** con `site:koshermap.org/articulos` en Google. El objetivo es ver
   las URLs individuales indexadas, no solo la home.
10. **Esperar entre 2 y 4 semanas** antes de pedir la revisión de AdSense de nuevo. Sin
    tiempo de por medio, el bot vuelve a ver lo mismo que ya rechazó.

### Fase 4 — Recién ahí, solicitar revisión

11. Confirmar que el promedio de palabras esté arriba de 800.
12. Confirmar que los artículos aparezcan indexados en Google.
13. Solicitar revisión.

---

## Lo que NO hay que hacer

- ❌ Pedir revisión inmediatamente después de cambios menores (cada rechazo agrega fricción).
- ❌ Agregar más artículos cortos para "sumar cantidad".
- ❌ Invertir tiempo en redes sociales pensando que ayuda a la aprobación.
- ❌ Borrar `/places` o secciones que ya tienen contenido propio.
- ❌ Reactivar `human:generate` (genera reseñas falsas, está deshabilitado a propósito).

---

## Historial de correcciones ya aplicadas (jul 2026)

Para no repetir trabajo:

- ✅ 30 artículos humanizados, con 6 anécdotas reales de primera mano
- ✅ Traducciones a en/pt/fr/he/ru actualizadas con esas anécdotas
- ✅ Autor ("Equipo KosherMap") + fecha + CTA en cada artículo
- ✅ ~6.000 productos scrapeados marcados `noindex`
- ✅ ~2.700 descripciones basura limpiadas (reseñas falsas, notas internas "NOPUBLICAR", "Rubro:")
- ✅ 210 productos despublicados (certificación no vigente según fuente oficial)
- ✅ Scraper de Ajdut corregido para filtrar por rubro publicado
- ✅ Generador de reseñas falsas (`human:generate`) deshabilitado
- ✅ Sidebar de artículos relacionados en productos, marcas, certificadoras, países y lugares
- ✅ `/places` con intro + FAQ
- ✅ Home reencuadrada como recurso de kashrut, con artículos primero
- ✅ Página `/terminos` creada; `ads.txt`, `robots.txt` y sitemaps verificados
