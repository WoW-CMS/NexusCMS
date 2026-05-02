import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

document.addEventListener('DOMContentLoaded', function () {
    const editorElement = document.querySelector('#editor');

    if (!editorElement) return;

    ClassicEditor
        .create(editorElement, {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo'],
            placeholder: 'Write your comment here...',
        })
        .then(editor => {
            editor.editing.view.change(writer => {
                writer.setStyle('min-height', '150px', editor.editing.view.document.getRoot());
            });

            editorElement.removeAttribute('required');

            const form = editorElement.closest('form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    editorElement.value = editor.getData();
                    if (editor.getData().trim() === '') {
                        e.preventDefault();
                        alert('Please write a comment before submitting.');
                    }
                });
            }
        })
        .catch(error => {
            console.error('CKEditor comment init error:', error);
        });
});