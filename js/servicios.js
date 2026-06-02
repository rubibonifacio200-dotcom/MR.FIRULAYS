document.addEventListener("DOMContentLoaded", () => {

    const btnMostrar = document.getElementById("btnMostrarRecomendaciones");

    const btnOcultar = document.getElementById("btnOcultarRecomendaciones");

    const section = document.getElementById("recomendacionesSection");

    
    btnMostrar.addEventListener("click", () => {

        section.classList.remove("hidden-section");

    });

    
    btnOcultar.addEventListener("click", () => {

        section.classList.add("hidden-section");

    });

});