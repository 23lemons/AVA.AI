document.addEventListener("DOMContentLoaded", function() {
    // Gestion du défilement doux pour les liens de navigation
    const navLinks = document.querySelectorAll('header nav ul li a');

    navLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();

            const targetId = link.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);

            if (targetSection) {
                targetSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Fonction pour incrémenter le compteur d'utilisateurs
    function incrementCount(id, start, end, duration) {
        let element = document.getElementById(id);
        let range = end - start;
        let current = start;
        let increment = end > start ? 15 : -1000;
        let step = Math.abs(Math.floor(duration / range));
        let timer = setInterval(() => {
            current += increment;
            if (current > end) {
                current = end;
            }
            element.textContent = current + "+";
            if (current == end) {
                clearInterval(timer);
            }
        }, step);
    }

    // Fonction pour vérifier si un élément est visible dans la fenêtre
    function isElementInViewport(el) {
        let rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Démarrer le compteur lorsque la section "À propos" est atteinte
    let aboutSection = document.getElementById('about');
    let counterStarted = false;

    window.addEventListener('scroll', function() {
        if (!counterStarted && isElementInViewport(aboutSection)) {
            incrementCount("user-count", 0, 1000, 50);
            counterStarted = true; // Empêche le compteur de se déclencher à nouveau
        }
    });
});
