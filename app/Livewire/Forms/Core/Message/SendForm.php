<?php

namespace App\Livewire\Forms\Core\Message;

use App\Enum\Core\NotificationFor;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Form;

class SendForm extends Form
{
    use ResponseTrait;

    public string $name    = '';
    public string $email   = '';
    public string $message = '';


    /* ------------------------------------------------------------------
     |  Validation
     | ------------------------------------------------------------------ */

    protected function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'min:3', 'max:200'],
            'email'   => ['required', 'string', 'email', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ];
    }

    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('guest.message', [
            'name',
            'email',
            'message',
        ]);
    }


    /* ------------------------------------------------------------------
     |  Persist
     | ------------------------------------------------------------------ */

    public function save(): array
    {
                    $data = $this->validate();
        try {
            return DB::transaction(function () use ($data) {
                /* ---- 1. store the message ---- */
                Message::create($data);

                /* ---- 2. notify admins ---- */
                Notification::create([
                    'message'           => 'new',
                    'active'            => true,
                    'for_type'          => NotificationFor::ADMIN,   // <-- enum
                    'targetable_type'   =>Message::class,
                ]);

                return $this->response( true, message: __('forms.guest.message.responses.send_success'));
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Throwable $e) {
            Log::error('Contact-message store failed', ['exception' => $e]);

            return $this->response( false, errors: [__('forms.common.errors.default')]);
        }
    }
}
