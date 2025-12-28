<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Models\Image;
use App\Models\OurQuality;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\ModelImageTrait;
use App\Traits\Core\Common\TableTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class OurQualitiesTable extends Component
{
    use WithPagination, TableTrait, GeneralTrait, ModelImageTrait;

    #[Url()]
    public $name = "";

    public $statusOptions = [];



    public function mount()  {
        $this->statusOptions = config('core.options.STATUS_OPTIONS')[app()->getLocale()];
    }

    /**
     * Reset all filters.
     */
    public function resetFilters()
    {
        $this->name = "";
        $this->resetPage();
    }

    /**
     * Get paginated list of our qualities with localized names.
     */
    #[Computed()]
    public function ourQualities()
    {
        $query = OurQuality::query();
        if (!empty($this->name)) {
            $query->where(function ($q) {
                $localeColumn = match (app()->getLocale()) {
                    'ar' => 'name_ar',
                    'en' => 'name_en',
                    default => 'name_fr',
                };

                $q->where($localeColumn, 'like', "%{$this->name}%");
            });
        }
        return $query->orderBy($this->sortBy, $this->sortDirection)
                     ->paginate($this->perPage);
    }

    /**
     * Change the status of a given OurQuality entity.
     */
    #[On("selected-value-updated")]
    public function changeStatusForOurQuality(OurQuality $entity, $value)
    {
        try {
            $activeCount = OurQuality::where('is_active', '1')->count();
            if ($activeCount <= 3 || $value === "0") {
                $entity->update(['is_active' => $value]);
            } else {
                $this->dispatch('selected-value-reset', $entity->id, 0);
                throw new \Exception(__("tables.our_qualities.errors.active_limit"));
            }
        } catch (\Exception $e) {
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Delete an OurQuality entity and its associated images.
     */
    #[On("delete-our-quality")]
    public function deleteOurQuality(OurQuality $ourQuality)
    {
        try {
            $images = Image::where([
                ['imageable_id', $ourQuality->id],
                ['imageable_type', OurQuality::class],
                ['use_case', 'our_quality']
            ])->get();
            if ($images->isNotEmpty()) {
                $this->deleteImages($images);
            }
            $ourQuality->delete();
        } catch (\Exception $e) {
            Log::error('Error in deleteOurQuality method: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));

        }
    }

    /**
     * Handle property updates and reset pagination if necessary.
     */
    public function updated($property)
    {
        if (in_array($property, ['name', 'perPage', 'sortBy', 'sortDirection'])) {
            $this->resetPage();
        }
    }


    public function openDeleteDialog($ourQuality){

        $locale = app()->getLocale();
      $name=$ourQuality["name_$locale"] ?? $ourQuality['name_fr'] ?? '';
        $data=[
            "question" => "dialogs.title.our_quality",
            "details" =>["our_quality",$name],
            "actionEvent"=>[
                            "event"=>"delete-our-quality",
                            "parameters"=>$ourQuality
                            ]
            ];

    $this->dispatch("open-dialog", $data);
    }
    /**
     * Validate the `name` filter input.
     */
    public function updatedName()
    {
        try {
            $this->validate([
                'name' => ['nullable', 'string', 'max:255'],
            ]);
        } catch (ValidationException $e) {
            $this->dispatch('open-errors', $e->validator->errors()->all());
        }
    }


    public function render()
    {
        return view('livewire.core.super-admin.our-qualities-table');
    }
}
