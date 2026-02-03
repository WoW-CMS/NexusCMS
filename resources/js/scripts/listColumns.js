class ListColumns {
    constructor() {
        this.modal = document.getElementById('columnsModal');
        this.checkboxes = document.querySelectorAll('.column-toggle');
        this.toggleButtons = document.querySelectorAll('.toggle-columns-modal');
        this.resetButton = document.querySelector('.reset-columns');
    }

    init() {
        if (!this.modal) return;

        // Toggle Modal Listeners
        this.toggleButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleModal();
            });
        });

        // Reset Button Listener
        if (this.resetButton) {
            this.resetButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.resetColumns();
            });
        }

        // Checkbox Listeners
        this.checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                this.toggleColumn(e.target);
            });
        });
    }

    toggleModal() {
        this.modal.classList.toggle('hidden');
    }

    toggleColumn(checkbox) {
        const columnName = checkbox.getAttribute('data-column');
        if (!columnName) return;

        const columns = document.querySelectorAll('.column-' + columnName);
        const isVisible = checkbox.checked;

        columns.forEach(col => {
            if (isVisible) {
                col.classList.remove('hidden');
            } else {
                col.classList.add('hidden');
            }
        });
    }

    resetColumns() {
        this.checkboxes.forEach(checkbox => {
            checkbox.checked = true;
            this.toggleColumn(checkbox);
        });
    }
}

// Initialize
const listColumns = new ListColumns();
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => listColumns.init());
} else {
    listColumns.init();
}

export default ListColumns;
