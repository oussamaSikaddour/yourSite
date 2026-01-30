<div wire:ignore>
    <textarea id="{{ $htmlId }}"></textarea>
</div>

@script
<script>
(() => {
    const getLang = () => {
        const storedLang = localStorage.getItem('language');
        switch (storedLang) {
            case 'Ar': return 'ar';
            case 'En': return 'en';
            case 'Fr': return 'fr_FR';
            default:   return 'fr_FR';
        }
    };

    const initializeTinyMCE = (editorId, initialContent, viewOnly) => {
        // ✅ IMPORTANT: destroy old instance if it exists (fix reopen)
        const existing = tinymce.get(editorId);
        if (existing) {
            existing.off();
            existing.remove();
        }

        tinymce.init({
            selector: `#${editorId}`,
            disabled: viewOnly == 1 || viewOnly === true,

            menubar: !viewOnly,
            statusbar: !viewOnly,
            toolbar: viewOnly ? false :
                'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | code | table',
            plugins: viewOnly ? '' : 'code table lists',
            language: getLang(),

            setup: function(editor) {
                editor.on('init', function() {
                    editor.setContent(initialContent || '');
                    editor.save();
                });

                if (!viewOnly) {
                    editor.on('change keyup blur', () => {
                        @this.call('setContent', editor.getContent());
                    });
                }
            },
        });
    };

    $wire.on('initialize-tiny-mce', () => {
        console.log('test');
        initializeTinyMCE(@js($htmlId), @js($content), @js($viewOnly));
    });

        window.addEventListener('tinymce-destroy-all', destroyEditor);
})();
</script>
@endscript
