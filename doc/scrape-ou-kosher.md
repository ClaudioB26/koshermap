# OU Kosher Scraping System

## Overview

Sistema completo para extraer productos de OU Kosher y relacionarlos inteligentemente con Open Food Facts para obtener códigos de barra e imágenes.

## Architecture

### 1. Intelligent Matching Engine

El sistema transforma el matching "ciego" en un "motor de decisión inteligente" que entiende las diferencias entre nombres corporativos y nombres de usuario.

**Ejemplo**: `MONDELEZ GLOBAL LLC + OREO COOKIE` ≡ `Oreo + Galletas Oreo`

### 2. Confidence Score System

| Factor | Puntos | Descripción |
|--------|--------|-------------|
| Marca exacta | 40 | Match perfecto de marca normalizada |
| Similitud nombre | 30 | Algoritmos avanzados de comparación |
| Categoría | 20 | Misma categoría de producto |
| País | 10 | País de origen/venta coincidente |
| Barcode | +15 | Bonus por tener código de barra |
| Imagen | +5 | Bonus por tener imagen |

**Decision Thresholds**:
- **≥80 puntos**: Auto-match automático
- **50-79 puntos**: Revisión humana requerida  
- **<50 puntos**: Rechazado (guardado para análisis)

### 3. Text Normalization Pipeline

Proceso agresivo de limpieza de texto:

```php
// Ejemplo de normalización
"MONDELEZ GLOBAL LLC - OREO® Cookies with Cream 118g"
→ "mondelez oreo cookies cream"
```

**Limpieza aplicada**:
- Sufijos empresariales: "Inc.", "Co.", "Corporation", "LLC"
- Símbolos: ®, ™, ©
- Stopwords: "with", "and", "flavor", "con", "sabor"
- Unidades: "118g", "1kg" → eliminadas para matching
- Case normalization y espacios múltiples

### 4. Cascade Search Strategy

Búsqueda multinivel con precisión decreciente:

1. **Alta precisión**: Marca + Nombre completo
2. **Media precisión**: Marca + Primeras 2 palabras
3. **Baja precisión**: Solo nombre completo
4. **Fuzzy search**: Palabras clave principales

### 5. Advanced Algorithms

- **Metaphone**: Similitud fonética ("Cereal" vs "Zereal")
- **Jaro-Winkler**: Mejor que Levenshtein para nombres cortos
- **Levenshtein**: Distancia de edición tradicional
- **Keywords matching**: Extracción de palabras clave

## Components

### Database Schema

```sql
-- Mapeo permanente OU → Open Food Facts
ou_off_mappings:
- ou_product_name, ou_brand_name
- off_product_name, off_brand_name  
- off_barcode, off_image_url
- confidence_score (0-100)
- match_status (auto_matched, manual_verified, pending_review, rejected)
- scoring_breakdown (JSON)

-- Búsquedas fallidas para curación humana
failed_matches:
- ou_product_name, ou_brand_name
- off_candidates (top 5 con scores)
- best_score, rejection_reason
- needs_human_review, reviewed_at

-- Cache de búsquedas por marca
brand_search_cache:
- search_brand, matched_off_brand
- success_count, average_confidence
```

### Services

#### `ProductTextNormalizer`
- Normalización agresiva de texto
- Generación de variaciones de búsqueda
- Algoritmos fonéticos y similitud

#### `IntelligentMatchingEngine`  
- Orquestación del proceso completo
- Cálculo de puntuación de confianza
- Integración con Open Food Facts API

### Jobs

#### `ProcessOUProductIntelligent`
- Versión mejorada del job original
- Utiliza motor de matching inteligente
- Fallback al método original si falla

### Commands

#### `ScrapeOUKosher` (Original Optimizado)
- Timeout extendido: 90s
- Headers mejorados con keep-alive
- Manejo específico de errores de conexión
- Pausas progresivas entre páginas

#### `ScrapeOUKosherRobust` (Nuevo)
- Modo resume desde última letra exitosa
- Letras específicas: `--letters=e,f,g`
- Page size reducido: 50 productos
- Reintentos exponenciales
- Cache de progreso persistente

## Usage

### Basic Scraping

```bash
# Scraper original optimizado
php artisan scrape:ou

# Scraper robusto (recomendado)
php artisan scrape:ou-robust
```

### Advanced Options

```bash
# Reanudar desde donde falló
php artisan scrape:ou-robust --resume

# Probar letras específicas que fallaron
php artisan scrape:ou-robust --letters=e,f,g

# Timeout personalizado
php artisan scrape:ou-robust --timeout=120
```

### Human Curation Dashboard

```bash
# Acceder al dashboard de matching
/admin/matching

# Ver mapeos existentes
/admin/matching/mappings

# Revisar matches fallidos
/admin/matching/failed
```

## Performance Metrics

### Coverage Rates
- **Barcode Coverage**: Porcentaje de productos con código de barra
- **Image Coverage**: Porcentaje de productos con imagen
- **Automation Rate**: Porcentaje de matches automáticos

### Quality Metrics  
- **Average Confidence**: Puntuación promedio de confianza
- **Review Rate**: Porcentaje que requiere revisión humana
- **Success Rate**: Porcentaje de matching exitoso

## Error Handling

### Connection Issues
- Timeouts extendidos (90s default)
- Reintentos específicos para errores de conexión
- Pausas progresivas para no sobrecargar API
- Modo recovery para letras fallidas

### Data Quality
- Validación de datos mínimos (nombre + marca requeridos)
- Limpieza automática de caracteres especiales
- Detección de placeholders ("Nombre Desconocido")

## Monitoring & Logging

### Log Levels
- `INFO`: Procesamiento normal y estadísticas
- `WARNING`: Timeouts y reintentos
- `ERROR`: Fallos graves y excepciones

### Key Metrics
- Products processed per letter
- API response times
- Matching confidence scores
- Failed match reasons

## Future Enhancements

### Planned Features
- Machine learning para mejorar matching
- API rate limiting automático
- Batch processing optimizado
- Real-time matching dashboard

### Optimization Opportunities
- Caching de respuestas de Open Food Facts
- Parallel processing de letras
- Incremental updates vs full scrape
- API key para Open Food Facts (rate limits)

## Troubleshooting

### Common Issues

#### Timeouts de OU API
```bash
# Usar scraper robusto con timeout extendido
php artisan scrape:ou-robust --timeout=120 --letters=e,f,g
```

#### Matching Accuracy Low
```bash
# Revisar failed matches en dashboard
/admin/matching/failed

# Ajustar thresholds en IntelligentMatchingEngine
const CONFIDENCE_THRESHOLDS = [
    'auto_match' => 75, // Bajar a 75
    'manual_review' => 40
];
```

#### Memory Issues
```bash
# Reducir page size en scraper
$perPage = 25; // En lugar de 50 o 100
```

## Production Deployment

### Environment Variables
```env
HTTP_VERIFY_SSL=true
QUEUE_CONNECTION=redis
```

### Queue Configuration
```bash
# Procesar jobs de scraping
php artisan queue:work --queue=scraping --timeout=300

# Monitorear cola
php artisan queue:monitor scraping
```

### Database Optimization
```sql
-- Índices recomendados
CREATE INDEX idx_ou_mappings_search ON ou_off_mappings(ou_product_name, ou_brand_name);
CREATE INDEX idx_failed_matches_review ON failed_matches(needs_human_review, reviewed_at);
```

---

**Last Updated**: 2026-03-04  
**Version**: 1.0  
**Author**: KosherStatus Development Team
