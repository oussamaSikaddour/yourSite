<?php

namespace App\Livewire\Core;

use App\Livewire\Forms\Core\User\AddForm;
use App\Livewire\Forms\Core\User\UpdateForm;
use App\Models\Image;
use App\Models\Person;
use App\Models\User;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class UserModal extends Component
{
    use WithFileUploads, GeneralTrait;

    /* -------------------- Forms -------------------- */
    public AddForm $addForm;
    public UpdateForm $updateForm;

    /* -------------------- Models & State -------------------- */
    public User $user;
    public $id;
    public $personId;
    public $form = "addForm";
    public $locale = 'fr';
    public $personsOptions = [];

    public $temporaryImageUrl;
    public $isActiveCheckBoxValue;


    /* ============================================================
       COMPUTED PROPERTIES
       ============================================================ */

    #[Computed]
    public function persons()
    {
        $nameLocale = $this->locale === 'ar' ? 'ar' : 'fr';
        $lastNameField = 'last_name_' . $nameLocale;
        $firstNameField = 'first_name_' . $nameLocale;

        return Person::query()
            ->select(['id', $lastNameField, $firstNameField])
            ->get()
            ->map(fn($person) => [
                'id'        => $person->id,
                'full_name' => trim($person->{$lastNameField} . ' ' . $person->{$firstNameField}),
            ]);
    }


    #[Computed]
    public function formEntity()
    {
        return $this->id ? $this->updateForm : $this->addForm;
    }


    /* ============================================================
       LIFECYCLE
       ============================================================ */

    public function mount(): void
    {
        $this->locale = app()->getLocale();

        $this->preparePersonsOptions();

        if ($this->id) {
            $this->form = "updateForm";
            $this->loadUserDataSafe();
        } else {
            $this->addForm->fill([
                'person_id' => $this->personId,
            ]);
        }
    }


    /* ============================================================
       DATA LOADING
       ============================================================ */

    protected function loadUserDataSafe(): void
    {
        try {
            $this->loadUserData();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->logModelError($e);
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    protected function loadUserData(): void
    {
        $this->user = User::findOrFail($this->id);

        $avatar = Image::where([
            ['imageable_id', '=', $this->user->id],
            ['imageable_type', '=', User::class],
            ['use_case', '=', 'avatar']
        ])->first();

        $this->temporaryImageUrl = $avatar?->url ?? $this->temporaryImageUrl;
        $this->isActiveCheckBoxValue = $this->user->is_active == 1;

        $this->fillUpdateForm();
    }

    protected function fillUpdateForm(): void
    {
        $this->updateForm->fill([
            'id'        => $this->id,
            'name'      => $this->user->name,
            'email'     => $this->user->email,
            'is_active' => $this->isActiveCheckBoxValue,
            'person_id' => $this->user->person_id,
        ]);
    }


    /* ============================================================
       FORM SUBMISSION
       ============================================================ */

    public function handleSubmit(): void
    {
        $this->dispatch('form-submitted');

        if (auth()->id() === $this->id) {
            $this->dispatch('update-nav-user-btn');
        }

        $response = $this->id
            ? $this->updateForm->save($this->user)
            : $this->addForm->save();

        if (!$this->id) {
            $this->addForm->reset();
            $this->addForm->fill(['person_id' => $this->personId]);
        }


        if ($response['status']) {
            $this->dispatch('update-users-table');
            $this->dispatch('update-persons-table');
            $this->dispatch('open-toast', $response['message']);
        } else {

            $this->dispatch('open-errors', $response['errors']);
        }
    }


    /* ============================================================
       PROPERTY UPDATES
       ============================================================ */

    public function updated($property): void
    {
        try {
            if (in_array($property, ['addForm.avatar', 'updateForm.avatar'])) {
                $this->temporaryImageUrl = $this->{$this->form}->avatar?->temporaryUrl();
            }

            if ($property === 'isActiveCheckBoxValue') {
                $this->updateForm->fill(['is_active' => $this->isActiveCheckBoxValue]);
            }
        } catch (\Exception $e) {
            $this->dispatch('open-errors', [__('modals.common.img-type-err')]);
        }
    }


    /* ============================================================
       HELPERS
       ============================================================ */

    protected function preparePersonsOptions(): void
    {

        $this->personsOptions = $this->populateSelectorOption(
            $this->persons(),
            'id',
            'full_name',
            __('selectors.default.persons')
        );
    }

    protected function logModelError(\Throwable $exception): void
    {
        Log::error("UserModal: Failed loading user.", [
            'message' => $exception->getMessage(),
            'user_id' => $this->id,
        ]);
    }


    /* ============================================================
       RENDER
       ============================================================ */

    public function render()
    {
        return view('livewire.core.user-modal');
    }
}
