<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Models\Message;
use App\Traits\Core\Common\TableTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class VisitorsMessagesTable extends Component
{
    use WithPagination, TableTrait;

    #[Url()]
    public $name = "";

    #[Url()]
    public $email = "";

    /**
     * Reset filters for search fields.
     */
    public function resetFilters()
    {
        $this->name = "";
        $this->email = "";
        $this->resetPage();
    }

    /**
     * Computed property to fetch filtered and paginated messages.
     */
    #[Computed()]
    public function messages()
    {
        return Message::query()
            ->when(trim($this->email), fn($query) => $query->where('email', 'like', "%".trim($this->email)."%"))
            ->when(trim($this->name), fn($query) => $query->where('name', 'like', "%".trim($this->name)."%"))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    /**
     * Reset pagination when filters or sorting change.
     */
    public function updated($property)
    {
        if (in_array($property, ['name', 'email', 'perPage', 'sortBy', 'sortDirection'])) {
            $this->resetPage();
        }
    }

    /**
     * Open delete confirmation dialog.
     */
    public function openDeleteMessageDialog($message)
    {
        $data = [
            "question" => "dialogs.title.message",
            "details" => ["message", $message['name']],
            "actionEvent" => [
                "event" => "delete-message",
                "parameters" => $message
            ]
        ];

        $this->dispatch("open-dialog", $data);
    }

    /**
     * Delete a message after confirmation.
     */
    #[On("delete-message")]
    public function deleteMessage(Message $msg)
    {
        try {
            $msg->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting message: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.core.super-admin.visitors-messages-table');
    }
}
