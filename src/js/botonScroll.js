document.addEventListener('DOMContentLoaded', function () {
    const scrollButtons = document.querySelector('.botones__scroll');
    const mediaQuery = window.matchMedia('(min-width: 600px)');
    let isScrolling;

    // Mostrar los botones inicialmente
    scrollButtons.style.top = '43.2rem';
    scrollButtons.style.bottom = 'auto';

    // Escuchar el evento de scroll
    window.addEventListener('scroll', function () {
        const scrollTop = window.scrollY; // Cantidad de scroll hacia abajo
        const scrollHeight = document.documentElement.scrollHeight; // Altura total del documento
        const clientHeight = document.documentElement.clientHeight; // Altura visible del viewport
        const tolerance = 5; // Tolerancia de 5px para detectar el final
        const isBottom = scrollTop + clientHeight >= scrollHeight - tolerance;
        const isTop = scrollTop === 0; // Verificar si estamos arriba del todo

        if (isTop) {
            console.log('Estamos en la parte superior');
            scrollButtons.style.top = '43.2rem';
            scrollButtons.style.bottom = 'auto';
        } else if (isBottom) {
            console.log('Estamos en la parte inferior');
            scrollButtons.style.bottom = mediaQuery.matches ? '8rem' : '16rem';
            scrollButtons.style.top = 'auto';
        } else {
            console.log('Estamos desplazándonos');
            scrollButtons.style.top = 'auto';
            scrollButtons.style.bottom = '2rem';
        }

        scrollButtons.classList.add('hidden');

        clearTimeout(isScrolling);
        isScrolling = setTimeout(() => {
            scrollButtons.classList.remove('hidden');
        }, 300);
    });

    // Función de desplazamiento suave con duración ajustada por tamaño de pantalla
    const smoothScroll = (targetY, duration) => {
        const startY = window.scrollY; // Posición inicial
        const distance = targetY - startY; // Distancia a desplazar
        const startTime = performance.now(); // Tiempo inicial

        const easeInOutQuad = (t) => t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;

        const animateScroll = (currentTime) => {
            const timeElapsed = currentTime - startTime;
            const progress = Math.min(timeElapsed / duration, 1); // Limitar el progreso entre 0 y 1
            const easedProgress = easeInOutQuad(progress); // Aplicar easing

            window.scrollTo(0, startY + distance * easedProgress);

            if (timeElapsed < duration) {
                requestAnimationFrame(animateScroll);
            }
        };

        requestAnimationFrame(animateScroll);
    };

    // Funcionalidad de los botones con velocidad ajustada
    const scrollUpButton = document.getElementById('arriba');
    const scrollDownButton = document.getElementById('abajo');

    scrollUpButton.addEventListener('click', () => {
        const duration = mediaQuery.matches ? 1000 : 2000; // 1000ms para >= 760px, 2000ms para < 760px
        smoothScroll(0, duration);
    });

    scrollDownButton.addEventListener('click', () => {
        const duration = mediaQuery.matches ? 1000 : 2000; // 1000ms para >= 760px, 2000ms para < 760px
        smoothScroll(document.body.scrollHeight, duration);
    });
});
