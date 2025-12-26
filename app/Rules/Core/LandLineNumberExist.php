<?php

namespace App\Rules\Core;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LandLineNumberExist implements ValidationRule
{
    private $model;
    private $currentId;
    private $attributes;

    /**
     * Constructor to initialize model, current ID and additional attributes.
     *
     * @param string $model - The model class to query.
     * @param int|null $currentId - The ID of the record being edited (if applicable).
     * @param array $attributes - Additional query filters (optional).
     */
    public function __construct($model, $currentId = null, $attributes = [])
    {
        $this->model = $model;
        $this->currentId = $currentId;
        $this->attributes = $attributes;
    }

    /**
     * Validate the given landline number (telephone or fax).
     *
     * @param string $attribute - The name of the field being validated.
     * @param mixed $value - The value of the field being validated.
     * @param Closure $fail - The closure to call if validation fails.
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {


        // Construct the base query to check if the number exists
        $query = $this->model::where(function ($query) use ($value) {
            $query->where('tel', $value)

                ->orWhere('fax', $value);
        });
        // If editing a record, exclude the current one from the query
        if ($this->currentId) {
            $query->where('id', '!=', $this->currentId);
        }
        // Apply additional attribute filters if provided
        if ($this->attributes && count($this->attributes)) {
            foreach ($this->attributes as $key => $v) {
                $query->where($key, $v);
            }
        }
        // Check if a record exists with the given number and is not soft-deleted
        $landLineExists = $query->whereNull('deleted_at')->exists();
        // If such a record exists, fail the validation with an appropriate message
        if ($landLineExists) {
            $fail(__('rules.land_line.invalid', ['number' => $value]));
        }
    }
}
