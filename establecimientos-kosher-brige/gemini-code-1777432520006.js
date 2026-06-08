const API_BASE = "https://tuservidor_laravel.test/api";

// 1. CAPTURA DE DATOS (Equivalente a tu capturarYEnviarCartera)
function capturarEstablecimientos() {
    // Selector de las fichas de locales en la lista izquierda
    const locales = document.querySelectorAll('div[role="article"]');
    let listaKosher = [];

    locales.forEach(local => {
        const nombre = local.querySelector('.fontHeadlineSmall')?.innerText;
        const link = local.querySelector('a')?.href;
        
        // Extraer coordenadas de la URL (el truco del @lat,log)
        const coords = link?.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);

        if (nombre) {
            listaKosher.push({
                nombre: nombre,
                google_link: link,
                lat: coords ? coords : null,
                lng: coords ? coords : null,
                // Podés sacar más datos buscando clases específicas para dirección/teléfono
            });
        }
    });

    if (listaKosher.length > 0) {
        console.log("📤 Enviando Locales detectados:", listaKosher);
        enviarAlServidor(listaKosher);
    }
}

// 2. ENVÍO A LARAVEL (Igual a tu función enviarPuntas)
function enviarAlServidor(datos) {
    fetch(`${API_BASE}/guardar-establecimientos`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ establecimientos: datos })
    })
    .then(r => r.json())
    .then(d => console.log("✅ Datos guardados en KosherStatus:", d))
    .catch(e => console.error("❌ Error:", e));
}

// 3. NAVEGACIÓN AUTOMÁTICA (Equivalente a tu ciclo de puntas)
function scrollearYCapturar() {
    const feed = document.querySelector('div[role="feed"]');
    if (feed) {
        feed.scrollBy(0, 800); // Baja para cargar más
        setTimeout(capturarEstablecimientos, 2000);
    }
}

// INICIALIZACIÓN
(function init() {
    console.log("🚀 KOSHER-BRIDGE INICIADO");
    // Ejecuta la captura cada 5 segundos mientras hacés scroll o navegás
    setInterval(scrollearYCapturar, 5000);
})();