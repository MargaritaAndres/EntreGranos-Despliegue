/* saludo.js
Muestra un saludo en los elementos con id="saludo-usuario"
El nombre se pasa desde PHP vía data-nombre */

document.addEventListener("DOMContentLoaded", () => {
    const span = document.getElementById("saludo-usuario");
    if (span) {
        const nombre = span.dataset.nombre || "Usuario";
        span.textContent = `👋 ¡Hola, ${nombre}!`;
    }
});
