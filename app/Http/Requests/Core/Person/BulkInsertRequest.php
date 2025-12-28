<?php

namespace App\Http\Requests\Core\Person;

use App\Http\Requests\ApiFormRequest;

class BulkInsertRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
public function authorize(): bool
{
    return $this->user()?->canAny(['super-admin-access','admin-access']) ?? false;
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',             // The file must be present
                'file',                 // Must be a file upload
                'mimes:xls,xlsx',       // Must be an Excel file (xls or xlsx)
                'max:10240',            // Max file size 10MB
            ],
        ];
    }





}
