<?php

namespace App\Livewire\Core\Admin;

use App\Livewire\Forms\Core\Occupation\AddForm;
use App\Livewire\Forms\Core\Occupation\UpdateForm;
use App\Models\Occupation;
use App\Models\Field;
use App\Models\FieldSpecialty;
use App\Models\FieldGrade;
use App\Models\Role;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class OccupationsModal extends Component
{
    use WithPagination, TableTrait, GeneralTrait;

    public AddForm $addForm;
    public UpdateForm $updateForm;
    public Occupation $occupation;

    public $occupation_id;
    public $person = [];
    public $selectedChoice;
    public $employeeName = "";
    public $form = "addForm";
    public $activeOccupationId;
    public $locale = "fr";

    public $fieldsOptions = [];
    public $fieldSpecialtiesOptions = [];
    public $fieldGradesOptions = [];




    #[Computed]
    public function fields()
    {
        return Field::get(['id', 'designation_' . $this->locale]);
    }
    #[Computed]
    public function fieldSpecialties()
    {
        $fieldId = $this->{$this->form}->field_id ?? null;


        return FieldSpecialty::where('field_id', $fieldId)
            ->select('id', 'designation_' . $this->locale)
            ->get();
    }

    #[Computed]
    public function fieldGrades()
    {
        $fieldId = $this->{$this->form}->field_id ?? null;
        return FieldGrade::where('field_id', $fieldId)
            ->select('id', 'designation_' . $this->locale)
            ->get();
    }

    /**
     * Reset form fields and prepare for adding.
     */
    public function resetForm()
    {
        $this->form = "addForm";
        $this->occupation_id = null;
        $this->selectedChoice = null;

        $this->addForm->reset();
        $this->updateForm->reset();

        $this->addForm->fill([
            'person_id' => $this->person['id'],
        ]);
    }

    /**
     * Handle switching between add and update forms.
     */
    public function updatedSelectedChoice()
    {
        $this->occupation_id = $this->selectedChoice;
        $this->form = $this->occupation_id ? 'updateForm' : 'addForm';


        if ($this->occupation_id) {
            $this->setOccupationForm($this->occupation_id);
        }
    }

    /**
     * Set active occupation for user.
     */
    public function updatedActiveOccupationId()
    {
        try {
            $personId = $this->person['id'];

            Occupation::where('person_id', $personId)->update(['is_active' => false]);
            Occupation::where('id', $this->activeOccupationId)->update(['is_active' => true]);
        } catch (\Exception $e) {
            Log::error('Error in updatedActiveOccupationId: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Computed: list occupations for this user with localized field/specialty/grade.
     */
    #[Computed()]
    public function occupations()
    {

        $fieldDesignation = match ($this->locale) {
            'ar' => 'fields.designation_ar',
            'fr' => 'fields.designation_fr',
            'en' => 'fields.designation_en',
            default => 'fields.designation_fr', // Fallback to default
        };
        $fieldSpecialtyDesignation = match ($this->locale) {
            'ar' => 'field_specialties.designation_ar',
            'fr' => 'field_specialties.designation_fr',
            'en' => 'field_specialties.designation_en',
            default => 'field_specialties.designation_fr', // Fallback to default
        };
        $fieldGradeDesignation = match ($this->locale) {
            'ar' => 'field_grades.designation_ar',
            'fr' => 'field_grades.designation_fr',
            'en' => 'field_grades.designation_en',
            default => 'field_grades.designation_fr', // Fallback to default
        };
        return Occupation::with(['field', 'specialty', 'grade'])
            ->where('person_id', $this->person['id'])
            ->join('fields', 'occupations.field_id', '=', 'fields.id')
            ->join('field_specialties', 'occupations.field_specialty_id', '=', 'field_specialties.id')
            ->join('field_grades', 'occupations.field_grade_id', '=', 'field_grades.id')
            ->select(
                'occupations.*',
                "$fieldDesignation as field",
                "$fieldSpecialtyDesignation as specialty",
                "$fieldGradeDesignation as fieldGrade",

            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->get();
    }

    /**
     * Mount component.
     */
    public function mount()
    {


        $this->loadSelectOptions();
        $this->employeeName = $this->person["full_name"] ?? '';

        $activeOccupation = Occupation::where('person_id', $this->person['id'])
            ->where('is_active', true)
            ->first();

        $this->activeOccupationId = $activeOccupation?->id;

        $this->resetForm();
    }

    /**
     * Load select dropdown options.
     */
    protected function loadSelectOptions()
    {

        $this->fieldsOptions = $this->populateSelectorOption($this->fields(),  'id', 'localized_designation', __('selectors.default.fields'));
        $this->fieldSpecialtiesOptions = $this->populateSelectorOption($this->fieldSpecialties(),  'id', 'localized_designation', __('selectors.default.field_specialties'));
        $this->fieldGradesOptions = $this->populateSelectorOption($this->fieldGrades(),  'id', 'localized_designation', __('selectors.default.field_grades'));
    }

    /**
     * Set update form fields from occupation.
     */
    public function setOccupationForm($occupationId)
    {
        try {
            $occupation = Occupation::where('person_id', $this->person['id'])->findOrFail($occupationId);
            $this->occupation = $occupation;

            $this->updateForm->fill([
                'id' => $occupationId,
                'person_id' => $this->person['id'],
                'field_id' => $occupation->field_id,

                'experience' => (int) $occupation->experience,
                'description_fr' => $occupation->description_fr,
                'description_ar' => $occupation->description_ar,
                'description_en' => $occupation->description_en,
            ]);

            $this->fieldSpecialtiesOptions = $this->populateSelectorOption($this->fieldSpecialties(),  'id', 'localized_designation', __('selectors.default.field_specialties'));
            $this->fieldGradesOptions = $this->populateSelectorOption($this->fieldGrades(),  'id', 'localized_designation', __('selectors.default.field_grades'));
            $this->updateForm->fill([
                'field_specialty_id' => $occupation->field_specialty_id,
                'field_grade_id' => $occupation->field_grade_id,

            ]);
        } catch (\Exception $e) {
            Log::error('Error in setOccupationForm: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Handle add or update.
     */
    public function handleSubmit()
    {
        $this->dispatch('form-submitted');

        $response = $this->occupation_id
            ? $this->updateForm->save($this->occupation)
            : $this->addForm->save();

        if ($response['status']) {
            $this->dispatch('update-occupations-table');
            if ($this->form === 'addForm') {
                $this->resetForm();
            }
            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }

    /**
     * Open delete confirmation dialog.
     */
    public function openDeleteOccupationDialog($occupation)
    {

        $grade = $occupation['fieldGrade'] ?? '';

        $data = [
            'question' => 'dialogs.title.occupation',
            'details' => ['occupation', $grade],
            'actionEvent' => [
                'event' => 'delete-occupation',
                'parameters' => $occupation,
            ],
        ];

        $this->dispatch('open-dialog', $data);
    }

    /**
     * Delete occupation.
     */
    #[On('delete-occupation')]
    public function deleteOccupation(Occupation $occupation)
    {
        try {
            $person = $occupation->holder; // or ->user if you renamed the relation
            $occupation->delete();

            $doctorRole = Role::where('slug', 'doctor')->first();

            if ($doctorRole && $person) {
                // Does the user still have an F_MED occupation?
                $hasFMedOccupation = $person->occupations()
                    ->whereHas('field', fn($q) => $q->where('acronym', 'F_MED'))
                    ->exists();

                // If none left, just detach (safe even if not attached)
                if (! $hasFMedOccupation) {
                    $person->roles()->detach($doctorRole->id);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in deleteOccupation: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }


    public function updated($property)
    {
        if (in_array($property, ['addForm.field_id', 'updateForm.field_id'])) {
            $this->fieldSpecialtiesOptions = $this->populateSelectorOption($this->fieldSpecialties(),  'id', 'localized_designation', __('selectors.default.field_specialties'));
            $this->fieldGradesOptions = $this->populateSelectorOption($this->fieldGrades(),  'id', 'localized_designation', __('selectors.default.field_grades'));
        }
    }

    public function render()
    {
        $this->dispatch('init-tooltip');
        return view('livewire.core.admin.occupations-modal');
    }
}
