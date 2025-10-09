import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

document.addEventListener('DOMContentLoaded', function() {
    const editorElement = document.querySelector('#editor');
    
    if (editorElement) {
        ClassicEditor
            .create(editorElement, {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo'],
                placeholder: 'Write your comment here...',
            })
            .then(editor => {
                // Configuración adicional si es necesaria
                editor.editing.view.change(writer => {
                    writer.setStyle('min-height', '150px', editor.editing.view.document.getRoot());
                });
                
                // Solución para el problema de validación del formulario
                const form = editorElement.closest('form');
                if (form) {
                    form.addEventListener('submit', function() {
                        // Asegurarse de que el contenido del editor se transfiera al textarea antes de enviar
                        editorElement.value = editor.getData();
                    });
                }
                
                // Eliminar el atributo required del textarea original para evitar problemas de validación
                editorElement.removeAttribute('required');
                
                // Agregar validación personalizada
                if (form) {
                    form.addEventListener('submit', function(e) {
                        if (editor.getData().trim() === '') {
                            e.preventDefault();
                            alert('Please write a comment before submitting.');
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error al inicializar CKEditor:', error);
            });
    }
});