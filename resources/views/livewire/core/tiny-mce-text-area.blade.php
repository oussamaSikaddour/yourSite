<div wire:ignore>
    <textarea id="{{ $htmlId }}"></textarea>
</div>

@script
    <script>
        const getLang = () => {
            const storedLang = localStorage.getItem('language');
            switch (storedLang) {
                case 'Ar':
                    return 'ar';
                case 'En':
                    return 'en';
                case 'Fr':
                    return 'fr_FR';
                    core:
                        return 'fr_FR'; // core language
            }
        };

        // Initialize TinyMCE editor
        const initializeTinyMCE = (editorId, initialContent, viewOnly) => {


            tinymce.init({
                selector: `#${editorId}`,

                disabled: viewOnly==1?true:false,
                menubar: !viewOnly,
                statusbar: !viewOnly,
                toolbar: viewOnly ? false :
                    'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | code | table',
                plugins: viewOnly ? '' : 'code table lists',
                language: getLang(),
                setup: function(editor) {
                    const updateContent = (content) => {
                        editor.setContent(content);
                        editor.save();
                    };

                    // Initialize content in the editor

                    editor.on('init', function() {
                        console.log(viewOnly)
                        updateContent(`{!! $content !!}`);
                    });

                    // Update Livewire content when mouse leaves the editor
                    if (!viewOnly) {
                        editor.on('MouseLeave', () => {
                            @this.call('setContent', editor.getContent());
                        });
                    }
                },
            });
        };

        // Listen for the event and initialize TinyMCE
        $wire.on('initialize-tiny-mce', () => {
            initializeTinyMCE('{{ $htmlId }}', `{!! $content !!}`, '{{ $viewOnly }}');
        });
    </script>
@endscript
