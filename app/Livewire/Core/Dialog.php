<?php

namespace App\Livewire\Core;

use Livewire\Attributes\On;
use Livewire\Component;

class Dialog extends Component
{
    public $isOpen = false;
    public $question = "";
    public $details = [];
    public $actionEvent = [];
    public $dialogQuestion = [];
    public $questionDetails = "";

    #[On("open-dialog")]
    public function openDialog($data)
    {

        $this->dispatch("add-open-to-dialog");
        $this->isOpen = true;
        $this->question = $data['question'];
        $this->details = $data['details'];
        $this->actionEvent = $data['actionEvent'];

        // Generate question details if applicable
        if (count($this->details) === 2 && array_key_exists($this->details[0], $this->dialogQuestion)) {
            $this->questionDetails = $this->resolveQuestionDetails($this->details[0], $this->details[1]);
        }
    }

    public function closeDialog()
    {
        $this->reset(['isOpen', 'question', 'details', 'actionEvent']);

    }

    public function confirmAction()
    {
        // Validate that the actionEvent contains the 'event' key before dispatching
        if (isset($this->actionEvent['event'])) {
            $this->dispatch($this->actionEvent['event'], $this->actionEvent['parameters'] ?? []);


            $this->dispatch("user-chose-yes");
        }
    }

    public function mount()
    {
        // Define dialog question templates as simple key-value pairs
        $this->dialogQuestion = [
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
    }

    /**
     * Resolve the question details from the question template.
     *
     * @param string $key The key of the question template.
     * @param string $attribute The attribute to replace in the template.
     * @return string
     */
    public function resolveQuestionDetails(string $key, string $attribute): string
    {

        if (array_key_exists($key, $this->dialogQuestion)) {
            return __($this->dialogQuestion[$key], ['attribute' => $attribute]);
        }

        return ''; // Fallback if the key is not found
    }

    public function render()
    {
        return view('livewire.core.dialog');
    }
}
