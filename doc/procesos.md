# Procesos de KosherMap: crons y tareas locales

## En producción (Hostinger)

Un único cron en hPanel, cada minuto:

    * * * * * /opt/alt/php84/usr/bin/php /home/u906361878/domains/koshermap.org/public_html/artisan schedule:run

El scheduler (`routes/console.php`) ejecuta una vez por día:

| Comando | Qué procesa |
|---|---|
| `tier:renewal-reminders` | Certificadoras/locales con plan pago que vence en los próximos 3 días: les manda un mail con botón "Renovar mi plan" y marca `tier_reminder_sent_at` para no repetirlo. |
| `tier:expire-overdue` | Planes pagos vencidos hace más de 5 días de gracia: los baja a gratis y avisa por mail. |

Salida de cada corrida: `storage/logs/tier-scheduler.log`.

No hay cola (`queue:work`) ni scrapers en producción: `SCRAPING_ENABLED=false` bloquea `scrape:places` y `sync:push`.
Los sitemaps y robots.txt se generan por ruta, no por cron.

## En local (Windows/Laragon): `scripts/procesos-kosher.bat`

| Modo | Qué hace |
|---|---|
| `completo` | Scrapers (Ajdut, KMD, Kehila, Chile, UK, BDK) → cola de jobs → categorizar BDK y por palabras clave → limpiar datos → `sync:push` a producción |
| `scrapers` | Solo scrapers rápidos + cola |
| `ou` | OU Kosher (lento; `--resume` retoma por letra) + cola |
| `limpieza` | `categorize:bdk`, `products:smart-categorize`, `products:clean-data` |
| `sync` / `sync-prueba` | Envía todo a producción / muestra qué enviaría |
| `lugares` | `scrape:places --limit=2 --sync` (Google Maps, máx. 2 ciudades por corrida) |
| `estado` | Chequea URLs clave de producción |
| `prueba` | Verifica que existan los comandos y corre los dry-run |

Sin argumento muestra un menú. Cada corrida deja `storage/logs/procesos-AAAAMMDD-HHMM.log`.

## Deshabilitado a propósito
- `human:generate`: generaba reseñas falsas (Google lo detectó como contenido de bajo valor).
