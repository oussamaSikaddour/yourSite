<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Models\Image;
use App\Models\OurQuality;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\ModelImageTrait;
use App\Traits\Core\Common\TableTrait;
use Illuminate\Support\Facades\DB;
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



#[On("selected-value-updated")]
public function changeArticleState(int|string $ourQualityId, string $value): void
{
    // normalize incoming value (you seem to use "0"/"1")
    $value = $value === '1' ? '1' : '0';

    try {
        DB::transaction(function () use ($ourQualityId, $value) {

            // Lock the row to ensure consistent decision-making under concurrency
            $selected = OurQuality::query()
                ->whereKey($ourQualityId)
                ->lockForUpdate()
                ->first();

            if (!$selected) {
                $this->dispatch('open-errors', __('forms.common.errors.default'));
                return;
            }

            // If disabling, always allow (fast path: no counting)
            if ($value === '0') {
                OurQuality::whereKey($ourQualityId)->update(['is_active' => '0']);
                return;
            }

            // If already active and user selects active again, do nothing
            if ((string) $selected->is_active === '1') {
                return;
            }

            // Activating: enforce max 3 actives
            $activeCount = OurQuality::query()
                ->where('is_active', '1')
                ->lockForUpdate()
                ->count();

            if ($activeCount < 3) {
                OurQuality::whereKey($ourQualityId)->update(['is_active' => '1']);
                return;
            }

            // Limit reached: reset UI and show error
            $this->dispatch('selected-value-reset', $ourQualityId, '0');
            $this->dispatch('open-errors', __("tables.our_qualities.errors.active_limit"));
        });
    } catch (\Throwable $e) {
        Log::error('Error updating ourQuality state: ' . $e->getMessage());
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
