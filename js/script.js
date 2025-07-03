// script.js 

document.addEventListener("DOMContentLoaded", function () {
    myFunction();

    // Ottieni il percorso del file dall'URL
    const currentPage = window.location.pathname.split('/').pop();
    
    // Controlla se siamo su "home.php"
    if (currentPage === 'details.php') {
        console.log('Sei su details.php');
        // Chiama una funzione specifica
        specificFunctionForHome();
    }
    /**/
});

function myFunction() {
   
    const header = document.querySelector("header");
    const heroBottom = header.getBoundingClientRect().bottom;
    header.classList.add("solid");
    header.style.position = "relative";

    const nav = document.querySelector("nav");
    const navBottom = nav.getBoundingClientRect().bottom;
    nav.classList.add("solid");


}

// Funzione specifica per details.php
function specificFunctionForHome() 
{
    //aggiunge la classe dettagli alla sidebar
    const sidebar = document.querySelector("aside.sidebar");
    if (sidebar) {
        sidebar.classList.add("dettagli");
    }
    
}

document.addEventListener("DOMContentLoaded", function() {
    const menuToggle = document.getElementById("menu-toggle");
    const navLinks = document.getElementById("nav-links");

    if (menuToggle && navLinks) {
        menuToggle.addEventListener("click", () => {
            const isExpanded = navLinks.classList.toggle("show");
            menuToggle.setAttribute('aria-expanded', isExpanded);
            // Cambia l'icona in base allo stato
            const icon = menuToggle.querySelector('i');
            if (icon) {
                if (isExpanded) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.querySelector('.toggle-filters');
    const filterForm = document.getElementById('filter-form');

    if (toggleButton && filterForm) {
        toggleButton.addEventListener('click', function () {
            const isActive = filterForm.classList.toggle('active');
            toggleButton.setAttribute('aria-expanded', isActive);
            // Cambia l'icona in base allo stato
            toggleButton.textContent = isActive ? '✖' : '☰'; // Cambia l'icona
        });
    }

    // Listener per il menu di ordinamento
    const sortOrderSelect = document.getElementById('sort_order');
    if (sortOrderSelect) {
        sortOrderSelect.addEventListener('change', function () {
            // Ottieni l'URL corrente
            const url = new URL(window.location.href);
            // Imposta o aggiorna il parametro 'sort_order'
            url.searchParams.set('sort_order', this.value);
            // Resetta la pagina a 1 quando si cambia l'ordinamento
            url.searchParams.set('page', '1');
            // Reindirizza alla nuova URL
            window.location.href = url.toString();
        });
    }

    // Listener per il form di ricerca
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            // Eventuale logica aggiuntiva prima di inviare il form
            // Ad esempio, validazione del campo di ricerca
        });
    }
});


function changeMainImage(thumbnail) {
    // Cambia l'immagine principale
    const mainImage = document.getElementById('main-image');
    mainImage.src = thumbnail.src;
    mainImage.alt = thumbnail.alt;

    // Aggiorna l'attributo href del link principale per Lightbox
    mainImage.parentElement.href = thumbnail.src;
}
