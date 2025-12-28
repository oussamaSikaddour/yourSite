<?php

namespace App\Livewire\Core;

use App\Models\Image;
use App\Models\User;
use App\Traits\Core\Common\TableTrait;
use App\Traits\Core\Common\TextAndPdfTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination, TableTrait, WithFileUploads, ResponseTrait, TextAndPdfTrait;

    #[Url]
    public string $name = '';

    #[Url]
    public ?string $email = null;




    public ?bool $isForSuperAdmin = null;

    public string $updateUserBtnTitle = '';


    public string $local = 'fr';

    protected array $filterable = ['name', 'email'];

    protected array $validationRules = [
        'name' => ['nullable', 'string', 'max:255'],
        'email' => ['nullable', 'string', 'email', 'max:255']
    ];

    public function mount(): void
    {

        $this->local = app()->getLocale();


    }


    public function resetFilters(): void
    {
        $this->reset(['name', 'email', 'password']);
        $this->resetPage();
    }

    #[Computed]
    public function users()
    {


        return User::query()
            ->when(!empty($this->name), function ($query) {

                $query->where("name", 'like', "%{$this->name}%");
            })
            ->when(
                !empty($this->email),
                fn($query) =>
                $query->where('email', 'like', "%{$this->email}%")
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }


    public function updated(string $property): void
    {
        if ($property === 'excelFile') {
            $errorsFileData = $this->whenExcelFileUploaded("Core\UsersImport", __('tables.users.excel.upload.success'));

            if (is_array($errorsFileData)) {
                $this->dispatch('errors-file-data', errorsFileData: $errorsFileData);
            }
        }

        if (in_array($property, $this->filterable) || $property === 'perPage') {
            $this->resetPage();
        }

        if (array_key_exists($property, $this->validationRules)) {
            try {
                $this->validateOnly($property, $this->validationRules);
            } catch (ValidationException $e) {
                $this->dispatch('open-errors', $e->validator->errors()->all());
            }
        }
    }

    #[On('errors-file-data')]
    public function downloadUsersErrorsTextFile($errorsFileData)
    {
        return $this->streamFileDownload($errorsFileData['filePath'], $errorsFileData['fileName']);
    }

    public function generateEmptyUsersExcel()
    {
        return $this->generateEmptyExcelWithHeaders("personnelVide", [
            'Nom (français)',
            'Prénom (français)',
            'Nom (Arabic)',
            'Prénom (Arabic)',
            'E-mail',
        ]);
    }

    public function generateUsersExcel()
    {
        return $this->generateExcel(fn() => $this->users()->map(fn($user) => [
            __("tables.users.name")      => $user->name,
            __("tables.users.email")             => $user->email,
            __("tables.users.registration_date") => $user->created_at->format('d-m-Y'),
        ])->toArray(), "users");
    }

    public function openDeleteUserDialog($user): void
    {

        $data = [
            "question" => "dialogs.title.user",
            "details" => ["user", $user['name']],
            "actionEvent" => [
                "event" => "delete-user",
                "parameters" => $user
            ],
        ];

        $this->dispatch("open-dialog", $data);
    }

    #[On("delete-user")]
    public function deleteUser(User $user): void
    {
        try {
            $images = Image::where([
                ['imageable_id', $user->id],
                ['imageable_type', User::class],
            ])->get();

            if ($images->isNotEmpty()) {
                $this->deleteImages($images);
            }

            $user->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    public function render()
    {
        return view('livewire.core.users-table');
    }
}
