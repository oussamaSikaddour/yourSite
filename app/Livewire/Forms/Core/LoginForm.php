<?php

namespace App\Livewire\Forms\Core;

use App\Enum\Core\Web\RoutesNames;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Form;

class LoginForm extends Form
{

    use ResponseTrait;
    public string $email = '';
    public string $password = '';

    /**
     * Define the validation rules.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email,deleted_at,NULL'], // Ensure soft-deleted users are ignored
            'password' => ['required', 'min:8', 'max:255'],
        ];
    }

    /**
     * Set custom validation attribute names for error messages.
     */
    public function validationAttributes(): array
    {
        return [
            'email' => __('forms.login.email'),
            'password' => __('forms.login.password'),
        ];
    }

    /**
     * Save the form data and handle login logic.
     */
    public function save()
    {

        $rateLimiterKey = 'login:' . request()->ip() . ':' . $this->email;

        // Check for too many attempts
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            return $this->response(false, errors: [__('forms.login.errors.too_many_attempts')]);
        }

        // Validate form data
        try {
            $data = $this->validate();

            if (Auth::attempt($data)) {
                $user = Auth::user();

                // Regenerate session to prevent fixation attacks
                session()->regenerate();



                return $this->response(true, data: ['route' => RoutesNames::DASHBOARD->value]);
            } else {
                // Increment rate limiter for failed attempts
                RateLimiter::hit($rateLimiterKey);
                return $this->response(false, errors: [__('forms.login.errors.invalid_credentials')]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        }
        catch (\Exception $e) {
            Log::error('Login form error: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
