<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Enum\Core\Web\RoutesNames;
use App\Livewire\Forms\Core\Role\ManageForm;
use App\Models\Role;
use App\Models\User;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class RolesModal extends Component
{
    use GeneralTrait;

    public ManageForm $form;
    public ?int $id = null;
    public ?User $user = null;


    #[On("logout-yourself")]
    public function logout()
    {
        return redirect()->route(RoutesNames::LOG_OUT->value);
    }

    public function mount()
    {
        if (!$this->id) {
            return;
        }

        try {
            $this->user = User::findOrFail($this->id);
            $this->form->roles = $this->user->roles->pluck('id')->toArray() ?? [];
        } catch (ModelNotFoundException $e) {
            Log::error('User not found: ' . $e->getMessage());
            $this->dispatch('open-errors', _('forms.common.errors.default'));
        }
    }

#[Computed]
public function existingRoles()
{

            $user = auth()->user();

            if (! $user) {
                return collect();
            }

            $userRoles = $user->roles()->pluck('slug')->toArray();

            // Define restrictions for each role
            $restrictions = [
                'super_admin' => ['service_admin', 'appointments_location_admin', 'doctor'],
                'admin' => ['super_admin', 'service_admin', 'appointments_location_admin', 'doctor'],
            ];

            // Find which restriction applies based on the user's highest role
            foreach ($restrictions as $role => $forbidden) {
                if (in_array($role, $userRoles)) {
                    return Role::whereNotIn('slug', $forbidden)->get();
                }
            }

            // Default: no roles available
            return collect();


}




    public function updateRolesOnKeydownEvent($value)
    {
        if (!isset($this->form->roles)) {
            $this->form->roles = [];
        }
        $this->checkAndUpdateArray($this->form->roles, $value);
    }

    public function handleSubmit()
    {
        $response = $this->form->save($this->user);

        if ($response['status']) {
            if (auth()->id() === $this->id) {
                $this->dispatch('open-toast', ['message'=>__('forms.role.responses.own_success'),'closing-event'=>'logout-yourself']);
            } else {
                $this->dispatch('open-toast', $response['message'] ?? __('forms.common.errors.default'));
            }
        } else {
            $this->dispatch('open-errors', $response['error'] ?? [__('forms.common.errors.default')]);
        }
    }

    public function render()
    {
        return view('livewire.core.super-admin.roles-modal');
    }
}
