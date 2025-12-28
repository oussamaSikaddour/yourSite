<?php

namespace App\View\Components\Core;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileInput extends Component
{
    public string $htmlId;
    public string $iconHtml;
    public ?string $typesToUpload;

    public function __construct(
        public string $icon = 'upload',
        public string $model = '',
        public string $text = 'Upload',
        public ?string $tooltip = null,
        public ?string $types = null,
        public bool $multiple = false,
        public string $type = 'default',
        public ?string $fileUri = null,
    ) {
        // Stable unique ID
        $this->htmlId = 'fi-' . Str::random(10);

        // Pre-resolved values
        $this->iconHtml = $this->resolveIconHtml($icon);
        $this->typesToUpload = $this->resolveMimeTypes($types);
    }


    private function resolveMimeTypes(?string $types): ?string
    {
        if (!$types) {
            return null;
        }

        $map = [
            'pdf'   => ['application/pdf'],
            'excel' => [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel',
            ],
            'img' => [
                'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            ],
            'doc'   => [
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ],
            'csv'   => ['text/csv'],
            'zip'   => ['application/zip'],
        ];

        $input = array_map(fn ($t) => trim(strtolower($t)), explode(',', $types));

        $resolved = [];

        foreach ($input as $t) {
            $resolved = array_merge(
                $resolved,
                $map[$t] ?? [$t]
            );
        }

        return implode(',', array_unique($resolved));
    }


    private function resolveIconHtml(string $icon): string
    {
        return config("core.icons.FONTAWESOME.$icon")
            ?? tap('<i class="fa-solid fa-question"></i>', function () use ($icon) {
                Log::warning("Icon '$icon' not found in config.");
            });
    }


    public function render()
    {
        return view('components.core.file-input');
    }
}
