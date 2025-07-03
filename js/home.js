document.addEventListener("DOMContentLoaded", () => {
    const header = document.querySelector("header");
    const hero = document.getElementById("hero");
    const nav = document.querySelector("nav");

    // Cambia lo sfondo della navbar al di fuori della zona hero
    window.addEventListener("scroll", () => {
        const heroBottom = hero.getBoundingClientRect().bottom;
        const navBottom = nav.getBoundingClientRect().bottom;
        if (heroBottom <= 0) {
            header.classList.add("solid");
            nav.classList.add("solid");
            header.classList.remove("transparent");
        } else {
            header.classList.add("transparent");
            header.classList.remove("solid");
            nav.classList.remove("solid");
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const menuToggle = document.getElementById("menu-toggle");
    const navLinks = document.getElementById("nav-links");
    const navBar = document.getElementById("nav");

    if (menuToggle && navLinks) {
        menuToggle.addEventListener("click", () => {
            const isExpanded = navLinks.classList.toggle("show");
            navBar.classList.toggle("show");
            menuToggle.setAttribute('aria-expanded', isExpanded);
            
            const icon = menuToggle.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            }
        });
    }
});