# Plan de marketing por email con Brevo (administrado desde la notebook)

Estado: **propuesta para decidir**. Nada de esto está construido todavía.

## 1. Objetivo

Usar los créditos mensuales de Brevo para tres cosas, en este orden de valor:

1. **Vender planes y banners a certificadoras y comercios** (ingreso directo).
2. **Activar a las certificadoras ya aprobadas**: que carguen productos y usen la insignia (más contenido y más enlaces).
3. **Traer tráfico de consumidores** con un newsletter mensual (productos nuevos, una nota, fechas del calendario judío).

## 2. A quién se le escribe (y con qué permiso)

El permiso es lo que decide el plan; Brevo y la ley lo exigen. Sin permiso explícito, **no** va a campañas masivas.

| Audiencia | Origen | ¿Se puede escribir en masa? | Qué se le manda |
|---|---|---|---|
| Certificadoras aprobadas / dueños de locales | Cuentas de KosherMap | Sí, mails de servicio y novedades de su cuenta (relación existente) | Onboarding, novedades, ofertas de plan |
| Empresas que pidieron certificarse (leads) | Formulario a una certificadora | **No** para marketing de KosherMap: pidieron contacto con la certificadora, no con nosotros | Solo el seguimiento transaccional que ya existe |
| Certificadoras / comercios que todavía no están | Investigación propia | Contacto **uno a uno**, no campaña masiva (mail personal) | Invitación a sumarse |
| Consumidores | Formulario de suscripción **nuevo** con doble confirmación | Sí, solo suscriptos | Newsletter mensual |

Reglas fijas: enlace de **baja** en cada mail, remitente `info@koshermap.org`, política de privacidad
actualizada para mencionar el newsletter, y no importar listas compradas.
(Pendiente de validar con un abogado: registro de la base de datos ante la AAIP en Argentina.)

## 3. Arquitectura: "administrar desde la notebook"

Mismo criterio que ya usamos para los scrapers (`sync:push`): **la operación vive en local; producción solo
guarda datos y consentimientos.**

```
 Producción (koshermap.org)                    Notebook (Laragon)                    Brevo
 ───────────────────────────                   ───────────────────                   ─────
 tabla newsletter_subscribers  ──(API sync)──► marketing:pull-contacts
 (email, idioma, consentimiento,                       │
  fecha, IP, estado)                                    ▼
 cuentas / certificadoras  ─────(API sync)──► marketing:sync-lists ───────────────► listas + contactos
                                                        │
                                       marketing:campaign (crear / programar) ────► campañas
                                       marketing:stats (aperturas, clics, bajas) ◄── estadísticas
                                                        │
                                       bajas / rebotes ◄────────────────────────── webhooks / consulta
                                                        ▼
                                              marcar en producción (sync)
```

- La **clave de API de Brevo vive solo en el `.env` local**, nunca en producción ni en git.
- Producción no necesita hablar con la API de Brevo: guarda el alta/consentimiento en su base y la notebook
  lo recoge. Los mails transaccionales siguen saliendo por SMTP como hoy.
- Una **pantalla en el admin local** (no en producción) para ver listas, programar y mirar resultados.

## 4. Qué se manda (calendario base)

| Cuándo | Qué | Audiencia |
|---|---|---|
| Al aprobarse | Bienvenida: cómo cargar productos + insignia | Certificadoras |
| +7 días | "Todavía no cargaste productos" (solo si 0) | Certificadoras |
| +21 días | Cómo se ve su ficha y qué cambia con un plan (con precio en USD) | Certificadoras |
| Mensual | Newsletter: 3 novedades + 1 nota + 1 fecha del calendario | Suscriptos |
| Estacional | Pesaj (abril 2027): "productos kosher para Pesaj"; Janucá (dic. 2026) | Suscriptos |
| Trimestral | Resumen de contactos recibidos (valor del plan) | Certificadoras |

Los mails de **vencimiento de plan** y **contacto de empresas** ya existen y no cambian.

## 5. Volumen y presupuesto

- Revisar en Brevo → *Plan* el tope vigente (créditos por mes o por día, y si incluye campañas y automatizaciones).
- Con las audiencias de hoy (decenas de certificadoras, una base de suscriptos que arranca en cero) alcanza
  holgado: el primer límite no es el cupo sino **construir la lista**.
- Dominio nuevo: empezar con envíos chicos y subir de a poco (reputación). DMARC hoy está en `p=none`; subirlo
  a `quarantine` cuando haya 4–6 semanas de envíos limpios.

## 6. Métricas

Apertura, clics, bajas y rebotes por campaña; y las que importan al negocio: certificadoras que cargaron
productos tras el onboarding, planes y banners comprados, y suscriptos nuevos por mes.

## 7. Etapas

| Etapa | Entregable | Depende de |
|---|---|---|
| 0 | Dominio autenticado (hecho) + política de privacidad con newsletter | tu decisión |
| 1 | Formulario de suscripción con doble confirmación + tabla en producción + baja | — |
| 2 | Comandos locales: traer contactos, sincronizar listas, crear y programar campañas, estadísticas | clave de API de Brevo |
| 3 | Pantalla local de administración (listas, campañas, resultados) | etapa 2 |
| 4 | Automatizaciones del onboarding de certificadoras | etapa 2 |

## 8. Decisiones que necesito de vos

1. ¿El foco inicial es **certificadoras/comercios** (ingreso) o **consumidores** (tráfico)? Recomiendo arrancar por
   certificadoras (etapas 2 y 4) y construir el newsletter en paralelo.
2. ¿Idioma de los mails: según el idioma del usuario (como ya hacemos) o solo español al principio?
3. ¿Quién firma los mails (nombre y cargo) y qué tono usamos?
4. ¿Qué plan de Brevo tenés hoy? (para confirmar los límites reales).
