<?php

namespace App\Livewire\Core;

use App\Livewire\Forms\Core\Person\AddForm;
use App\Livewire\Forms\Core\Person\UpdateForm;
use App\Models\Image;
use App\Models\Person;
use App\Models\User;
use App\Traits\Core\Common\GeneralTrait;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Log;

class PersonModal extends Component
{
    use WithFileUploads, GeneralTrait;

    public AddForm $addForm;
    public UpdateForm $updateForm;

    public Person $person;
    public $id;
    public $form = "addForm";
    public $local = 'fr';
    public $isPaidCheckBoxValue;
    public $isActiveCheckBoxValue;

    public $temporaryImageUrl;

    public function mount()
    {
        $this->local = app()->getLocale();
        if ($this->id) {
            $this->form = "updateForm";
            $this->loadPersonDataSafe();
        }
    }

    protected function loadPersonDataSafe()
    {
        try {
            $this->loadPersonData();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("PersonModal: Person not found", [
                'message' => $e->getMessage(),
                'person_id' => $this->id,
            ]);
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    protected function loadPersonData()
    {
        $this->person = Person::findOrFail($this->id);

        $avatar = Image::where('imageable_id', $this->person->user?->id)
            ->where('imageable_type', User::class)
            ->where('use_case', 'avatar')
            ->first();

        $this->temporaryImageUrl = $avatar?->url ?? $this->temporaryImageUrl;
        $this->isPaidCheckBoxValue = $this->person->is_paid === 1;
        $this->isActiveCheckBoxValue = $this->person->user?->is_active === 1;
        $this->updateForm->fill([
            'user.is_active' =>  $this->isActiveCheckBoxValue,
            'person.id' => $this->person->id,
            'person.first_name_fr' => $this->person->first_name_fr,
            'person.last_name_fr' => $this->person->last_name_fr,
            'person.first_name_ar' => $this->person->first_name_ar,
            'person.last_name_ar' => $this->person->last_name_ar,
            'person.birth_date' => $this->person->birth_date,
            'person.birth_place_fr' => $this->person->birth_place_fr,
            'person.birth_place_ar' => $this->person->birth_place_ar,
            'person.birth_place_en' => $this->person->birth_place_en,
            'person.address_fr' => $this->person->address_fr,
            'person.address_ar' => $this->person->address_ar,
            'person.address_en' => $this->person->address_en,
            'person.phone' => $this->person->phone,
            'person.is_paid' => $this->isPaidCheckBoxValue,
        ]);
    }

    public function handleSubmit()
    {
        $this->dispatch('form-submitted');

        $response = $this->id
            ? $this->updateForm->save($this->person)
            : $this->addForm->save();

        if (!$this->id) {
            $this->addForm->reset();
        }


        if ($response['status']) {
            $this->dispatch('update-persons-table');
            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }

    public function updated($property)
    {
        if (in_array($property, ['addForm.image', 'updateForm.image'])) {
            $this->temporaryImageUrl = $this->{$this->form}->image?->temporaryUrl();
        }

            if ($property === 'isPaidCheckBoxValue') {
                $this->updateForm->fill(['person.is_paid' => $this->isPaidCheckBoxValue]);
            }

            if ($property === 'isActiveCheckBoxValue') {
                $this->updateForm->fill(['user.is_active' => $this->isActiveCheckBoxValue]);
            }
    }

    public function render()
    {
        return view('livewire.core.person-modal');
    }
}
