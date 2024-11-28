window.onload = function() {
    var mensaje = document.getElementById("mensaje-flotante");
    if (mensaje) {
        mensaje.classList.add("show");
        setTimeout(function(){ mensaje.classList.remove("show"); }, 3000);
    }
}


window.onload = function() {
    var mensaje = document.getElementById("mensaje-flotante");
    if (mensaje) {
        mensaje.classList.add("show");
        setTimeout(function(){ mensaje.classList.remove("show"); }, 3000);
    }
}


window.onclick = function(event) {
    if (!event.target.matches('.btn-usuario')) {
        var dropdowns = document.getElementsByClassName("menu-desplegable");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.style.display === "block") {
                openDropdown.style.display = "none";
            }
        }
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const mensajeFlotante = document.getElementById('mensaje-flotante');

    if (mensajeFlotante) {
        // Mostrar el mensaje flotante durante 5 segundos
        setTimeout(function () {
            mensajeFlotante.classList.remove('show');
        }, 5000);
    }
});
