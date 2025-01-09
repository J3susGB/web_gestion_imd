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

        // Ocultar botones mientras se hace scroll
        scrollButtons.classList.add('hidden');

        // Detener el temporizador y programar la reaparición
        clearTimeout(isScrolling);
        isScrolling = setTimeout(() => {
            scrollButtons.classList.remove('hidden');
        }, 300);
    });

    // Función de desplazamiento suave con easing
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

    // Funcionalidad de los botones
    const scrollUpButton = document.getElementById('arriba');
    const scrollDownButton = document.getElementById('abajo');

    scrollUpButton.addEventListener('click', () => {
        smoothScroll(0, 1000); // Desplazarse hacia arriba con duración de 1000ms
    });

    scrollDownButton.addEventListener('click', () => {
        smoothScroll(document.body.scrollHeight, 1000); // Desplazarse hacia abajo con duración de 1000ms
    });
});
