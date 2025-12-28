<?php

namespace App\Rules\Core;

use App\Models\Daira;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDaira implements ValidationRule
{
    /**
     * Wilaya code for filtering dairas.
     */
    private string $wilayaCode;

    /**
     * Flattened list of all possible accepted names (lowercased).
     */
    private array $acceptedNames = [];

    /**
     * Create a new rule instance.
     */
    public function __construct(string $wilayaCode = '13')
    {
        $this->wilayaCode = $wilayaCode;

        // Preload and flatten all accepted names into one array
        $this->acceptedNames = Daira::query()
            ->select(['dairates.id', 'dairates.designation_ar', 'dairates.designation_fr', 'dairates.designation_en'])
            ->join('wilayates', 'wilayates.id', '=', 'dairates.wilaya_id')
            ->where('wilayates.code', $this->wilayaCode)
            ->get()
            ->flatMap(fn ($daira) => [
                mb_strtolower($daira->designation_ar),
                mb_strtolower($daira->designation_fr),
                mb_strtolower($daira->designation_en),
            ])
            ->toArray();
    }

    /**
     * Validate if the given value matches any known daira name.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $submittedValue = mb_strtolower($value);

        if (!in_array($submittedValue, $this->acceptedNames, true)) {
            $fail(__('rules.daira.invalid', ['name' => $value]));
        }
    }
}
