document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.toggle-btn');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            row.classList.toggle('expanded');

            // Se almeno una riga ha la classe .expanded,
            // aggiunge/rimuove la classe .any-expanded alla TABELLA
            const table = row.closest('table');
            if (table.querySelector('tr.expanded')) {
                table.classList.add('any-expanded');
            } else {
                table.classList.remove('any-expanded');
            }

            // Cambiamo il testo del pulsante
            if (row.classList.contains('expanded')) {
                this.textContent = 'Nascondi';
            } else {
                this.textContent = 'Espandi';
            }
        });
    });
});
