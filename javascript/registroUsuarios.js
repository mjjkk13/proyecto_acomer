    // Escuchar el cambio en el campo de selección de rol
    document.getElementById('rol').addEventListener('change', function() {
        var cursosDisponibles = document.getElementById('cursosDisponibles');
        // Mostrar el campo de grados disponibles solo si el rol es docente
        if (this.value === 'docente') {
          cursosDisponibles.style.display = 'block';

        } else {
          cursosDisponibles.style.display = 'none';

        }
      });