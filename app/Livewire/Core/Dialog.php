<?php

namespace App\Livewire\Core;

use Livewire\Attributes\On;
use Livewire\Component;

final class Dialog extends Component
{
    public bool $isOpen = false;

    public string $question = '';
    /** @var array<int, mixed> */
    public array $details = [];

    /** @var array{event?:string,parameters?:mixed} */
    public array $actionEvent = [];

    public string $questionDetails = '';

    // Force stable re-render sync
    public bool $needsInertSync = false;

    /** @var array<string,string> */
    protected array $dialogQuestion = [
        "our_quality" => 'dialogs.delete.our_quality',
        "message" => "dialogs.delete.message",
        "user" => "dialogs.delete.user",
        "person" => "dialogs.delete.person",
        'wilaya' => "dialogs.delete.wilaya",
        'daira' => "dialogs.delete.daira",
        'commune' => "dialogs.delete.commune",
        "field" => "dialogs.delete.field",
        "field_grade" => "dialogs.delete.field_grade",
        "field_specialty" => "dialogs.delete.field_specialty",
        "occupation" => "dialogs.delete.occupation",
        "banking_information" => "dialogs.delete.banking_information",
        "bank" => "dialogs.delete.bank",
        'service' => "dialogs.delete.service",
        'menu' => "dialogs.delete.menu",
        'external_link' => "dialogs.delete.external_link",
        'article' => "dialogs.delete.article",
        'trend' => "dialogs.delete.trend",
        'slide' => "dialogs.delete.slide",
        'patient-visit' => "dialogs.delete.patient-visit",
        'slider' => "dialogs.delete.slider",
        'schedule' => "dialogs.delete.schedule",
        'publish_schedule' => "dialogs.publish_schedule",
        'schedule_day' => "dialogs.delete.schedule_day",
        'establishment' => "dialogs.delete.establishment",
        'remove-coordinator' => "dialogs.remove-coordinator",
        'remove-appointments-location-admin' => "dialogs.remove-appointments-location-admin",
        'confirm-appointment' => "dialogs.confirm-appointment",
        'cancel-appointment' => "dialogs.cancel-appointment",
        "bonus" => "dialogs.delete.bonus",
        "global_transfer" => "dialogs.delete.global_transfer",
        "transfer" => "dialogs.delete.transfer",
        "add_bonuses" => "dialogs.add.bonuses",
    ];

    #[On('open-dialog')]
    public function openDialog(array $data = []): void
    {
        $this->isOpen = true;

        $this->question = (string) ($data['question'] ?? '');
        $this->details = (array) ($data['details'] ?? []);
        $this->actionEvent = (array) ($data['actionEvent'] ?? []);

        $this->questionDetails = '';

        // Build details text if possible
        if (count($this->details) === 2) {
            $key = (string) $this->details[0];
            $attribute = (string) $this->details[1];
            $this->questionDetails = $this->resolveQuestionDetails($key, $attribute);
        }

        // apply inert after DOM update
        $this->needsInertSync = true;
    }

    public function closeDialog(): void
    {
        $this->isOpen = false;

        $this->question = '';
        $this->details = [];
        $this->actionEvent = [];
        $this->questionDetails = '';

        // remove inert after DOM update
        $this->needsInertSync = true;
    }

    public function confirmAction(): void
    {
        $event = $this->actionEvent['event'] ?? null;
        if (!is_string($event) || $event === '') {
            return;
        }

        // Dispatch the action
        $this->dispatch($event, $this->actionEvent['parameters'] ?? []);

        // Close dialog
        $this->closeDialog();
    }

    public function rendered(): void
    {
        if (!$this->needsInertSync) {
            return;
        }

        $this->needsInertSync = false;

        // Browser event for Dialog.js to apply/remove inert based on .open class
        $this->dispatch('dialog-sync-inert');
    }

    private function resolveQuestionDetails(string $key, string $attribute): string
    {
        if (!array_key_exists($key, $this->dialogQuestion)) {
            return '';
        }

        return __($this->dialogQuestion[$key], ['attribute' => $attribute]);
    }

    public function render()
    {
        return view('livewire.core.dialog');
    }
}
