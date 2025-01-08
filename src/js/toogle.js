(function() {
    document.querySelectorAll('.toogle_check').forEach(toggle => {
        toggle.addEventListener('change', async function () {
            const userId = this.getAttribute('data-id');
            const isActive = this.checked ? 1 : 0;

            try {
                const response = await fetch('/admin/perfiles/actualizar-estado', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: userId, activo: isActive }),
                });

                const result = await response.json();
                if (!result.success) {
                    alert('Error actualizando el estado');
                    this.checked = !this.checked; // Revertir si hay error
                }
            } catch (error) {
                alert('Error en el servidor');
                this.checked = !this.checked; // Revertir si hay error
            }
        });
    });
})();
